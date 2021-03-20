<?php
    include 'modules/db_connection.php';

    $connection = openConnection();

    if(isset($_GET["action"])){
    	if($_GET["action"]=="delete"){
		    $query = "DELETE FROM players WHERE playerID = ".$_GET["id"];
		    $result = $connection->query($query);
		   	header("LOCATION: players.php");
    	} else if($_GET["action"]=="add"){
		    $query = "INSERT INTO players (`name`, `birthdayDate`, `phoneNumber`, `subscribed`) VALUES ('".$_GET['name']."', '".$_GET['date']."', '".$_GET['number']."', '".$_GET['subscribed']."')";
            echo $query;
		    $result = $connection->query($query);
		   	header("LOCATION: players.php");
    	} else if($_GET["action"]=="edit"){
            $query = "SELECT * FROM players";
            $result = $connection->query($query);
            $numRow = $result->num_rows;

            for ($i=0; $i < $numRow; $i++) { 
                $query = "UPDATE players SET `name` = '".$_GET["name".$i]."', `birthdayDate` = '".$_GET["date".$i]."', `phoneNumber` ='".$_GET["number".$i]."', `subscribed` ='".$_GET["subscribed".$i]."' WHERE playerID = ".$_GET["playerID".$i]."";
                $result = $connection->query($query);
            }
            header("LOCATION: players.php");
        }
    }
?>