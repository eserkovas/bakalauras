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

?>

<!doctype html>

<html lang="en" class="h-100">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="./css/stilius.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>Pagrindinis | Logistikos sistema (IF180026)</title>

    </head>

    <body class="h-100">

        <div class="container-fluid h-100 p-0">

            <div class="row m-0 h-100">

                <?php require_once 'navigacija.php'; ?>

                    <div class="container-fluid">

                        <h2 class="py-3 fw-bold"><i class='fa-solid fa-house fa-fw my-auto me-2'></i> Pagrindinis</h2>

                        <?php

                            if(empty($veiksmas)) {

                                echo "
                                
                                <div class='d-flex flex-row justify-content-start mb-3'>

                                    <h4 class='my-auto'>Nauji užsakymai</h4>

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

                                if($stmt = $conn->prepare("SELECT uzsakymai.id AS id, uzsakymai.data AS data, uzsakymai.statusas AS statusas, sandeliai.pavadinimas AS sandelis FROM uzsakymai, sandeliai WHERE uzsakymai.sandelis = sandeliai.id AND uzsakymai.statusas = 0 ORDER BY data DESC")) {

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

                                                    echo "<td class='align-middle'>Nepradėtas vykdyti</td>";

                                                } else if($uzsakymas["statusas"] == 1) {

                                                    echo "<td class='align-middle'>Vykdomas</td>";

                                                } else if($uzsakymas["statusas"] == 2) {

                                                    echo "<td class='align-middle'>Įvykdytas</td>";

                                                } else if($uzsakymas["statusas" == 3]) {

                                                    echo "<td class='align-middle'>Išsiųstas klientui</td>";

                                                }

                                                echo "

                                                <td class='align-middle'>".$uzsakymas["sandelis"]."</td>

                                                ";

                                                if(in_array($_SESSION["grupe"], array(1))) {

                                                    echo "
                                                    
                                                        <td>
                                                            <a href='uzsakymai.php?veiksmas=vykdyti&uid=".$uzsakymas["id"]."'><button type='button' class='btn btn-success btn-sm'>Vykdyti užsakymą</button></a>
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
                                            Naujų užsakymų nėra
                                        </div>

                                        ";

                                    }

                                    $stmt->close();
                                    
                                } else {

                                    klaida("Klaida gaunant naujus užsakymus");
                                    echo "<meta http-equiv='Refresh' content='0; url=./pagrindinis.php'>";
                                    exit;

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