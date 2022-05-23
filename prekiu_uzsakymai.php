<?php

session_start();

require_once 'sesija.php';

require_once 'duomenu_baze.php';

require_once 'funkcijos.php';

$veiksmas = null;
$rez = null;
$klaida = null;

if(isset($_GET["veiksmas"])) {

    $veiksmas = $_GET["veiksmas"];

}

if(isset($_SESSION["rezultatas"])) {

    $rez = $_SESSION["rezultatas"];

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

        <title>Prekių užsakymai | Logistikos sistema (IF180026)</title>

    </head>

    <body class="h-100">

        <div class="container-fluid h-100 p-0">

            <div class="row m-0 h-100">

                <?php require_once 'navigacija.php'; ?>

                    <div class="container-fluid">

                        <h2 class="py-3 fw-bold"><i class='fa-solid fa-boxes-stacked fa-fw my-auto me-2'></i> Prekių užsakymai</h2>

                        <?php

                            if(empty($veiksmas)) {

                                echo "
                                
                                <div class='d-flex flex-row justify-content-start mb-3'>

                                    <h4 class='my-auto'>Prekių užsakymų sąrašas</h4>

                                </div>
        
                                <div class='container-fluid p-0'>

                                    <div class='col-12 col-lg-6 p-0'>

                                        <form method='post' action='prekiu_uzsakymai.php?veiksmas=paieska'>

                                            <div class='input-group mb-3'>

                                                <input type='text' class='form-control' id='paieska' name='paieska' placeholder='Prekių užsakymo informacija'>
                                                <button class='btn btn-outline-secondary' type='submit'>Ieškoti</button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                                ";

                                if(!empty($rez)) {

                                    echo "
                                    
                                    <div class='alert alert-success' role='alert'>
                                        ".$rez."
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

                                if($stmt = $conn->prepare("SELECT prekes_uzsakymai.id AS id, prekes.pavadinimas AS preke, sandeliai.pavadinimas AS sandelis, tiekejai.pavadinimas AS tiekejas, prekes_uzsakymai.kiekis AS kiekis, prekes_uzsakymai.suma AS suma, prekes_uzsakymai.data AS data, prekes_uzsakymai.patvirtintas AS patvirtintas FROM prekes_uzsakymai, prekes, sandeliai, tiekejai WHERE prekes.id = prekes_uzsakymai.preke AND sandeliai.id = prekes_uzsakymai.sandelis AND tiekejai.id = prekes_uzsakymai.tiekejas ORDER BY prekes_uzsakymai.data DESC")) {

                                    $stmt->execute();

                                    $rezultatas = $stmt->get_result();

                                    if($rezultatas->num_rows > 0) {

                                        echo "
                                        
                                        <div class='table-responsive'>
        
                                            <table class='table table-striped table-hover'>
                                            
                                                <thead>
                
                                                    <tr>
                                                        <th scope='col'>ID</th>
                                                        <th scope='col'>Prekės pavadinimas</th>
                                                        <th scope='col'>Sandėlis</th>
                                                        <th scope='col'>Tiekėjas</th>
                                                        <th scope='col'>Kiekis</th>
                                                        <th scope='col'>Užsakymo suma</th>
                                                        <th scope='col'>Data</th>
                                                        <th scope='col'>Patvirtintas</th>
                                                        <th scope='col'></th>
                                                    </tr>
                
                                                </thead>
                
                                                <tbody>

                                        ";

                                        while($uzsakymas = $rezultatas->fetch_assoc()) {

                                            echo "
                                            
                                            <tr>
                                                <th class='align-middle'>".$uzsakymas["id"]."</th>
                                                <td class='align-middle'>".$uzsakymas["preke"]."</td>
                                                <td class='align-middle'>".$uzsakymas["sandelis"]."</td>
                                                <td class='align-middle'>".str_replace("\\", "", $uzsakymas["tiekejas"])."</td>
                                                <td class='align-middle'>".$uzsakymas["kiekis"]."</td>
                                                <td class='align-middle'>".number_format($uzsakymas["suma"] / 100, 2, ",", "")." Eur</td>
                                                <td class='align-middle'>".$uzsakymas["data"]."</td>
                                            ";

                                            if(!$uzsakymas["patvirtintas"]) {

                                                echo "
                                                
                                                <td class='align-middle'>Ne</td>
                                                <td class='align-middle d-flex flex-row py-5 py-lg-2'>
                                                    <a href='prekiu_uzsakymai.php?veiksmas=patvirtinti&puid=".$uzsakymas["id"]."'><button type='button' class='btn btn-success btn-sm me-2 '>Patvirtinti</button></a>
                                                    <a href='prekiu_uzsakymai.php?veiksmas=redaguoti&puid=".$uzsakymas["id"]."'><button type='button' class='btn btn-warning btn-sm me-2 '>Redaguoti</button></a>
                                                    <a href='prekiu_uzsakymai.php?veiksmas=istrinti&puid=".$uzsakymas["id"]."'><button type='button' class='btn btn-danger btn-sm'>Ištrinti</button></a>
                                                </td>

                                                ";

                                            } else {

                                                echo "

                                                <td class='align-middle'>Taip</td>
                                                <td></td>
                                                
                                                ";

                                            }
                                            
                                            echo "

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
                                            Prekių užsakymų nėra
                                        </div>

                                        ";

                                    }

                                    $stmt->close();
                                    
                                } else {

                                    klaida("Klaida gaunant prekių užsakymus");
                                    echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                    exit;

                                }

                            } else {

                                if($veiksmas == "paieska") {

                                    leidimas(array(2));

                                    $paieska = sanitizavimas($_POST["paieska"], "string");

                                    if(!validavimas($paieska, "nera")) {
        
                                        klaida("Nenurodyta paieškos frazė");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                        exit;

                                    }

                                    echo "
                                
                                    <div class='d-flex flex-row justify-content-start mb-3'>
    
                                        <h4 class='my-auto'>Prekių užsakymų sąrašas</h4>
    
                                    </div>
            
                                    <div class='container-fluid p-0'>
    
                                        <div class='col-12 col-lg-6 p-0'>
    
                                            <form method='post' action='prekiu_uzsakymai.php?veiksmas=paieska'>
    
                                                <div class='input-group mb-3'>
    
                                                    <input type='text' class='form-control' id='paieska' name='paieska' placeholder='Prekių užsakymo informacija' value='".$paieska."'>
                                                    <button class='btn btn-outline-secondary' type='submit'>Ieškoti</button>
    
                                                </div>
    
                                            </form>
    
                                        </div>
    
                                    </div>
    
                                    ";

                                    if($stmt = $conn->prepare("SELECT prekes_uzsakymai.id AS id, prekes.pavadinimas AS preke, sandeliai.pavadinimas AS sandelis, tiekejai.pavadinimas AS tiekejas, prekes_uzsakymai.kiekis AS kiekis, prekes_uzsakymai.suma AS suma, prekes_uzsakymai.data AS data, prekes_uzsakymai.patvirtintas AS patvirtintas FROM prekes_uzsakymai, prekes, sandeliai, tiekejai WHERE (prekes.pavadinimas LIKE ? OR sandeliai.pavadinimas LIKE ? OR tiekejai.pavadinimas LIKE ? OR prekes_uzsakymai.data LIKE ?) AND prekes.id = prekes_uzsakymai.preke AND sandeliai.id = prekes_uzsakymai.sandelis AND tiekejai.id = prekes_uzsakymai.tiekejas")) {

                                        $paieska = "%".$paieska."%";

                                        $stmt->bind_param("ssss", $paieska, $paieska, $paieska, $paieska);

                                        $stmt->execute();
    
                                        $rezultatas = $stmt->get_result();
    
                                        if($rezultatas->num_rows > 0) {
    
                                            echo "
                                            
                                            <div class='table-responsive'>
            
                                                <table class='table table-striped table-hover'>
                                                
                                                    <thead>
                    
                                                        <tr>
                                                            <th scope='col'>ID</th>
                                                            <th scope='col'>Prekės pavadinimas</th>
                                                            <th scope='col'>Sandėlis</th>
                                                            <th scope='col'>Tiekėjas</th>
                                                            <th scope='col'>Kiekis</th>
                                                            <th scope='col'>Užsakymo suma</th>
                                                            <th scope='col'>Data</th>
                                                            <th scope='col'>Patvirtintas</th>
                                                            <th scope='col'></th>
                                                        </tr>
                    
                                                    </thead>
                    
                                                    <tbody>
    
                                            ";
    
                                            while($uzsakymas = $rezultatas->fetch_assoc()) {
    
                                                echo "
                                                
                                                <tr>
                                                    <th class='align-middle'>".$uzsakymas["id"]."</th>
                                                    <td class='align-middle'>".$uzsakymas["preke"]."</td>
                                                    <td class='align-middle'>".$uzsakymas["sandelis"]."</td>
                                                    <td class='align-middle'>".str_replace("\\", "", $uzsakymas["tiekejas"])."</td>
                                                    <td class='align-middle'>".$uzsakymas["kiekis"]."</td>
                                                    <td class='align-middle'>".number_format($uzsakymas["suma"] / 100, 2, ",", "")." Eur</td>
                                                    <td class='align-middle'>".$uzsakymas["data"]."</td>
                                                ";
    
                                                if(!$uzsakymas["patvirtintas"]) {
    
                                                    echo "
                                                    
                                                    <td class='align-middle'>Ne</td>
                                                    <td class='align-middle d-flex flex-row py-5 py-lg-2'>
                                                        <a href='prekiu_uzsakymai.php?veiksmas=patvirtinti&puid=".$uzsakymas["id"]."'><button type='button' class='btn btn-success btn-sm me-2 '>Patvirtinti</button></a>
                                                        <a href='prekiu_uzsakymai.php?veiksmas=redaguoti&puid=".$uzsakymas["id"]."'><button type='button' class='btn btn-warning btn-sm me-2 '>Redaguoti</button></a>
                                                        <a href='prekiu_uzsakymai.php?veiksmas=istrinti&puid=".$uzsakymas["id"]."'><button type='button' class='btn btn-danger btn-sm'>Ištrinti</button></a>
                                                    </td>
    
                                                    ";
    
                                                } else {
    
                                                    echo "
    
                                                    <td class='align-middle'>Taip</td>
                                                    <td></td>
                                                    
                                                    ";
    
                                                }
                                                
                                                echo "
    
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
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                        exit;
    
                                    }

                                } else if($veiksmas == "patvirtinti") {

                                    leidimas(array(2));

                                    $puid = null;

                                    if(isset($_GET["puid"]) && !empty($_GET["puid"])) {

                                        $puid = sanitizavimas($_GET["puid"], "int");

                                    }

                                    if(!validavimas($puid, "int")) {

                                        klaida("Prekių užsakymo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id FROM prekes_uzsakymai WHERE id = ? AND patvirtintas = 0")) {

                                        $stmt->bind_param("i", $puid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            if($stmt2 = $conn->prepare("UPDATE prekes_uzsakymai SET patvirtintas = 1 WHERE id = ?")) {

                                                $stmt2->bind_param("i", $puid);
        
                                                $stmt2->execute();
        
                                                $stmt2->close();
        
                                                rezultatas("Prekių užsakymas sėkmingai patvirtintas ir išsiųstas tiekėjui");

                                                echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
        
                                            } else {
        
                                                klaida("Klaida patvirtinant prekių užsakymą");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                                exit;
        
                                            }

                                        } else {

                                            klaida("Prekių užsakymo su nurodytu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant prekių užsakymo informaciją");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "redaguoti") {

                                    leidimas(array(2));

                                    $puid = null;

                                    if(isset($_GET["puid"]) && !empty($_GET["puid"])) {

                                        $puid = sanitizavimas($_GET["puid"], "int");

                                    }

                                    if(!validavimas($puid, "int")) {

                                        klaida("Prekių užsakymo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT prekes.pavadinimas AS preke, sandeliai.pavadinimas AS sandelis, prekes_uzsakymai.kiekis AS kiekis, tiekejai.pavadinimas AS tiekejoPav, prekes_uzsakymai.tiekejas AS tiekejas, prekes_uzsakymai.preke AS pid FROM prekes_uzsakymai, prekes, sandeliai, tiekejai WHERE prekes.id = prekes_uzsakymai.preke AND sandeliai.id = prekes_uzsakymai.sandelis AND tiekejai.id = prekes_uzsakymai.tiekejas AND prekes_uzsakymai.id = ? AND prekes_uzsakymai.patvirtintas = 0")) {

                                        $stmt->bind_param("i", $puid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $uzsakymas = $rezultatas->fetch_assoc();

                                            echo "
                                
                                            <div class='d-flex flex-row justify-content-start mb-3'>
            
                                                <h4>Redaguoti prekių užsakymo informaciją</h4>
                    
                                            </div>
                    
                                            <form method='post' action='prekiu_uzsakymai.php?veiksmas=atnaujinti&puid=".$puid."'>
        
                                                <div class='mb-3'>
                                                    <label for='pavadinimas' class='form-label'>Prekės pavadinimas</label>
                                                    <input type='text' class='form-control' id='pavadinimas' name='pavadinimas' value='".$uzsakymas["preke"]."' disabled>
                                                </div>
        
                                                <div class='mb-3'>
                                                    <label for='sandelis' class='form-label'>Sandėlis</label>
                                                    <input type='text' class='form-control' id='sandelis' name='sandelis' value='".$uzsakymas["sandelis"]."' disabled>
                                                </div>

                                                <div class='mb-3'>
                                                    <label for='tiekejas' class='form-label'>Tiekėjas</label>
                                                    <select class='form-select' id='tiekejas' name='tiekejas'>
                                                        <option value='".$uzsakymas["tiekejas"]."'selected>".str_replace("\\", "", $uzsakymas["tiekejoPav"])."</option>
                                                        
                                                ";
                                    
                                                if($stmt2 = $conn->prepare("SELECT tiekejai.id AS id, tiekejai.pavadinimas AS pavadinimas FROM tiekejai, prekes_tiekejai WHERE prekes_tiekejai.tiekejas = tiekejai.id AND prekes_tiekejai.preke = ? AND tiekejai.id != ?")) {
                                    
                                                    $stmt2->bind_param("ii", $uzsakymas["pid"], $uzsakymas["tiekejas"]);

                                                    $stmt2->execute();
                                    
                                                    $rezultatas2 = $stmt2->get_result();
                                    
                                                    if($rezultatas2->num_rows > 0) {
                                    
                                                        while($tiekejas = $rezultatas2->fetch_assoc()) {
                                    
                                                            echo "<option value='".$tiekejas["id"]."'>".str_replace("\\", "", $tiekejas["pavadinimas"])."</option>";
                                    
                                                        }
                                    
                                                    }
                                    
                                                    $stmt2->close();
                                    
                                                } else {
                                    
                                                    klaida("Klaida gaunant duomenis apie tiekėjus");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                                    exit;
                                    
                                                }                                                        
                                    
                                                echo "
                                    
                                                    </select>
                                                </div>
                                                
                                                <div class='mb-3'>
                                                    <label for='kiekis' class='form-label'>Kiekis</label>
                                                    <input type='text' class='form-control' id='kiekis' name='kiekis' value='".$uzsakymas["kiekis"]."'>
                                                </div>
        
                                                <button type='submit' class='btn btn-success'>Atnaujinti</button>
        
                                            </form>

                                            ";

                                        } else {

                                            klaida("Prekių užsakymo su nurodytu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant prekių užsakymo informaciją");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "atnaujinti") {

                                    leidimas(array(2));

                                    $puid = null;

                                    if(isset($_GET["puid"]) && !empty($_GET["puid"])) {

                                        $puid = sanitizavimas($_GET["puid"], "int");

                                    }

                                    if(!validavimas($puid, "int")) {

                                        klaida("Prekių užsakymo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id, preke FROM prekes_uzsakymai WHERE id = ?")) {

                                        $stmt->bind_param("i", $puid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $preke = $rezultatas->fetch_assoc();
                                            $tiekejas = sanitizavimas($_POST["tiekejas"], "int");
                                            $kiekis = sanitizavimas($_POST["kiekis"], "int");
        
                                            if(!validavimas($tiekejas, "int")) {
        
                                                klaida("Tiekėjas nenurodytas arba nurodytas neteisingai");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                                exit;
        
                                            }

                                            if($kiekis != 0) {

                                                if(!validavimas($kiekis, "int")) {

                                                    klaida("Prekių kiekis nenurodytas arba nurodytas neteisingai");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                                    exit;
            
                                                }
        
                                            }
        
                                            if($kiekis <= 0) {
        
                                                klaida("Prekės kiekis privalo būti didesnis už nulį");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                                exit;
        
                                            }

                                            if($stmt2 = $conn->prepare("UPDATE prekes_uzsakymai SET tiekejas = ?, kiekis = ?, suma = (SELECT SUM(? * kaina) FROM prekes_tiekejai WHERE preke = ? AND tiekejas = ?) WHERE id = ?")) {

                                                $stmt2->bind_param("iiiiii", $tiekejas, $kiekis, $kiekis, $preke["preke"], $tiekejas, $puid);

                                                $stmt2->execute();

                                                $stmt2->close();

                                                rezultatas("Prekių užsakymo informacija sėkmingai atnaujinta");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                                exit;

                                            } else {

                                                klaida("Klaida atnaujinant prekių užsakymo informaciją");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                                exit;

                                            }

                                        } else {

                                            klaida("Prekių užsakymo su nurodytu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant prekių užsakymo informaciją");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "istrinti") {

                                    leidimas(array(2));

                                    $puid = null;

                                    if(isset($_GET["puid"]) && !empty($_GET["puid"])) {

                                        $puid = sanitizavimas($_GET["puid"], "int");

                                    }

                                    if(!validavimas($puid, "int")) {

                                        klaida("Prekių užsakymo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id FROM prekes_uzsakymai WHERE id = ?")) {

                                        $stmt->bind_param("i", $puid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            if($stmt2 = $conn->prepare("DELETE FROM prekes_uzsakymai WHERE id = ?")) {

                                                $stmt2->bind_param("i", $puid);
        
                                                $stmt2->execute();
        
                                                $stmt2->close();
        
                                                rezultatas("Prekių užsakymas sėkmingai ištrintas");

                                                echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
        
                                            } else {
        
                                                klaida("Klaida ištrinant prekių užsakymą");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                                exit;
        
                                            }

                                        } else {

                                            klaida("Prekių užsakymo su nurodytu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant prekių užsakymo informaciją");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekiu_uzsakymai.php'>";
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