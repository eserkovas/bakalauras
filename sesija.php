<?php

session_start();

require_once 'funkcijos.php';

if(!isset($_SESSION["vartotojas"]) || empty($_SESSION["vartotojas"])) {

    klaida("Privalote prisijungti");
    header("location:index.php");
    exit;

}

?>