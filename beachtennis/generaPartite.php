<?php

    function normalizeMatches($matches) {
        for ($i=0; $i < count($matches); $i++) { 
            $pairs = $matches[$i];
            if ($pairs[0] > $pairs[1]) {
                $matches[$i][0] = $pairs[1];
                $matches[$i][1] = $pairs[0];
            }
        }

        return $matches;
    }

    function printMatches($matches) {
        for ($i = 0; $i < count($matches); $i++) { 
            echo 'coppia #' . ($i+1) . ':  ' . $matches[$i][0] . '-' . $matches[$i][1] . '<br>';
        }
    }
    
    function shouldMaxCounterBeIncremented($counters, $maxCounter, $numPerGirone) {
        for ($i = 0; $i < $numPerGirone; $i++) {
            if ($counters[$i] != $maxCounter) {
                return false;
            }
        }
        return true;
    }

    function isInLastPairs($lastMatch, $currentPair) {
        return $currentPair == $lastMatch[0] || $currentPair == $lastMatch[1];
    }

    function isInMatches($matches, $pairs) {
        $firstPair = $pairs[0];
        $secondPair = $pairs[1];

        for ($i = 0; $i < count($matches); $i++) {
            if ( ($firstPair == $matches[$i][0] || $firstPair == $matches[$i][1]) &&
                ($secondPair == $matches[$i][0] || $secondPair == $matches[$i][1]))
            {
                return true;
            }
        }

        return false;
    }

    function creaPartitePerGirone($numPerGirone, $primoGirone, $indiceGirone, $connessione) {

        $matches = array();
        $maxMatches = ($numPerGirone*($numPerGirone-1))/2;
        $counters = array();
        $maxCounter = 1;
        $lastMatch = array();
        
        $pairs = array();

        $currentMatch = array();
            
        $query = "SELECT codCoppia, pos FROM coppia_girone WHERE codGirone = ".($primoGirone+$indiceGirone)." ORDER BY pos ASC";
        $resultCoppiaGirone = $connessione->query($query);

        $codGirone = $primoGirone;

        for ($i=0; $i < $numPerGirone; $i++) { 
            //Init pairs
            array_push($pairs, mysqli_fetch_assoc($resultCoppiaGirone)["codCoppia"]);

            //Init counters
            array_push($counters, 0);
        }

        // init last pairs
        $lastMatch[0] = -1;
        $lastMatch[1] = -1;

        for ($i=0; $i < $maxMatches; $i++) { 
            
            // Find first pair
            for ($j=0; $j < $numPerGirone; $j++) { 
                if ($counters[$j] < $maxCounter) {

                    $currentPair = $pairs[$j];
                    $isInLastPairs = isInLastPairs($lastMatch, $currentPair);

                    // maybe useless check
                    if (!$isInLastPairs || $numPerGirone < 5) {
                        $currentMatch[0] = $currentPair;
                        $counters[$j]++;
                        break;
                    }
                }
            }

            $incrementMaxCounter = shouldMaxCounterBeIncremented($counters, $maxCounter, $numPerGirone);
            if ($incrementMaxCounter) {
                $maxCounter++;
            }

            // Find second pair
            for ($j=0; $j < $numPerGirone; $j++) { 
                if ($counters[$j] < $maxCounter) {

                    $currentPair = $pairs[$j];
                    $isInLastPairs = isInLastPairs($lastMatch, $currentPair);

                    if (!$isInLastPairs || $numPerGirone < 5) {

                        $currentMatch[1] = $currentPair;
                        
                        $isInMatches = isInMatches($matches, $currentMatch);
                        if (!$isInMatches) {
                            $counters[$j]++;
                            break;
                        }
                    }
                }
            }

            // add current match to matches
            array_push($matches, $currentMatch);

            // override last pairs
            $lastMatch = $currentMatch;

            $incrementMaxCounter = shouldMaxCounterBeIncremented($counters, $maxCounter, $numPerGirone);
            if ($incrementMaxCounter) {
                $maxCounter++;
            }

            // echo 'coppia #' . ($i+1) . ':  ' . $currentMatch[0] . '-' . $currentMatch[1] . '<br>';
            // for ($j = 0; $j < $numPerGirone; $j++) {
            //     echo "coppia: " . $pairs[$j] . "       counter: " . $counters[$j] . "<br>";
            // }
            // echo "<br>";

        }

        return normalizeMatches($matches);
    }

    function creaPartite($numPerGirone, $primoGirone, $connessione, $numGironi) {
        $matches = array();
        for ($i=0; $i < $numGironi; $i++) { 
            array_push($matches, creaPartitePerGirone($numPerGirone, $primoGirone, $i, $connessione));
        }
        return $matches;
    }

    function inserisciPartiteNelDatabase($matches, $primoGirone, $codEvento, $under, $coppiePerGirone, $connessione) {
        $numMatches = count($matches[0]);
        for ($numMatch=0; $numMatch < $numMatches; $numMatch++) {
            for ($numGirone=0; $numGirone < count($matches); $numGirone++) { 
                $codGirone = $primoGirone+$numGirone;

                $coppia1 = $matches[$numGirone][$numMatch][0];
                $coppia2 = $matches[$numGirone][$numMatch][1];

                $query = "INSERT INTO `partite` (`codGirone`, `codEvento`, `codCoppia1`, `codCoppia2`, `under`) 
                          VALUES ('".$codGirone."', '".$codEvento."', '".$coppia1."', '".$coppia2."', '".$under."')";
                $connessione->query($query);
            }
        }
    }

?>

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
    } else {
        $matches = creaPartite($numPerGirone, $primoGirone, $connessione, $numGironi);
        inserisciPartiteNelDatabase($matches, $primoGirone, $codEvento, $under, $numPerGirone, $connessione);
    }

    header("LOCATION: partite.php?codEvento=".$codEvento."&under=".$under);
?>