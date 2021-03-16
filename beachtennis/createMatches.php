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

    function createMatchesForRound($numForRound, $firstRound, $roundIndex, $connection) {

        $matches = array();
        $maxMatches = ($numForRound*($numForRound-1))/2;
        $counters = array();
        $maxCounter = 1;
        $lastMatch = array();
        
        $pairs = array();

        $currentMatch = array();
            
        $query = "SELECT coupleID, pos FROM couple_round WHERE roundID = ".($firstRound+$roundIndex)." ORDER BY pos ASC";
        $resultCoupleRound = $connection->query($query);

        for ($i=0; $i < $numForRound; $i++) {
            //Init pairs
            array_push($pairs, mysqli_fetch_assoc($resultCoupleRound)["coupleID"]);

            //Init counters
            array_push($counters, 0);
        }

        // init last pairs
        $lastMatch[0] = -1;
        $lastMatch[1] = -1;

        for ($i=0; $i < $maxMatches; $i++) { 
            
            // Find first pair
            for ($j=0; $j < $numForRound; $j++) {
                if ($counters[$j] < $maxCounter) {

                    $currentPair = $pairs[$j];
                    $isInLastPairs = isInLastPairs($lastMatch, $currentPair);

                    // maybe useless check
                    if (!$isInLastPairs || $numForRound < 5) {
                        $currentMatch[0] = $currentPair;
                        $counters[$j]++;
                        break;
                    }
                }
            }

            $incrementMaxCounter = shouldMaxCounterBeIncremented($counters, $maxCounter, $numForRound);
            if ($incrementMaxCounter) {
                $maxCounter++;
            }

            // Find second pair
            for ($j=0; $j < $numForRound; $j++) {
                if ($counters[$j] < $maxCounter) {

                    $currentPair = $pairs[$j];
                    $isInLastPairs = isInLastPairs($lastMatch, $currentPair);

                    if (!$isInLastPairs || $numForRound < 5) {

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

            $incrementMaxCounter = shouldMaxCounterBeIncremented($counters, $maxCounter, $numForRound);
            if ($incrementMaxCounter) {
                $maxCounter++;
            }

        }

        return normalizeMatches($matches);
    }

    function createMatches($numForRound, $firstRound, $connection, $numRounds) {
        $matches = array();
        for ($i=0; $i < $numRounds; $i++) {
            array_push($matches, createMatchesForRound($numForRound, $firstRound, $i, $connection));
        }
        return $matches;
    }

    function insertMatchesInDatabase($matches, $firstRound, $eventID, $under, $connection) {
        $numMatches = count($matches[0]);
        for ($numMatch=0; $numMatch < $numMatches; $numMatch++) {
            for ($numRound=0; $numRound < count($matches); $numRound++) { 
                $roundID = $firstRound+$numRound;

                $couple1 = $matches[$numRound][$numMatch][0];
                $couple2 = $matches[$numRound][$numMatch][1];

                $query = "INSERT INTO `matches` (`roundID`, `eventID`, `idCouple1`, `idCouple2`, `under`) VALUES ('".$roundID."', '".$eventID."', '".$couple1."', '".$couple2."', '".$under."')";
                $connection->query($query);
            }
        }
    }

?>

<?php
    $connection = new mysqli("localhost","root","","beachtennis");

    if($connection->connect_errno)
        die("<h1>Errore connessione al database</h1>");

    if(!(isset($_GET) && isset($_GET["eventID"]) && isset($_GET["under"]) && isset($_GET["ids"]) && isset($_GET["source"])))
        header("LOCATION: index.php");
    
    //Get preliminar data
    $eventID = $_GET["eventID"];
    $under = $_GET["under"];
    $source = $_GET["source"];

    if($source == "rounds")
        $query = "SELECT * FROM rounds WHERE eventID = ".$eventID." AND under = ".$under." AND numCouples>2 ORDER BY roundID ASC";
    else if($source == "scoreboard")
        $query = "SELECT * FROM rounds WHERE eventID = ".$eventID." AND under = ".$under." AND numCouples=2 ORDER BY roundID ASC";

    $result1 = $connection->query($query);
    
    $ids = explode(",",$_GET["ids"]);

    $line = mysqli_fetch_assoc($result1);

    $numForRound = $line["numCouples"];
    $firstRound = $line["roundID"];

    $numRounds = $result1->num_rows;

    if($source == "rounds")
        $query = "SELECT * FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under;
    else if($source == "scoreboard")
        $query = "SELECT * FROM winners WHERE eventID = ".$eventID." AND under = ".$under;

    $result2 = $connection->query($query);

    $numCouples = $result2->num_rows;

    //Create association between couples and rounds

    $query = "SELECT * FROM couple_round WHERE roundID = ".$firstRound;

    $resultTemp = $connection->query($query);
    if($resultTemp->num_rows != 0){
        if($source == "rounds")
            echo "<script>window.alert('Sono già presenti delle matches relative a questo evento. Non è più possibile modificare i rounds ad esso relativi. Se vuoi creare dei nuovi rounds cancella tutte le matches di questo evento'); window.location.href = \"rounds.php?eventID=".$eventID."&under=".$under."\";</script>";
        else if ($source == "scoreboard")
            echo "<script>window.alert('Le finali sono già state generate. Prima di rigenerarle vanno cancellate quelle già presenti'); window.location.href = \"scoreboard.php?eventID=".$eventID."&under=".$under."\";</script>";
        return;
    }

    for($i=0; $i<$numRounds; $i++){
        for($j=0; $j<$numForRound; $j++){
            $query = "INSERT INTO `couple_round` (`roundID`, `eventID`, `coupleID`, `under`, `pos`, `numCouples`) VALUES ('".($firstRound+$i)."', '".$eventID."', '".$ids[($numCouples+($i*$numForRound)+$j)]."', '".$under."', '".$j."', '".$numForRound."')";
            $connection->query($query);
        }
    }

    //Create matches
    if($numForRound==2) {
        for ($i=0; $i < $numRounds; $i++) {
            //A vs B
            for ($i=0; $i < $numRounds; $i++) {
                $query = "SELECT coupleID, pos FROM couple_round WHERE roundID = ".($firstRound+$i)." ORDER BY pos ASC";
                $resultCoupleRound = $connection->query($query);

                $roundID = $firstRound+$i;
                $couple1 = mysqli_fetch_assoc($resultCoupleRound)["coupleID"];
                $couple2 = mysqli_fetch_assoc($resultCoupleRound)["coupleID"];

                $query = "INSERT INTO `matches` (`roundID`, `eventID`, `idCouple1`, `idCouple2`, `under`, `final`) VALUES ('".$roundID."', '".$eventID."', '".$couple1."', '".$couple2."', '".$under."', '1')";
                $connection->query($query);
            }
        }
    } else {
        $matches = createMatches($numForRound, $firstRound, $connection, $numRounds);
        insertMatchesInDatabase($matches, $firstRound, $eventID, $under, $connection);
    }

    header("LOCATION: matches.php?eventID=".$eventID."&under=".$under);
?>