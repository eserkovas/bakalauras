<?php

session_start();

require_once 'duomenu_baze.php';

require_once 'funkcijos.php';

$pastas = sanitizavimas($_POST["pastas"], "pastas");
$slaptazodis = sanitizavimas($_POST["slaptazodis"], "string");

if(!validavimas($pastas, "pastas")) {
        
    klaida("Elektroninis paštas nenurodytas arba nurodytas neteisingai");
    header("location:index.php");
    exit;

}

if(!validavimas($slaptazodis, "nera")) {

    klaida("Nenurodytas slaptažodis");
    header("location:index.php");
    exit;

}

if($stmt = $conn->prepare("SELECT pastas, slaptazodis, grupe FROM vartotojai WHERE pastas = ?")) {

    $stmt->bind_param("s", $pastas);

    $stmt->execute();

    $rezultatas = $stmt->get_result();

    if($rezultatas->num_rows == 1) {

        $vartotojas = $rezultatas->fetch_assoc();

        if(password_verify($slaptazodis, $vartotojas["slaptazodis"])) {

            session_regenerate_id();

            $_SESSION["vartotojas"] = $pastas;
            $_SESSION["grupe"] = $vartotojas["grupe"];

            header("location:pagrindinis.php");

        } else {

            klaida("Prisijungimo duomenys neteisingi");
            header("location:index.php");
            exit;    

        }

    } else {

        klaida("Prisijungimo duomenys neteisingi");
        header("location:index.php");
        exit;    

    }

} else {

    klaida("Klaida gaunant vartotojo duomenis");
    header("location:index.php");
    exit;

}

$conn->close();

?>