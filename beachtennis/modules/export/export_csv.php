<?php
include_once '../../modules/db_connection.php';
include "CSV.php";

// TODO: create a file for every export type?

if (isset($_GET["source"])) {
    switch ($_GET["source"]) {
        case "couples": exportCouples(); break;
        case "players": exportPlayers(); break;
        case "events": exportEvents(); break;
        case "couplesUnder": exportCouplesUnder(); break;
        case "matches": exportMatches(); break;
        // TODO: should be eventMatches?
        case "matchesEvent": exportEventMatches(); break;
        case "roundsUnder": exportRoundsUnder(); break;
    }
}

function getCurrentDate() {
    return date("Y-m-d");
}

function getFileName($section) {
    return getCurrentDate()." - ".$section;
}

function exportCouples() {
    $connection = openConnection();
    $result = $connection->query("SELECT * FROM `couples`");

    $fileName = getFileName("coppie");
    $csv = new CSV($fileName);

    $csv->addElement("Nome");
    $csv->addElement("Partecipante 1");
    $csv->addElement("Partecipante 2");
    $csv->addElement("Under");
    $csv->newLine();

    for ($i = 0; $i < $result->num_rows; $i++) {
        $line = mysqli_fetch_array($result);

        $query = "SELECT * FROM players WHERE playerID = ".$line["part1"];
        $player1 = mysqli_fetch_assoc($connection->query($query));
        $csv->addElement($player1["name"]);
        $csv->addElement($player1["birthdayDate"]);

        $query = "SELECT * FROM players WHERE playerID = ".$line["part2"];
        $player2 = mysqli_fetch_assoc($connection->query($query));
        $csv->addElement($player2["name"]);
        $csv->addElement($player2["birthdayDate"]);

        $csv->newLine();
    }

    $csv->close();
}

function exportPlayers() {
    $connection = openConnection();
    $result = $connection->query("SELECT * FROM `players`");

    $fileName = getFileName("iscritti");
    $csv = new CSV($fileName);

    $csv->addElement("Nome");
    $csv->addElement("Data di Nascita");
    $csv->addElement("Numero di Telefono");
    $csv->addElement("Iscritto");
    $csv->newLine();

    for ($i = 0; $i < $result->num_rows; $i++) {
        $line = mysqli_fetch_array($result);
        $csv->addElement($line["name"]);
        $csv->addElement($line["birthdayDate"]);
        $csv->addElement($line["phoneNumber"]);
        $csv->addElement($line["subscribed"]);
        $csv->newLine();
    }

    $csv->close();
}

function exportEvents() {
    $connection = openConnection();
    $result = $connection->query("SELECT * FROM `events`");

    $fileName = getCurrentDate() . " - " . "eventi";
    $csv = new CSV($fileName);

    $csv->addElement("Nome Evento");
    $csv->addElement("Data Inizio");
    $csv->addElement("Data Fine");
    $csv->newLine();

    for ($i = 0; $i < $result->num_rows; $i++) {
        $line = mysqli_fetch_array($result);
        $csv->addElement($line["eventName"]);
        $csv->addElement($line["startDate"]);
        $csv->addElement($line["endDate"]);
        $csv->newLine();
    }

    $csv->close();
}

function exportCouplesUnder() {
    if (!(isset($_GET) && isset($_GET["eventID"]) && isset($_GET["under"]))) {
        header("LOCATION: index.php");
    }

    $eventID = $_GET["eventID"];
    $under = $_GET["under"];

    $connection = openConnection();
    $eventName = mysqli_fetch_assoc($connection->query("SELECT * FROM events WHERE eventID = " . $eventID))["eventName"];

    $fileName = getFileName("coppie " . $eventName . " under " . $under);
    $csv = new CSV($fileName);

    $csv->addElement("Nome");
    $csv->addElement("Partecipante 1");
    $csv->addElement("Partecipante 2");
    $csv->addElement("Punteggio");
    $csv->newLine();

    $query = "SELECT * FROM couple_event WHERE eventID = " . $eventID . " AND under = " . $under . " ORDER BY points DESC";
    $result = $connection->query($query);

    for ($i = 0; $i < $result->num_rows; $i++) {

        $lineCoupleEvent = mysqli_fetch_assoc($result);

        $query = "SELECT * FROM couples WHERE coupleID = " . $lineCoupleEvent["coupleID"];
        $resultCouple = $connection->query($query);

        $line = mysqli_fetch_assoc($resultCouple);
        $csv->addElement($line["name"]);

        $query = "SELECT * FROM players WHERE playerID = " . $line["part1"];
        $linePlayer = mysqli_fetch_assoc($connection->query($query));
        $playerName1 = $linePlayer["name"];
        $birthDate1 = $linePlayer["birthdayDate"];
        $csv->addElement($playerName1 . " (" . $birthDate1 . ")");

        $query = "SELECT * FROM players WHERE playerID = " . $line["part2"];
        $linePlayer = mysqli_fetch_assoc($connection->query($query));
        $playerName2 = $linePlayer["name"];
        $birthDate2 = $linePlayer["birthdayDate"];
        $csv->addElement($playerName2 . " (" . $birthDate2 . ")");

        $csv->addElement($lineCoupleEvent["points"]);

        $csv->newLine();
    }

    $csv->close();
}

function exportMatches() {
    if (!(isset($_GET) && isset($_GET["eventID"]) && isset($_GET["under"]))) {
        header("LOCATION: index.php");
    }

    $eventID = $_GET["eventID"];
    $under = $_GET["under"];

    $connection = openConnection();
    $eventName = mysqli_fetch_assoc($connection->query("SELECT * FROM events WHERE eventID = " . $eventID))["eventName"];

    $fileName = getFileName("partite " . $eventName . " under " . $under);
    $csv = new CSV($fileName);

    $csv->addElement("Coppia 1");
    $csv->addElement("Coppia 2");
    $csv->addElement("Punteggio Coppia 1");
    $csv->addElement("Punteggio Coppia 2");
    $csv->addElement("Data");
    $csv->addElement("Campo");
    $csv->addElement("Finale");
    $csv->addElement("Vincitore");
    $csv->addElement("Differenza");
    $csv->newLine();

    $query = "SELECT * FROM matches WHERE eventID = " . $eventID . " AND under = " . $under . " ORDER BY matchID ASC";
    $resultMatches = $connection->query($query);

    $numRow = $resultMatches->num_rows;
    for ($i = 0; $i < $numRow; $i++) {
        $line = mysqli_fetch_assoc($resultMatches);

        $query = "SELECT name FROM couples WHERE coupleID = " . $line["idCouple1"];
        $resultCouple = $connection->query($query);
        $couple1 = mysqli_fetch_assoc($resultCouple)["name"];

        $query = "SELECT name FROM couples WHERE coupleID = " . $line["idCouple2"];
        $resultCouple = $connection->query($query);
        $couple2 = mysqli_fetch_assoc($resultCouple)["name"];

        if ($line["points1"] > $line["points2"])
            $winner = $couple1;
        else if ($line["points1"] < $line["points2"])
            $winner = $couple2;
        else
            $winner = "Pareggio";

        $difference = abs($line["points1"] - $line["points2"]);

        $csv->addElement($couple1);
        $csv->addElement($couple2);
        $csv->addElement($line["points1"]);
        $csv->addElement($line["points2"]);
        $csv->addElement($line["date"]);
        $csv->addElement($line["field"]);
        $csv->addElement($line["finale"]);
        $csv->addElement($winner);
        $csv->addElement($difference);
        $csv->newLine();
    }

    $csv->close();
}

function exportRoundsUnder() {
    if (!(isset($_GET) && isset($_GET["eventID"]) && isset($_GET["under"]))) {
        header("LOCATION: index.php");
    }

    $eventID = $_GET["eventID"];
    $under = $_GET["under"];

    $connection = openConnection();
    $eventName = mysqli_fetch_assoc($connection->query("SELECT * FROM events WHERE eventID = " . $eventID))["eventName"];

    $fileName = getFileName("gironi " . $eventName . " under " . $under);
    $csv = new CSV($fileName);

    $csv->addElement("Girone");
    $csv->addElement("Coppia");
    $csv->addElement("Posizione");
    $csv->newLine();

    $query = "SELECT * FROM couple_round WHERE eventID = " . $eventID . " AND under = " . $under . " AND numCouples>2";
    $resultCoupleRound = $connection->query($query);

    $numRow = $resultCoupleRound->num_rows;
    $lineCoupleRound = mysqli_fetch_assoc($resultCoupleRound);

    $numForRound = $lineCoupleRound["numCouples"];
    $numRounds = $numRow / $numForRound;

    mysqli_date_seek($resultCoupleRound, 0);

    for ($i = 0; $i < $numRounds; $i++) {
        for ($j = 0; $j < $numForRound; $j++) {
            $line = mysqli_fetch_assoc($resultCoupleRound);

            $query = "SELECT * FROM couples WHERE coupleID = " . $line["coupleID"];
            $resultCouple = $connection->query($query);
            $couple = mysqli_fetch_assoc($resultCouple)["name"];

            $csv->addElement($i + 1);
            $csv->addElement($couple);
            $csv->addElement($line["pos"] + 1);
            $csv->newLine();
        }
    }

    $csv->close();
}

function exportEventMatches() {
    if (!(isset($_GET) && isset($_GET["eventID"]))) {
        header("LOCATION: index.php");
    }

    $eventID = $_GET["eventID"];

    $connection = openConnection();
    $eventName = mysqli_fetch_assoc($connection->query("SELECT * FROM events WHERE eventID = " . $eventID))["eventName"];

    $query = "SELECT under FROM matches WHERE eventID = " . $eventID . " GROUP BY under";
    $resultUnder = $connection->query($query);

    $numUnder = $resultUnder->num_rows;

    $matchesForUnder = array();

    $thereAreMatches = false;

    for ($i = 0; $i < $numUnder; $i++) {
        $query = "SELECT * FROM matches WHERE eventID = " . $eventID . " AND under = " . mysqli_fetch_assoc($resultUnder)["under"] . " ORDER BY matchID ASC";

        $resultMatches = $connection->query($query);

        if ($resultMatches->num_rows != 0)
            $thereAreMatches = true;

        array_push($matchesForUnder, $resultMatches);
    }

    if (!$thereAreMatches) {
        header("LOCATION: index.php");
    } else {

        $maxRows = $matchesForUnder[0]->num_rows;
        for ($i = 1; $i < $numUnder; $i++) {
            if ($matchesForUnder[$i]->num_rows > $maxRows)
                $maxRows = $matchesForUnder[$i]->num_rows;
        }
        $fileName = getFileName("partite " . $eventName);
        $csv = new CSV($fileName);

        $csv->addElement("Under");
        $csv->addElement("Coppia 1");
        $csv->addElement("Coppia 2");
        $csv->addElement("Punteggio Coppia 1");
        $csv->addElement("Punteggio Coppia 2");
        $csv->addElement("Data");
        $csv->addElement("Campo");
        $csv->addElement("Finale");
        $csv->addElement("Vincitore");
        $csv->addElement("Differenza");
        $csv->newLine();

        for ($i = 0; $i < $maxRows; $i++) {
            for ($j = 0; $j < $numUnder; $j++) {
                if ($i < $matchesForUnder[$j]->num_rows) {

                    $line = mysqli_fetch_assoc($matchesForUnder[$j]);

                    $query = "SELECT name FROM couples WHERE coupleID = " . $line["idCouple1"];
                    $resultCouple = $connection->query($query);
                    $couple1 = mysqli_fetch_assoc($resultCouple)["name"];

                    $query = "SELECT name FROM couples WHERE coupleID = " . $line["idCouple2"];
                    $resultCouple = $connection->query($query);
                    $couple2 = mysqli_fetch_assoc($resultCouple)["name"];

                    if ($line["points1"] > $line["points2"])
                        $winner = $couple1;
                    else if ($line["points1"] < $line["points2"])
                        $winner = $couple2;
                    else
                        $winner = "Pareggio";

                    $difference = abs($line["points1"] - $line["points2"]);

                    $csv->addElement($line["under"]);
                    $csv->addElement($couple1);
                    $csv->addElement($line["points1"]);
                    $csv->addElement($line["points2"]);
                    $csv->addElement($line["date"]);
                    $csv->addElement($line["field"]);
                    $csv->addElement($line["finale"]);
                    $csv->addElement($winner);
                    $csv->addElement($difference);
                    $csv->newLine();
                }
            }
        }

        $csv->close();
    }
}