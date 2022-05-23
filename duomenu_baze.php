<?php

$conn = new mysqli('194.135.87.14', 'haplay_bak', '6tr5adesPuFQ6JY8', 'haplay_bak');

if($conn->connect_error) {

    echo "Nepavyko prisijungti prie duomenų bazės";

    exit;
    
}

$conn->set_charset("utf8");

?>