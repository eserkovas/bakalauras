<?php

session_start();

require_once 'sesija.php';

require_once 'duomenu_baze.php';

require_once 'funkcijos.php';

$veiksmas = null;
$rezultatas = null;
$klaida = null;

if(isset($_GET["veiksmas"])) {

    $veiksmas = $_GET["veiksmas"];

}

if(isset($_SESSION["rezultatas"])) {

    $rezultatas = $_SESSION["rezultatas"];

    unset($_SESSION["rezultatas"]);

}

if(isset($_SESSION["klaida"])) {

    $klaida = $_SESSION["klaida"];

    unset($_SESSION["klaida"]);

}

leidimas(array(2));

?>

<!doctype html>

<html lang="en" class="h-100">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="./css/stilius.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>Sandėliai | Logistikos sistema (IF180026)</title>

    </head>

    <body class="h-100">

        <div class="container-fluid h-100 p-0">

            <div class="row m-0 h-100">

                <?php require_once 'navigacija.php'; ?>

                    <div class="container-fluid">

                        <h2 class="py-3 fw-bold"><i class='fa-solid fa-warehouse fa-fw my-auto me-2'></i> Sandėliai</h2>

                        <?php

                            if(empty($veiksmas)) {

                                echo "
                                
                                <div class='d-flex flex-row justify-content-between mb-3'>

                                    <h4 class='my-auto'>Sandėlių sąrašas</h4>
        
                                    <a href='sandeliai.php?veiksmas=naujas'><button type='button' class='btn btn-primary'>Naujas sandėlis</button></a>
        
                                </div>
        
                                <div class='container-fluid p-0'>

                                    <div class='col-12 col-lg-6 p-0'>

                                        <form method='post' action='sandeliai.php?veiksmas=paieska'>

                                            <div class='input-group mb-3'>

                                                <input type='text' class='form-control' id='paieska' name='paieska' placeholder='Sandėlio informacija'>
                                                <button class='btn btn-outline-secondary' type='submit'>Ieškoti</button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                                ";

                                if(!empty($rezultatas)) {

                                    echo "
                                    
                                    <div class='alert alert-success' role='alert'>
                                        ".$rezultatas."
                                    </div>

                                    ";

                                }

                                if(!empty($klaida)) {

                                    echo "
                                    
                                    <div class='alert alert-danger' role='alert'>
                                        ".$klaida."
                                    </div>

                                    ";

                                }

                                if($stmt = $conn->prepare("SELECT * FROM sandeliai")) {

                                    $stmt->execute();

                                    $rezultatas = $stmt->get_result();

                                    if($rezultatas->num_rows > 0) {

                                        echo "
                                        
                                        <div class='table-responsive'>
        
                                            <table class='table table-striped table-hover'>
                                            
                                                <thead>
                
                                                    <tr>
                                                        <th scope='col'>ID</th>
                                                        <th scope='col'>Pavadinimas</th>
                                                        <th scope='col'>Telefono numeris</th>
                                                        <th scope='col'>Adresas</th>
                                                        <th scope='col'>Miestas</th>
                                                        <th scope='col'>Pašto kodas</th>
                                                        <th scope='col'></th>
                                                    </tr>
                
                                                </thead>
                
                                                <tbody>

                                        ";

                                        while($sandelis = $rezultatas->fetch_assoc()) {

                                            echo "
                                            
                                            <tr>
                                                <th class='align-middle'>".$sandelis["id"]."</th>
                                                <td class='align-middle'>".$sandelis["pavadinimas"]."</td>
                                                <td class='align-middle'>".$sandelis["telefonas"]."</td>
                                                <td class='align-middle'>".$sandelis["adresas"]."</td>
                                                <td class='align-middle'>".$sandelis["miestas"]."</td>
                                                <td class='align-middle'>".$sandelis["kodas"]."</td>
                                                <td class='align-middle d-flex flex-row py-5 py-lg-2'>
                                                    <a href='sandeliai.php?veiksmas=redaguoti&sid=".$sandelis["id"]."'><button type='button' class='btn btn-warning btn-sm me-2 '>Redaguoti</button></a>
                                                    <a href='sandeliai.php?veiksmas=istrinti&sid=".$sandelis["id"]."'><button type='button' class='btn btn-danger btn-sm'>Ištrinti</button></a>
                                                </td>
                                            </tr>
                                            
                                            ";

                                        }

                                        echo "
                                        
                                                </tbody>
                
                                            </table>
                
                                        </div>

                                        ";

                                    } else {

                                        echo "
                                        
                                        <div class='alert alert-secondary' role='alert'>
                                            Sandėlių sistemoje nėra
                                        </div>

                                        ";

                                    }

                                    $stmt->close();
                                    
                                } else {

                                    klaida("Klaida gaunant sandėlius");
                                    echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                    exit;

                                }

                            } else {

                                if($veiksmas == "paieska") {

                                    leidimas(array(2));

                                    $paieska = sanitizavimas($_POST["paieska"], "string");

                                    if(!validavimas($paieska, "nera")) {
        
                                        klaida("Nenurodyta paieškos frazė");
                                        echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                        exit;

                                    }

                                    echo "
                                    
                                    <div class='d-flex flex-row justify-content-between mb-3'>

                                        <h4 class='my-auto'>Sandėlių sąrašas</h4>
            
                                        <a href='sandeliai.php?veiksmas=naujas'><button type='button' class='btn btn-primary'>Naujas sandėlis</button></a>
            
                                    </div>
            
                                    <div class='container-fluid p-0'>

                                        <div class='col-12 col-lg-6 p-0'>

                                            <form method='post' action='sandeliai.php?veiksmas=paieska'>

                                                <div class='input-group mb-3'>

                                                    <input type='text' class='form-control' id='paieska' name='paieska' placeholder='Sandėlio informacija' value='".$paieska."'>
                                                    <button class='btn btn-outline-secondary' type='submit'>Ieškoti</button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>
                                    
                                    ";

                                    if($stmt = $conn->prepare("SELECT * FROM sandeliai WHERE pavadinimas LIKE ? OR telefonas LIKE ? OR adresas LIKE ? OR miestas LIKE ? OR kodas LIKE ?")) {

                                        $paieska = "%".$paieska."%";

                                        $stmt->bind_param("sssss", $paieska, $paieska, $paieska, $paieska, $paieska);

                                        $stmt->execute();
    
                                        $rezultatas = $stmt->get_result();
    
                                        if($rezultatas->num_rows > 0) {
    
                                            echo "
                                            
                                            <div class='table-responsive'>
            
                                                <table class='table table-striped table-hover'>
                                                
                                                    <thead>
                    
                                                        <tr>
                                                            <th scope='col'>ID</th>
                                                            <th scope='col'>Pavadinimas</th>
                                                            <th scope='col'>Telefono numeris</th>
                                                            <th scope='col'>Adresas</th>
                                                            <th scope='col'>Miestas</th>
                                                            <th scope='col'>Pašto kodas</th>
                                                            <th scope='col'></th>
                                                        </tr>
                    
                                                    </thead>
                    
                                                    <tbody>
    
                                            ";
    
                                            while($sandelis = $rezultatas->fetch_assoc()) {
    
                                                echo "
                                                
                                                <tr>
                                                    <th class='align-middle'>".$sandelis["id"]."</th>
                                                    <td class='align-middle'>".$sandelis["pavadinimas"]."</td>
                                                    <td class='align-middle'>".$sandelis["telefonas"]."</td>
                                                    <td class='align-middle'>".$sandelis["adresas"]."</td>
                                                    <td class='align-middle'>".$sandelis["miestas"]."</td>
                                                    <td class='align-middle'>".$sandelis["kodas"]."</td>
                                                    <td class='align-middle d-flex flex-row py-5 py-lg-2'>
                                                        <a href='sandeliai.php?veiksmas=redaguoti&sid=".$sandelis["id"]."'><button type='button' class='btn btn-warning btn-sm me-2 '>Redaguoti</button></a>
                                                        <a href='sandeliai.php?veiksmas=istrinti&sid=".$sandelis["id"]."'><button type='button' class='btn btn-danger btn-sm'>Ištrinti</button></a>
                                                    </td>
                                                </tr>
                                                
                                                ";
    
                                            }
    
                                            echo "
                                            
                                                    </tbody>
                    
                                                </table>
                    
                                            </div>
    
                                            ";
    
                                        } else {
    
                                            echo "
                                            
                                            <div class='alert alert-secondary' role='alert'>
                                                Paieškos rezultatų pagal frazę \"".str_replace("%", "", $paieska)."\" nėra
                                            </div>
    
                                            ";
    
                                        }
    
                                        $stmt->close();
                                        
                                    } else {
    
                                        klaida("Klaida vykdant paiešką");
                                        echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                        exit;
    
                                    }

                                } else if($veiksmas == "naujas") {

                                    leidimas(array(2));

                                    echo "
                                
                                    <div class='d-flex flex-row justify-content-start mb-3'>
    
                                        <h4>Naujas sandėlis</h4>
            
                                    </div>
            
                                    <form method='post' action='sandeliai.php?veiksmas=sukurti'>

                                        <div class='mb-3'>
                                            <label for='pavadinimas' class='form-label'>Pavadinimas</label>
                                            <input type='text' class='form-control' id='pavadinimas' name='pavadinimas'>
                                        </div>

                                        <div class='mb-3'>
                                            <label for='telefonas' class='form-label'>Telefono numeris</label>
                                            <input type='text' class='form-control' id='telefonas' name='telefonas'>
                                        </div>

                                        <div class='mb-3'>
                                            <label for='adresas' class='form-label'>Adresas</label>
                                            <input type='text' class='form-control' id='adresas' name='adresas'>
                                        </div>

                                        <div class='mb-3'>
                                            <label for='miestas' class='form-label'>Miestas</label>
                                            <input type='text' class='form-control' id='miestas' name='miestas'>
                                        </div>

                                        <div class='mb-3'>
                                            <label for='kodas' class='form-label'>Pašto kodas</label>
                                            <input type='text' class='form-control' id='kodas' name='kodas'>
                                        </div>

                                        <button type='submit' class='btn btn-success'>Sukurti</button>

                                    </form>
    
                                    ";

                                } elseif ($veiksmas == "sukurti") {

                                    leidimas(array(2));

                                    $pavadinimas = sanitizavimas($_POST["pavadinimas"], "string");
                                    $telefonas = sanitizavimas($_POST["telefonas"], "string");
                                    $adresas = sanitizavimas($_POST["adresas"], "string");
                                    $miestas = sanitizavimas($_POST["miestas"], "string");
                                    $kodas = sanitizavimas($_POST["kodas"], "string");

                                    if(!validavimas($pavadinimas, "nera")) {

                                        klaida("Nenurodytas sandėlio pavadinimas");
                                        echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                        exit;

                                    }

                                    if(!validavimas($telefonas, "telefonas")) {

                                        klaida("Sandėlio telefono numeris nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                        exit;

                                    }

                                    if(!validavimas($adresas, "nera")) {

                                        klaida("Nenurodytas sandėlio adresas");
                                        echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                        exit;

                                    }

                                    if(!validavimas($miestas, "nera")) {
                                        
                                        klaida("Nenurodytas sandėlio miestas");
                                        echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                        exit;

                                    }

                                    if(!validavimas($kodas, "pasto-kodas")) {

                                        klaida("Sandėlio pašto kodas nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id FROM sandeliai WHERE (adresas LIKE ? AND miestas LIKE ?) OR kodas LIKE ?")) {

                                        $stmt->bind_param("sss", $adresas, $miestas, $kodas);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 0) {

                                            if($stmt2 = $conn->prepare("INSERT INTO sandeliai (pavadinimas, telefonas, adresas, miestas, kodas) VALUES (?, ?, ?, ?, ?)")) {

                                                $stmt2->bind_param("sssss", $pavadinimas, $telefonas, $adresas, $miestas, $kodas);
        
                                                $stmt2->execute();
        
                                                $stmt2->close();
        
                                                rezultatas("Naujas sandėlis sėkmingai sukurtas");
                                                echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                                exit;
        
                                            } else {
        
                                                klaida("Klaida kuriant naują sandėlį");
                                                echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                                exit;
        
                                            }

                                        } else {

                                            klaida("Sandėlis panašiu adresu jau yra sukurtas");
                                            echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie sandėlį");
                                        echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "redaguoti") {

                                    leidimas(array(2));

                                    $sid = null;

                                    if(isset($_GET["sid"]) && !empty($_GET["sid"])) {

                                        $sid = sanitizavimas($_GET["sid"], "int");

                                    }

                                    if(!validavimas($sid, "int")) {

                                        klaida("Sandėlio ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT * FROM sandeliai WHERE id = ?")) {

                                        $stmt->bind_param("i", $sid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $sandelis = $rezultatas->fetch_assoc();

                                            echo "
                                
                                            <div class='d-flex flex-row justify-content-start mb-3'>
            
                                                <h4>Redaguoti sandėlio informaciją</h4>
                    
                                            </div>
                    
                                            <form method='post' action='sandeliai.php?veiksmas=atnaujinti&sid=".$sid."'>
        
                                                <div class='mb-3'>
                                                    <label for='pavadinimas' class='form-label'>Pavadinimas</label>
                                                    <input type='text' class='form-control' id='pavadinimas' name='pavadinimas' value='".$sandelis["pavadinimas"]."'>
                                                </div>
        
                                                <div class='mb-3'>
                                                    <label for='telefonas' class='form-label'>Telefono numeris</label>
                                                    <input type='text' class='form-control' id='telefonas' name='telefonas' value='".$sandelis["telefonas"]."'>
                                                </div>

                                                <div class='mb-3'>
                                                    <label for='adresas' class='form-label'>Adresas</label>
                                                    <input type='text' class='form-control' id='adresas' name='adresas' value='".$sandelis["adresas"]."'>
                                                </div>
        
                                                <div class='mb-3'>
                                                    <label for='miestas' class='form-label'>Miestas</label>
                                                    <input type='text' class='form-control' id='miestas' name='miestas' value='".$sandelis["miestas"]."'>
                                                </div>
        
                                                <div class='mb-3'>
                                                    <label for='kodas' class='form-label'>Pašto kodas</label>
                                                    <input type='text' class='form-control' id='kodas' name='kodas' value='".$sandelis["kodas"]."'>
                                                </div>
        
                                                <button type='submit' class='btn btn-success'>Atnaujinti</button>
        
                                            </form>
            
                                            ";

                                        } else {

                                            klaida("Sandėlio su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie sandėlį");
                                        echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "atnaujinti") {

                                    leidimas(array(2));

                                    $sid = null;

                                    if(isset($_GET["sid"]) && !empty($_GET["sid"])) {

                                        $sid = sanitizavimas($_GET["sid"], "int");

                                    }

                                    if(!validavimas($sid, "int")) {

                                        klaida("Sandėlio ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT adresas, miestas, kodas FROM sandeliai WHERE id = ?")) {

                                        $stmt->bind_param("i", $sid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $tikrinimas = $rezultatas->fetch_assoc();

                                            $pavadinimas = sanitizavimas($_POST["pavadinimas"], "string");
                                            $telefonas = sanitizavimas($_POST["telefonas"], "string");
                                            $adresas = sanitizavimas($_POST["adresas"], "string");
                                            $miestas = sanitizavimas($_POST["miestas"], "string");
                                            $kodas = sanitizavimas($_POST["kodas"], "string");
        
                                            if(!validavimas($pavadinimas, "nera")) {
        
                                                klaida("Nenurodytas sandėlio pavadinimas");
                                                echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                                exit;
        
                                            }
        
                                            if(!validavimas($telefonas, "telefonas")) {

                                                klaida("Sandėlio telefono numeris nenurodytas arba nurodytas neteisingai");
                                                echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                                exit;
        
                                            }

                                            if(!validavimas($adresas, "nera")) {
        
                                                klaida("Nenurodytas sandėlio adresas");
                                                echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                                exit;
        
                                            }
        
                                            if(!validavimas($miestas, "nera")) {
                                                
                                                klaida("Nenurodytas sandėlio miestas");
                                                echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                                exit;
        
                                            }
        
                                            if(!validavimas($kodas, "pasto-kodas")) {
        
                                                klaida("Sandėlio pašto kodas nenurodytas arba nurodytas neteisingai");
                                                echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                                exit;
        
                                            }

                                            if($tikrinimas["adresas"] == $adresas && $tikrinimas["miestas"] == $miestas && $tikrinimas["kodas"] == $kodas) {

                                                if($stmt2 = $conn->prepare("UPDATE sandeliai SET pavadinimas = ?, telefonas = ?, adresas = ?, miestas = ?, kodas = ? WHERE id = ?")) {

                                                    $stmt2->bind_param("sssssi", $pavadinimas, $telefonas, $adresas, $miestas, $kodas, $sid);
    
                                                    $stmt2->execute();
    
                                                    $stmt2->close();
    
                                                    rezultatas("Sandėlio informacija sėkmingai atnaujinta");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                                    exit;
    
                                                } else {
    
                                                    klaida("Klaida atnaujinant sandėlio informaciją");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                                    exit;
    
                                                }

                                            } else {

                                                if($stmt2 = $conn->prepare("SELECT id FROM sandeliai WHERE (adresas LIKE ? AND miestas LIKE ?) OR kodas LIKE ?")) {

                                                    $stmt2->bind_param("sss", $adresas, $miestas, $kodas);
            
                                                    $stmt2->execute();
            
                                                    $rezultatas2 = $stmt2->get_result();
            
                                                    if($rezultatas2->num_rows == 0) {
            
                                                        if($stmt3 = $conn->prepare("UPDATE sandeliai SET pavadinimas = ?, telefonas = ?, adresas = ?, miestas = ?, kodas = ? WHERE id = ?")) {

                                                            $stmt3->bind_param("sssssi", $pavadinimas, $telefonas, $adresas, $miestas, $kodas, $sid);
            
                                                            $stmt3->execute();
            
                                                            $stmt3->close();
            
                                                            rezultatas("Sandėlio informacija sėkmingai atnaujinta");
                                                            echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                                            exit;
            
                                                        } else {
            
                                                            klaida("Klaida atnaujinant sandėlio informaciją");
                                                            echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                                            exit;
            
                                                        }
            
                                                    } else {
            
                                                        klaida("Sandėlis panašiu adresu jau yra sukurtas");
                                                        echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                                        exit;
            
                                                    }
            
                                                    $stmt2->close();
            
                                                } else {
            
                                                    klaida("Klaida gaunant duomenis apie sandėlį");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                                    exit;
            
                                                }

                                            }

                                        } else {

                                            klaida("Sandėlio su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie sandėlį");
                                        echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "istrinti") {

                                    leidimas(array(2));
                                    
                                    $sid = null;

                                    if(isset($_GET["sid"]) && !empty($_GET["sid"])) {

                                        $sid = sanitizavimas($_GET["sid"], "int");

                                    }

                                    if(!validavimas($sid, "int")) {

                                        klaida("Sandėlio ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id FROM sandeliai WHERE id = ?")) {

                                        $stmt->bind_param("i", $sid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            if($stmt2 = $conn->prepare("DELETE FROM sandeliai WHERE id = ?")) {

                                                $stmt2->bind_param("i", $sid);

                                                $stmt2->execute();

                                                $stmt2->close();

                                                rezultatas("Sandėlis sėkmingai ištrintas");
                                                echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                                exit;

                                            } else {

                                                klaida("Klaida ištrinant sandėlį");
                                                echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                                exit;

                                            }

                                        } else {

                                            klaida("Sandėlio su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie sandėlį");
                                        echo "<meta http-equiv='Refresh' content='0; url=./sandeliai.php'>";
                                        exit;

                                    }

                                }

                            }

                        ?>

                    </div>

                </div>

            </div>

        </div>

        <script src="https://kit.fontawesome.com/74991d5891.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    </body>

</html>

<?php

$conn->close();

?>