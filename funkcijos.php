<?php

function leidimas($grupes) {

    session_start();

    $vieta = str_replace(array($_SERVER["QUERY_STRING"], "?"), "", explode('/', $_SERVER['REQUEST_URI'])[2]);

    if(!in_array($_SESSION["grupe"], $grupes)) {

        if(empty($_SERVER["QUERY_STRING"])) {

            klaida("Neturite leidimo naudotis šia funkcija");
            header("location:pagrindinis.php");
            exit;

        } else {

            klaida("Neturite leidimo naudotis šia funkcija");
            header("location:".$vieta);
            exit;

        }

    }

}

function dezes($svoris) {

    $dezes = -1;

    if($svoris < 30000) {

        $dezes = 1;
        
    } else {

        $dezes = round($svoris / 30000);

    }

    return $dezes;

}

function sanitizavimas($ivestis, $tipas) {

    $rezultatas = $ivestis;

    if($tipas == "string") {

        $rezultatas = filter_var($rezultatas, FILTER_SANITIZE_ADD_SLASHES);
        $rezultatas = filter_var($rezultatas, FILTER_SANITIZE_STRING);

    } else if($tipas == "int") {

        $rezultatas = filter_var($rezultatas, FILTER_SANITIZE_ADD_SLASHES);
        $rezultatas = filter_var($rezultatas, FILTER_SANITIZE_NUMBER_INT);       

    } else if($tipas == "double") {

        $rezultatas = filter_var($rezultatas, FILTER_SANITIZE_ADD_SLASHES);
        $rezultatas = filter_var($rezultatas, FILTER_SANITIZE_NUMBER_FLOAT);       

    } else if($tipas == "pastas") {

        $rezultatas = filter_var($rezultatas, FILTER_SANITIZE_ADD_SLASHES);
        $rezultatas = filter_var($rezultatas, FILTER_SANITIZE_EMAIL);

    }

    return $rezultatas;

}

function validavimas($ivestis, $tipas) {

    if(!empty($ivestis)) {

        if($tipas == "nera") {

            return true;

        } else if($tipas == "int") {

            if(filter_var($ivestis, FILTER_VALIDATE_INT)) {
    
                return true;
    
            } else {
    
                return false;
    
            }        
    
        } else if($tipas == "double") {

            if(filter_var($ivestis, FILTER_VALIDATE_FLOAT)) {
    
                return true;
    
            } else {
    
                return false;
    
            }        

        } else if($tipas == "pastas") {
    
            if(filter_var($ivestis, FILTER_VALIDATE_EMAIL)) {
    
                return true;
    
            } else {
    
                return false;
    
            }
    
        } else if($tipas == "pasto-kodas") {

            if(ctype_digit($ivestis)) {

                if(strlen($ivestis) == 5) {

                    return true;

                } else {

                    return false;

                }


            } else {

                return false;

            }

        } else if($tipas == "telefonas") {

            if($ivestis[0] == '+') {

                if(strlen($ivestis) == 12) {

                    if(ctype_digit(substr($ivestis, 1))) {

                        return true;

                    } else {

                        return false;

                    }

                } else {

                    return false;

                }

            } else {

                if(strlen($ivestis) == 9) {

                    if(ctype_digit($ivestis)) {

                        return true;

                    } else {

                        return false;

                    }

                } else {

                    return false;

                }

            }

        }

    } else {

        return false;

    }

}

function klaida($pranesimas) {

    $_SESSION["klaida"] = $pranesimas;

}

function rezultatas($pranesimas) {

    $_SESSION["rezultatas"] = $pranesimas;

}

?>