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

leidimas(array(1, 2));

?>

<!doctype html>

<html lang="en" class="h-100">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="./css/stilius.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>Prekės | Logistikos sistema (IF180026)</title>

    </head>

    <body class="h-100">

        <div class="container-fluid h-100 p-0">

            <div class="row m-0 h-100">

                <?php require_once 'navigacija.php'; ?>

                    <div class="container-fluid">

                        <h2 class="py-3 fw-bold"><i class='fa-solid fa-boxes-stacked fa-fw my-auto me-2'></i> Prekės</h2>

                        <?php

                            if(empty($veiksmas)) {

                                echo "
                                
                                <div class='d-flex flex-row justify-content-between mb-3'>

                                    <h4 class='my-auto'>Prekių sąrašas</h4>
        
                                ";

                                if(in_array($_SESSION["grupe"], array(2))) {

                                    echo "<a href='prekes.php?veiksmas=nauja'><button type='button' class='btn btn-primary'>Nauja prekė</button></a>";

                                }
                                
                                echo "
        
                                </div>
        
                                <div class='container-fluid p-0'>

                                    <div class='col-12 col-lg-6 p-0'>

                                        <form method='post' action='prekes.php?veiksmas=paieska'>

                                            <div class='input-group mb-3'>

                                                <input type='text' class='form-control' id='paieska' name='paieska' placeholder='Prekės informacija'>
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

                                if($stmt = $conn->prepare("SELECT * FROM prekes")) {

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
                                                        <th scope='col'>Kodas</th>
                                                        <th scope='col'>Svoris</th>
                                                        <th scope='col'></th>
                                                    </tr>
                
                                                </thead>
                
                                                <tbody>

                                        ";

                                        while($preke = $rezultatas->fetch_assoc()) {

                                            echo "
                                            
                                            <tr>

                                                <th class='align-middle'>".$preke["id"]."</th>
                                                <td class='align-middle'>".str_replace("\\", "", $preke["pavadinimas"])."</td>
                                                <td class='align-middle'>".$preke["kodas"]."</td>
                                                <td class='align-middle'>".$preke["svoris"]." g</td>

                                                <td class='align-middle d-flex flex-row py-3 py-lg-2'>
                                                    <a href='prekes.php?veiksmas=perziureti&pid=".$preke["id"]."'><button type='button' class='btn btn-primary btn-sm me-2'>Peržiūrėti</button></a>
                                            
                                            ";

                                            if(in_array($_SESSION["grupe"], array(2))) {

                                                echo "
                                                
                                                    <a href='prekes.php?veiksmas=redaguoti&pid=".$preke["id"]."'><button type='button' class='btn btn-warning btn-sm me-2 '>Redaguoti</button></a>
                                                    <a href='prekes.php?veiksmas=istrinti&pid=".$preke["id"]."'><button type='button' class='btn btn-danger btn-sm'>Ištrinti</button></a>

                                                ";

                                            }

                                            echo "

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
                                            Prekių sistemoje nėra
                                        </div>

                                        ";

                                    }

                                    $stmt->close();
                                    
                                } else {

                                    klaida("Klaida gaunant prekes");
                                    echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                    exit;

                                }

                            } else {

                                if($veiksmas == "paieska") {

                                    $paieska = sanitizavimas($_POST["paieska"], "string");

                                    if(!validavimas($paieska, "nera")) {
        
                                        klaida("Nenurodyta paieškos frazė");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    echo "
                                    
                                    <div class='d-flex flex-row justify-content-between mb-3'>

                                        <h4 class='my-auto'>Prekių sąrašas</h4>
            
                                    ";

                                    if(in_array($_SESSION["grupe"], array(2))) {

                                        echo "<a href='prekes.php?veiksmas=nauja'><button type='button' class='btn btn-primary'>Nauja prekė</button></a>";

                                    }

                                    echo "
            
                                    </div>
            
                                    <div class='container-fluid p-0'>

                                        <div class='col-12 col-lg-6 p-0'>

                                            <form method='post' action='prekes.php?veiksmas=paieska'>

                                                <div class='input-group mb-3'>

                                                    <input type='text' class='form-control' id='paieska' name='paieska' placeholder='Prekės informacija' value='".$paieska."'>
                                                    <button class='btn btn-outline-secondary' type='submit'>Ieškoti</button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>
                                    
                                    ";

                                    if($stmt = $conn->prepare("SELECT * FROM prekes WHERE kodas LIKE ? OR pavadinimas LIKE ?")) {

                                        $paieska = "%".$paieska."%";

                                        $stmt->bind_param("ss", $paieska, $paieska);

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
                                                            <th scope='col'>Kodas</th>
                                                            <th scope='col'>Svoris</th>
                                                            <th scope='col'></th>
                                                        </tr>
                    
                                                    </thead>
                    
                                                    <tbody>
    
                                            ";
    
                                            while($preke = $rezultatas->fetch_assoc()) {

                                                echo "
                                                
                                                <tr>
    
                                                    <th class='align-middle'>".$preke["id"]."</th>
                                                    <td class='align-middle'>".str_replace("\\", "", $preke["pavadinimas"])."</td>
                                                    <td class='align-middle'>".$preke["kodas"]."</td>
                                                    <td class='align-middle'>".$preke["svoris"]." g</td>
    
                                                    <td class='align-middle d-flex flex-row py-3 py-lg-2'>
                                                        <a href='prekes.php?veiksmas=perziureti&pid=".$preke["id"]."'><button type='button' class='btn btn-primary btn-sm me-2 '>Peržiūrėti</button></a>
                                                
                                                ";
    
                                                if(in_array($_SESSION["grupe"], array(2))) {
    
                                                    echo "
                                                    
                                                        <a href='prekes.php?veiksmas=redaguoti&pid=".$preke["id"]."'><button type='button' class='btn btn-warning btn-sm me-2 '>Redaguoti</button></a>
                                                        <a href='prekes.php?veiksmas=istrinti&pid=".$preke["id"]."'><button type='button' class='btn btn-danger btn-sm'>Ištrinti</button></a>
    
                                                    ";
    
                                                }
    
                                                echo "
    
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
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
    
                                    }

                                } else if($veiksmas == "nauja") {

                                    leidimas(array(2));

                                    echo "
                                
                                    <div class='d-flex flex-row justify-content-start mb-3'>
    
                                        <h4>Nauja prekė</h4>
            
                                    </div>
            
                                    <form method='post' action='prekes.php?veiksmas=sukurti'>

                                        <div class='mb-3'>
                                            <label for='pavadinimas' class='form-label'>Pavadinimas</label>
                                            <input type='text' class='form-control' id='pavadinimas' name='pavadinimas'>
                                        </div>

                                        <div class='mb-3'>
                                            <label for='kodas' class='form-label'>Prekės kodas</label>
                                            <input type='text' class='form-control' id='kodas' name='kodas'>
                                        </div>

                                        <div class='mb-3'>
                                            <label for='svoris' class='form-label'>Prekės svoris (g)</label>
                                            <input type='text' class='form-control' id='svoris' name='svoris'>
                                        </div>

                                        <button type='submit' class='btn btn-success'>Sukurti</button>

                                    </form>
    
                                    ";

                                } else if($veiksmas == "sukurti") {

                                    leidimas(array(2));

                                    $pavadinimas = sanitizavimas($_POST["pavadinimas"], "string");
                                    $kodas = sanitizavimas($_POST["kodas"], "int");
                                    $svoris = sanitizavimas($_POST["svoris"], "int");

                                    if(!validavimas($pavadinimas, "nera")) {
        
                                        klaida("Nenurodytas prekės pavadinimas");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    if(!validavimas($kodas, "int")) {
        
                                        klaida("Nenurodytas prekės kodas");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    if($svoris != 0) {

                                        if(!validavimas($svoris, "int")) {
        
                                            klaida("Prekės svoris nenurodytas arba nurodytas neteisingai");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;
    
                                        }

                                    }

                                    if($svoris < 0) {

                                        klaida("Prekės svoris privalo būti didesnis už nulį");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id FROM prekes WHERE kodas = ?")) {

                                        $stmt->bind_param("i", $kodas);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 0) {

                                            if($stmt2 = $conn->prepare("INSERT INTO prekes (pavadinimas, kodas, svoris) VALUES (?, ?, ?)")) {

                                                $stmt2->bind_param("sii", $pavadinimas, $kodas, $svoris);
        
                                                $stmt2->execute();
        
                                                $stmt2->close();
        
                                                rezultatas("Nauja prekė sėkmingai sukurta");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;
        
                                            } else {
        
                                                klaida("Klaida kuriant naują prekę");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;
        
                                            }

                                        } else {

                                            klaida("Prekė su pateiktu kodu jau sukurta");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;

                                        }

                                    } else {

                                        klaida("Klaida gaunant duomenis apie prekę");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "redaguoti") {

                                    leidimas(array(2));

                                    $pid = null;

                                    if(isset($_GET["pid"]) && !empty($_GET["pid"])) {

                                        $pid = sanitizavimas($_GET["pid"], "int");

                                    }

                                    if(!validavimas($pid, "int")) {

                                        klaida("Prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT * FROM prekes WHERE id = ?")) {

                                        $stmt->bind_param("i", $pid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $preke = $rezultatas->fetch_assoc();

                                            echo "
                                
                                            <div class='d-flex flex-row justify-content-start mb-3'>
            
                                                <h4>Redaguoti prekę</h4>
                    
                                            </div>
                    
                                            <form method='post' action='prekes.php?veiksmas=atnaujinti&pid=".$pid."'>
        
                                                <div class='mb-3'>
                                                    <label for='pavadinimas' class='form-label'>Pavadinimas</label>
                                                    <input type='text' class='form-control' id='pavadinimas' name='pavadinimas' value='".$preke["pavadinimas"]."'>
                                                </div>
        
                                                <div class='mb-3'>
                                                    <label for='kodas' class='form-label'>Prekės kodas</label>
                                                    <input type='text' class='form-control' id='kodas' name='kodas' value='".$preke["kodas"]."'>
                                                </div>
        
                                                <div class='mb-3'>
                                                    <label for='svoris' class='form-label'>Prekės svoris (g)</label>
                                                    <input type='text' class='form-control' id='svoris' name='svoris' value='".$preke["svoris"]."'>
                                                </div>
        
                                                <button type='submit' class='btn btn-success'>Atnaujinti</button>
        
                                            </form>
            
                                            ";

                                        } else {

                                            klaida("Prekės su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;

                                        }

                                    } else {

                                        klaida("Klaida gaunant duomenis apie prekę");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "atnaujinti") {

                                    leidimas(array(2));

                                    $pid = null;

                                    if(isset($_GET["pid"]) && !empty($_GET["pid"])) {

                                        $pid = sanitizavimas($_GET["pid"], "int");

                                    }

                                    if(!validavimas($pid, "int")) {

                                        klaida("Prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    $pavadinimas = sanitizavimas($_POST["pavadinimas"], "string");
                                    $kodas = sanitizavimas($_POST["kodas"], "int");
                                    $svoris = sanitizavimas($_POST["svoris"], "int");

                                    if(!validavimas($pavadinimas, "nera")) {
        
                                        klaida("Nenurodytas prekės pavadinimas");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    if(!validavimas($kodas, "int")) {
        
                                        klaida("Nenurodytas prekės kodas");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    if($svoris != 0) {

                                        if(!validavimas($svoris, "int")) {
        
                                            klaida("Prekės svoris nenurodytas arba nurodytas neteisingai");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;
    
                                        }

                                    }

                                    if($svoris < 0) {

                                        klaida("Prekės svoris privalo būti didesnis už nulį");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT kodas FROM prekes WHERE id = ?")) {

                                        $stmt->bind_param("i", $pid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $tikrinimas = $rezultatas->fetch_assoc();

                                            if($tikrinimas["kodas"] == $kodas) {

                                                if($stmt2 = $conn->prepare("UPDATE prekes SET pavadinimas = ?, kodas = ?, svoris = ? WHERE id = ?")) {

                                                    $stmt2->bind_param("siii", $pavadinimas, $kodas, $svoris, $pid);

                                                    $stmt2->execute();

                                                    $stmt2->close();

                                                    rezultatas("Prekės informacija sėkmingai atnaujinta");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                    exit;

                                                }

                                            } else {

                                                if($stmt2 = $conn->prepare("SELECT id FROM prekes WHERE kodas = ?")) {

                                                    $stmt2->bind_param("i", $kodas);
            
                                                    $stmt2->execute();
            
                                                    $rezultatas2 = $stmt2->get_result();
            
                                                    if($rezultatas2->num_rows == 0) {
            
                                                        if($stmt3 = $conn->prepare("UPDATE prekes SET pavadinimas = ?, kodas = ?, svoris = ? WHERE id = ?")) {

                                                            $stmt3->bind_param("siii", $pavadinimas, $kodas, $svoris, $pid);
        
                                                            $stmt3->execute();
        
                                                            $stmt3->close();
        
                                                            rezultatas("Prekės informacija sėkmingai atnaujinta");
                                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                            exit;
        
                                                        }
            
                                                    } else {
            
                                                        klaida("Prekė su pateiktu kodu jau sukurta");
                                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                        exit;
            
                                                    }
            
                                                    $stmt2->close();

                                                } else {
            
                                                    klaida("Klaida gaunant duomenis apie prekę");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                    exit;
            
                                                }

                                            }

                                        } else {

                                            klaida("Prekės su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie prekę");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "perziureti") {

                                    leidimas(array(1, 2));

                                    $pid = null;

                                    if(isset($_GET["pid"]) && !empty($_GET["pid"])) {

                                        $pid = sanitizavimas($_GET["pid"], "int");

                                    }

                                    if(!validavimas($pid, "int")) {

                                        klaida("Prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT * FROM prekes WHERE id = ?")) {

                                        $stmt->bind_param("i", $pid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $preke = $rezultatas->fetch_assoc();

                                            echo "
                                
                                            <div class='d-flex flex-row justify-content-start mb-3'>
            
                                                <h4>".$preke["pavadinimas"]." (".$preke["kodas"].")</h4>

                                            </div>
            
                                            <div class='container-fluid p-0'>

                                                <p>Prekės svoris: <b>".$preke["svoris"]." g</b></p>

                                            </div>

                                            <div class='d-flex flex-row justify-content-between mb-3'>
            
                                                <h4>Prekės likučiai</h4>

                                            ";

                                            if(in_array($_SESSION["grupe"], array(2))) {

                                                echo "<a href='prekes.php?veiksmas=sandelis&pid=".$pid."'><button type='button' class='btn btn-primary'>Priskirti sandėlį</button></a>";

                                            }

                                            echo "

                                            </div>

                                            <div class='container-fluid p-0'>

                                            ";

                                                if($stmt2 = $conn->prepare("SELECT prekes_sandeliai.likutis AS likutis, sandeliai.id AS sandelioID, sandeliai.pavadinimas AS sandelioPav FROM prekes_sandeliai, sandeliai WHERE prekes_sandeliai.preke = ? AND prekes_sandeliai.sandelis=sandeliai.id")) {

                                                    $stmt2->bind_param("s", $preke["id"]);

                                                    $stmt2->execute();

                                                    $rezultatas2 = $stmt2->get_result();

                                                    if($rezultatas2->num_rows > 0) {

                                                        echo "
                                        
                                                        <div class='table-responsive'>
                        
                                                            <table class='table table-striped table-hover'>
                                                            
                                                                <thead>
                                
                                                                    <tr>
                                                                        <th scope='col'>ID</th>
                                                                        <th scope='col'>Sandėlis</th>
                                                                        <th scope='col'>Prekės likutis</th>
                                                                        <th scope='col'></th>
                                                                    </tr>
                                
                                                                </thead>
                                
                                                                <tbody>
                
                                                        ";

                                                        while($sandelis = $rezultatas2->fetch_assoc()) {

                                                            echo "
                                                            
                                                            <tr>

                                                                <th class='align-middle'>".$sandelis["sandelioID"]."</th>
                                                                <td class='align-middle'>".$sandelis["sandelioPav"]."</td>
                                                                <td class='align-middle'>".$sandelis["likutis"]."</td>

                                                            ";

                                                            if(in_array($_SESSION["grupe"], array(2))) {

                                                                echo "
                                                                
                                                                <td class='align-middle d-flex flex-row py-3 py-lg-2'>
                                                                    <a href='prekes.php?veiksmas=redaguoti_sandeli&pid=".$pid."&sid=".$sandelis["sandelioID"]."'><button type='button' class='btn btn-warning btn-sm me-2 '>Redaguoti</button></a>
                                                                    <a href='prekes.php?veiksmas=istrinti_sandeli&pid=".$pid."&sid=".$sandelis["sandelioID"]."'><button type='button' class='btn btn-danger btn-sm'>Ištrinti</button></a>
                                                                </td>
                                                                
                                                                ";

                                                            } else {

                                                                echo "<td></td>";

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
                                                            Prekė neturi priskirtų sandėlių
                                                        </div>
                
                                                        ";

                                                    }

                                                    $stmt2->close();

                                                } else {

                                                    klaida("Klaida gaunant prekės likučius sandėliuose");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                    exit;

                                                }

                                            echo "

                                            </div>

                                            <div class='d-flex flex-row justify-content-between mb-3'>
            
                                                <h4>Prekės tiekėjai</h4>

                                            ";

                                            if(in_array($_SESSION["grupe"], array(2))) {

                                                echo "<a href='prekes.php?veiksmas=tiekejas&pid=".$pid."'><button type='button' class='btn btn-primary'>Priskirti tiekėją</button></a>";

                                            }

                                            echo "

                                            </div>

                                            <div class='container-fluid p-0'>

                                            ";

                                                if($stmt2 = $conn->prepare("SELECT prekes_tiekejai.kaina AS kaina, tiekejai.id AS tiekejoID, tiekejai.pavadinimas AS tiekejoPav FROM prekes_tiekejai, tiekejai WHERE prekes_tiekejai.preke = ? AND prekes_tiekejai.tiekejas=tiekejai.id")) {

                                                    $stmt2->bind_param("s", $preke["id"]);

                                                    $stmt2->execute();

                                                    $rezultatas2 = $stmt2->get_result();

                                                    if($rezultatas2->num_rows > 0) {

                                                        echo "
                                        
                                                        <div class='table-responsive'>
                        
                                                            <table class='table table-striped table-hover'>
                                                            
                                                                <thead>
                                
                                                                    <tr>
                                                                        <th scope='col'>ID</th>
                                                                        <th scope='col'>Tiekėjas</th>
                                                                        <th scope='col'>Prekės kaina</th>
                                                                        <th scope='col'></th>
                                                                    </tr>
                                
                                                                </thead>
                                
                                                                <tbody>
                
                                                        ";

                                                        while($tiekejas = $rezultatas2->fetch_assoc()) {

                                                            echo "
                                                            
                                                            <tr>

                                                                <th class='align-middle'>".$tiekejas["tiekejoID"]."</th>
                                                                <td class='align-middle'>".str_replace("\\", "", $tiekejas["tiekejoPav"])."</td>
                                                                <td class='align-middle'>".number_format($tiekejas["kaina"] / 100, 2, ",", "")." Eur</td>

                                                            ";

                                                            if(in_array($_SESSION["grupe"], array(2))) {

                                                                echo "
                                                                
                                                                <td class='align-middle d-flex flex-row py-3 py-lg-2'>
                                                                    <a href='prekes.php?veiksmas=redaguoti_tiekeja&pid=".$pid."&tid=".$tiekejas["tiekejoID"]."'><button type='button' class='btn btn-warning btn-sm me-2 '>Redaguoti</button></a>
                                                                    <a href='prekes.php?veiksmas=istrinti_tiekeja&pid=".$pid."&tid=".$tiekejas["tiekejoID"]."'><button type='button' class='btn btn-danger btn-sm'>Ištrinti</button></a>
                                                                </td>
                                                                
                                                                ";

                                                            } else {

                                                                echo "<td></td>";

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
                                                            Prekė neturi priskirtų tiekėjų
                                                        </div>
                
                                                        ";

                                                    }

                                                    $stmt2->close();

                                                } else {

                                                    klaida("Klaida gaunant prekės tiekėjus");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                    exit;

                                                }

                                            echo "

                                            </div>

                                            ";

                                        } else {

                                            klaida("Prekės su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;

                                        }

                                    } else {

                                        klaida("Klaida gaunant duomenis apie prekę");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "sandelis") {

                                    leidimas(array(2));

                                    $pid = null;

                                    if(isset($_GET["pid"]) && !empty($_GET["pid"])) {

                                        $pid = sanitizavimas($_GET["pid"], "int");

                                    }

                                    if(!validavimas($pid, "int")) {

                                        klaida("Prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id FROM sandeliai")) {

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 0) {

                                            klaida("Sistemoje nėra sandėlių");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant sandėlių skaičių");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT * FROM prekes WHERE id = ?")) {

                                        $stmt->bind_param("i", $pid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $preke = $rezultatas->fetch_assoc();

                                            echo "
                                
                                            <div class='d-flex flex-row justify-content-start mb-3'>
            
                                                <h4>Sandėlio priskyrimas prekei <b>".$preke["pavadinimas"]." (".$preke["kodas"].")</b></h4>
                    
                                            </div>
                    
                                            <form method='post' action='prekes.php?veiksmas=priskirti_sandeli&pid=".$pid."'>
        
                                                <div class='mb-3'>
                                                    <select class='form-select' id='sandelis' name='sandelis'>
                                                        <option value='null'selected>Pasirinkite sandėlį</option>
                                                        
                                            ";

                                            if($stmt2 = $conn->prepare("SELECT id, pavadinimas FROM sandeliai")) {

                                                $stmt2->execute();

                                                $rezultatas2 = $stmt2->get_result();

                                                if($rezultatas2->num_rows > 0) {

                                                    while($sandelis = $rezultatas2->fetch_assoc()) {

                                                        echo "<option value='".$sandelis["id"]."'>".$sandelis["pavadinimas"]."</option>";

                                                    }

                                                }

                                                $stmt2->close();

                                            } else {

                                                klaida("Klaida gaunant duomenis apie sandėlius");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;

                                            }                                                        
        
                                            echo "

                                                    </select>
                                                </div>

                                                <div class='mb-3'>
                                                    <label for='likutis' class='form-label'>Prekės likutis</label>
                                                    <input type='text' class='form-control' id='likutis' name='likutis'>
                                                </div>
        
                                                <button type='submit' class='btn btn-success'>Priskirti</button>
        
                                            </form>
            
                                            ";

                                        } else {

                                            klaida("Prekės su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie prekę");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "priskirti_sandeli") {

                                    leidimas(array(2));

                                    $pid = null;

                                    if(isset($_GET["pid"]) && !empty($_GET["pid"])) {

                                        $pid = sanitizavimas($_GET["pid"], "int");

                                    }

                                    if(!validavimas($pid, "int")) {

                                        klaida("Prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }
                                    
                                    $sid = sanitizavimas($_POST["sandelis"], "int");
                                    $likutis = sanitizavimas($_POST["likutis"], "int");

                                    if(!validavimas($sid, "int")) {

                                        klaida("Sandėlis nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    if($likutis != 0) {

                                        if(!validavimas($likutis, "int")) {

                                            klaida("Prekės likutis nenurodytas arba nurodytas neteisingai");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;
    
                                        }

                                    }

                                    if($likutis < 0) {

                                        klaida("Prekės likutis privalo būti didesnis arba lygus nuliui");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                        
                                    }

                                    if($stmt = $conn->prepare("SELECT id FROM prekes_sandeliai WHERE preke = ? AND sandelis = ?")) {

                                        $stmt->bind_param("ii", $pid, $sid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 0) {

                                            if($stmt2 = $conn->prepare("INSERT INTO prekes_sandeliai (preke, sandelis, likutis) VALUES (?, ?, ?)")) {

                                                $stmt2->bind_param("iii", $pid, $sid, $likutis);

                                                $stmt2->execute();

                                                $stmt2->close();

                                                rezultatas("Sandėlis sėkmingai priskirtas prekei");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;

                                            } else {

                                                klaida("Klaida priskiriant sandėlį prekei");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;

                                            }

                                        } else {

                                            klaida("Sandėlis jau yra priskirtas šiai prekei");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie prekės sandėlius");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "redaguoti_sandeli") {

                                    leidimas(array(2));

                                    $pid = null;

                                    if(isset($_GET["pid"]) && !empty($_GET["pid"])) {

                                        $pid = sanitizavimas($_GET["pid"], "int");

                                    }

                                    if(!validavimas($pid, "int")) {

                                        klaida("Prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    $sid = null;

                                    if(isset($_GET["sid"]) && !empty($_GET["sid"])) {

                                        $sid = sanitizavimas($_GET["sid"], "int");

                                    }

                                    if(!validavimas($sid, "int")) {

                                        klaida("Sandėlio ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT prekes.pavadinimas AS pavadinimas, prekes.kodas AS kodas, sandeliai.pavadinimas AS sandelis, prekes_sandeliai.likutis AS likutis FROM prekes, sandeliai, prekes_sandeliai WHERE prekes_sandeliai.sandelis = sandeliai.id AND prekes_sandeliai.preke = prekes.id AND prekes_sandeliai.preke = ? AND prekes_sandeliai.sandelis = ?")) {

                                        $stmt->bind_param("ii", $pid, $sid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $preke = $rezultatas->fetch_assoc();

                                            echo "
                                
                                            <div class='d-flex flex-row justify-content-start mb-3'>
            
                                                <h4>Likučio atnaujinimas sandėlyje prekei <b>".$preke["pavadinimas"]." (".$preke["kodas"].")</b></h4>
                    
                                            </div>
                    
                                            <form method='post' action='prekes.php?veiksmas=atnaujinti_likuti&pid=".$pid."&sid=".$sid."'>
        
                                                <div class='mb-3'>
                                                    <select class='form-select' id='sandelis' name='sandelis' disabled>
                                                        <option value='".$sid."'selected>".$preke["sandelis"]."</option>
                                                    </select>
                                                </div>

                                                <div class='mb-3'>
                                                    <label for='likutis' class='form-label'>Prekės likutis</label>
                                                    <input type='text' class='form-control' id='likutis' name='likutis' value='".$preke["likutis"]."'>
                                                </div>
        
                                                <button type='submit' class='btn btn-success'>Atnaujinti</button>
        
                                            </form>
            
                                            ";

                                        } else {

                                            klaida("Prekės su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie prekę");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "atnaujinti_likuti") {

                                    leidimas(array(2));

                                    $pid = null;

                                    if(isset($_GET["pid"]) && !empty($_GET["pid"])) {

                                        $pid = sanitizavimas($_GET["pid"], "int");

                                    }

                                    if(!validavimas($pid, "int")) {

                                        klaida("Prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }
                                    
                                    $sid = null;

                                    if(isset($_GET["sid"]) && !empty($_GET["sid"])) {

                                        $sid = sanitizavimas($_GET["sid"], "int");

                                    }

                                    if(!validavimas($sid, "int")) {

                                        klaida("Sandėlio ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    $likutis = sanitizavimas($_POST["likutis"], "int");

                                    if($likutis != 0) {

                                        if(!validavimas($likutis, "int")) {

                                            klaida("Prekės likutis nenurodytas arba nurodytas neteisingai");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;
    
                                        }

                                    }

                                    if($likutis < 0) {

                                        klaida("Prekės likutis privalo būti didesnis arba lygus nuliui");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                        
                                    }

                                    if($stmt = $conn->prepare("SELECT id FROM prekes_sandeliai WHERE preke = ? AND sandelis = ?")) {

                                        $stmt->bind_param("ii", $pid, $sid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            if($stmt2 = $conn->prepare("UPDATE prekes_sandeliai SET likutis = ? WHERE preke = ? AND sandelis = ?")) {

                                                $stmt2->bind_param("iii", $likutis, $pid, $sid);

                                                $stmt2->execute();

                                                $stmt2->close();

                                                rezultatas("Prekės likutis sandėlyje atnaujintas sėkmingai");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;

                                            } else {

                                                klaida("Klaida atnaujinant prekės likutį sandėlyje");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;

                                            }

                                        } else {

                                            klaida("Sandėlis nėra priskirtas šiai prekei");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie prekės sandėlius");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "istrinti_sandeli") {

                                    leidimas(array(2));

                                    $pid = null;

                                    if(isset($_GET["pid"]) && !empty($_GET["pid"])) {

                                        $pid = sanitizavimas($_GET["pid"], "int");

                                    }

                                    if(!validavimas($pid, "int")) {

                                        klaida("Prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    $sid = null;

                                    if(isset($_GET["sid"]) && !empty($_GET["sid"])) {

                                        $sid = sanitizavimas($_GET["sid"], "int");

                                    }

                                    if(!validavimas($sid, "int")) {

                                        klaida("Sandėlio ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id FROM prekes_sandeliai WHERE preke = ? AND sandelis = ?")) {

                                        $stmt->bind_param("ii", $pid, $sid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            if($stmt2 = $conn->prepare("DELETE FROM prekes_sandeliai WHERE preke = ? AND sandelis = ?")) {

                                                $stmt2->bind_param("ii", $pid, $sid);

                                                $stmt2->execute();

                                                $stmt2->close();

                                                rezultatas("Prekės sandėlis pašalintas sėkmingai");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;

                                            } else {

                                                klaida("Klaida šalinant prekės sandėlį");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;

                                            }

                                        } else {

                                            klaida("Sandėlis nėra priskirtas šiai prekei");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie prekės sandėlius");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "tiekejas") {

                                    leidimas(array(2));

                                    $pid = null;
                                
                                    if(isset($_GET["pid"]) && !empty($_GET["pid"])) {
                                
                                        $pid = sanitizavimas($_GET["pid"], "int");
                                
                                    }
                                
                                    if(!validavimas($pid, "int")) {
                                
                                        klaida("Prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                
                                    if($stmt = $conn->prepare("SELECT id FROM tiekejai")) {
                                
                                        $stmt->execute();
                                
                                        $rezultatas = $stmt->get_result();
                                
                                        if($rezultatas->num_rows == 0) {
                                
                                            klaida("Sistemoje nėra tiekėjų");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;
                                
                                        }
                                
                                        $stmt->close();
                                
                                    } else {
                                
                                        klaida("Klaida gaunant tiekėjų skaičių");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                
                                    if($stmt = $conn->prepare("SELECT * FROM prekes WHERE id = ?")) {
                                
                                        $stmt->bind_param("i", $pid);
                                
                                        $stmt->execute();
                                
                                        $rezultatas = $stmt->get_result();
                                
                                        if($rezultatas->num_rows == 1) {
                                
                                            $preke = $rezultatas->fetch_assoc();
                                
                                            echo "
                                
                                            <div class='d-flex flex-row justify-content-start mb-3'>
                                
                                                <h4>Tiekėjo priskyrimas prekei <b>".$preke["pavadinimas"]." (".$preke["kodas"].")</b></h4>
                                
                                            </div>
                                
                                            <form method='post' action='prekes.php?veiksmas=priskirti_tiekeja&pid=".$pid."'>
                                
                                                <div class='mb-3'>
                                                    <select class='form-select' id='tiekejas' name='tiekejas'>
                                                        <option value='null'selected>Pasirinkite tiekėją</option>
                                                        
                                            ";
                                
                                            if($stmt2 = $conn->prepare("SELECT id, pavadinimas FROM tiekejai")) {
                                
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
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;
                                
                                            }                                                        
                                
                                            echo "
                                
                                                    </select>
                                                </div>
                                
                                                <div class='mb-3'>
                                                    <label for='kaina' class='form-label'>Prekės kaina</label>
                                                    <input type='text' class='form-control' id='kaina' name='kaina'>
                                                </div>
                                
                                                <button type='submit' class='btn btn-success'>Priskirti</button>
                                
                                            </form>
                                
                                            ";
                                
                                        } else {
                                
                                            klaida("Prekės su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;
                                
                                        }
                                
                                        $stmt->close();
                                
                                    } else {
                                
                                        klaida("Klaida gaunant duomenis apie prekę");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                
                                } else if($veiksmas == "priskirti_tiekeja") {
                                
                                    leidimas(array(2));

                                    $pid = null;
                                
                                    if(isset($_GET["pid"]) && !empty($_GET["pid"])) {
                                
                                        $pid = sanitizavimas($_GET["pid"], "int");
                                
                                    }
                                
                                    if(!validavimas($pid, "int")) {
                                
                                        klaida("Prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                    
                                    $tid = sanitizavimas($_POST["tiekejas"], "int");
                                    $kaina = sanitizavimas($_POST["kaina"], "double");
                                
                                    if(!validavimas($tid, "int")) {
                                
                                        klaida("Tiekėjas nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                
                                    if(!validavimas($kaina, "double")) {
                                
                                        klaida("Prekės kaina nenurodyta arba nurodyta neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                
                                    if($kaina < 0) {
                                
                                        klaida("Prekės kaina privalo būti didesnė arba lygi nuliui");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                        
                                    }
                                
                                    if($stmt = $conn->prepare("SELECT id FROM prekes_tiekejai WHERE preke = ? AND tiekejas = ?")) {
                                
                                        $stmt->bind_param("ii", $pid, $tid);
                                
                                        $stmt->execute();
                                
                                        $rezultatas = $stmt->get_result();
                                
                                        if($rezultatas->num_rows == 0) {
                                
                                            if($stmt2 = $conn->prepare("INSERT INTO prekes_tiekejai (preke, tiekejas, kaina) VALUES (?, ?, ?)")) {
                                
                                                $stmt2->bind_param("iid", $pid, $tid, $kaina);
                                
                                                $stmt2->execute();
                                
                                                $stmt2->close();
                                
                                                rezultatas("Tiekėjas sėkmingai priskirtas prekei");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;
                                
                                            } else {
                                
                                                klaida("Klaida priskiriant tiekėją prekei");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;
                                
                                            }
                                
                                        } else {
                                
                                            klaida("Tiekėjas jau yra priskirtas šiai prekei");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;
                                
                                        }
                                
                                        $stmt->close();
                                
                                    } else {
                                
                                        klaida("Klaida gaunant duomenis apie prekės tiekėjus");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                
                                } else if($veiksmas == "redaguoti_tiekeja") {
                                
                                    leidimas(array(2));

                                    $pid = null;
                                
                                    if(isset($_GET["pid"]) && !empty($_GET["pid"])) {
                                
                                        $pid = sanitizavimas($_GET["pid"], "int");
                                
                                    }
                                
                                    if(!validavimas($pid, "int")) {
                                
                                        klaida("Prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                
                                    $tid = null;
                                
                                    if(isset($_GET["tid"]) && !empty($_GET["tid"])) {
                                
                                        $tid = sanitizavimas($_GET["tid"], "int");
                                
                                    }
                                
                                    if(!validavimas($tid, "int")) {
                                
                                        klaida("Tiekėjo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                
                                    if($stmt = $conn->prepare("SELECT prekes.pavadinimas AS pavadinimas, prekes.kodas AS kodas, tiekejai.pavadinimas AS tiekejas, prekes_tiekejai.kaina AS kaina FROM prekes, tiekejai, prekes_tiekejai WHERE prekes_tiekejai.tiekejas = tiekejai.id AND prekes_tiekejai.preke = prekes.id AND prekes_tiekejai.preke = ? AND prekes_tiekejai.tiekejas = ?")) {
                                
                                        $stmt->bind_param("ii", $pid, $tid);
                                
                                        $stmt->execute();
                                
                                        $rezultatas = $stmt->get_result();
                                
                                        if($rezultatas->num_rows == 1) {
                                
                                            $preke = $rezultatas->fetch_assoc();
                                
                                            echo "
                                
                                            <div class='d-flex flex-row justify-content-start mb-3'>
                                
                                                <h4>Tiekėjo kainos atnaujinimas prekei <b>".$preke["pavadinimas"]." (".$preke["kodas"].")</b></h4>
                                
                                            </div>
                                
                                            <form method='post' action='prekes.php?veiksmas=atnaujinti_kaina&pid=".$pid."&tid=".$tid."'>
                                
                                                <div class='mb-3'>
                                                    <select class='form-select' id='tiekejas' name='tiekejas' disabled>
                                                        <option value='".$tid."'selected>".str_replace("\\", "", $preke["tiekejas"])."</option>
                                                    </select>
                                                </div>
                                
                                                <div class='mb-3'>
                                                    <label for='kaina' class='form-label'>Prekės kaina</label>
                                                    <input type='text' class='form-control' id='kaina' name='kaina' value='".number_format($preke["kaina"] / 100, 2, ",", "")."'>
                                                </div>
                                
                                                <button type='submit' class='btn btn-success'>Atnaujinti</button>
                                
                                            </form>
                                
                                            ";
                                
                                        } else {
                                
                                            klaida("Prekės su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;
                                
                                        }
                                
                                        $stmt->close();
                                
                                    } else {
                                
                                        klaida("Klaida gaunant duomenis apie prekę");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                
                                } else if($veiksmas == "atnaujinti_kaina") {
                                
                                    leidimas(array(2));

                                    $pid = null;
                                
                                    if(isset($_GET["pid"]) && !empty($_GET["pid"])) {
                                
                                        $pid = sanitizavimas($_GET["pid"], "int");
                                
                                    }
                                
                                    if(!validavimas($pid, "int")) {
                                
                                        klaida("Prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                    
                                    $tid = null;
                                
                                    if(isset($_GET["tid"]) && !empty($_GET["tid"])) {
                                
                                        $tid = sanitizavimas($_GET["tid"], "int");
                                
                                    }
                                
                                    if(!validavimas($tid, "int")) {
                                
                                        klaida("Tiekėjo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                
                                    $kaina = sanitizavimas($_POST["kaina"], "double");
                                
                                    if(!validavimas($kaina, "double")) {
                                
                                        klaida("Prekės kaina nenurodyta arba nurodyta neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                
                                    if($kaina < 0) {
                                
                                        klaida("Prekės kaina privalo būti didesnė arba lygi nuliui");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                        
                                    }
                                
                                    if($stmt = $conn->prepare("SELECT id FROM prekes_tiekejai WHERE preke = ? AND tiekejas = ?")) {
                                
                                        $stmt->bind_param("ii", $pid, $tid);
                                
                                        $stmt->execute();
                                
                                        $rezultatas = $stmt->get_result();
                                
                                        if($rezultatas->num_rows == 1) {
                                
                                            if($stmt2 = $conn->prepare("UPDATE prekes_tiekejai SET kaina = ? WHERE preke = ? AND tiekejas = ?")) {
                                
                                                $stmt2->bind_param("dii", $kaina, $pid, $tid);
                                
                                                $stmt2->execute();
                                
                                                $stmt2->close();
                                
                                                rezultatas("Tiekėjo prekės kaina atnaujinta sėkmingai");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;
                                
                                            } else {
                                
                                                klaida("Klaida atnaujinant tiekėjo prekės kainą");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;
                                
                                            }
                                
                                        } else {
                                
                                            klaida("Tiekėjas nėra priskirtas šiai prekei");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;
                                
                                        }
                                
                                        $stmt->close();
                                
                                    } else {
                                
                                        klaida("Klaida gaunant duomenis apie prekės tiekėjus");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                
                                } else if($veiksmas == "istrinti_tiekeja") {
                                
                                    leidimas(array(2));

                                    $pid = null;
                                
                                    if(isset($_GET["pid"]) && !empty($_GET["pid"])) {
                                
                                        $pid = sanitizavimas($_GET["pid"], "int");
                                
                                    }
                                
                                    if(!validavimas($pid, "int")) {
                                
                                        klaida("Prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                
                                    $tid = null;
                                
                                    if(isset($_GET["tid"]) && !empty($_GET["tid"])) {
                                
                                        $tid = sanitizavimas($_GET["tid"], "int");
                                
                                    }
                                
                                    if(!validavimas($tid, "int")) {
                                
                                        klaida("Tiekėjo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }
                                
                                    if($stmt = $conn->prepare("SELECT id FROM prekes_tiekejai WHERE preke = ? AND tiekejas = ?")) {
                                
                                        $stmt->bind_param("ii", $pid, $tid);
                                
                                        $stmt->execute();
                                
                                        $rezultatas = $stmt->get_result();
                                
                                        if($rezultatas->num_rows == 1) {
                                
                                            if($stmt2 = $conn->prepare("DELETE FROM prekes_tiekejai WHERE preke = ? AND tiekejas = ?")) {
                                
                                                $stmt2->bind_param("ii", $pid, $tid);
                                
                                                $stmt2->execute();
                                
                                                $stmt2->close();
                                
                                                rezultatas("Prekės tiekėjas pašalintas sėkmingai");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;
                                
                                            } else {
                                
                                                klaida("Klaida šalinant prekės tiekėją");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;
                                
                                            }
                                
                                        } else {
                                
                                            klaida("Tiekėjas nėra priskirtas šiai prekei");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;
                                
                                        }
                                
                                        $stmt->close();
                                
                                    } else {
                                
                                        klaida("Klaida gaunant duomenis apie prekės tiekėjus");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;
                                
                                    }

                                } else if($veiksmas == "istrinti") {

                                    leidimas(array(2));

                                    $pid = null;

                                    if(isset($_GET["pid"]) && !empty($_GET["pid"])) {

                                        $pid = sanitizavimas($_GET["pid"], "int");

                                    }

                                    if(!validavimas($pid, "int")) {

                                        klaida("Prekės ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id FROM prekes WHERE id = ?")) {

                                        $stmt->bind_param("i", $pid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            if($stmt2 = $conn->prepare("DELETE FROM prekes WHERE id = ?")) {

                                                $stmt2->bind_param("i", $pid);

                                                $stmt2->execute();

                                                $stmt2->close();

                                            } else {

                                                klaida("Klaida ištrinant prekę");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;

                                            }

                                            if($stmt2 = $conn->prepare("DELETE FROM prekes_tiekejai WHERE preke = ?")) {

                                                $stmt2->bind_param("i", $pid);

                                                $stmt2->execute();

                                                $stmt2->close();

                                            } else {

                                                klaida("Klaida ištrinant prekės tiekėjų informaciją");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;

                                            }

                                            if($stmt2 = $conn->prepare("DELETE FROM prekes_sandeliai WHERE preke = ?")) {

                                                $stmt2->bind_param("i", $pid);

                                                $stmt2->execute();

                                                $stmt2->close();

                                            } else {

                                                klaida("Klaida ištrinant prekės sandėlių informaciją");
                                                echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                                exit;

                                            }

                                            rezultatas("Prekė sėkmingai ištrinta");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;

                                        } else {

                                            klaida("Prekės su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie prekę");
                                        echo "<meta http-equiv='Refresh' content='0; url=./prekes.php'>";
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