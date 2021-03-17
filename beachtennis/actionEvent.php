<?php
    include 'modules/db_connection.php';

    $connection = openConnection();

    if(isset($_GET["action"])){
    	if($_GET["action"]=="delete"){
		    $query = "DELETE FROM events WHERE eventID = ".$_GET["id"];
		    $result = $connection->query($query);
		   	header("LOCATION: deleteEvent.php");
    	} else if($_GET["action"]=="add"){
            $query = "INSERT INTO events (`eventName`, `startDate`, `endDate`) VALUES ('".$_GET['name']."', '".$_GET['startDate']."', '".$_GET['endDate']."')";
            $result = $connection->query($query);
            header("LOCATION: events.php");
        } else if($_GET["action"]=="edit"){
            $query = "UPDATE events SET `eventName` = '".$_GET["name"]."', `startDate` = '".$_GET["startDate"]."', `endDate` ='".$_GET["endDate"]."' WHERE eventID = ".$_GET["id"]."";
            $result = $connection->query($query);
            header("LOCATION: events.php");
        }
    }
?>