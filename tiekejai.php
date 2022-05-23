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

        <title>Tiekėjai | Logistikos sistema (IF180026)</title>

    </head>

    <body class="h-100">

        <div class="container-fluid h-100 p-0">

            <div class="row m-0 h-100">

                <?php require_once 'navigacija.php'; ?>

                    <div class="container-fluid">

                        <h2 class="py-3 fw-bold"><i class='fa-solid fa-truck fa-fw my-auto me-2'></i> Tiekėjai</h2>

                        <?php

                            if(empty($veiksmas)) {

                                echo "
                                
                                <div class='d-flex flex-row justify-content-between mb-3'>

                                    <h4 class='my-auto'>Tiekėjų sąrašas</h4>
        
                                    <a href='tiekejai.php?veiksmas=naujas'><button type='button' class='btn btn-primary'>Naujas tiekėjas</button></a>
        
                                </div>
        
                                <div class='container-fluid p-0'>

                                    <div class='col-12 col-lg-6 p-0'>

                                        <form method='post' action='tiekejai.php?veiksmas=paieska'>

                                            <div class='input-group mb-3'>

                                                <input type='text' class='form-control' id='paieska' name='paieska' placeholder='Tiekėjo informacija'>
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

                                if($stmt = $conn->prepare("SELECT * FROM tiekejai")) {

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
                                                        <th scope='col'>Elektroninis paštas</th>
                                                        <th scope='col'>Prekių kategorijos</th>
                                                        <th scope='col'>Pastaba</th>
                                                        <th scope='col'></th>
                                                    </tr>
                
                                                </thead>
                
                                                <tbody>

                                        ";

                                        while($tiekejas = $rezultatas->fetch_assoc()) {

                                            echo "
                                            
                                            <tr>
                                                <th class='align-middle'>".$tiekejas["id"]."</th>
                                                <td class='align-middle'>".str_replace("\\", "", $tiekejas["pavadinimas"])."</td>
                                                <td class='align-middle'>".$tiekejas["telefonas"]."</td>
                                                <td class='align-middle'>".$tiekejas["pastas"]."</td>
                                                <td class='align-middle'>".$tiekejas["prekes"]."</td>
                                                <td class='align-middle'>".$tiekejas["pastaba"]."</td>
                                                <td class='align-middle d-flex flex-row py-5 py-lg-2'>
                                                    <a href='tiekejai.php?veiksmas=redaguoti&tid=".$tiekejas["id"]."'><button type='button' class='btn btn-warning btn-sm me-2 '>Redaguoti</button></a>
                                                    <a href='tiekejai.php?veiksmas=istrinti&tid=".$tiekejas["id"]."'><button type='button' class='btn btn-danger btn-sm'>Ištrinti</button></a>
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
                                            Tiekėjų sistemoje nėra
                                        </div>

                                        ";

                                    }

                                    $stmt->close();
                                    
                                } else {

                                    klaida("Klaida gaunant tiekėjus");
                                    echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                    exit;

                                }

                            } else {

                                if($veiksmas == "paieska") {

                                    leidimas(array(2));

                                    $paieska = sanitizavimas($_POST["paieska"], "string");

                                    if(!validavimas($paieska, "nera")) {
        
                                        klaida("Nenurodyta paieškos frazė");
                                        echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                        exit;

                                    }

                                    echo "
                                    
                                    <div class='d-flex flex-row justify-content-between mb-3'>

                                        <h4 class='my-auto'>Tiekėjų sąrašas</h4>
            
                                        <a href='tiekejai.php?veiksmas=naujas'><button type='button' class='btn btn-primary'>Naujas tiekėjas</button></a>
            
                                    </div>
            
                                    <div class='container-fluid p-0'>

                                        <div class='col-12 col-lg-6 p-0'>

                                            <form method='post' action='tiekejai.php?veiksmas=paieska'>

                                                <div class='input-group mb-3'>

                                                    <input type='text' class='form-control' id='paieska' name='paieska' placeholder='Tiekėjo informacija' value='".$paieska."'>
                                                    <button class='btn btn-outline-secondary' type='submit'>Ieškoti</button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                    ";

                                    if($stmt = $conn->prepare("SELECT * FROM tiekejai WHERE pavadinimas LIKE ? OR telefonas LIKE ? OR pastas LIKE ? OR prekes LIKE ? OR pastaba LIKE ?")) {

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
                                                            <th scope='col'>Elektroninis paštas</th>
                                                            <th scope='col'>Prekių kategorijos</th>
                                                            <th scope='col'>Pastaba</th>
                                                            <th scope='col'></th>
                                                        </tr>
                    
                                                    </thead>
                    
                                                    <tbody>
    
                                            ";
    
                                            while($tiekejas = $rezultatas->fetch_assoc()) {
    
                                                echo "
                                                
                                                <tr>
                                                    <th class='align-middle'>".$tiekejas["id"]."</th>
                                                    <td class='align-middle'>".str_replace("\\", "", $tiekejas["pavadinimas"])."</td>
                                                    <td class='align-middle'>".$tiekejas["telefonas"]."</td>
                                                    <td class='align-middle'>".$tiekejas["pastas"]."</td>
                                                    <td class='align-middle'>".$tiekejas["prekes"]."</td>
                                                    <td class='align-middle'>".$tiekejas["pastaba"]."</td>
                                                    <td class='align-middle d-flex flex-row py-5 py-lg-2'>
                                                        <a href='tiekejai.php?veiksmas=redaguoti&tid=".$tiekejas["id"]."'><button type='button' class='btn btn-warning btn-sm me-2 '>Redaguoti</button></a>
                                                        <a href='tiekejai.php?veiksmas=istrinti&tid=".$tiekejas["id"]."'><button type='button' class='btn btn-danger btn-sm'>Ištrinti</button></a>
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
                                        echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                        exit;
    
                                    }

                                } else if($veiksmas == "naujas") {

                                    leidimas(array(2));

                                    echo "
                                
                                    <div class='d-flex flex-row justify-content-start mb-3'>
    
                                        <h4>Naujas tiekėjas</h4>
            
                                    </div>
            
                                    <form method='post' action='tiekejai.php?veiksmas=sukurti'>

                                        <div class='mb-3'>
                                            <label for='pavadinimas' class='form-label'>Pavadinimas</label>
                                            <input type='text' class='form-control' id='pavadinimas' name='pavadinimas'>
                                        </div>

                                        <div class='mb-3'>
                                            <label for='telefonas' class='form-label'>Telefono numeris</label>
                                            <input type='text' class='form-control' id='telefonas' name='telefonas'>
                                        </div>

                                        <div class='mb-3'>
                                            <label for='pastas' class='form-label'>Elektroninis paštas</label>
                                            <input type='email' class='form-control' id='pastas' name='pastas'>
                                        </div>

                                        <div class='mb-3'>
                                            <label for='prekes' class='form-label'>Prekių kategorijos</label>
                                            <input type='text' class='form-control' id='prekes' name='prekes'>
                                        </div>

                                        <div class='mb-3'>
                                            <label for='pastaba' class='form-label'>Pastaba</label>
                                            <input type='text' class='form-control' id='pastaba' name='pastaba'>
                                        </div>

                                        <button type='submit' class='btn btn-success'>Sukurti</button>

                                    </form>
    
                                    ";

                                } elseif ($veiksmas == "sukurti") {

                                    leidimas(array(2));

                                    $pavadinimas = sanitizavimas($_POST["pavadinimas"], "string");
                                    $telefonas = sanitizavimas($_POST["telefonas"], "string");
                                    $pastas = sanitizavimas($_POST["pastas"], "pastas");

                                    $prekes = null;

                                    if(isset($_POST["prekes"]) && !empty($_POST["prekes"])) {

                                        $prekes = sanitizavimas($_POST["prekes"], "string");

                                    }

                                    $pastaba = null;

                                    if(isset($_POST["pastaba"]) && !empty($_POST["pastaba"])) {

                                        $pastaba = sanitizavimas($_POST["pastaba"], "string");

                                    }

                                    if(!validavimas($pavadinimas, "nera")) {

                                        klaida("Nenurodytas tiekėjo pavadinimas");
                                        echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                        exit;

                                    }

                                    if(!validavimas($telefonas, "telefonas")) {

                                        klaida("Tiekėjo telefono numeris nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                        exit;

                                    }

                                    if(!validavimas($pastas, "pastas")) {
                                        
                                        klaida("Nenurodytas tiekėjo elektroninis paštas");
                                        echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id FROM tiekejai WHERE pavadinimas = ?")) {

                                        $stmt->bind_param("s", $pavadinimas);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 0) {

                                            if($stmt2 = $conn->prepare("INSERT INTO tiekejai (pavadinimas, telefonas, pastas, prekes, pastaba) VALUES (?, ?, ?, ?, ?)")) {

                                                $stmt2->bind_param("sssss", $pavadinimas, $telefonas, $pastas, $prekes, $pastaba);
        
                                                $stmt2->execute();
        
                                                $stmt2->close();
        
                                                rezultatas("Naujas tiekėjas sėkmingai sukurtas");
                                                echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                                exit;
        
                                            } else {
        
                                                klaida("Klaida kuriant naują tiekėją");
                                                echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                                exit;
        
                                            }

                                        } else {

                                            klaida("Tiekėjas panašiu pavadinimu jau yra sukurtas");
                                            echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                            exit;

                                        }

                                    } else {

                                        klaida("Klaida gaunant duomenis apie tiekėją");
                                        echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "redaguoti") {

                                    leidimas(array(2));

                                    $tid = null;

                                    if(isset($_GET["tid"]) && !empty($_GET["tid"])) {

                                        $tid = sanitizavimas($_GET["tid"], "int");

                                    }

                                    if(!validavimas($tid, "int")) {

                                        klaida("Tiekėjo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT * FROM tiekejai WHERE id = ?")) {

                                        $stmt->bind_param("i", $tid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $tiekejas = $rezultatas->fetch_assoc();

                                            echo "
                                
                                            <div class='d-flex flex-row justify-content-start mb-3'>
            
                                                <h4>Redaguoti tiekėjo informaciją</h4>
                    
                                            </div>
                    
                                            <form method='post' action='tiekejai.php?veiksmas=atnaujinti&tid=".$tid."'>
        
                                                <div class='mb-3'>
                                                    <label for='pavadinimas' class='form-label'>Pavadinimas</label>
                                                    <input type='text' class='form-control' id='pavadinimas' name='pavadinimas' value='".str_replace("\\", "", $tiekejas["pavadinimas"])."'>
                                                </div>
        
                                                <div class='mb-3'>
                                                    <label for='telefonas' class='form-label'>Telefono numeris</label>
                                                    <input type='text' class='form-control' id='telefonas' name='telefonas' value='".$tiekejas["telefonas"]."'>
                                                </div>
        
                                                <div class='mb-3'>
                                                    <label for='pastas' class='form-label'>Elektroninis paštas</label>
                                                    <input type='email' class='form-control' id='pastas' name='pastas' value='".$tiekejas["pastas"]."'>
                                                </div>
        
                                                <div class='mb-3'>
                                                    <label for='prekes' class='form-label'>Prekių kategorijos</label>
                                                    <input type='text' class='form-control' id='prekes' name='prekes' value='".$tiekejas["prekes"]."'>
                                                </div>

                                                <div class='mb-3'>
                                                    <label for='pastaba' class='form-label'>Pastaba</label>
                                                    <input type='text' class='form-control' id='pastaba' name='pastaba' value='".$tiekejas["pastaba"]."'>
                                                </div>
        
                                                <button type='submit' class='btn btn-success'>Atnaujinti</button>
        
                                            </form>
            
                                            ";

                                        } else {

                                            klaida("Tiekėjo su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie tiekėją");
                                        echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "atnaujinti") {

                                    leidimas(array(2));

                                    $tid = null;

                                    if(isset($_GET["tid"]) && !empty($_GET["tid"])) {

                                        $tid = sanitizavimas($_GET["tid"], "int");

                                    }

                                    if(!validavimas($tid, "int")) {

                                        klaida("Tiekėjo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT pavadinimas FROM tiekejai WHERE id = ?")) {

                                        $stmt->bind_param("i", $tid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $tikrinimas = $rezultatas->fetch_assoc();

                                            $pavadinimas = sanitizavimas($_POST["pavadinimas"], "string");
                                            $telefonas = sanitizavimas($_POST["telefonas"], "string");
                                            $pastas = sanitizavimas($_POST["pastas"], "pastas");
        
                                            $prekes = null;
        
                                            if(isset($_POST["prekes"]) && !empty($_POST["prekes"])) {
        
                                                $prekes = sanitizavimas($_POST["prekes"], "string");
        
                                            }
        
                                            $pastaba = null;
        
                                            if(isset($_POST["pastaba"]) && !empty($_POST["pastaba"])) {
        
                                                $pastaba = sanitizavimas($_POST["pastaba"], "string");
        
                                            }
        
                                            if(!validavimas($pavadinimas, "nera")) {
        
                                                klaida("Nenurodytas tiekėjo pavadinimas");
                                                echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                                exit;
        
                                            }
        
                                            if(!validavimas($telefonas, "telefonas")) {
        
                                                klaida("Tiekėjo telefono numeris nenurodytas arba nurodytas neteisingai");
                                                echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                                exit;
        
                                            }
        
                                            if(!validavimas($pastas, "pastas")) {
                                                
                                                klaida("Nenurodytas tiekėjo elektroninis paštas");
                                                echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                                exit;
        
                                            }

                                            if($tikrinimas["pavadinimas"] == $pavadinimas) {

                                                if($stmt2 = $conn->prepare("UPDATE tiekejai SET pavadinimas = ?, telefonas = ?, pastas = ?, prekes = ?, pastaba = ? WHERE id = ?")) {

                                                    $stmt2->bind_param("sssssi", $pavadinimas, $telefonas, $pastas, $prekes, $pastaba, $tid);
    
                                                    $stmt2->execute();
    
                                                    $stmt2->close();
    
                                                    rezultatas("Tiekėjo informacija sėkmingai atnaujinta");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                                    exit;
    
                                                } else {
    
                                                    klaida("Klaida atnaujinant tiekėjo informaciją");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                                    exit;
    
                                                }

                                            } else {

                                                if($stmt2 = $conn->prepare("SELECT id FROM tiekejai WHERE pavadinimas = ?")) {

                                                    $stmt2->bind_param("s", $pavadinimas);
            
                                                    $stmt2->execute();
            
                                                    $rezultatas2 = $stmt2->get_result();
            
                                                    if($rezultatas2->num_rows == 0) {

                                                        if($stmt3 = $conn->prepare("UPDATE tiekejai SET pavadinimas = ?, telefonas = ?, pastas = ?, prekes = ?, pastaba = ? WHERE id = ?")) {

                                                            $stmt3->bind_param("sssssi", $pavadinimas, $telefonas, $pastas, $prekes, $pastaba, $tid);
            
                                                            $stmt3->execute();
            
                                                            $stmt3->close();
            
                                                            rezultatas("Tiekėjo informacija sėkmingai atnaujinta");
                                                            echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                                            exit;
            
                                                        } else {
            
                                                            klaida("Klaida atnaujinant tiekėjo informaciją");
                                                            echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                                            exit;
            
                                                        }
            
                                                    } else {
            
                                                        klaida("Tiekėjas panašiu pavadinimu jau yra sukurtas");
                                                        echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                                        exit;
            
                                                    }

                                                    $stmt2->close();
            
                                                } else {
            
                                                    klaida("Klaida gaunant duomenis apie tiekėją");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                                    exit;
            
                                                }

                                            }

                                        } else {

                                            klaida("Tiekėjo su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie tiekėją");
                                        echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "istrinti") {

                                    leidimas(array(2));
                                    
                                    $tid = null;

                                    if(isset($_GET["tid"]) && !empty($_GET["tid"])) {

                                        $tid = sanitizavimas($_GET["tid"], "int");

                                    }

                                    if(!validavimas($tid, "int")) {

                                        klaida("Tiekėjo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id FROM tiekejai WHERE id = ?")) {

                                        $stmt->bind_param("i", $tid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            if($stmt2 = $conn->prepare("DELETE FROM tiekejai WHERE id = ?")) {

                                                $stmt2->bind_param("i", $tid);

                                                $stmt2->execute();

                                                $stmt2->close();

                                                rezultatas("Tiekėjas sėkmingai ištrintas");
                                                echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                                exit;

                                            } else {

                                                klaida("Klaida ištrinant tiekėją");
                                                echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                                exit;

                                            }

                                        } else {

                                            klaida("Tiekėjo su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie tiekėją");
                                        echo "<meta http-equiv='Refresh' content='0; url=./tiekejai.php'>";
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