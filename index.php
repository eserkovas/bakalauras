<?php

session_start();

$klaida = null;

if(isset($_SESSION["klaida"])) {

    $klaida = $_SESSION["klaida"];

    unset($_SESSION["klaida"]);

}

?>

<!doctype html>

<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="./css/stilius.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <title>Prisijungimas | Logistikos sistema (IF180026)</title>

    </head>

    <body>

        <div class="container col-10 col-lg-3 border rounded shadow p-3 position-absolute top-50 start-50 translate-middle">

            <h4 class="fw-bold text-center pt-3">Logistikos sistema</h4>
            <p class="text-center">IF180026</p>

            <form method="post" action="prisijungimas.php">

                <div class="mb-3">
                    <label for="pastas" class="form-label">Elektroninis paštas</label>
                    <input type="email" class="form-control" id="pastas" name="pastas">
                </div>

                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Slaptažodis</label>
                    <input type="password" class="form-control" id="slaptazodis" name="slaptazodis">
                </div>
                
                <button type="submit" class="btn btn-primary">Prisijungti</button>

            </form>

            <?php

            if(!empty($klaida)) {

                echo "
                
                <div class='alert alert-danger mt-3' role='alert'>
                    ".$klaida."
                </div>

                ";

            }

            ?>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    </body>

</html>