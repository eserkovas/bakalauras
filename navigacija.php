<?php

echo "

    <div class='col-12 col-lg-2 bg-secondary text-center d-lg-block d-none'>

        <div class='navigacija d-flex flex-column'>

            <p class='py-4 fw-bold'><a class='adresas text-center' href='pagrindinis.php'>Logistikos sistema</a></p>

            <a class='adresas d-flex flex-row py-2' href='pagrindinis.php'>
                <i class='fa-solid fa-house fa-fw my-auto mx-2'></i>
                <p class='m-0'>Pagrindinis</p>
            </a>

";

    if(in_array($_SESSION["grupe"], array(1, 2))) {

        echo "
        
            <a class='adresas d-flex flex-row py-2' href='uzsakymai.php'>
                <i class='fa-solid fa-box fa-fw my-auto mx-2'></i>
                <p class='m-0'>Užsakymai</p>
            </a>

        ";

    }

    if(in_array($_SESSION["grupe"], array(1, 2))) {

        echo "
        
            <a class='adresas d-flex flex-row py-2' href='prekes.php'>
                <i class='fa-solid fa-boxes-stacked fa-fw my-auto mx-2'></i>
                <p class='m-0'>Prekės</p>
            </a>

        ";

    }

    if(in_array($_SESSION["grupe"], array(2))) {

        echo "
        
            <a class='adresas d-flex flex-row py-2' href='prekiu_uzsakymai.php'>
                <i class='fa-solid fa-dolly fa-fw my-auto mx-2'></i>
                <p class='m-0'>Prekių užsakymai</p>
            </a>

        ";

    }

    if(in_array($_SESSION["grupe"], array(2))) {

        echo "
        
            <a class='adresas d-flex flex-row py-2' href='sandeliai.php'>
                <i class='fa-solid fa-warehouse fa-fw my-auto mx-2'></i>
                <p class='m-0'>Sandėliai</p>
            </a>

        ";

    }

    if(in_array($_SESSION["grupe"], array(2))) {

        echo "
        
            <a class='adresas d-flex flex-row py-2' href='tiekejai.php'>
                <i class='fa-solid fa-truck fa-fw my-auto mx-2'></i>
                <p class='m-0'>Tiekėjai</p>
            </a>

        ";

    }

    if(in_array($_SESSION["grupe"], array(3))) {

        echo "
        
            <a class='adresas d-flex flex-row py-2' href='vartotojai.php'>
                <i class='fa-solid fa-users fa-fw my-auto mx-2'></i>
                <p class='m-0'>Vartotojai</p>
            </a>

        ";

    }

echo "

        </div>

    </div>

    <div class='col p-0'>

        <div class='container-fluid py-4 bg-light text-end border shadow-sm d-lg-block d-none'>

            ".$_SESSION["vartotojas"]." <a href='atsijungti.php'><i class='fa-solid fa-arrow-right-from-bracket ms-2'></i></a>

        </div>

        <div class='navigacija-mb container-fluid py-4 bg-secondary text-end border shadow-sm d-lg-none border-0 text-light'>

            <div class='container-fluid d-flex flex-row p-0 justify-content-between'>

                <p class='fw-bold my-auto'><a class='text-start text-light text-decoration-none' href='pagrindinis.php'>Logistikos sistema</a></p>

                <button type='button' class='btn btn-dark' data-bs-toggle='collapse' data-bs-target='#navigacija-mb' aria-expanded='false' aria-controls='navigacija-mb'><i class='bi bi-list'></i></button>

            </div>

            <div class='collapse mt-3' id='navigacija-mb'>

                <a class='adresas d-flex flex-row py-2' href='pagrindinis.php'>
                    <i class='fa-solid fa-house fa-fw my-auto mx-2'></i>
                    <p class='m-0'>Pagrindinis</p>
                </a>

            ";

            if(in_array($_SESSION["grupe"], array(1, 2))) {

                echo "
                
                    <a class='adresas d-flex flex-row py-2' href='uzsakymai.php'>
                        <i class='fa-solid fa-box fa-fw my-auto mx-2'></i>
                        <p class='m-0'>Užsakymai</p>
                    </a>
        
                ";
        
            }
        
            if(in_array($_SESSION["grupe"], array(1, 2))) {
        
                echo "
                
                    <a class='adresas d-flex flex-row py-2' href='prekes.php'>
                        <i class='fa-solid fa-boxes-stacked fa-fw my-auto mx-2'></i>
                        <p class='m-0'>Prekės</p>
                    </a>
        
                ";
        
            }
        
            if(in_array($_SESSION["grupe"], array(2))) {
        
                echo "
                
                    <a class='adresas d-flex flex-row py-2' href='prekiu_uzsakymai.php'>
                        <i class='fa-solid fa-dolly fa-fw my-auto mx-2'></i>
                        <p class='m-0'>Prekių užsakymai</p>
                    </a>
        
                ";
        
            }
        
            if(in_array($_SESSION["grupe"], array(2))) {
        
                echo "
                
                    <a class='adresas d-flex flex-row py-2' href='sandeliai.php'>
                        <i class='fa-solid fa-warehouse fa-fw my-auto mx-2'></i>
                        <p class='m-0'>Sandėliai</p>
                    </a>
        
                ";
        
            }
        
            if(in_array($_SESSION["grupe"], array(2))) {
        
                echo "
                
                    <a class='adresas d-flex flex-row py-2' href='tiekejai.php'>
                        <i class='fa-solid fa-truck fa-fw my-auto mx-2'></i>
                        <p class='m-0'>Tiekėjai</p>
                    </a>
        
                ";
        
            }
        
            if(in_array($_SESSION["grupe"], array(3))) {
        
                echo "
                
                    <a class='adresas d-flex flex-row py-2' href='vartotojai.php'>
                        <i class='fa-solid fa-users fa-fw my-auto mx-2'></i>
                        <p class='m-0'>Vartotojai</p>
                    </a>
        
                ";
        
            }

            echo "

                <div class='mt-3 text-center d-flex flex-row justify-content-between'><p class='m-0'>".$_SESSION["vartotojas"]."</p> <a href='atsijungti.php' class='adresas'><i class='fa-solid fa-arrow-right-from-bracket ms-2'></i></a></div>

            </div>
            
        </div>

";

?>