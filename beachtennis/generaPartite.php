<?php
    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");

    if(!(isset($_GET) && isset($_GET["codEvento"]) && isset($_GET["under"]) && isset($_GET["ids"]) && isset($_GET["source"])))
        header("LOCATION: index.php");
    
    //Get preliminar data
    $codEvento = $_GET["codEvento"];
    $under = $_GET["under"];
    $source = $_GET["source"];

    if($source == "gironi")
        $query = "SELECT * FROM gironi WHERE codEvento = ".$codEvento." AND under = ".$under." AND numCoppie>2 ORDER BY codGirone ASC";
    else if($source == "tabellone")
        $query = "SELECT * FROM gironi WHERE codEvento = ".$codEvento." AND under = ".$under." AND numCoppie=2 ORDER BY codGirone ASC";

    $result1 = $connessione->query($query);
    
    $ids = explode(",",$_GET["ids"]);

    $line = mysqli_fetch_assoc($result1);

    $numPerGirone = $line["numCoppie"];
    $primoGirone = $line["codGirone"];

    $numGironi = $result1->num_rows;

    if($source == "gironi")
        $query = "SELECT * FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under;
    else if($source == "tabellone")
        $query = "SELECT * FROM vincitori WHERE codEvento = ".$codEvento." AND under = ".$under;

    $result2 = $connessione->query($query);

    $numCoppie = $result2->num_rows;

    //Create association between coppie and gironi

    $query = "SELECT * FROM coppia_girone WHERE codGirone = ".$primoGirone;

    $resultTemp = $connessione->query($query);
    if($resultTemp->num_rows != 0){
        if($source == "gironi")
            echo "<script>window.alert('Sono già presenti delle partite relative a questo evento. Non è più possibile modificare i gironi ad esso relativi. Se vuoi creare dei nuovi gironi cancella tutte le partite di questo evento'); window.location.href = \"gironi.php?codEvento=".$codEvento."&under=".$under."\";</script>";
        else if ($source == "tabellone")
            echo "<script>window.alert('Le finali sono già state generate. Prima di rigenerarle vanno cancellate quelle già presenti'); window.location.href = \"tabellone.php?codEvento=".$codEvento."&under=".$under."\";</script>";
        return;
    }

    for($i=0; $i<$numGironi; $i++){
        for($j=0; $j<$numPerGirone; $j++){
            $query = "INSERT INTO `coppia_girone` (`codGirone`, `codEvento`, `codCoppia`, `under`, `pos`, `numCoppie`) VALUES ('".($primoGirone+$i)."', '".$codEvento."', '".$ids[($numCoppie+($i*$numPerGirone)+$j)]."', '".$under."', '".$j."', '".$numPerGirone."')";
            $connessione->query($query);
        }
    }

    //Create partite
    if($numPerGirone==2) {
        for ($i=0; $i < $numGironi; $i++) {
            //A vs B
            for ($i=0; $i < $numGironi; $i++) {
                $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
                $resultCoppiaGirone = $connessione->query($query);

                $codGirone = $primoGirone+$i;
                $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
                $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

                $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`, `finale`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."', '1')";
                $connessione->query($query);
            }
        }
    } else if($numPerGirone==3) {
        for ($i=0; $i < $numGironi; $i++) {
            //A vs B
            for ($i=0; $i < $numGironi; $i++) {
                
                $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
                $resultCoppiaGirone = $connessione->query($query);

                $codGirone = $primoGirone+$i;
                $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
                $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

                $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
                $connessione->query($query);
            }

            //A vs C
            for ($i=0; $i < $numGironi; $i++) {
                
                $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
                $resultCoppiaGirone = $connessione->query($query);

                $codGirone = $primoGirone+$i;
                $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

                mysqli_fetch_assoc($resultCoppiaGirone); //Read useless second row

                $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

                $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
                $connessione->query($query);
            }

            //B vs C
            for ($i=0; $i < $numGironi; $i++) {
                
                $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
                $resultCoppiaGirone = $connessione->query($query);

                mysqli_fetch_assoc($resultCoppiaGirone); //Read useless first row

                $codGirone = $primoGirone+$i;
                $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
                $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

                $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
                $connessione->query($query);
            }

        }
    } else if ($numPerGirone==4) {
        //A vs B
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }

        //C vs D
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            //Fetch first 2
            mysqli_fetch_assoc($resultCoppiaGirone);
            mysqli_fetch_assoc($resultCoppiaGirone);

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }

        //A vs C
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless second row

            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }

        //B vs D
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless first row

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless third row

            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }

        //A vs D
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless second row
            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless third row

            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }

        //B vs C
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless first row

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }
    } else if ($numPerGirone==5) {

        //A vs B
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }

        //C vs D
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            //Fetch first 2
            mysqli_fetch_assoc($resultCoppiaGirone);
            mysqli_fetch_assoc($resultCoppiaGirone);

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }

        //A vs E
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless second row
            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless third row
            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless forth row

            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }

        //B vs C
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless first row

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }

        //D vs E
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless first row
            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless second row
            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless third row

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];
            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }

        //A vs C
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless second row

            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }

        //B vs D
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless first row

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless third row

            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }

        //C vs E
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless first row
            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless second row

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless forth row

            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }

        //A vs D
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless second row
            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless third row

            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }

        //B vs E
        for ($i=0; $i < $numGironi; $i++) {
            
            $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$i)." ORDER BY pos ASC";
            $resultCoppiaGirone = $connessione->query($query);

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless first row

            $codGirone = $primoGirone+$i;
            $coppia1 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless third row
            mysqli_fetch_assoc($resultCoppiaGirone); //Read useless forth row

            $coppia2 = mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"];

            $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
            $connessione->query($query);
        }
    }

    header("LOCATION: partite.php?codEvento=".$codEvento."&under=".$under);
?>