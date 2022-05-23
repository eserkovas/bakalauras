<?php

session_start();

unset($_SESSION["vartotojas"]);
unset($_SESSION["grupe"]);

session_unset();

session_destroy();

header("location:index.php");

?>