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

leidimas(array(3));

?>

<!doctype html>

<html lang="en" class="h-100">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="./css/stilius.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>Vartotojai | Logistikos sistema (IF180026)</title>

    </head>

    <body class="h-100">

        <div class="container-fluid h-100 p-0">

            <div class="row m-0 h-100">

                <?php require_once 'navigacija.php'; ?>

                    <div class="container-fluid">

                        <h2 class="py-3 fw-bold"><i class='fa-solid fa-users fa-fw my-auto me-2'></i> Vartotojai</h2>

                        <?php

                            if(empty($veiksmas)) {

                                echo "
                                
                                <div class='d-flex flex-row justify-content-between mb-3'>

                                    <h4 class='my-auto'>Vartotojų sąrašas</h4>
        
                                    <a href='vartotojai.php?veiksmas=naujas'><button type='button' class='btn btn-primary'>Naujas vartotojas</button></a>
        
                                </div>
        
                                <div class='container-fluid p-0'>

                                    <div class='col-12 col-lg-6 p-0'>

                                        <form method='post' action='vartotojai.php?veiksmas=paieska'>

                                            <div class='input-group mb-3'>

                                                <input type='text' class='form-control' id='paieska' name='paieska' placeholder='Vartotojo informacija'>
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

                                if($stmt = $conn->prepare("SELECT id, vardas, pastas, grupe FROM vartotojai")) {

                                    $stmt->execute();

                                    $rezultatas = $stmt->get_result();

                                    if($rezultatas->num_rows > 0) {

                                        echo "
                                        
                                        <div class='table-responsive'>
        
                                            <table class='table table-striped table-hover'>
                                            
                                                <thead>
                
                                                    <tr>
                                                        <th scope='col'>ID</th>
                                                        <th scope='col'>Vardas</th>
                                                        <th scope='col'>Elektroninis paštas</th>
                                                        <th scope='col'>Grupė</th>
                                                        <th scope='col'></th>
                                                    </tr>
                
                                                </thead>
                
                                                <tbody>

                                        ";

                                        while($vartotojas = $rezultatas->fetch_assoc()) {

                                            echo "
                                            
                                            <tr>
                                                <th class='align-middle'>".$vartotojas["id"]."</th>
                                                <td class='align-middle'>".$vartotojas["vardas"]."</td>
                                                <td class='align-middle'>".$vartotojas["pastas"]."</td>
                                            
                                            ";

                                            if($vartotojas["grupe"] == 1) {

                                                echo "<td class='align-middle'>Darbuotojai</td>";

                                            } else if($vartotojas["grupe"] == 2) {

                                                echo "<td class='align-middle'>Vadybininkai</td>";
                                                
                                            } else if($vartotojas["grupe"] == 3) {

                                                echo "<td class='align-middle'>Administratoriai</td>";
                                                
                                            }

                                            echo "

                                                <td class='align-middle d-flex flex-row py-3 py-lg-auto'>
                                                    <a href='vartotojai.php?veiksmas=redaguoti&vid=".$vartotojas["id"]."'><button type='button' class='btn btn-warning btn-sm me-2'>Redaguoti</button></a>
                                                    <a href='vartotojai.php?veiksmas=istrinti&vid=".$vartotojas["id"]."'><button type='button' class='btn btn-danger btn-sm'>Ištrinti</button></a>
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
                                            Vartotojų sistemoje nėra
                                        </div>

                                        ";

                                    }

                                    $stmt->close();
                                    
                                } else {

                                    klaida("Klaida gaunant sistemos vartotojus");
                                    echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                    exit;

                                }

                            } else {

                                if($veiksmas == "paieska") {

                                    leidimas(array(3));

                                    $paieska = sanitizavimas($_POST["paieska"], "string");

                                    if(!validavimas($paieska, "nera")) {
        
                                        klaida("Nenurodyta paieškos frazė");
                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                        exit;

                                    }

                                    echo "
                                
                                    <div class='d-flex flex-row justify-content-between mb-3'>
    
                                        <h4 class='my-auto'>Vartotojų sąrašas</h4>
            
                                        <a href='vartotojai.php?veiksmas=naujas'><button type='button' class='btn btn-primary'>Naujas vartotojas</button></a>
            
                                    </div>
            
                                    <div class='container-fluid p-0'>
    
                                        <div class='col-12 col-lg-6 p-0'>
    
                                            <form method='post' action='vartotojai.php?veiksmas=paieska'>
    
                                                <div class='input-group mb-3'>
    
                                                    <input type='text' class='form-control' id='paieska' name='paieska' placeholder='Vartotojo informacija' value='".$paieska."'>
                                                    <button class='btn btn-outline-secondary' type='submit'>Ieškoti</button>
    
                                                </div>
    
                                            </form>
    
                                        </div>
    
                                    </div>
    
                                    ";

                                    if($stmt = $conn->prepare("SELECT id, vardas, pastas, grupe FROM vartotojai WHERE vardas LIKE ? OR pastas LIKE ?")) {
                                        
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
                                                            <th scope='col'>Vardas</th>
                                                            <th scope='col'>Elektroninis paštas</th>
                                                            <th scope='col'>Grupė</th>
                                                            <th scope='col'></th>
                                                        </tr>
                    
                                                    </thead>
                    
                                                    <tbody>
    
                                            ";
    
                                            while($vartotojas = $rezultatas->fetch_assoc()) {
    
                                                echo "
                                                
                                                <tr>
                                                    <th class='align-middle'>".$vartotojas["id"]."</th>
                                                    <td class='align-middle'>".$vartotojas["vardas"]."</td>
                                                    <td class='align-middle'>".$vartotojas["pastas"]."</td>
                                                
                                                ";
    
                                                if($vartotojas["grupe"] == 1) {
    
                                                    echo "<td class='align-middle'>Darbuotojai</td>";
    
                                                } else if($vartotojas["grupe"] == 2) {
    
                                                    echo "<td class='align-middle'>Vadybininkai</td>";
                                                    
                                                } else if($vartotojas["grupe"] == 3) {
    
                                                    echo "<td class='align-middle'>Administratoriai</td>";
                                                    
                                                }
    
                                                echo "
    
                                                    <td class='align-middle d-flex flex-row py-3 py-lg-auto'>
                                                        <a href='vartotojai.php?veiksmas=redaguoti&vid=".$vartotojas["id"]."'><button type='button' class='btn btn-warning btn-sm me-2'>Redaguoti</button></a>
                                                        <a href='vartotojai.php?veiksmas=istrinti&vid=".$vartotojas["id"]."'><button type='button' class='btn btn-danger btn-sm'>Ištrinti</button></a>
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
                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                        exit;
    
                                    }

                                } else if($veiksmas == "naujas") {

                                    leidimas(array(3));

                                    echo "
                                
                                    <div class='d-flex flex-row justify-content-start mb-3'>
    
                                        <h4>Naujas vartotojas</h4>
            
                                    </div>
            
                                    <form method='post' action='vartotojai.php?veiksmas=sukurti'>

                                        <div class='mb-3'>
                                            <label for='vardas' class='form-label'>Vardas</label>
                                            <input type='text' class='form-control' id='vardas' name='vardas'>
                                        </div>

                                        <div class='mb-3'>
                                            <label for='pastas' class='form-label'>Elektroninis paštas</label>
                                            <input type='email' class='form-control' id='pastas' name='pastas'>
                                        </div>

                                        <div class='mb-3'>
                                            <label for='slaptazodis' class='form-label'>Slaptažodis</label>
                                            <input type='password' class='form-control' id='slaptazodis' name='slaptazodis'>
                                        </div>

                                        <div class='mb-3'>
                                            <label for='grupe' class='form-label'>Grupė</label>
                                            <select id='grupe' name='grupe' class='form-select'>
                                                <option value='null' selected>Pasirinkite grupę</option>
                                                <option value='1'>Darbuotojai</option>
                                                <option value='2'>Vadybininkai</option>
                                                <option value='3'>Administratoriai</option>
                                            </select>
                                        </div>

                                        <button type='submit' class='btn btn-success'>Sukurti</button>

                                    </form>
    
                                    ";

                                } elseif ($veiksmas == "sukurti") {

                                    leidimas(array(3));

                                    $vardas = sanitizavimas($_POST["vardas"], "string");
                                    $pastas = sanitizavimas($_POST["pastas"], "pastas");
                                    $slaptazodis = sanitizavimas($_POST["slaptazodis"], "string");
                                    $grupe = sanitizavimas($_POST["grupe"], "int");

                                    if(!validavimas($vardas, "nera")) {

                                        klaida("Nenurodytas vartotojo vardas");
                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                        exit;

                                    }

                                    if(!validavimas($pastas, "pastas")) {

                                        klaida("Vartotojo elektroninis paštas nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                        exit;

                                    }

                                    if(!validavimas($grupe, "int")) {
                                        
                                        klaida("Vartotojo grupė nenurodyta arba nurodyta neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                        exit;

                                    }

                                    if(!validavimas($slaptazodis, "nera")) {

                                        klaida("Nenurodytas vartotojo slaptažodis");
                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                        exit;

                                    }

                                    $slaptazodis = password_hash($slaptazodis, PASSWORD_DEFAULT);

                                    if($stmt = $conn->prepare("SELECT id FROM vartotojai WHERE pastas LIKE ?")) {

                                        $stmt->bind_param("s", $pastas);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 0) {

                                            if($stmt2 = $conn->prepare("INSERT INTO vartotojai (vardas, pastas, slaptazodis, grupe) VALUES (?, ?, ?, ?)")) {

                                                $stmt2->bind_param("sssi", $vardas, $pastas, $slaptazodis, $grupe);
        
                                                $stmt2->execute();
        
                                                $stmt2->close();
        
                                                rezultatas("Naujas vartotojas sėkmingai sukurtas");
                                                echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                exit;
        
                                            } else {
        
                                                klaida("Klaida kuriant naują vartotoją");
                                                echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                exit;
        
                                            }

                                        } else {

                                            klaida("Vartotojas su tokiu pačiu el. paštu jau sukurtas");
                                            echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie vartotoją");
                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "redaguoti") {

                                    leidimas(array(3));

                                    $vid = null;

                                    if(isset($_GET["vid"]) && !empty($_GET["vid"])) {

                                        $vid = sanitizavimas($_GET["vid"], "int");

                                    }

                                    if(!validavimas($vid, "int")) {

                                        klaida("Vartotojo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT vardas, pastas, grupe FROM vartotojai WHERE id = ?")) {

                                        $stmt->bind_param("i", $vid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $vartotojas = $rezultatas->fetch_assoc();

                                            echo "
                                
                                            <div class='d-flex flex-row justify-content-start mb-3'>
            
                                                <h4>Redaguoti vartotojo informaciją</h4>
                    
                                            </div>
                    
                                            <form method='post' action='vartotojai.php?veiksmas=atnaujinti&vid=".$vid."'>
        
                                                <div class='mb-3'>
                                                    <label for='vardas' class='form-label'>Vardas</label>
                                                    <input type='text' class='form-control' id='vardas' name='vardas' value='".$vartotojas["vardas"]."'>
                                                </div>
        
                                                <div class='mb-3'>
                                                    <label for='pastas' class='form-label'>Elektroninis paštas</label>
                                                    <input type='email' class='form-control' id='pastas' name='pastas' value='".$vartotojas["pastas"]."'>
                                                </div>
        
                                                <div class='mb-3'>
                                                    <label for='slaptazodis' class='form-label'>Slaptažodis</label>
                                                    <input type='password' class='form-control' id='slaptazodis' name='slaptazodis'>
                                                </div>
        
                                                <div class='mb-3'>
                                                    <label for='grupe' class='form-label'>Grupė</label>
                                                    <select id='grupe' name='grupe' class='form-select'>
                                                        <option value='null'>Pasirinkite grupę</option>
                                                ";

                                                    if($vartotojas["grupe"] == 1) {

                                                        echo "<option value='1' selected>Darbuotojai</option>";

                                                    } else {

                                                        echo "<option value='1'>Darbuotojai</option>";

                                                    }

                                                    if($vartotojas["grupe"] == 2) {

                                                        echo "<option value='2' selected>Vadybininkai</option>";

                                                    } else {

                                                        echo "<option value='2'>Vadybininkai</option>";

                                                    }

                                                    if($vartotojas["grupe"] == 3) {

                                                        echo "<option value='3' selected>Administratoriai</option>";

                                                    } else {

                                                        echo "<option value='3'>Administratoriai</option>";

                                                    }

                                                echo "
                                                    </select>
                                                </div>
        
                                                <button type='submit' class='btn btn-success'>Atnaujinti</button>
        
                                            </form>
            
                                            ";

                                        } else {

                                            klaida("Vartotojo su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie vartotoją");
                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "atnaujinti") {

                                    leidimas(array(3));

                                    $vid = null;

                                    if(isset($_GET["vid"]) && !empty($_GET["vid"])) {

                                        $vid = sanitizavimas($_GET["vid"], "int");

                                    }

                                    if(!validavimas($vid, "int")) {

                                        klaida("Vartotojo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT pastas FROM vartotojai WHERE id = ?")) {

                                        $stmt->bind_param("i", $vid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            $tikrinimas = $rezultatas->fetch_assoc();

                                            $vardas = sanitizavimas($_POST["vardas"], "string");
                                            $pastas = sanitizavimas($_POST["pastas"], "pastas");
                                            $grupe = sanitizavimas($_POST["grupe"], "int");
                                            $slaptazodis = null;        
                                            
                                            if(!validavimas($vardas, "nera")) {

                                                klaida("Nenurodytas vartotojo vardas");
                                                echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                exit;
        
                                            }
        
                                            if(!validavimas($pastas, "pastas")) {
        
                                                klaida("Vartotojo elektroninis paštas nenurodytas arba nurodytas neteisingai");
                                                echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                exit;
        
                                            }
        
                                            if(!validavimas($grupe, "int")) {
                                                
                                                klaida("Vartotojo grupė nenurodyta arba nurodyta neteisingai");
                                                echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                exit;
        
                                            }

                                            if(isset($_POST["slaptazodis"]) && !empty($_POST["slaptazodis"])) {

                                                $slaptazodis = sanitizavimas($_POST["slaptazodis"], "string");

                                                if(!validavimas($slaptazodis, "nera")) {
        
                                                    klaida("Nenurodytas vartotojo slaptažodis");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                    exit;
            
                                                }

                                                $slaptazodis = password_hash($slaptazodis, PASSWORD_DEFAULT);

                                            }

                                            if($tikrinimas["pastas"] == $pastas) {

                                                if(empty($slaptazodis)) {

                                                    if($stmt2 = $conn->prepare("UPDATE vartotojai SET vardas = ?, pastas = ?, grupe = ? WHERE id = ?")) {
    
                                                        $stmt2->bind_param("ssii", $vardas, $pastas, $grupe, $vid);
    
                                                        $stmt2->execute();
    
                                                        $stmt2->close();
    
                                                        rezultatas("Vartotojo informacija sėkmingai atnaujinta");
                                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                        exit;
    
                                                    } else {
    
                                                        klaida("Klaida atnaujinant vartotojo informaciją");
                                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                        exit;
    
                                                    }
    
                                                } else {
    
                                                    if($stmt2 = $conn->prepare("UPDATE vartotojai SET vardas = ?, pastas = ?, slaptazodis = ?, grupe = ? WHERE id = ?")) {
    
                                                        $stmt2->bind_param("sssii", $vardas, $pastas, $slaptazodis, $grupe, $vid);
    
                                                        $stmt2->execute();
    
                                                        $stmt2->close();
                                                        
                                                        rezultatas("Vartotojo informacija sėkmingai atnaujinta");
                                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                        exit;
    
                                                    } else {
    
                                                        klaida("Klaida atnaujinant vartotojo informaciją");
                                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                        exit;
    
                                                    }
    
                                                }

                                            } else {

                                                if($stmt2 = $conn->prepare("SELECT id FROM vartotojai WHERE pastas LIKE ?")) {

                                                    $stmt2->bind_param("s", $pastas);
            
                                                    $stmt2->execute();
            
                                                    $rezultatas2 = $stmt2->get_result();
            
                                                    if($rezultatas2->num_rows == 0) {

                                                        if(empty($slaptazodis)) {

                                                            if($stmt3 = $conn->prepare("UPDATE vartotojai SET vardas = ?, pastas = ?, grupe = ? WHERE id = ?")) {
            
                                                                $stmt3->bind_param("ssii", $vardas, $pastas, $grupe, $vid);
            
                                                                $stmt3->execute();
            
                                                                $stmt3->close();
            
                                                                rezultatas("Vartotojo informacija sėkmingai atnaujinta");
                                                                echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                                exit;
            
                                                            } else {
            
                                                                klaida("Klaida atnaujinant vartotojo informaciją");
                                                                echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                                exit;
            
                                                            }
            
                                                        } else {
            
                                                            if($stmt3 = $conn->prepare("UPDATE vartotojai SET vardas = ?, pastas = ?, slaptazodis = ?, grupe = ? WHERE id = ?")) {
            
                                                                $stmt3->bind_param("sssii", $vardas, $pastas, $slaptazodis, $grupe, $vid);
            
                                                                $stmt3->execute();
            
                                                                $stmt3->close();
                                                                
                                                                rezultatas("Vartotojo informacija sėkmingai atnaujinta");
                                                                echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                                exit;
            
                                                            } else {
            
                                                                klaida("Klaida atnaujinant vartotojo informaciją");
                                                                echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                                exit;
            
                                                            }
            
                                                        }
            
                                                    } else {
            
                                                        klaida("Vartotojas su tokiu pačiu el. paštu jau sukurtas");
                                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                        exit;
            
                                                    }
            
                                                    $stmt2->close();
            
                                                } else {
            
                                                    klaida("Klaida gaunant duomenis apie vartotoją");
                                                    echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                    exit;
            
                                                }

                                            }

                                        } else {

                                            klaida("Vartotojo su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie vartotoją");
                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                        exit;

                                    }

                                } else if($veiksmas == "istrinti") {

                                    leidimas(array(3));
                                    
                                    $vid = null;

                                    if(isset($_GET["vid"]) && !empty($_GET["vid"])) {

                                        $vid = sanitizavimas($_GET["vid"], "int");

                                    }

                                    if(!validavimas($vid, "int")) {

                                        klaida("Vartotojo ID nenurodytas arba nurodytas neteisingai");
                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                        exit;

                                    }

                                    if($stmt = $conn->prepare("SELECT id FROM vartotojai WHERE id = ?")) {

                                        $stmt->bind_param("i", $vid);

                                        $stmt->execute();

                                        $rezultatas = $stmt->get_result();

                                        if($rezultatas->num_rows == 1) {

                                            if($stmt2 = $conn->prepare("DELETE FROM vartotojai WHERE id = ?")) {

                                                $stmt2->bind_param("i", $vid);

                                                $stmt2->execute();

                                                $stmt2->close();

                                                rezultatas("Vartotojas sėkmingai ištrintas");
                                                echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                exit;

                                            } else {

                                                klaida("Klaida ištrinant vartotoją");
                                                echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                                exit;

                                            }

                                        } else {

                                            klaida("Vartotojo su pateiktu ID nėra");
                                            echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
                                            exit;

                                        }

                                        $stmt->close();

                                    } else {

                                        klaida("Klaida gaunant duomenis apie vartotoją");
                                        echo "<meta http-equiv='Refresh' content='0; url=./vartotojai.php'>";
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