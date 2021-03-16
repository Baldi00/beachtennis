<?php
    if(!(isset($_GET) && isset($_GET["eventID"]) && isset($_GET["under"]) && isset($_GET["reset"]) && isset($_GET["source"]))){
        header("LOCATION: index.php");
    }
    
    $eventID = $_GET["eventID"];
    $under = $_GET["under"];
    $reset = $_GET["reset"];
    $source = $_GET["source"];

    $connection = new mysqli("localhost","root","","beachtennis");

    if($connection->connect_errno)
        die("<h1>Errore connessione al database</h1>");
    
    if($reset == "rounds") {
        $query = "DELETE FROM rounds WHERE eventID = ".$eventID." AND under = ".$under;
        $connection->query($query);
        $query = "DELETE FROM winners WHERE eventID = ".$eventID." AND under = ".$under;
        $connection->query($query);
        $query = "UPDATE `couple_event` SET `points` = '0' WHERE `eventID` = ".$eventID." AND `under` = ".$under;
        $connection->query($query);
    } else if($reset == "matches") {
        $query = "DELETE FROM matches WHERE eventID = ".$eventID." AND under = ".$under;
        $connection->query($query);
        $query = "DELETE FROM couple_round WHERE eventID = ".$eventID." AND under = ".$under;
        $connection->query($query);
        $query = "DELETE FROM winners WHERE eventID = ".$eventID." AND under = ".$under;
        $connection->query($query);
        $query = "DELETE FROM rounds WHERE eventID = ".$eventID." AND under = ".$under." AND numCouples = 2";
        $connection->query($query);
        $query = "UPDATE `couple_event` SET `points` = '0' WHERE `eventID` = ".$eventID." AND `under` = ".$under;
        $connection->query($query);
    }

    if($source == "rounds")
        header("LOCATION: rounds.php?eventID=".$eventID."&under=".$under);
    else if($source == "matches")
        header("LOCATION: matches.php?eventID=".$eventID."&under=".$under);
?>