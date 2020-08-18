<?php
    if(!(isset($_GET) && isset($_GET["codEvento"]) && isset($_GET["under"]) && isset($_GET["reset"]) && isset($_GET["source"]))){
        header("LOCATION: index.php");
    }
    
    $codEvento = $_GET["codEvento"];
    $under = $_GET["under"];
    $reset = $_GET["reset"];
    $source = $_GET["source"];

    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");
    
    if($reset == "gironi") {
        $query = "DELETE FROM gironi WHERE codEvento = ".$codEvento." AND under = ".$under;
        $connessione->query($query);
        $query = "DELETE FROM vincitori WHERE codEvento = ".$codEvento." AND under = ".$under;
        $connessione->query($query);
        $query = "UPDATE `coppia_evento` SET `punt` = '0' WHERE `codEvento` = ".$codEvento." AND `under` = ".$under;
        $connessione->query($query);
    } else if($reset == "partite") {
        $query = "DELETE FROM partite WHERE codEvento = ".$codEvento." AND under = ".$under;
        $connessione->query($query);
        $query = "DELETE FROM coppia_girone WHERE codEvento = ".$codEvento." AND under = ".$under;
        $connessione->query($query);
        $query = "DELETE FROM vincitori WHERE codEvento = ".$codEvento." AND under = ".$under;
        $connessione->query($query);
        $query = "DELETE FROM gironi WHERE codEvento = ".$codEvento." AND under = ".$under." AND numCoppie = 2";
        $connessione->query($query);
        $query = "UPDATE `coppia_evento` SET `punt` = '0' WHERE `codEvento` = ".$codEvento." AND `under` = ".$under;
        $connessione->query($query);
    }

    if($source == "gironi")
        header("LOCATION: gironi.php?codEvento=".$codEvento."&under=".$under);
    else if($source == "partite")
        header("LOCATION: partite.php?codEvento=".$codEvento."&under=".$under);
?>