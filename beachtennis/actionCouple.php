<?php
    include 'modules/db_connection.php';

    $connection = openConnection();

    if(isset($_GET["action"])){
    	if($_GET["action"]=="delete"){
		    $query = "DELETE FROM couples WHERE coupleID = ".$_GET["id"];
		    $result = $connection->query($query);
		   	header("LOCATION: deleteCouple.php");
    	} else if($_GET["action"]=="add"){
		    $query = "INSERT INTO couples (`name`, `part1`, `part2`, `under`) VALUES ('".$_GET['name']."', '".$_GET['part1']."', '".$_GET['part2']."', '".$_GET['under']."')";
		    $result = $connection->query($query);
		   	header("LOCATION: couples.php");
    	} else if($_GET["action"]=="edit"){
            $query = "SELECT * FROM couples";
            $result = $connection->query($query);
            $numRow = $result->num_rows;

            for ($i=0; $i < $numRow; $i++) { 
                $query = "UPDATE couples SET `name` = '".$_GET["name".$i]."', `part1` = '".$_GET["part1".$i]."', `part2` ='".$_GET["part2".$i]."', `under` ='".$_GET["under".$i]."' WHERE coupleID = ".$_GET["coupleID".$i]."";
                $result = $connection->query($query);
            }
            header("LOCATION: couples.php");
        }
    }
?>