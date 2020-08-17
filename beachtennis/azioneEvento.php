<?php
    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");

    if(isset($_GET["azione"])){
    	if($_GET["azione"]=="elimina"){
		    $query = "DELETE FROM Eventi WHERE codEvento = ".$_GET["cod"];
		    $result = $connessione->query($query);
		   	header("LOCATION: eliminaEvento.php");
    	} else if($_GET["azione"]=="aggiungi"){
            $query = "INSERT INTO `eventi` (`nomeEvento`, `dataInizio`, `dataFine`) VALUES ('".$_GET['nome']."', '".$_GET['dataInizio']."', '".$_GET['dataFine']."')";
            $result = $connessione->query($query);
            header("LOCATION: eventi.php");
        } else if($_GET["azione"]=="modifica"){
            $query = "UPDATE `eventi` SET `nomeEvento` = '".$_GET["nome"]."', `dataInizio` = '".$_GET["dataInizio"]."', `dataFine` ='".$_GET["dataFine"]."' WHERE codEvento = ".$_GET["cod"]."";
            $result = $connessione->query($query);
            header("LOCATION: eventi.php");
        }
    }
?>