<?php
    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");

    if(isset($_GET["azione"])){
    	if($_GET["azione"]=="elimina"){
		    $query = "DELETE FROM Giocatori WHERE codGiocatore = ".$_GET["cod"];
		    $result = $connessione->query($query);
		   	header("LOCATION: eliminaIscritto.php");
    	} else if($_GET["azione"]=="aggiungi"){
		    $query = "INSERT INTO `giocatori` (`nome`, `annoNascita`, `numeroTelefono`, `iscritto`) VALUES ('".$_GET['nome']."', '".$_GET['anno']."', '".$_GET['numero']."', '".$_GET['iscritto']."')";
            echo $query;
		    $result = $connessione->query($query);
		   	header("LOCATION: iscritti.php");
    	} else if($_GET["azione"]=="modifica"){
            $query = "SELECT * FROM giocatori";
            $result = $connessione->query($query);
            $numRow = $result->num_rows;

            for ($i=0; $i < $numRow; $i++) { 
                $query = "UPDATE `giocatori` SET `nome` = '".$_GET["nome".$i]."', `annoNascita` = '".$_GET["anno".$i]."', `numeroTelefono` ='".$_GET["numero".$i]."', `iscritto` ='".$_GET["iscritto".$i]."' WHERE codGiocatore = ".$_GET["codGiocatore".$i]."";
                $result = $connessione->query($query);
            }
            header("LOCATION: iscritti.php");
        }
    }
?>