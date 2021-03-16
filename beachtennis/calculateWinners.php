<?php
    if(!(isset($_GET) && isset($_GET["eventID"]) && isset($_GET["under"]))){
        header("LOCATION: index.php");
    }
    
    $eventID = $_GET["eventID"];
    $under = $_GET["under"];

    $connection = new mysqli("localhost","root","","beachtennis");

    if($connection->connect_errno)
        die("<h1>Errore connessione al database</h1>");
    
    $query = "SELECT * FROM rounds WHERE eventID = ".$eventID." AND under = ".$under." AND numCouples>2";

    $result = $connection->query($query);
    $line = mysqli_fetch_assoc($result);

    $numRounds = $result->num_rows;
    $numForRound = $line["numCouples"];

    mysqli_data_seek($result, 0);

    if($numForRound==3){
        for ($i=0; $i < $numRounds; $i++) {
            $line = mysqli_fetch_assoc($result);
            $roundID = $line["roundID"];
            $query = "SELECT * FROM matches WHERE eventID = ".$eventID." AND under = ".$under." AND roundID = ".$roundID." ORDER BY matchID";
            $resultMatches = $connection->query($query);
            
            $part1 = mysqli_fetch_assoc($resultMatches);
            $part2 = mysqli_fetch_assoc($resultMatches);
            $part3 = mysqli_fetch_assoc($resultMatches);

            $winA = 0;
            $winB = 0;
            $winC = 0;

            if($part1["points1"]>$part1["points2"])
                $winA++;
            else
                $winB++;

            if($part2["points1"]>$part2["points2"])
                $winA++;
            else
                $winC++;

            if($part3["points1"]>$part3["points2"])
                $winB++;
            else
                $winC++;

            $idCoupleA = $part1["idCouple1"];
            $idCoupleB = $part1["idCouple2"];
            $idCoupleC = $part2["idCouple2"];

            if($winA == 2){
                if($winB==1){
                    $query1 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".$idCoupleA."','".$eventID."', '".$under."')";
                    $query2 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".$idCoupleB."','".$eventID."', '".$under."')";
                } else {
                    $query1 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".$idCoupleA."','".$eventID."', '".$under."')";
                    $query2 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".$idCoupleC."','".$eventID."', '".$under."')";
                }
            } else if($winB == 2) {
                if($winA == 1) {
                    $query1 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".$idCoupleA."','".$eventID."', '".$under."')";
                    $query2 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".$idCoupleB."','".$eventID."', '".$under."')";
                } else {
                    $query1 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".$idCoupleB."','".$eventID."', '".$under."')";
                    $query2 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".$idCoupleC."','".$eventID."', '".$under."')";
                }
            } else if($winC == 2) {
                if($winA == 1){
                    $query1 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".$idCoupleA."','".$eventID."', '".$under."')";
                    $query2 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".$idCoupleC."','".$eventID."', '".$under."')";
                } else {
                    $query1 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".$idCoupleB."','".$eventID."', '".$under."')";
                    $query2 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".$idCoupleC."','".$eventID."', '".$under."')";
                }
            } else {
                $pointsA = ($part1["points1"]-$part1["points2"])+($part2["points1"]-$part2["points2"]);
                $pointsB = ($part1["points2"]-$part1["points1"])+($part3["points1"]-$part3["points2"]);
                $pointsC = ($part2["points2"]-$part2["points1"])+($part3["points2"]-$part3["points1"]);

                $coupleScore = array();

                $coupleScore[$idCoupleA] = $pointsA;
                $coupleScore[$idCoupleB] = $pointsB;
                $coupleScore[$idCoupleC] = $pointsC;

                arsort($coupleScore);

                $query1 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".array_keys($coupleScore)[0]."','".$eventID."', '".$under."')";
                $query2 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".array_keys($coupleScore)[1]."','".$eventID."', '".$under."')";
            }

            $query3 = "INSERT INTO `rounds` (`eventID`, `under`, `numCouples`) VALUES ('".$eventID."', '".$under."', '2')";

            $connection->query($query1);
            $connection->query($query2);
            $connection->query($query3);
        }
    } else if ($numForRound==4) {
        for ($i=0; $i < $numRounds; $i++) {
            $line = mysqli_fetch_assoc($result);
            $roundID = $line["roundID"];
            $query = "SELECT * FROM couple_round WHERE roundID = ".$roundID." AND eventID = ".$eventID." AND under = ".$under." AND numCouples>2 ORDER BY pos";
            $resultRoundCouple = $connection->query($query);

            $idCoupleA = mysqli_fetch_assoc($resultRoundCouple)["coupleID"];
            $idCoupleB = mysqli_fetch_assoc($resultRoundCouple)["coupleID"];
            $idCoupleC = mysqli_fetch_assoc($resultRoundCouple)["coupleID"];
            $idCoupleD = mysqli_fetch_assoc($resultRoundCouple)["coupleID"];

            $pointsA = mysqli_fetch_assoc($connection->query("SELECT points FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under." AND coupleID = ".$idCoupleA))["points"];
            $pointsB = mysqli_fetch_assoc($connection->query("SELECT points FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under." AND coupleID = ".$idCoupleB))["points"];
            $pointsC = mysqli_fetch_assoc($connection->query("SELECT points FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under." AND coupleID = ".$idCoupleC))["points"];
            $pointsD = mysqli_fetch_assoc($connection->query("SELECT points FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under." AND coupleID = ".$idCoupleD))["points"];

            $coupleScore = array();

            $coupleScore[$idCoupleA] = $pointsA;
            $coupleScore[$idCoupleB] = $pointsB;
            $coupleScore[$idCoupleC] = $pointsC;
            $coupleScore[$idCoupleD] = $pointsD;

            arsort($coupleScore);

            $query1 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".array_keys($coupleScore)[0]."','".$eventID."', '".$under."')";
            $query2 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".array_keys($coupleScore)[1]."','".$eventID."', '".$under."')";

            $query3 = "INSERT INTO `rounds` (`eventID`, `under`, `numCouples`) VALUES ('".$eventID."', '".$under."', '2')";

            $connection->query($query1);
            $connection->query($query2);
            $connection->query($query3);
        }
    } else if ($numForRound==5) {
        for ($i=0; $i < $numRounds; $i++) {
            $line = mysqli_fetch_assoc($result);
            $roundID = $line["roundID"];
            $query = "SELECT * FROM couple_round WHERE roundID = ".$roundID." AND eventID = ".$eventID." AND under = ".$under." AND numCouples>2 ORDER BY pos";
            $resultRoundCouple = $connection->query($query);

            $idCoupleA = mysqli_fetch_assoc($resultRoundCouple)["coupleID"];
            $idCoupleB = mysqli_fetch_assoc($resultRoundCouple)["coupleID"];
            $idCoupleC = mysqli_fetch_assoc($resultRoundCouple)["coupleID"];
            $idCoupleD = mysqli_fetch_assoc($resultRoundCouple)["coupleID"];
            $idCoupleE = mysqli_fetch_assoc($resultRoundCouple)["coupleID"];

            $pointsA = mysqli_fetch_assoc($connection->query("SELECT points FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under." AND coupleID = ".$idCoupleA))["points"];
            $pointsB = mysqli_fetch_assoc($connection->query("SELECT points FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under." AND coupleID = ".$idCoupleB))["points"];
            $pointsC = mysqli_fetch_assoc($connection->query("SELECT points FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under." AND coupleID = ".$idCoupleC))["points"];
            $pointsD = mysqli_fetch_assoc($connection->query("SELECT points FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under." AND coupleID = ".$idCoupleD))["points"];
            $pointsE = mysqli_fetch_assoc($connection->query("SELECT points FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under." AND coupleID = ".$idCoupleE))["points"];

            $coupleScore = array();

            $coupleScore[$idCoupleA] = $pointsA;
            $coupleScore[$idCoupleB] = $pointsB;
            $coupleScore[$idCoupleC] = $pointsC;
            $coupleScore[$idCoupleD] = $pointsD;
            $coupleScore[$idCoupleE] = $pointsE;

            arsort($coupleScore);

            $query1 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".array_keys($coupleScore)[0]."','".$eventID."', '".$under."')";
            $query2 = "INSERT INTO `winners` (`coupleID`, `eventID`, `under`) VALUES ('".array_keys($coupleScore)[1]."','".$eventID."', '".$under."')";

            $query3 = "INSERT INTO `rounds` (`eventID`, `under`, `numCouples`) VALUES ('".$eventID."', '".$under."', '2')";

            $connection->query($query1);
            $connection->query($query2);
            $connection->query($query3);
        }
    }

    header("LOCATION: scoreboard.php?eventID=".$eventID."&under=".$under);
?>