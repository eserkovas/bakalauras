<?php

$conn = new mysqli('', '', '', '');

if($conn->connect_error) {

    echo "Nepavyko prisijungti prie duomenų bazės";

    exit;
    
}

$conn->set_charset("utf8");

?>
