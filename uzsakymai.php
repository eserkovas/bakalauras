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

leidimas(array(1, 2));

?>

<!doctype html>

<html lang="en" class="h-100">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="./css/stilius.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>Užsakymai | Logistikos sistema (IF180026)</title>

    </head>

    <body class="h-100">

        <div class="container-fluid h-100 p-0">

            <div class="row m-0 h-100">

                <?php require_once 'navigacija.php'; ?>

                    <div class="container-fluid mb-3">

                        <h2 class="py-3 fw-bold"><i class='fa-solid fa-box fa-fw my-auto me-2'></i> Užsakymai</h2>

                        <?php

                            if(empty($veiksmas)) {

                                echo "
                                
                                <div class='d-flex flex-row justify-content-start mb-3'>

                                    <h4 class='my-auto'>Užsakymų sąrašas</h4>

                                </div>
        
                                <div class='container-fluid p-0'>

                                    <div class='col-12 col-lg-6 p-0'>

                                        <form method='post' action='uzsakymai.php?veiksmas=paieska'>

                                            <div class='input-group mb-3'>

                                                <input type='text' class='form-control' id='paieska' name='paieska' placeholder='Užsakymo informacija'>
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

                                if($stmt = $conn->prepare("SELECT uzsakymai.id AS id, uzsakymai.data AS data, uzsakymai.statusas AS statusas, sandeliai.pavadinimas AS sandelis FROM uzsakymai, sandeliai WHERE uzsakymai.sandelis = sandeliai.id")) {

                                    $stmt->execute();

                                    $rezultatas = $stmt->get_result();

                                    if($rezultatas->num_rows > 0) {

                                        echo "
                                        
                                        <div class='table-responsive'>
        
                                            <table class='table table-striped table-hover'>
                                            
                                                <thead>
                
                                                    <tr>
                                                        <th scope='col'>Numeris</th>
                                                        <th scope='col'>Užsakymo data</th>
                                                        <th scope='col'>Užsakymo statusas</th>
                                                        <th scope='col'>Sandėlis</th>
                                                        <th scope='col'></th>
                                                    </tr>
                
                                                </thead>
                
                                                <tbody>

                                        ";

                                        while($uzsakymas = $rezultatas->fetch_assoc()) {

                                            echo "
                                            
                                            <tr>
                                                <th class='align-middle'>".$uzsakymas["id"]."</th>
                                                <td class='align-middle'>".$uzsakymas["data"]."</td>

                                                ";

                                                if($uzsakymas["statusas"] == 0) {

                                                    echo "
                                                    
                                                        <td class='align-middle'>Nepradėtas vykdyti</td>
                                                    
                                                        <td class='align-middle'>".$uzsakymas["sandelis"]."</td>

                                                    ";

                                                    if(in_array($_SESSION["grupe"], array(1))) {

                                                        echo "
                                                        
                                                            <td class='align-middle py-2'>
                                                                <a href='uzsakymai.php?veiksmas=vykdyti&uid=".$uzsakymas["id"]."'><button type='button' class='btn btn-success btn-sm'>Vykdyti užsakymą</button></a>
                                                            </td>
                                                        
                                                        ";
    
                                                    } else {

                                                        echo "<td></td>";

                                                    }

                                                } else if($uzsakymas["statusas"] == 1) {

                                                    echo "
                                                    
                                                        <td class='align-middle'>Vykdomas</td>
                                                        
                                                        <td class='align-middle'>".$uzsakymas["sandelis"]."</td>

                                                        <td class='align-middle py-2'>
                                                            <a href='uzsakymai.php?veiksmas=perziureti&uid=".$uzsakymas["id"]."'><button type='button' class='btn btn-primary btn-sm'>Peržiūrėti užsakymą</button></a>
                                                        </td>

                                                    ";

                                                } else if($uzsakymas["statusas"] == 2) {

                                                    echo "
                                                    
                                                        <td class='align-middle'>Įvykdytas</td>
                                                        
                                                        <td class='align-middle'>".$uzsakymas["sandelis"]."</td>

                                                        <td class='align-middle py-2'>
                                                            <a href='uzsakymai.php?veiksmas=perziureti&uid=".$uzsakymas["id"]."'><button type='button' class='btn btn-primary btn-sm'>Peržiūrėti užsakymą</button></a>
                                                        </td>

                                                    ";

                                                } else if($uzsakymas["statusas" == 3]) {

                                                    echo "
                                                    
                                                        <td class='align-middle'>Išsiųstas klientui</td>
                                                        
                                                        <td class='align-middle'>".$uzsakymas["sandelis"]."</td>

                                                        <td class='align-middle py-2'>
                                                            <a href='uzsakymai.php?veiksmas=perziureti&uid=".$uzsakymas["id"]."'><button type='button' class='btn btn-primary btn-sm'>Peržiūrėti užsakymą</button></a>
                                                        </td>

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
                                            Užsakymų nėra
                                        </div>

                                        ";

                                    }

                                    $stmt->close();
                                    
                                } else {

                                    klaida("Klaida gaunant užsakymus");
                                    echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                    exit;

                                }

                            } else {

                                if($veiksmas == "paieska") {

                                    $paieska = sanitizavimas($_POST["paieska"], "string");

                                    if(!validavimas($paieska, "nera")) {
        
                                        klaida("Nenurodyta paieškos frazė");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;

                                    }

                                    echo "
                                
                                    <div class='d-flex flex-row justify-content-start mb-3'>
    
                                        <h4 class='my-auto'>Užsakymų sąrašas</h4>
    
                                    </div>
            
                                    <div class='container-fluid p-0'>
    
                                        <div class='col-12 col-lg-6 p-0'>
    
                                            <form method='post' action='uzsakymai.php?veiksmas=paieska'>
    
                                                <div class='input-group mb-3'>
    
                                                    <input type='text' class='form-control' id='paieska' name='paieska' placeholder='Užsakymo informacija' value='".$paieska."'>
                                                    <button class='btn btn-outline-secondary' type='submit'>Ieškoti</button>
    
                                                </div>
    
                                            </form>
    
                                        </div>
    
                                    </div>
    
                                    ";

                                    if($stmt = $conn->prepare("SELECT uzsakymai.id AS id, uzsakymai.data AS data, uzsakymai.statusas AS statusas, sandeliai.pavadinimas AS sandelis FROM uzsakymai, sandeliai WHERE uzsakymai.sandelis = sandeliai.id AND (uzsakymai.id LIKE ? OR uzsakymai.data LIKE ? OR sandeliai.pavadinimas LIKE ?)")) {

                                        $paieska = "%".$paieska."%";

                                        $stmt->bind_param("sss", $paieska, $paieska, $paieska);

                                        $stmt->execute();
    
                                        $rezultatas = $stmt->get_result();
    
                                        if($rezultatas->num_rows > 0) {
    
                                            echo "
                                            
                                            <div class='table-responsive'>
            
                                                <table class='table table-striped table-hover'>
                                                
                                                    <thead>
                    
                                                        <tr>
                                                            <th scope='col'>Numeris</th>
                                                            <th scope='col'>Užsakymo data</th>
                                                            <th scope='col'>Užsakymo statusas</th>
                                                            <th scope='col'>Sandėlis</th>
                                                            <th scope='col'></th>
                                                        </tr>
                    
                                                    </thead>
                    
                                                    <tbody>
    
                                            ";
    
                                            while($uzsakymas = $rezultatas->fetch_assoc()) {
    
                                                echo "
                                                
                                                <tr>
                                                    <th class='align-middle'>".$uzsakymas["id"]."</th>
                                                    <td class='align-middle'>".$uzsakymas["data"]."</td>

                                                    ";
    
                                                    if($uzsakymas["statusas"] == 0) {
    
                                                        echo "
                                                        
                                                            <td class='align-middle'>Nepradėtas vykdyti</td>
                                                        
                                                            <td class='align-middle'>".$uzsakymas["sandelis"]."</td>
                                                            
                                                        ";

                                                        if(in_array($_SESSION["grupe"], array(1))) {

                                                            echo "
                                                            
                                                            <td class='align-middle py-2'>
                                                                <a href='uzsakymai.php?veiksmas=vykdyti&uid=".$uzsakymas["id"]."'><button type='button' class='btn btn-success btn-sm'>Vykdyti užsakymą</button></a>
                                                            </td>

                                                            ";

                                                        } else {

                                                            echo "<td></td>";

                                                        }
    
                                                    } else if($uzsakymas["statusas"] == 1) {
    
                                                        echo "
                                                        
                                                            <td class='align-middle'>Vykdomas</td>
                                                            
                                                            <td class='align-middle'>".$uzsakymas["sandelis"]."</td>

                                                            <td class='align-middle py-2'>
                                                                <a href='uzsakymai.php?veiksmas=perziureti&uid=".$uzsakymas["id"]."'><button type='button' class='btn btn-primary btn-sm'>Peržiūrėti užsakymą</button></a>
                                                            </td>
    
                                                        ";
    
                                                    } else if($uzsakymas["statusas"] == 2) {
    
                                                        echo "
                                                        
                                                            <td class='align-middle'>Įvykdytas</td>
                                                            
                                                            <td class='align-middle'>".$uzsakymas["sandelis"]."</td>

                                                            <td class='align-middle py-2'>
                                                                <a href='uzsakymai.php?veiksmas=perziureti&uid=".$uzsakymas["id"]."'><button type='button' class='btn btn-primary btn-sm'>Peržiūrėti užsakymą</button></a>
                                                            </td>
    
                                                        ";
    
                                                    } else if($uzsakymas["statusas" == 3]) {
    
                                                        echo "
                                                        
                                                            <td class='align-middle'>Išsiųstas klientui</td>
                                                            
                                                            <td class='align-middle'>".$uzsakymas["sandelis"]."</td>

                                                            <td class='align-middle py-2'>
                                                                <a href='uzsakymai.php?veiksmas=perziureti&uid=".$uzsakymas["id"]."'><button type='button' class='btn btn-primary btn-sm'>Peržiūrėti užsakymą</button></a>
                                                            </td>
    
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
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;
    
                                    }

                                } else if($veiksmas == "vykdyti") {

                                    leidimas(array(1));

                                    $uid = null;

                                    if(isset($_GET["uid"]) && !empty($_GET["uid"])) {

                                        $uid = sanitizavimas($_GET["uid"], "int");

                                    }

                                    if(!validavimas($uid, "int")) {

                                        klaida("Užsakymo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id FROM uzsakymai WHERE id = ?")) {

                                        $stmt->bind_param("i", $uid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            if($stmt2 = $conn->prepare("UPDATE uzsakymai SET statusas = 1 WHERE id = ?")) {

                                                $stmt2->bind_param("i", $uid);
        
                                                $stmt2->execute();
        
                                                $stmt2->close();
        
                                                echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php?veiksmas=perziureti&uid=".$uid."'>";
        
                                            } else {
        
                                                klaida("Klaida nustatant užsakymo statusą");
                                                echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                exit;
        
                                            }

                                        } else {

                                            klaida("Užsakymo su nurodytu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant užsakymo informaciją");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "perziureti") {

                                    leidimas(array(1, 2));

                                    $uid = null;

                                    if(isset($_GET["uid"]) && !empty($_GET["uid"])) {

                                        $uid = sanitizavimas($_GET["uid"], "int");

                                    }

                                    if(!validavimas($uid, "int")) {

                                        klaida("Užsakymo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("UPDATE uzsakymai_prekes, prekes SET uzsakymai_prekes.svoris = prekes.svoris WHERE uzsakymai_prekes.svoris = 0 AND uzsakymai_prekes.preke = prekes.id AND prekes.svoris > 0 AND uzsakymai_prekes.uzsakymas = ?")) {

                                        $stmt->bind_param("i", $uid);

                                        $stmt->execute();

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant prekių svorius");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;
                                        
                                    }

                                    if($stmt = $conn->prepare("SELECT COUNT(uzsakymai_prekes.preke) AS prekiu, SUM(uzsakymai_prekes.kiekis) AS kiekis, SUM(uzsakymai_prekes.kiekis * uzsakymai_prekes.svoris) AS svoris, uzsakymai.statusas AS statusas FROM uzsakymai, uzsakymai_prekes WHERE uzsakymai_prekes.uzsakymas = uzsakymai.id AND uzsakymai.id = ?")) {

                                        $stmt->bind_param("i", $uid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $uzsakymas = $rezultatas->fetch_assoc();

                                            echo "
                                            
                                            <div class='d-flex flex-row justify-content-start mb-3'>
            
                                                <h4 class='fw-bold'>Užsakymas #".$uid."</h4>

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

                                            if($uzsakymas["statusas"] == 0) {

                                                klaida("Užsakymas nėra pradėtas vykdyti");
                                                echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                exit;

                                            } else if($uzsakymas["statusas"] == 1) {

                                                echo "

                                                <div class='container-fluid p-0'>
    
                                                    <h4>Užsakytos prekės</h4>
    
                                                </div>
    
                                                ";
    
                                                if($stmt2 = $conn->prepare("SELECT prekes.pavadinimas AS pavadinimas, prekes.kodas AS kodas, uzsakymai_prekes.id AS upid, uzsakymai_prekes.svoris AS svoris, uzsakymai_prekes.preke AS preke, uzsakymai_prekes.informacija AS informacija, uzsakymai_prekes.kiekis AS kiekis, uzsakymai_prekes.paruosta AS paruosta FROM prekes, uzsakymai_prekes WHERE prekes.id = uzsakymai_prekes.preke AND uzsakymai_prekes.uzsakymas = ?")) {
    
                                                    $stmt2->bind_param("i", $uid);
    
                                                    $stmt2->execute();
                
                                                    $rezultatas2 = $stmt2->get_result();
                
                                                    if($rezultatas2->num_rows > 0) {
                
                                                        echo "
                                                        
                                                        <div class='table-responsive'>
                        
                                                            <table class='table table-striped table-hover'>
                                                            
                                                                <thead>
                                
                                                                    <tr>
                                                                        <th scope='col'>Kodas</th>
                                                                        <th scope='col'>Prekės pavadinimas</th>
                                                                        <th scope='col'>Prekės informacija</th>
                                                                        <th scope='col'>Kiekis</th>
                                                                        <th scope='col'>Svoris (1 vnt.)</th>
                                                                        <th scope='col'>Paruošta</th>
                                                                        <th scope='col'></th>
                                                                    </tr>
                                
                                                                </thead>
                                
                                                                <tbody>
                
                                                        ";
                
                                                        while($preke = $rezultatas2->fetch_assoc()) {
                
                                                            echo "
                                                            
                                                            <tr>
                                                                <th class='align-middle'>".$preke["kodas"]."</th>
                                                                <td class='align-middle'>".$preke["pavadinimas"]."</td>
                                                                <td class='align-middle'>".$preke["informacija"]."</td>
                                                                <td class='align-middle'>".$preke["kiekis"]."</td>
                                                                
                                                                ";
    
                                                                if($preke["svoris"] == 0) {
    
                                                                    if(in_array($_SESSION["grupe"], array(1))) {

                                                                        echo "<td class='align-middle'><a href='uzsakymai.php?veiksmas=svoris&upid=".$preke["upid"]."'><button type='button' class='btn btn-warning btn-sm'>Įvesti</button></a></td>";

                                                                    } else {

                                                                        echo "<td class='align-middle fst-italic'>Neįvesta</td>";

                                                                    }
    
                                                                } else {
    
                                                                    echo "<td class='align-middle'>".$preke["svoris"]." g</td>";
                                                                    
                                                                }
                
                                                                if(in_array($_SESSION["grupe"], array(1))) {

                                                                    if($preke["paruosta"] == 0) {
                
                                                                        echo "
        
                                                                            <td class='align-middle'>Ne</td>
        
                                                                            <td>
                                                                                <a href='uzsakymai.php?veiksmas=paruosta&upid=".$preke["upid"]."'><button type='button' class='btn btn-success btn-sm'>Žymėti kaip paruoštą</button></a>
                                                                            </td>
                                                                            
                                                                        ";
                    
                                                                    } else if($preke["paruosta"] == 1) {
                    
                                                                        echo "
        
                                                                            <td class='align-middle'>Taip</td>
        
                                                                            <td>
                                                                                <a href='uzsakymai.php?veiksmas=neparuosta&upid=".$preke["upid"]."'><button type='button' class='btn btn-danger btn-sm'>Žymėti kaip neparuoštą</button></a>
                                                                            </td>
                    
                                                                        ";
                                                                    
                                                                    }

                                                                } else {

                                                                    if($preke["paruosta"] == 0) {
                
                                                                        echo "
        
                                                                            <td class='align-middle'>Ne</td>
        
                                                                            <td></td>
                                                                            
                                                                        ";
                    
                                                                    } else if($preke["paruosta"] == 1) {
                    
                                                                        echo "
        
                                                                            <td class='align-middle'>Taip</td>
        
                                                                            <td></td>
                    
                                                                        ";
                                                                    
                                                                    }

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
                                                            Užsakyme prekių nėra
                                                        </div>
                
                                                        ";
                
                                                    }
                
                                                    $stmt2->close();
                                                    
                                                } else {
                
                                                    klaida("Klaida gaunant užsakymo prekes");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                    exit;
                
                                                }
    
                                                echo "
    
                                                <div class='container-fluid p-0'>
    
                                                    <h4>Užsakymo informacija</h4>
    
                                                    <p class='mb-1'>Prekių skaičius: <b>".$uzsakymas["prekiu"]."</b></p>
    
                                                    <p class='mb-1'>Bendras kiekis: <b>".$uzsakymas["kiekis"]."</b></p>
    
                                                    <p class='mb-1'>Bendras svoris: <b>".number_format($uzsakymas["svoris"] / 1000, 3, ",", "")." kg</b></p>
    
                                                ";
    
                                                if($stmt2 = $conn->prepare("SELECT dezes FROM uzsakymai WHERE id = ?")) {
    
                                                    $stmt2->bind_param("i", $uid);
    
                                                    $stmt2->execute();
                
                                                    $rezultatas2 = $stmt2->get_result();
                
                                                    if($rezultatas2->num_rows == 1) {
                
                                                        $dezes = $rezultatas2->fetch_assoc();
    
                                                        if(empty($dezes["dezes"])) {
    
                                                            echo "<p class='mb-2'>Dėžių kiekis: <b>".dezes($uzsakymas["svoris"])."</b></p>";
    
                                                        } else {
    
                                                            echo "<p class='mb-2'>Dėžių kiekis: <b>".$dezes["dezes"]."</b></p>";
    
                                                        }
                
                                                    } else {
                
                                                        klaida("Užsakymo su pateiktu ID nėra");
                                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                        exit;
                
                                                    }
                
                                                    $stmt2->close();
                                                    
                                                } else {
                
                                                    klaida("Klaida gaunant užsakymo dėžes");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                    exit;
                
                                                }
    
                                                if(in_array($_SESSION["grupe"], array(1))) {

                                                    echo "<a href='uzsakymai.php?veiksmas=dezes&uid=".$uid."'><button type='button' class='btn btn-primary btn-sm'>Keisti dėžių kiekį</button></a>";

                                                }

                                                echo "
    
                                                </div>
                                                
                                                <div class='container-fluid p-0 mt-4'>
    
                                                ";
    
                                                if(in_array($_SESSION["grupe"], array(1))) {

                                                    if($stmt2 = $conn->prepare("SELECT id FROM uzsakymai_prekes WHERE paruosta = 0 AND uzsakymas = ?")) {
    
                                                        $stmt2->bind_param("i", $uid);
        
                                                        $stmt2->execute();
        
                                                        $rezultatas2 = $stmt2->get_result();
        
                                                        if($rezultatas2->num_rows == 0) {
        
                                                            echo "<a href='uzsakymai.php?veiksmas=issiusti&uid=".$uid."'><button type='button' class='btn btn-success btn-lg'>Perduoti išsiuntimui</button></a>";
        
                                                        } else {
        
                                                            echo "<button type='button' class='btn btn-success btn-lg' disabled>Perduoti išsiuntimui</button>";
        
                                                        }
        
                                                        $stmt2->close();
        
                                                    } else {
        
                                                        klaida("Klaida gaunant užsakymo duomenis");
                                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                        exit;
        
                                                    }

                                                }
    
                                                echo "
    
                                                </div>
    
                                                ";

                                            } else {

                                                echo "
                                                
                                                <div class='container-fluid p-0'>
    
                                                    <h4>Užsakytos prekės</h4>
    
                                                </div>
    
                                                ";
    
                                                if($stmt2 = $conn->prepare("SELECT prekes.pavadinimas AS pavadinimas, prekes.kodas AS kodas, uzsakymai_prekes.id AS upid, uzsakymai_prekes.svoris AS svoris, uzsakymai_prekes.preke AS preke, uzsakymai_prekes.informacija AS informacija, uzsakymai_prekes.kiekis AS kiekis, uzsakymai_prekes.paruosta AS paruosta FROM prekes, uzsakymai_prekes WHERE prekes.id = uzsakymai_prekes.preke AND uzsakymai_prekes.uzsakymas = ?")) {
    
                                                    $stmt2->bind_param("i", $uid);
    
                                                    $stmt2->execute();
                
                                                    $rezultatas2 = $stmt2->get_result();
                
                                                    if($rezultatas2->num_rows > 0) {
                
                                                        echo "
                                                        
                                                        <div class='table-responsive'>
                        
                                                            <table class='table table-striped table-hover'>
                                                            
                                                                <thead>
                                
                                                                    <tr>
                                                                        <th scope='col'>Kodas</th>
                                                                        <th scope='col'>Prekės pavadinimas</th>
                                                                        <th scope='col'>Prekės informacija</th>
                                                                        <th scope='col'>Kiekis</th>
                                                                        <th scope='col'>Svoris (1 vnt.)</th>
                                                                    </tr>
                                
                                                                </thead>
                                
                                                                <tbody>
                
                                                        ";
                
                                                        while($preke = $rezultatas2->fetch_assoc()) {
                
                                                            echo "
                                                            
                                                            <tr>
                                                                <th class='align-middle'>".$preke["kodas"]."</th>
                                                                <td class='align-middle'>".$preke["pavadinimas"]."</td>
                                                                <td class='align-middle'>".$preke["informacija"]."</td>
                                                                <td class='align-middle'>".$preke["kiekis"]."</td>
                                                                <td class='align-middle'>".$preke["svoris"]." g</td>
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
                                                            Užsakyme prekių nėra
                                                        </div>
                
                                                        ";
                
                                                    }
                
                                                    $stmt2->close();
                                                    
                                                } else {
                
                                                    klaida("Klaida gaunant užsakymo prekes");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                    exit;
                
                                                }
    
                                                echo "
    
                                                <div class='container-fluid p-0'>
    
                                                    <h4>Užsakymo informacija</h4>
    
                                                    <p class='mb-1'>Prekių skaičius: <b>".$uzsakymas["prekiu"]."</b></p>
    
                                                    <p class='mb-1'>Bendras kiekis: <b>".$uzsakymas["kiekis"]."</b></p>
    
                                                    <p class='mb-1'>Bendras svoris: <b>".number_format($uzsakymas["svoris"] / 1000, 3, ",", "")." kg</b></p>
    
                                                ";
    
                                                if($stmt2 = $conn->prepare("SELECT dezes FROM uzsakymai WHERE id = ?")) {
    
                                                    $stmt2->bind_param("i", $uid);
    
                                                    $stmt2->execute();
                
                                                    $rezultatas2 = $stmt2->get_result();
                
                                                    if($rezultatas2->num_rows == 1) {
                
                                                        $dezes = $rezultatas2->fetch_assoc();
    
                                                        echo "<p class='mb-2'>Dėžių kiekis: <b>".$dezes["dezes"]."</b></p>";
                
                                                    } else {
                
                                                        klaida("Užsakymo su pateiktu ID nėra");
                                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                        exit;
                
                                                    }
                
                                                    $stmt2->close();
                                                    
                                                } else {
                
                                                    klaida("Klaida gaunant užsakymo dėžes");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                    exit;
                
                                                }
    
                                                echo "

                                                </div>
                                                
                                                <div class='container-fluid p-0 mt-4'>
    
                                                    <h4>Siuntos informacija</h4>
    
                                                    <div class='row'>

                                                    ";

                                                    if($stmt2 = $conn->prepare("SELECT sandeliai.pavadinimas AS sanPav, sandeliai.telefonas AS sanTelefonas, sandeliai.adresas AS sanAdresas, sandeliai.miestas AS sanMiestas, sandeliai.kodas AS sanKodas, uzsakymai.gavejas AS gavejas, uzsakymai.gavejo_telefonas AS gavTelefonas, uzsakymai.gavejo_pastas AS gavPastas, uzsakymai.adresas AS priAdresas, uzsakymai.miestas AS priMiestas, uzsakymai.kodas AS priKodas, uzsakymai.siuntos_kodas AS siuntos_kodas, uzsakymai.siuntos_data AS siuntos_data FROM sandeliai, uzsakymai WHERE sandeliai.id = uzsakymai.sandelis AND uzsakymai.id = ?")) {
    
                                                        $stmt2->bind_param("i", $uid);
        
                                                        $stmt2->execute();
                    
                                                        $rezultatas2 = $stmt2->get_result();
                    
                                                        if($rezultatas2->num_rows == 1) {
                    
                                                            $siunta = $rezultatas2->fetch_assoc();
        
                                                            echo "
                                                            
                                                            <div class='col'>

                                                                <p class='mb-2'>Siuntos numeris: <b>".$siunta["siuntos_kodas"]."</b></p>
                                                                <p class='mb-2'>Siuntos data: <b>".$siunta["siuntos_data"]."</b></p>

                                                            </div>

                                                            <div class='col'>

                                                                <p class='mb-2'>Paėmimo vieta:</br> <b>".$siunta["sanPav"]."</b></br>".$siunta["sanAdresas"].", ".$siunta["sanMiestas"].", ".$siunta["sanKodas"]."</br>".$siunta["sanTelefonas"]."</p>
                                                            
                                                            </div>

                                                            <div class='col'>

                                                                <p class='mb-2'>Pristatymo vieta:</br> <b>".$siunta["gavejas"]."</b></br>".$siunta["priAdresas"].", ".$siunta["priMiestas"].", ".$siunta["priKodas"]."</br>".$siunta["gavTelefonas"]."</br>".$siunta["gavPastas"]."</p>
                                                            
                                                            </div>

                                                            ";
                    
                                                        } else {
                    
                                                            klaida("Užsakymo su pateiktu ID nėra");
                                                            echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                            exit;
                    
                                                        }
                    
                                                        $stmt2->close();
                                                        
                                                    } else {
                    
                                                        klaida("Klaida gaunant siuntos duomenis");
                                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                        exit;
                    
                                                    }

                                                    echo "

                                                    </div>

                                                </div>

                                                ";

                                            }

                                        } else {

                                            klaida("Užsakymo su nurodytu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant užsakymo informaciją");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "svoris") {

                                    leidimas(array(1));

                                    $upid = null;

                                    if(isset($_GET["upid"]) && !empty($_GET["upid"])) {

                                        $upid = sanitizavimas($_GET["upid"], "int");

                                    }

                                    if(!validavimas($upid, "int")) {

                                        klaida("Užsakymo prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;

                                    }
                                 
                                    if($stmt = $conn->prepare("SELECT uzsakymai_prekes.uzsakymas AS uzsakymas, uzsakymai_prekes.svoris AS svoris, prekes.pavadinimas AS pavadinimas, prekes.kodas AS kodas FROM uzsakymai_prekes, prekes WHERE uzsakymai_prekes.preke = prekes.id AND uzsakymai_prekes.id = ?")) {

                                        $stmt->bind_param("i", $upid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $preke = $rezultatas->fetch_assoc();

                                            echo "
                                            
                                            <div class='d-flex flex-row justify-content-start mb-3'>
            
                                                <h4 class='fw-bold'>Užsakymas #".$preke["uzsakymas"]."</h4>
    
                                            </div>
                                            
                                            <div class='container-fluid p-0 mb-3'>
    
                                                <h4>Prekės <b>".$preke["pavadinimas"]." (".$preke["kodas"].")</b> svorio nustatymas</h4>
    
                                            </div>
    
                                            <form method='post' action='uzsakymai.php?veiksmas=nustatyti_svori&upid=".$upid."&uid=".$preke["uzsakymas"]."'>

                                                <div class='mb-3'>
                                                    <label for='svoris' class='form-label'>Prekės svoris (g)</label>
                                                    <input type='text' class='form-control' id='svoris' name='svoris' value='".$preke["svoris"]."'>
                                                </div>

                                                <button type='submit' class='btn btn-success'>Nustatyti</button>

                                            </form>

                                            ";
                                            
                                        } else {

                                            klaida("Užsakymo prekės pagal nurodytą ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                            exit;

                                        }

                                    } else {

                                        klaida("Klaida gaunant duomenis apie užsakymo prekę");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;
                                        
                                    }

                                } else if($veiksmas == "nustatyti_svori") {

                                    leidimas(array(1));

                                    $uid = null;

                                    if(isset($_GET["uid"]) && !empty($_GET["uid"])) {

                                        $uid = sanitizavimas($_GET["uid"], "int");

                                    }

                                    if(!validavimas($uid, "int")) {

                                        klaida("Užsakymo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;

                                    }

                                    $upid = null;

                                    if(isset($_GET["upid"]) && !empty($_GET["upid"])) {

                                        $upid = sanitizavimas($_GET["upid"], "int");

                                    }

                                    if(!validavimas($upid, "int")) {

                                        klaida("Užsakymo prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;

                                    }

                                    $svoris = sanitizavimas($_POST["svoris"], "int");

                                    if($svoris != 0) {

                                        if(!validavimas($svoris, "int")) {
        
                                            klaida("Prekės svoris nenurodytas arba nurodytas neteisingai");
                                            echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php?veiksmas=perziureti&uid=".$uid."'>";
                                            exit;
    
                                        }

                                    }

                                    if($svoris <= 0) {

                                        klaida("Prekės svoris privalo būti didesnis už nulį");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php?veiksmas=perziureti&uid=".$uid."'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id, uzsakymas FROM uzsakymai_prekes WHERE id = ?")) {

                                        $stmt->bind_param("i", $upid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $uzsakymas = $rezultatas->fetch_assoc();

                                            if($stmt2 = $conn->prepare("UPDATE uzsakymai_prekes SET svoris = ? WHERE id = ?")) {

                                                $stmt2->bind_param("ii", $svoris, $upid);

                                                $stmt2->execute();

                                                $stmt2->close();

                                                rezultatas("Prekės svoris nustatytas sėkmingai");
                                                echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php?veiksmas=perziureti&uid=".$uzsakymas["uzsakymas"]."'>";
                                                exit;

                                            } else {

                                                klaida("Klaida nustatant prekės svorį");
                                                echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                exit;

                                            }
                                            
                                        } else {

                                            klaida("Užsakymo prekės pagal nurodytą ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie užsakymo prekę");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;
                                        
                                    }

                                } else if($veiksmas == "paruosta") {

                                    leidimas(array(1));

                                    $upid = null;

                                    if(isset($_GET["upid"]) && !empty($_GET["upid"])) {

                                        $upid = sanitizavimas($_GET["upid"], "int");

                                    }

                                    if(!validavimas($upid, "int")) {

                                        klaida("Užsakymo prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id, uzsakymas, svoris FROM uzsakymai_prekes WHERE id = ?")) {

                                        $stmt->bind_param("i", $upid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $uzsakymas = $rezultatas->fetch_assoc();

                                            if($uzsakymas["svoris"] <= 0 || empty($uzsakymas["svoris"])) {

                                                klaida("Nenustatytas prekės svoris");
                                                echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php?veiksmas=perziureti&uid=".$uzsakymas["uzsakymas"]."'>";
                                                exit;

                                            }

                                            if($stmt2 = $conn->prepare("UPDATE uzsakymai_prekes SET paruosta = 1 WHERE id = ?")) {

                                                $stmt2->bind_param("i", $upid);

                                                $stmt2->execute();

                                                $stmt2->close();

                                                echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php?veiksmas=perziureti&uid=".$uzsakymas["uzsakymas"]."'>";
                                                exit;

                                            } else {

                                                klaida("Klaida nustatant prekės paruošimą");
                                                echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                exit;

                                            }
                                            
                                        } else {

                                            klaida("Užsakymo prekės pagal nurodytą ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie užsakymo prekę");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;
                                        
                                    }

                                } else if($veiksmas == "neparuosta") {

                                    leidimas(array(1));

                                    $upid = null;

                                    if(isset($_GET["upid"]) && !empty($_GET["upid"])) {

                                        $upid = sanitizavimas($_GET["upid"], "int");

                                    }

                                    if(!validavimas($upid, "int")) {

                                        klaida("Užsakymo prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id, uzsakymas FROM uzsakymai_prekes WHERE id = ?")) {

                                        $stmt->bind_param("i", $upid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $uzsakymas = $rezultatas->fetch_assoc();

                                            if($stmt2 = $conn->prepare("UPDATE uzsakymai_prekes SET paruosta = 0 WHERE id = ?")) {

                                                $stmt2->bind_param("i", $upid);

                                                $stmt2->execute();

                                                $stmt2->close();

                                                echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php?veiksmas=perziureti&uid=".$uzsakymas["uzsakymas"]."'>";
                                                exit;

                                            } else {

                                                klaida("Klaida nustatant prekės paruošimą");
                                                echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                exit;

                                            }
                                            
                                        } else {

                                            klaida("Užsakymo prekės pagal nurodytą ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie užsakymo prekę");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;
                                        
                                    }

                                } else if($veiksmas == "dezes") {

                                    leidimas(array(1));

                                    $uid = null;

                                    if(isset($_GET["uid"]) && !empty($_GET["uid"])) {

                                        $uid = sanitizavimas($_GET["uid"], "int");

                                    }

                                    if(!validavimas($uid, "int")) {

                                        klaida("Užsakymo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;

                                    }
                                 
                                    if($stmt = $conn->prepare("SELECT uzsakymai.dezes AS dezes, SUM(uzsakymai_prekes.kiekis * uzsakymai_prekes.svoris) AS svoris FROM uzsakymai, uzsakymai_prekes WHERE uzsakymai_prekes.uzsakymas = uzsakymai.id AND uzsakymai.id = ?")) {

                                        $stmt->bind_param("i", $uid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $uzsakymas = $rezultatas->fetch_assoc();

                                            echo "
                                            
                                            <div class='d-flex flex-row justify-content-start mb-3'>
            
                                                <h4 class='fw-bold'>Užsakymas #".$uid."</h4>
    
                                            </div>
                                            
                                            <div class='container-fluid p-0 mb-3'>
    
                                                <h4>Dėžių kiekio nustatymas</h4>
    
                                            </div>
    
                                            <form method='post' action='uzsakymai.php?veiksmas=nustatyti_dezes&uid=".$uid."'>

                                                <div class='mb-3'>
                                                    <label for='dezes' class='form-label'>Dėžių kiekis</label>
                                                    
                                                    ";

                                                    if(empty($uzsakymas["dezes"])) {

                                                        echo "<input type='text' class='form-control' id='dezes' name='dezes' value='".dezes($uzsakymas["svoris"])."'>";
                                                        
                                                    } else {

                                                        echo "<input type='text' class='form-control' id='dezes' name='dezes' value='".$uzsakymas["dezes"]."'>";

                                                    }

                                                    echo "

                                                </div>

                                                <button type='submit' class='btn btn-success'>Nustatyti</button>

                                            </form>

                                            ";
                                            
                                        } else {

                                            klaida("Užsakymo su nurodytu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                            exit;

                                        }

                                    } else {

                                        klaida("Klaida gaunant duomenis apie užsakymą");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;
                                        
                                    }

                                } else if($veiksmas == "nustatyti_dezes") {

                                    leidimas(array(1));

                                    $uid = null;

                                    if(isset($_GET["uid"]) && !empty($_GET["uid"])) {

                                        $uid = sanitizavimas($_GET["uid"], "int");

                                    }

                                    if(!validavimas($uid, "int")) {

                                        klaida("Užsakymo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;

                                    }

                                    $dezes = sanitizavimas($_POST["dezes"], "int");

                                    if($dezes != 0) {

                                        if(!validavimas($dezes, "int")) {
        
                                            klaida("Dėžių kiekis nenurodytas arba nurodytas neteisingai");
                                            echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php?veiksmas=perziureti&uid=".$uid."'>";
                                            exit;
    
                                        }

                                    }

                                    if($dezes <= 0) {

                                        klaida("Dėžių kiekis privalo būti didesnis už nulį");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php?veiksmas=perziureti&uid=".$uid."'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id FROM uzsakymai WHERE id = ?")) {

                                        $stmt->bind_param("i", $uid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            if($stmt2 = $conn->prepare("UPDATE uzsakymai SET dezes = ? WHERE id = ?")) {

                                                $stmt2->bind_param("ii", $dezes, $uid);

                                                $stmt2->execute();

                                                $stmt2->close();

                                                rezultatas("Dėžių kiekis nustatytas sėkmingai");
                                                echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php?veiksmas=perziureti&uid=".$uid."'>";
                                                exit;

                                            } else {

                                                klaida("Klaida nustatant dėžių kiekį");
                                                echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                exit;

                                            }
                                            
                                        } else {

                                            klaida("Užsakymo su nurodytu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie užsakymą");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;
                                        
                                    }

                                } else if($veiksmas == "issiusti") {

                                    leidimas(array(1));

                                    $uid = null;

                                    if(isset($_GET["uid"]) && !empty($_GET["uid"])) {

                                        $uid = sanitizavimas($_GET["uid"], "int");

                                    }

                                    if(!validavimas($uid, "int")) {

                                        klaida("Užsakymo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT uzsakymai.dezes AS dezes, SUM(uzsakymai_prekes.kiekis * uzsakymai_prekes.svoris) AS svoris FROM uzsakymai, uzsakymai_prekes WHERE uzsakymai_prekes.uzsakymas = uzsakymai.id AND uzsakymai.id = ?")) {

                                        $stmt->bind_param("i", $uid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $uzsakymas = $rezultatas->fetch_assoc();

                                            if($stmt2 = $conn->prepare("SELECT id FROM uzsakymai_prekes WHERE paruosta = 0 AND uzsakymas = ?")) {
    
                                                $stmt2->bind_param("i", $uid);

                                                $stmt2->execute();

                                                $rezultatas2 = $stmt2->get_result();

                                                if($rezultatas2->num_rows > 0) {

                                                    klaida("Visi produktai privalo būti pažymėti kaip paruošti");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php?veiksmas=perziureti&uid=".$uid."'>";
                                                    exit;

                                                }

                                                $stmt2->close();

                                            } else {

                                                klaida("Klaida gaunant užsakymo duomenis");
                                                echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                exit;

                                            }

                                            if($stmt2 = $conn->prepare("UPDATE uzsakymai SET statusas = 2, uzsakymai.svoris = (SELECT SUM(kiekis * svoris) FROM uzsakymai_prekes WHERE uzsakymas = ?), siuntos_data = NOW() WHERE id = ?")) {

                                                $stmt2->bind_param("ii", $uid, $uid);

                                                $stmt2->execute();

                                                $stmt2->close();

                                            } else {

                                                klaida("Klaida nustatant užsakymo duomenis");
                                                echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                exit;

                                            }
                                            
                                            if(empty($uzsakymas["dezes"])) {

                                                if($stmt2 = $conn->prepare("UPDATE uzsakymai SET dezes = ? WHERE id = ?")) {

                                                    $dezes = dezes($uzsakymas["svoris"]);

                                                    $stmt2->bind_param("ii", $dezes, $uid);
    
                                                    $stmt2->execute();
    
                                                    $stmt2->close();
    
                                                } else {
    
                                                    klaida("Klaida nustatant užsakymo dėžių kiekį");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                                    exit;
    
                                                }
                                                
                                            }

                                            rezultatas("Užsakymas sėkmingai perduotas išsiuntimui");
                                            echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php?veiksmas=perziureti&uid=".$uid."'>";
                                            exit;

                                        } else {

                                            klaida("Užsakymo pagal nurodytą ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie užsakymą");
                                        echo "<meta http-equiv='Refresh' content='0; url=./uzsakymai.php'>";
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