<?php
    include 'modules/db_connection.php';

    if(!(isset($_GET) && isset($_GET["eventID"]) && isset($_GET["under"])))
        header("LOCATION: index.php");
    
    $eventID = $_GET["eventID"];
    $under = $_GET["under"];

    $connection = openConnection();

    $query = "SELECT * FROM events WHERE eventID = ".$eventID;

    $resultEvents = $connection->query($query);
    $lineEvents = mysqli_fetch_assoc($resultEvents);
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="public/unknown/js/jquery-3.5.1.slim.min.js"></script>
    <script src="public/unknown/js/popper.min.js"></script>
    <script src="public/bootstrap/js/bootstrap.min.js"></script>

    <script>
        function validate(form) {

            document.getElementById("params").value;
            var boxes = document.getElementsByClassName("box");

            document.getElementById("params").value = "";
            for (var i = 0; i < boxes.length; i++) {
              document.getElementById("params").value += boxes[i].id + ",";
            }

            var valid = true;

            var ids = document.getElementById("params").value.split(",");
            var numCouples = parseInt(document.getElementById("numCouples").value);
            var numForRound = parseInt(document.getElementById("numForRound").value);
            var numRounds = parseInt(document.getElementById("numRounds").value);

            for(var i=numCouples; i < numCouples+(numRounds*numForRound) && valid; i++)
                if(ids[i]==0)
                    valid = false;

            if(!valid) {
                alert("Completa i gironi prima di generare le partite");
                return false;
            }
            return true;
        }
    </script>

    <link rel="stylesheet" href="public/unknown/css/draggableboxes.css">
    <script src="public/unknown/js/draganddrop.js"></script>

    <title>Beach Tennis</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="index.php">Beach Tennis</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="couplesForEvent.php?eventID='.$eventID.'&under='.$under.'">Coppie</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="rounds.php?eventID='.$eventID.'&under='.$under.'">Gironi</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="matches.php?eventID='.$eventID.'&under='.$under.'">Partite</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="scoreboard.php?eventID='.$eventID.'&under='.$under.'">Tabellone</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="matchesForEvent.php?eventID='.$eventID.'">Tutte le Partite</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="selectUnder.php?eventID='.$eventID.'">Cambia Under</a>';?>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="events.php">Cambia Evento</a>
                </li>
            </ul>
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item">
                    <a style="cursor: pointer" class="nav-link" onclick="resetMatches();"></a>
                </li>
                <li class="nav-item">
                    <a style="cursor: pointer" class="nav-link" onclick="resetRounds();"></a>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Tabellone <?php echo $lineEvents["eventName"]." Under ".$under;?></h3>


    <?php

        $query = "SELECT * FROM matches WHERE eventID = ".$eventID." AND under = ".$under." ORDER BY matchID ASC";
        $resultMatches = $connection->query($query);

        if($resultMatches->num_rows==0){
            echo "<h4 style='margin: 20px; text-align: center;'>Le partite non sono ancora state create. Vai in \"Gironi\" per generarle</h4>";
            echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"rounds.php?eventID=".$eventID."&under=".$under."\";'>Gironi</button></div>";
        } else {
        
            $query = "SELECT * FROM matches WHERE eventID = ".$eventID." AND under = ".$under." AND final = 0 ORDER BY matchID ASC";
            $resultMatches = $connection->query($query);

            $complete = true;
            for ($i=0; $i < $resultMatches->num_rows && $complete; $i++) { 
                $line = mysqli_fetch_assoc($resultMatches);
                if($line["points1"]=="" || $line["points2"]=="")
                    $complete = false;

            }

            if(!$complete){
                echo "<h4 style='margin: 20px; text-align: center;'>Le partite non si sono ancora svolte tutte. Vai in \"Partite\" e completa i pointseggi</h4>";
                echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"matches.php?eventID=".$eventID."&under=".$under."\";'>Partite</button></div>";
            } else {
                $query = "SELECT * FROM winners WHERE eventID = ".$eventID." AND under = ".$under;
                $resultWinners = $connection->query($query);

                $query = "SELECT * FROM rounds WHERE eventID = ".$eventID." AND under = ".$under." AND numCouples=2";
                $resultRounds = $connection->query($query);

                if($resultWinners->num_rows == 0) {
                    echo "<h4 style='margin: 20px; text-align: center;'>Le partite sono state completate</h4>";
                    echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"calculateWinners.php?eventID=".$eventID."&under=".$under."\";'>Calcola vincitori</button></div>";
                } else {
                    //Get & set some preliminar infos
                    $numCouples = $resultWinners->num_rows;
                    $numForRound = 2;
                    $numRounds = $resultWinners->num_rows/2;

                    echo "<input type='hidden' id='numCouples' value='".$numCouples."'>";
                    echo "<input type='hidden' id='numForRound' value='".$numForRound."'>";
                    echo "<input type='hidden' id='numRounds' value='".$numRounds."'>";

                    //Continue...
                    $query = "SELECT * FROM couple_round WHERE eventID = ".$eventID." AND under = ".$under." AND numCouples=2 ORDER BY roundID,pos ASC";
                    $resultCoupleRounds = $connection->query($query);

                    $query = "SELECT * FROM couple_round WHERE eventID = ".$eventID." AND under = ".$under." AND numCouples>2";
                    $resultTemp = $connection->query($query);
                    $firstRound = mysqli_fetch_assoc($resultTemp)["roundID"];

                    //Check if gironi had already been set
                    if($resultCoupleRounds->num_rows == 0) {

                        $query = "SELECT * FROM winners WHERE eventID = ".$eventID." AND under = ".$under;
                        $winners = $connection->query($query);

                        echo '  <div class="draggablecontainer" style="width: 40%; float: left; text-align: center">
                                    <h3 style="margin-left: 10px;">Coppie vincitrici</h3>';
                        for ($i=0; $i < $winners->num_rows; $i++) {
                            $lineCoppiaEvento = mysqli_fetch_assoc($winners);
                            $query = "SELECT * FROM couples WHERE coupleID = ".$lineCoppiaEvento["coupleID"];
                            $resultCouple = $connection->query($query);
                            $lineCouples = mysqli_fetch_assoc($resultCouple);

                            $query = "SELECT * FROM players WHERE playerID = ".$lineCouples["part1"];
                            $resultPart1 = $connection->query($query);
                            $linePart1 = mysqli_fetch_assoc($resultPart1);

                            $query = "SELECT * FROM players WHERE playerID = ".$lineCouples["part2"];
                            $resultPart2 = $connection->query($query);
                            $linePart2 = mysqli_fetch_assoc($resultPart2);

                            $query = "SELECT * FROM couple_round WHERE eventID = ".$eventID." AND under = ".$under." AND coupleID = ".$lineCouples["coupleID"]." AND numCouples>2";
                            $resultTemp = $connection->query($query);
                            $roundID = mysqli_fetch_assoc($resultTemp)["roundID"];

                            echo '<div draggable="true" id="'.$lineCouples["coupleID"].'" class="box">'.$lineCouples["name"].' ('.$linePart1["birthdayDate"].' - '.$linePart2["birthdayDate"].') [G'.($roundID-$firstRound+1).']</div>';
                        }
                        echo '  </div>
                                <div class="draggablecontainer" style="width: 60%; float: left; text-align: center">';

                        for ($i=0; $i < $resultRounds->num_rows; $i++) {
                            echo '<h3 style="margin-left: 10px;">Finale '.($i+1).'</h3>';
                            for ($j=0; $j < $numForRound; $j++) {
                                echo '<div draggable="true" id="0" class="box"></div>';
                            }
                        }
                        echo '</div>';

                    } else {

                        $query = "SELECT * FROM winners WHERE eventID = ".$eventID." AND under = ".$under;
                        $resultCouples = $connection->query($query);

                        echo '  <div class="draggablecontainer" style="width: 40%; float: left; text-align: center">
                                    <h3 style="margin-left: 10px;">Coppie vincitori</h3>';

                        for ($i=0; $i < $resultCouples->num_rows; $i++) {

                            $lineCouples = mysqli_fetch_assoc($connection->query("SELECT * FROM couples WHERE coupleID = ".mysqli_fetch_assoc($resultCouples)["coupleID"]));
                            mysqli_data_seek($resultCoupleRounds, 0);
                            $used = false;

                            for($j=0; $j < $resultCoupleRounds->num_rows && !$used; $j++){
                                $lineCoupleRound = mysqli_fetch_assoc($resultCoupleRounds);
                                if($lineCouples["coupleID"]==$lineCoupleRound["coupleID"])
                                    $used = true;
                            }

                            if($used) {
                                echo '<div draggable="true" id="0" class="box"></div>';
                            } else {

                                $query = "SELECT * FROM players WHERE playerID = ".$lineCouples["part1"];
                                $resultPart1 = $connection->query($query);
                                $linePart1 = mysqli_fetch_assoc($resultPart1);

                                $query = "SELECT * FROM players WHERE playerID = ".$lineCouples["part2"];
                                $resultPart2 = $connection->query($query);
                                $linePart2 = mysqli_fetch_assoc($resultPart2);

                                $query = "SELECT * FROM couple_round WHERE eventID = ".$eventID." AND under = ".$under." AND coupleID = ".$lineCouples["coupleID"]." AND numCouples>2";
                                $resultTemp = $connection->query($query);
                                $roundID = mysqli_fetch_assoc($resultTemp)["roundID"];

                                echo '<div draggable="true" id="'.$lineCouples["coupleID"].'" class="box">'.$lineCouples["name"].' ('.$linePart1["birthdayDate"].' - '.$linePart2["birthdayDate"].') [G'.($roundID-$firstRound+1).']</div>';
                            }
                        }

                        echo '  </div>
                                <div class="draggablecontainer" style="width: 60%; float: left; text-align: center">';
                        
                        mysqli_data_seek($resultCoupleRounds, 0);
                        for ($i=0; $i < $resultRounds->num_rows; $i++) {
                            echo '<h3 style="margin-left: 10px;">Finale '.($i+1).'</h3>';
                            for ($j=0; $j < $numForRound; $j++) {
                                $lineCoupleRound = mysqli_fetch_assoc($resultCoupleRounds);
                                $query = "SELECT * FROM couples WHERE coupleID = ".$lineCoupleRound["coupleID"];
                                $resultCouple = $connection->query($query);
                                $lineCouples = mysqli_fetch_assoc($resultCouple);

                                $query = "SELECT * FROM players WHERE playerID = ".$lineCouples["part1"];
                                $resultPart1 = $connection->query($query);
                                $linePart1 = mysqli_fetch_assoc($resultPart1);

                                $query = "SELECT * FROM players WHERE playerID = ".$lineCouples["part2"];
                                $resultPart2 = $connection->query($query);
                                $linePart2 = mysqli_fetch_assoc($resultPart2);

                                $query = "SELECT * FROM couple_round WHERE eventID = ".$eventID." AND under = ".$under." AND coupleID = ".$lineCouples["coupleID"]." AND numCouples>2";
                                $resultTemp = $connection->query($query);
                                $roundID = mysqli_fetch_assoc($resultTemp)["roundID"];
                                
                                echo '<div draggable="true" id="'.$lineCoupleRound["coupleID"].'" class="box">'.$lineCouples["name"].' ('.$linePart1["birthdayDate"].' - '.$linePart2["birthdayDate"].') [G'.($roundID-$firstRound+1).']</div>';
                            }
                        }
                        echo '</div>';
                    }
                    echo '  <form action="createMatches.php" onsubmit="return validate(this);" style="text-align: center; width: 100%; float: left;">
                                <input type="hidden" name="ids" id="params" value="">';
                    echo '      <input type="hidden" name="eventID" value="'.$eventID.'">';
                    echo '      <input type="hidden" name="under" value="'.$under.'">';
                    echo '      <input type="hidden" name="source" value="scoreboard">';
                    echo '      <div class="row" style="margin: 10px">
                                    <div class="col-sm" colspan="2">
                                        <input class="btn btn-primary" type="submit" value="Genera partite" style="width: 100%;">
                                    </div>
                                </div>
                            </form>';
                }
            }
        }
    ?>

    <script type="text/javascript">
        function resetRounds(){
            if (confirm("Sei sicuro di voler cancellare tutti i gironi di questo evento?"))
                window.location.href = '<?php echo "deleteRoundsMatches.php?eventID=".$eventID."&under=".$under."&reset=rounds&source=rounds";?>';
        }

        function resetMatches(){
            if (confirm("Sei sicuro di voler cancellare tutte le partite di questo evento?"))
                window.location.href = '<?php echo "deleteRoundsMatches.php?eventID=".$eventID."&under=".$under."&reset=matches&source=matches";?>';
        }
    </script>

</body>
</html>