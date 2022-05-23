<?php

require_once 'duomenu_baze.php';

require_once 'funkcijos.php';

require_once 'nustatymai.php';

if($stmt = $conn->prepare("SELECT * FROM prekes_sandeliai WHERE likutis <= ?")) {

    $stmt->bind_param("i", $prekiu_riba);

    $stmt->execute();

    $rezultatas = $stmt->get_result();

    if($rezultatas->num_rows > 0) {

        while($preke = $rezultatas->fetch_assoc()) {

            $tiekejas = -1;

            if($stmt2 = $conn->prepare("SELECT tiekejas FROM prekes_tiekejai WHERE preke = ? ORDER BY kaina ASC LIMIT 1")) {

                $stmt2->bind_param("i", $preke["preke"]);

                $stmt2->execute();

                $rezultatas2 = $stmt2->get_result();

                if($rezultatas2->num_rows == 1) {

                    $tiekejo_info = $rezultatas2->fetch_assoc();

                    $tiekejas = $tiekejo_info["tiekejas"];

                } else {

                    echo "Prekė su ID ".$preke["preke"]." neturi tiekėjų";

                }

                $stmt2->close();

            } else {

                echo "Klaida gaunant tiekėjo prekės kainą";

            }

            $suma = -1;

            if($stmt2 = $conn->prepare("SELECT SUM(? * kaina) AS suma FROM prekes_tiekejai WHERE preke = ? AND tiekejas = ?")) {

                $kiekis = ($prekiu_limitas - $preke["likutis"]);

                $stmt2->bind_param("iii", $kiekis, $preke["preke"], $tiekejas);

                $stmt2->execute();

                $rezultatas2 = $stmt2->get_result();

                if($rezultatas2->num_rows == 1) {

                    $sumos_info = $rezultatas2->fetch_assoc();

                    $suma = $sumos_info["suma"];

                } else {

                    echo "Prekė su ID ".$preke["preke"]." neturi tiekėjų";

                }

                $stmt2->close();

            } else {

                echo "Klaida gaunant prekių užsakymo sumą";

            }

            if($stmt2 = $conn->prepare("SELECT id FROM prekes_uzsakymai WHERE preke = ? AND sandelis = ? AND patvirtintas = 0")) {

                $kiekis = ($prekiu_limitas - $preke["likutis"]);

                $stmt2->bind_param("ii", $preke["preke"], $preke["sandelis"]);

                $stmt2->execute();

                $rezultatas2 = $stmt2->get_result();

                if($rezultatas2->num_rows == 0) {

                    if($stmt3 = $conn->prepare("INSERT INTO prekes_uzsakymai (preke, sandelis, kiekis, tiekejas, suma) VALUES (?, ?, ?, ?, ?)")) {

                        $kiekis = ($prekiu_limitas - $preke["likutis"]);
        
                        $stmt3->bind_param("iiiii", $preke["preke"], $preke["sandelis"], $kiekis, $tiekejas, $suma);
        
                        $stmt3->execute();
        
                        $stmt3->close();
        
                    } else {
        
                        echo "Klaida kuriant prekių užsakymą";
        
                    }

                }

                $stmt2->close();

            } else {

                echo "Klaida tikrinant prekių užsakymus";

            }

        }

    } else {

        echo "Prekių likučiai pakankami";

    }

    $stmt->close();

} else {

    echo "Klaida gaunant prekių likučius";

}

?>