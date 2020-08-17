<?php
    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");

    if(isset($_GET["azione"])){
    	if($_GET["azione"]=="elimina"){
		    $query = "DELETE FROM Coppie WHERE codCoppia = ".$_GET["cod"];
		    $result = $connessione->query($query);
		   	header("LOCATION: eliminaCoppia.php");
    	} else if($_GET["azione"]=="aggiungi"){
		    $query = "INSERT INTO `coppie` (`nome`, `part1`, `part2`, `under`) VALUES ('".$_GET['nome']."', '".$_GET['part1']."', '".$_GET['part2']."', '".$_GET['under']."')";
		    $result = $connessione->query($query);
		   	header("LOCATION: coppie.php");
    	} else if($_GET["azione"]=="modifica"){
            $query = "SELECT * FROM coppie";
            $result = $connessione->query($query);
            $numRow = $result->num_rows;

            for ($i=0; $i < $numRow; $i++) { 
                $query = "UPDATE `coppie` SET `nome` = '".$_GET["nome".$i]."', `part1` = '".$_GET["part1".$i]."', `part2` ='".$_GET["part2".$i]."', `under` ='".$_GET["under".$i]."' WHERE codCoppia = ".$_GET["codCoppia".$i]."";
                $result = $connessione->query($query);
            }
            header("LOCATION: coppie.php");
        }
    }
?>