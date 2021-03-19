<?php
    include 'modules/db_connection.php';

    if(!(isset($_GET) && isset($_GET["eventID"]) && isset($_GET["under"])))
        header("LOCATION: index.php");
    
    $eventID = $_GET["eventID"];
    $under = $_GET["under"];

    $connection = openConnection();
    
    if(isset($_POST) && isset($_POST["numRounds"]) && isset($_POST["couplesForRound"])){
        $query = "INSERT INTO `rounds` (`eventID`, `under`, `numCouples`) VALUES ('".$eventID."', '".$under."', '".$_POST["couplesForRound"]."')";

        for ($i=0; $i < $_POST["numRounds"]; $i++) {
            $connection->query($query);
        }

        header("LOCATION: rounds.php?eventID=".$eventID."&under=".$under);
    }

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
                    <?php echo '<a class="nav-link" href="exportCSV.php?source=roundsUnder&eventID='.$eventID.'&under='.$under.'">Esporta rounds</a>';?>
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
                    <a style="cursor: pointer" class="nav-link" onclick="resetMatches();">Reset partite</a>
                </li>
                <li class="nav-item">
                    <a style="cursor: pointer" class="nav-link" onclick="resetRounds();">Reset gironi</a>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Gironi <?php echo $lineEvents["eventName"]." Under ".$under;?></h3>


    <?php
    
        $query = "SELECT * FROM rounds WHERE eventID = ".$eventID." AND under = ".$under." AND numCouples>2";
        $resultRounds = $connection->query($query);

        //Check if rounds for this evento is empty

        if($resultRounds->num_rows == 0) {

            echo '<form action="rounds.php?eventID='.$eventID.'&under='.$under.'" method="post">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td><div class="col-sm" width="50%"></div><div class="col-sm" width="50%"><input placeholder="Numero di gironi" class="form-control" type="number" required name="numRounds" min=1 style="width: 100%"></div></td>
                            <td><div class="col-sm" width="50%"></div><div class="col-sm" width="50%"><input placeholder="Coppie per girone" class="form-control" type="number" min="3" max="5" name="couplesForRound" style="width: 100%"></div></td>
                        </tr>
                    </tbody>
                </table>

                <div class="row" style="margin: 10px;">
                    <div class="col-sm" colspan="2"><input class="btn btn-primary" type="submit" value="Crea rounds" style="width: 100%"></div>
                </div>
            </form>';
        } else {

            echo '
                <div class="row" style="margin-top: 30px; margin-bottom: 20px; text-align: center">
                    <div class="col-sm" colspan="2">
                        <button class="btn btn-primary" onclick="randomGeneration();" style="width: 30%;">Assegnazione casuale</button>
                    </div>
                </div>';

            //Get & set some preliminar infos
            $numCouples = 0;
            $numForRound = 0;
            $numRounds = 0;

            $query = "SELECT * FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under;
            $resultTemp = $connection->query($query);
            $numCouples = $resultTemp->num_rows;

            $query = "SELECT * FROM rounds WHERE eventID = ".$eventID." AND under = ".$under." AND numCouples>2";
            $resultTemp = $connection->query($query);
            $numRounds = $resultTemp->num_rows;
            $numForRound = mysqli_fetch_assoc($resultTemp)["numCouples"];

            echo "<input type='hidden' id='numCouples' value='".$numCouples."'>";
            echo "<input type='hidden' id='numForRound' value='".$numForRound."'>";
            echo "<input type='hidden' id='numRounds' value='".$numRounds."'>";


            //Continue...
            $query = "SELECT * FROM couple_round WHERE eventID = ".$eventID." AND under = ".$under." AND numCouples>2 ORDER BY roundID,pos ASC";
            $resultCoupleRound = $connection->query($query);


            //Check if rounds had already been set
            if($resultCoupleRound->num_rows == 0) {

                $query = "SELECT * FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under;
                $resultCoppiaEvento = $connection->query($query);

                echo '  <div class="draggablecontainer" style="width: 40%; float: left; text-align: center">
                            <h3 style="margin-left: 10px;">Coppie</h3>';
                for ($i=0; $i < $resultCoppiaEvento->num_rows; $i++) {
                    $lineCoppiaEvento = mysqli_fetch_assoc($resultCoppiaEvento);
                    $query = "SELECT * FROM couples WHERE coupleID = ".$lineCoppiaEvento["coupleID"];
                    $resultCouple = $connection->query($query);
                    $lineCouples = mysqli_fetch_assoc($resultCouple);

                    $query = "SELECT * FROM players WHERE playerID = ".$lineCouples["part1"];
                    $resultPart1 = $connection->query($query);
                    $linePart1 = mysqli_fetch_assoc($resultPart1);

                    $query = "SELECT * FROM players WHERE playerID = ".$lineCouples["part2"];
                    $resultPart2 = $connection->query($query);
                    $linePart2 = mysqli_fetch_assoc($resultPart2);

                    echo '<div draggable="true" id="'.$lineCouples["coupleID"].'" class="box">'.$lineCouples["name"].' ('.$linePart1["birthdayDate"].' - '.$linePart2["birthdayDate"].')</div>';
                }
                echo '  </div>
                        <div class="draggablecontainer" style="width: 60%; float: left; text-align: center">';

                for ($i=0; $i < $resultRounds->num_rows; $i++) {
                    echo '<h3 style="margin-left: 10px;">Girone '.($i+1).'</h3>';
                    for ($j=0; $j < $numForRound; $j++) {
                        echo '<div draggable="true" id="0" class="box"></div>';
                    }
                }
                echo '</div>';

            } else {

                $query = "SELECT * FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under;
                $resultCouples = $connection->query($query);

                echo '  <div class="draggablecontainer" style="width: 40%; float: left; text-align: center">
                            <h3 style="margin-left: 10px;">Coppie</h3>';

                for ($i=0; $i < $resultCouples->num_rows; $i++) {

                    $lineCouples = mysqli_fetch_assoc($connection->query("SELECT * FROM couples WHERE coupleID = ".mysqli_fetch_assoc($resultCouples)["coupleID"]));

                    mysqli_data_seek($resultCoupleRound, 0);
                    $used = false;

                    for($j=0; $j < $resultCoupleRound->num_rows && !$used; $j++){
                        $lineCoupleRound = mysqli_fetch_assoc($resultCoupleRound);
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

                        echo '<div draggable="true" id="'.$lineCouples["coupleID"].'" class="box">'.$lineCouples["name"].' ('.$linePart1["birthdayDate"].' - '.$linePart2["birthdayDate"].')</div>';
                    }
                }

                echo '  </div>
                        <div class="draggablecontainer" style="width: 60%; float: left; text-align: center">';
                
                mysqli_data_seek($resultCoupleRound, 0);
                for ($i=0; $i < $resultRounds->num_rows; $i++) {
                    echo '<h3 style="margin-left: 10px;">Girone '.($i+1).'</h3>';
                    for ($j=0; $j < $numForRound; $j++) {
                        $lineCoupleRound = mysqli_fetch_assoc($resultCoupleRound);
                        $query = "SELECT * FROM couples WHERE coupleID = ".$lineCoupleRound["coupleID"];
                        $resultCouple = $connection->query($query);
                        $lineCouples = mysqli_fetch_assoc($resultCouple);

                        $query = "SELECT * FROM players WHERE playerID = ".$lineCouples["part1"];
                        $resultPart1 = $connection->query($query);
                        $linePart1 = mysqli_fetch_assoc($resultPart1);

                        $query = "SELECT * FROM players WHERE playerID = ".$lineCouples["part2"];
                        $resultPart2 = $connection->query($query);
                        $linePart2 = mysqli_fetch_assoc($resultPart2);

                        echo '<div draggable="true" id="'.$lineCoupleRound["coupleID"].'" class="box">'.$lineCouples["name"].' ('.$linePart1["birthdayDate"].' - '.$linePart2["birthdayDate"].')</div>';
                    }
                }
                echo '</div>';
            }

            echo '  <form action="createMatches.php" onsubmit="return validate(this);" style="text-align: center; width: 100%; float: left;">
                        <input type="hidden" name="ids" id="params" value="">';
            echo '      <input type="hidden" name="eventID" value="'.$eventID.'">';
            echo '      <input type="hidden" name="under" value="'.$under.'">';
            echo '      <input type="hidden" name="source" value="rounds">';
            echo '      <div class="row" style="margin: 10px">
                            <div class="col-sm" colspan="2">
                                <input class="btn btn-primary" type="submit" value="Genera partite" style="width: 100%;">
                            </div>
                        </div>
                    </form>';
        }
    ?>

    <script type="text/javascript">
        function randomGeneration(){
            document.getElementById("params").value;
            var boxes = document.getElementsByClassName("box");

            var idCouples = new Array();

            var numEmptyBox = 0;

            for(var i=0; i<boxes.length; i++)
                if(boxes[i].id == 0)
                    numEmptyBox++;
                else {
                    idCouples.push(boxes[i].id);
                }

            if(numEmptyBox > idCouples.length){
                window.alert("Non ci sono couples sufficienti per creare i rounds");
                return;
            }
            
            var randomized = shuffle(idCouples);

            var coupleName = new Array();

            console.log(document.getElementsByClassName("box"));

            for(var i=0; i<numEmptyBox; i++) {
                var found = false;
                for(var j=0; j<boxes.length && !found; j++) {
                    if(boxes[j].id == randomized[i]) {
                        coupleName.push(boxes[j].innerHTML);
                        document.getElementsByClassName("box")[j].innerHTML = "";
                        document.getElementsByClassName("box")[j].id = 0;
                        found = true;
                    }
                }
            }

            for(var i=0; i<numEmptyBox; i++) {
                document.getElementsByClassName("box")[document.getElementsByClassName("box").length-numEmptyBox+i].innerHTML = coupleName[i];
                document.getElementsByClassName("box")[document.getElementsByClassName("box").length-numEmptyBox+i].id = randomized[i];
            }
        }

        function shuffle(array) {
            var currentIndex = array.length, temporaryValue, randomIndex;

            // While there remain elements to shuffle...
            while (0 !== currentIndex) {

                // Pick a remaining element...
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex -= 1;

                // And swap it with the current element.
                temporaryValue = array[currentIndex];
                array[currentIndex] = array[randomIndex];
                array[randomIndex] = temporaryValue;
            }

            return array;
        }

        function resetRounds(){
            if (confirm("Sei sicuro di voler cancellare tutti i gironi di questo evento?"))
                window.location.href = '<?php echo "deleteRoundsMatches.php?eventID=".$eventID."&under=".$under."&reset=rounds&source=rounds";?>';
        }

        function resetMatches(){
            if (confirm("Sei sicuro di voler cancellare tutte le partite di questo evento?"))
                window.location.href = '<?php echo "deleteRoundsMatches.php?eventID=".$eventID."&under=".$under."&reset=partite&source=rounds";?>';
        }
    </script>

</body>
</html>