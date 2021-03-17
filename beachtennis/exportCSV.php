<!DOCTYPE html>
<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="public/css/bootstrap.min.css">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="public/js/jquery-3.5.1.slim.min.js"></script>
    <script src="public/js/popper.min.js"></script>
    <script src="public/js/bootstrap.min.js"></script>

    <title>Beach Tennis</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="index.php">Beach Tennis</a>
        <button class="navbar-toggler" type="button" date-toggle="collapse" date-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>

    <?php
        include 'modules/db_connection.php';

        $today = date("Y-m-d");

        $connection = openConnection();

        if(isset($_GET["source"])){
            if($_GET["source"]=="couples"){
                $result = $connection->query("SELECT * FROM `couples`");

                $file = fopen("csv/".$today." - couples.csv", 'w');

                fwrite($file, "Nome;Partecipante 1;Partecipante 2;Under;\r\n");

                for ($i=0; $i < $result->num_rows; $i++) {
                    $line = mysqli_fetch_array($result); 

                    $query = "SELECT * FROM players WHERE playerID = ".$line["part1"];
                    $linePlayer = mysqli_fetch_assoc($connection->query($query));
                    $part1 = $linePlayer["name"];
                    $year1 = $linePlayer["birthdayDate"];

                    $query = "SELECT * FROM players WHERE playerID = ".$line["part2"];
                    $linePlayer = mysqli_fetch_assoc($connection->query($query));
                    $part2 = $linePlayer["name"];
                    $year2 = $linePlayer["birthdayDate"];

                    fwrite($file, $line["name"].";".$part1." (".$year1.");".$part2." (".$year2.");".$line["under"].";\r\n");
                }

                fclose($file);

                echo "<h4 style='margin: 20px; text-align: center;'>Coppie esportate con successo</h4>";
                echo "<div width='100%' align='center'><button style='margin-right: 10px;' class='btn btn-primary' onclick='window.location.href = \"csv/".$today." - couples.csv\";'>Scarica</button>";
                echo "<button style='margin-left: 10px;' class='btn btn-primary' onclick='window.location.href = \"couples.php\";'>Torna indietro</button></div>";

            } else if($_GET["source"]=="players"){
                $result = $connection->query("SELECT * FROM `players`");

                $file = fopen("csv/".$today." - iscritti.csv", 'w');

                fwrite($file, "Nome;Data di Nascita;Numero di Telefono;Iscritto;\r\n");

                for ($i=0; $i < $result->num_rows; $i++) {
                    $line = mysqli_fetch_array($result); 
                    fwrite($file, $line["name"].";".$line["birthdayDate"].";".$line["phoneNumber"].";".$line["subscribed"].";\r\n");
                }

                fclose($file);

                echo "<h4 style='margin: 20px; text-align: center;'>Iscritti esportati con successo</h4>";
                echo "<div width='100%' align='center'><button style='margin-right: 10px;' class='btn btn-primary' onclick='window.location.href = \"csv/".$today." - iscritti.csv\";'>Scarica</button>";
                echo "<button style='margin-left: 10px;' class='btn btn-primary' onclick='window.location.href = \"players.php\";'>Torna indietro</button></div>";

            } else if($_GET["source"]=="events"){

                $result = $connection->query("SELECT * FROM `events`");

                $file = fopen("csv/".$today." - eventi.csv", 'w');

                fwrite($file, "Nome Evento;Data Inizio;Data Fine;\r\n");

                for ($i=0; $i < $result->num_rows; $i++) {
                    $line = mysqli_fetch_array($result); 
                    fwrite($file, $line["eventName"].";".$line["startDate"].";".$line["endDate"].";\r\n");
                }

                fclose($file);

                echo "<h4 style='margin: 20px; text-align: center;'>Eventi esportati con successo</h4>";
                echo "<div width='100%' align='center'><button style='margin-right: 10px;' class='btn btn-primary' onclick='window.location.href = \"csv/".$today." - eventi.csv\";'>Scarica</button>";
                echo "<button style='margin-left: 10px;' class='btn btn-primary' onclick='window.location.href = \"events.php\";'>Torna indietro</button></div>";

            } else if($_GET["source"]=="couplesUnder"){
                if(!(isset($_GET) && isset($_GET["eventID"]) && isset($_GET["under"]))){
                    header("LOCATION: index.php");
                }
                
                $eventID = $_GET["eventID"];
                $under = $_GET["under"];

                $eventName = mysqli_fetch_assoc($connection->query("SELECT * FROM events WHERE eventID = ".$eventID))["eventName"];

                $file = fopen("csv/".$today." - coppie ".$eventName." Under ".$under.".csv", 'w');
                fwrite($file, "Nome;Partecipante 1;Partecipante 2;Punteggio;\r\n");

                $query = "SELECT * FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under." ORDER BY points DESC";
                $result = $connection->query($query);

                $numRow = $result->num_rows;
                for($i=0; $i<$numRow; $i++){

                    $lineCoupleEvent = mysqli_fetch_assoc($result);

                    $query = "SELECT * FROM couples WHERE coupleID = ".$lineCoupleEvent["coupleID"];
                    $resultCouple = $connection->query($query);

                    $line = mysqli_fetch_assoc($resultCouple);

                    $query = "SELECT * FROM players WHERE playerID = ".$line["part1"];
                    $linePlayer = mysqli_fetch_assoc($connection->query($query));
                    $part1 = $linePlayer["name"];
                    $year1 = $linePlayer["birthdayDate"];

                    $query = "SELECT * FROM players WHERE playerID = ".$line["part2"];
                    $linePlayer = mysqli_fetch_assoc($connection->query($query));
                    $part2 = $linePlayer["name"];
                    $year2 = $linePlayer["birthdayDate"];

                    $points = $lineCoupleEvent["points"];

                    fwrite($file, $line["name"].";".$part1." (".$year1.");".$part2." (".$year2.");".$points.";\r\n");

                }

                fclose($file);

                echo "<h4 style='margin: 20px; text-align: center;'>Coppie esportate con successo</h4>";
                echo "<div width='100%' align='center'><button style='margin-right: 10px;' class='btn btn-primary' onclick='window.location.href = \"csv/".$today." - couples ".$eventName." Under ".$under.".csv\";'>Scarica</button>";
                echo "<button style='margin-left: 10px;' class='btn btn-primary' onclick='window.location.href = \"couplesForEvent.php?eventID=".$eventID."&under=".$under."\";'>Torna indietro</button></div>";

            } else if($_GET["source"]=="matches"){
                if(!(isset($_GET) && isset($_GET["eventID"]) && isset($_GET["under"]))){
                    header("LOCATION: index.php");
                }
                
                $eventID = $_GET["eventID"];
                $under = $_GET["under"];

                $eventName = mysqli_fetch_assoc($connection->query("SELECT * FROM events WHERE eventID = ".$eventID))["eventName"];

                $file = fopen("csv/".$today." - partite ".$eventName." Under ".$under.".csv", 'w');
                fwrite($file, "Coppia 1;Coppia 2;Punteggio Coppia 1;Punteggio Coppia 2;Data;Campo;Finale;Vincitore;Differenza\r\n");

                $query = "SELECT * FROM matches WHERE eventID = ".$eventID." AND under = ".$under." ORDER BY matchID ASC";
                $resultMatches = $connection->query($query);

                $numRow = $resultMatches->num_rows;
                for($i=0; $i<$numRow; $i++){
                    $line = mysqli_fetch_assoc($resultMatches);

                    $query = "SELECT name FROM couples WHERE coupleID = ".$line["idCouple1"];
                    $resultCouple = $connection->query($query);
                    $couple1 = mysqli_fetch_assoc($resultCouple)["name"];

                    $query = "SELECT name FROM couples WHERE coupleID = ".$line["idCouple2"];
                    $resultCouple = $connection->query($query);
                    $couple2 = mysqli_fetch_assoc($resultCouple)["name"];

                    if($line["points1"] > $line["points2"])
                        $winner = $couple1;
                    else if($line["points1"] < $line["points2"])
                        $winner = $couple2;
                    else
                        $winner = "Pareggio";
                    
                    $difference = abs($line["points1"]-$line["points2"]);

                    fwrite($file, $couple1.";".$couple2.";".$line["points1"].";".$line["points2"].";".$line["date"].";".$line["field"].";".$line["finale"].";".$winner.";".$difference."\r\n");
                }

                fclose($file);

                echo "<h4 style='margin: 20px; text-align: center;'>Partite esportate con successo</h4>";
                echo "<div width='100%' align='center'><button style='margin-right: 10px;' class='btn btn-primary' onclick='window.location.href = \"csv/".$today." - partite ".$eventName." Under ".$under.".csv\";'>Scarica</button>";
                echo "<button style='margin-left: 10px;' class='btn btn-primary' onclick='window.location.href = \"matches.php?eventID=".$eventID."&under=".$under."\";'>Torna indietro</button></div>";

            } else if($_GET["source"]=="matchesEvent"){
                if(!(isset($_GET) && isset($_GET["eventID"]))){
                    header("LOCATION: index.php");
                }
                
                $eventID = $_GET["eventID"];

                $eventName = mysqli_fetch_assoc($connection->query("SELECT * FROM events WHERE eventID = ".$eventID))["eventName"];

                $query = "SELECT under FROM matches WHERE eventID = ".$eventID." GROUP BY under";
                $resultUnder = $connection->query($query);

                $numUnder = $resultUnder->num_rows;

                $matchesForUnder = array();

                $thereAreMatches = false;

                for ($i=0; $i < $numUnder; $i++) { 
                    $query = "SELECT * FROM matches WHERE eventID = ".$eventID." AND under = ".mysqli_fetch_assoc($resultUnder)["under"]." ORDER BY matchID ASC";

                    $resultMatches = $connection->query($query);

                    if($resultMatches->num_rows != 0)
                        $thereAreMatches = true;

                    array_push($matchesForUnder, $resultMatches);
                }

                if(!$thereAreMatches){
                    header("LOCATION: index.php");
                } else {
                    
                    $maxRows = $matchesForUnder[0]->num_rows;
                    for ($i=1; $i < $numUnder; $i++) { 
                        if($matchesForUnder[$i]->num_rows > $maxRows)
                            $maxRows = $matchesForUnder[$i]->num_rows;
                    }

                    $file = fopen("csv/".$today." - partite ".$eventName.".csv", 'w');
                    fwrite($file, "Under;Coppia 1;Coppia 2;Punteggio Coppia 1;Punteggio Coppia 2;Data;Campo;Finale;Vincitore;Differenza\r\n");

                    for ($i=0; $i < $maxRows; $i++) { 
                        for ($j=0; $j < $numUnder; $j++) { 
                            if($i < $matchesForUnder[$j]->num_rows){

                                $line = mysqli_fetch_assoc($matchesForUnder[$j]);

                                $query = "SELECT name FROM couples WHERE coupleID = ".$line["idCouple1"];
                                $resultCouple = $connection->query($query);
                                $couple1 = mysqli_fetch_assoc($resultCouple)["name"];

                                $query = "SELECT name FROM couples WHERE coupleID = ".$line["idCouple2"];
                                $resultCouple = $connection->query($query);
                                $couple2 = mysqli_fetch_assoc($resultCouple)["name"];

                                if($line["points1"] > $line["points2"])
                                    $winner = $couple1;
                                else if($line["points1"] < $line["points2"])
                                    $winner = $couple2;
                                else
                                    $winner = "Pareggio";
                                
                                $difference = abs($line["points1"]-$line["points2"]);

                                fwrite($file, $line["under"].";".$couple1.";".$couple2.";".$line["points1"].";".$line["points2"].";".$line["date"].";".$line["field"].";".$line["finale"].";".$winner.";".$difference."\r\n");
                            }
                        }
                    }
                }

                fclose($file);

                echo "<h4 style='margin: 20px; text-align: center;'>Partite esportate con successo</h4>";
                echo "<div width='100%' align='center'><button style='margin-right: 10px;' class='btn btn-primary' onclick='window.location.href = \"csv/".$today." - partite ".$eventName.".csv\";'>Scarica</button>";
                echo "<button style='margin-left: 10px;' class='btn btn-primary' onclick='window.location.href = \"matchesForEvent.php?eventID=".$eventID."\";'>Torna indietro</button></div>";

            } else if($_GET["source"]=="roundsUnder"){

                if(!(isset($_GET) && isset($_GET["eventID"]) && isset($_GET["under"]))){
                    header("LOCATION: index.php");
                }
                
                $eventID = $_GET["eventID"];
                $under = $_GET["under"];

                $eventName = mysqli_fetch_assoc($connection->query("SELECT * FROM events WHERE eventID = ".$eventID))["eventName"];

                $file = fopen("csv/".$today." - gironi ".$eventName." Under ".$under.".csv", 'w');
                fwrite($file, "Girone;Coppia;Posizione\r\n");

                $query = "SELECT * FROM couple_round WHERE eventID = ".$eventID." AND under = ".$under." AND numCouples>2";
                $resultCoupleRound = $connection->query($query);

                $numRow = $resultCoupleRound->num_rows;
                $lineCoupleRound = mysqli_fetch_assoc($resultCoupleRound);

                $numForRound = $lineCoupleRound["numCouples"];
                $numRounds = $numRow / $numForRound;

                mysqli_date_seek($resultCoupleRound,0);

                for($i=0; $i<$numRounds; $i++){
                    for ($j=0; $j < $numForRound; $j++) {
                        $line = mysqli_fetch_assoc($resultCoupleRound);

                        $query = "SELECT * FROM couples WHERE coupleID = ".$line["coupleID"];
                        $resultCouple = $connection->query($query);
                        $couple = mysqli_fetch_assoc($resultCouple)["name"];

                        fwrite($file, ($i+1).";".$couple.";".($line["pos"]+1).";\r\n");
                    }
                }

                fclose($file);

                echo "<h4 style='margin: 20px; text-align: center;'>Gironi esportati con successo</h4>";
                echo "<div width='100%' align='center'><button style='margin-right: 10px;' class='btn btn-primary' onclick='window.location.href = \"csv/".$today." - gironi ".$eventName." Under ".$under.".csv\";'>Scarica</button>";
                echo "<button style='margin-left: 10px;' class='btn btn-primary' onclick='window.location.href = \"rounds.php?eventID=".$eventID."&under=".$under."\";'>Torna indietro</button></div>";

            }
        }
    ?>

</body>
</html>

