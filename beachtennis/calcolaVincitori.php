<?php
    if(!(isset($_GET) && isset($_GET["codEvento"]) && isset($_GET["under"]))){
        header("LOCATION: index.php");
    }
    
    $codEvento = $_GET["codEvento"];
    $under = $_GET["under"];

    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");
    
    $query = "SELECT * FROM gironi WHERE codEvento = ".$codEvento." AND under = ".$under." AND numCoppie>2";

    $result = $connessione->query($query);
    $line = mysqli_fetch_assoc($result);

    $numGironi = $result->num_rows;
    $numPerGirone = $line["numCoppie"];

    mysqli_data_seek($result, 0);

    if($numPerGirone==3){
        for ($i=0; $i < $numGironi; $i++) { 
            $line = mysqli_fetch_assoc($result);
            $codGirone = $line["codGirone"];
            $query = "SELECT * FROM partite WHERE codEvento = ".$codEvento." AND under = ".$under." AND codGirone = ".$codGirone." ORDER BY codPartita";
            $resultPartite = $connessione->query($query);
            
            $part1 = mysqli_fetch_assoc($resultPartite);
            $part2 = mysqli_fetch_assoc($resultPartite);
            $part3 = mysqli_fetch_assoc($resultPartite);

            $winA = 0;
            $winB = 0;
            $winC = 0;

            if($part1["punt1"]>$part1["punt2"])
                $winA++;
            else
                $winB++;

            if($part2["punt1"]>$part2["punt2"])
                $winA++;
            else
                $winC++;

            if($part3["punt1"]>$part3["punt2"])
                $winB++;
            else
                $winC++;

            $codCoppiaA = $part1["codCoppia1"];
            $codCoppiaB = $part1["codCoppia2"];
            $codCoppiaC = $part2["codCoppia2"];

            if($winA == 2){
                if($winB==1){
                    $query1 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".$codCoppiaA."','".$codEvento."', '".$under."')";
                    $query2 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".$codCoppiaB."','".$codEvento."', '".$under."')";
                } else {
                    $query1 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".$codCoppiaA."','".$codEvento."', '".$under."')";
                    $query2 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".$codCoppiaC."','".$codEvento."', '".$under."')";
                }
            } else if($winB == 2) {
                if($winA == 1) {
                    $query1 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".$codCoppiaA."','".$codEvento."', '".$under."')";
                    $query2 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".$codCoppiaB."','".$codEvento."', '".$under."')";
                } else {
                    $query1 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".$codCoppiaB."','".$codEvento."', '".$under."')";
                    $query2 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".$codCoppiaC."','".$codEvento."', '".$under."')";
                }
            } else if($winC == 2) {
                if($winA == 1){
                    $query1 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".$codCoppiaA."','".$codEvento."', '".$under."')";
                    $query2 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".$codCoppiaC."','".$codEvento."', '".$under."')";
                } else {
                    $query1 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".$codCoppiaB."','".$codEvento."', '".$under."')";
                    $query2 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".$codCoppiaC."','".$codEvento."', '".$under."')";
                }
            } else {
                $puntA = ($part1["punt1"]-$part1["punt2"])+($part2["punt1"]-$part2["punt2"]);
                $puntB = ($part1["punt2"]-$part1["punt1"])+($part3["punt1"]-$part3["punt2"]);
                $puntC = ($part2["punt2"]-$part2["punt1"])+($part3["punt2"]-$part3["punt1"]);

                $coppiaPunteggio = array();

                $coppiaPunteggio[$codCoppiaA] = $puntA;
                $coppiaPunteggio[$codCoppiaB] = $puntB;
                $coppiaPunteggio[$codCoppiaC] = $puntC;

                arsort($coppiaPunteggio);

                $query1 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".array_keys($coppiaPunteggio)[0]."','".$codEvento."', '".$under."')";
                $query2 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".array_keys($coppiaPunteggio)[1]."','".$codEvento."', '".$under."')";
            }

            $query3 = "INSERT INTO `gironi` (`codEvento`, `under`, `numCoppie`) VALUES ('".$codEvento."', '".$under."', '2')";

            $connessione->query($query1);
            $connessione->query($query2);
            $connessione->query($query3);
        }
    } else if ($numPerGirone==4) {
        for ($i=0; $i < $numGironi; $i++) { 
            $line = mysqli_fetch_assoc($result);
            $codGirone = $line["codGirone"];
            $query = "SELECT * FROM coppia_girone WHERE codGirone = ".$codGirone." AND codEvento = ".$codEvento." AND under = ".$under." AND numCoppie>2 ORDER BY pos";
            $resultCoppiaGirone = $connessione->query($query);

            $codCoppiaA = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
            $codCoppiaB = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
            $codCoppiaC = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
            $codCoppiaD = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $puntA = mysqli_fetch_assoc($connessione->query("SELECT punt FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under." AND codCoppia = ".$codCoppiaA))["punt"];
            $puntB = mysqli_fetch_assoc($connessione->query("SELECT punt FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under." AND codCoppia = ".$codCoppiaB))["punt"];
            $puntC = mysqli_fetch_assoc($connessione->query("SELECT punt FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under." AND codCoppia = ".$codCoppiaC))["punt"];
            $puntD = mysqli_fetch_assoc($connessione->query("SELECT punt FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under." AND codCoppia = ".$codCoppiaD))["punt"];

            $coppiaPunteggio = array();

            $coppiaPunteggio[$codCoppiaA] = $puntA;
            $coppiaPunteggio[$codCoppiaB] = $puntB;
            $coppiaPunteggio[$codCoppiaC] = $puntC;
            $coppiaPunteggio[$codCoppiaD] = $puntD;

            arsort($coppiaPunteggio);

            $query1 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".array_keys($coppiaPunteggio)[0]."','".$codEvento."', '".$under."')";
            $query2 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".array_keys($coppiaPunteggio)[1]."','".$codEvento."', '".$under."')";

            $query3 = "INSERT INTO `gironi` (`codEvento`, `under`, `numCoppie`) VALUES ('".$codEvento."', '".$under."', '2')";

            $connessione->query($query1);
            $connessione->query($query2);
            $connessione->query($query3);
        }
    } else if ($numPerGirone==5) {
        for ($i=0; $i < $numGironi; $i++) { 
            $line = mysqli_fetch_assoc($result);
            $codGirone = $line["codGirone"];
            $query = "SELECT * FROM coppia_girone WHERE codGirone = ".$codGirone." AND codEvento = ".$codEvento." AND under = ".$under." AND numCoppie>2 ORDER BY pos";
            $resultCoppiaGirone = $connessione->query($query);

            $codCoppiaA = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
            $codCoppiaB = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
            $codCoppiaC = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
            $codCoppiaD = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
            $codCoppiaE = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $puntA = mysqli_fetch_assoc($connessione->query("SELECT punt FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under." AND codCoppia = ".$codCoppiaA))["punt"];
            $puntB = mysqli_fetch_assoc($connessione->query("SELECT punt FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under." AND codCoppia = ".$codCoppiaB))["punt"];
            $puntC = mysqli_fetch_assoc($connessione->query("SELECT punt FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under." AND codCoppia = ".$codCoppiaC))["punt"];
            $puntD = mysqli_fetch_assoc($connessione->query("SELECT punt FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under." AND codCoppia = ".$codCoppiaD))["punt"];
            $puntE = mysqli_fetch_assoc($connessione->query("SELECT punt FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under." AND codCoppia = ".$codCoppiaE))["punt"];

            $coppiaPunteggio = array();

            $coppiaPunteggio[$codCoppiaA] = $puntA;
            $coppiaPunteggio[$codCoppiaB] = $puntB;
            $coppiaPunteggio[$codCoppiaC] = $puntC;
            $coppiaPunteggio[$codCoppiaD] = $puntD;
            $coppiaPunteggio[$codCoppiaE] = $puntE;

            arsort($coppiaPunteggio);

            $query1 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".array_keys($coppiaPunteggio)[0]."','".$codEvento."', '".$under."')";
            $query2 = "INSERT INTO `vincitori` (`codCoppia`, `codEvento`, `under`) VALUES ('".array_keys($coppiaPunteggio)[1]."','".$codEvento."', '".$under."')";

            $query3 = "INSERT INTO `gironi` (`codEvento`, `under`, `numCoppie`) VALUES ('".$codEvento."', '".$under."', '2')";

            $connessione->query($query1);
            $connessione->query($query2);
            $connessione->query($query3);
        }
    }

    header("LOCATION: tabellone.php?codEvento=".$codEvento."&under=".$under);
?>