<?php
    include 'modules/db_connection.php';

    if(!(isset($_GET) && isset($_GET["eventID"]) && isset($_GET["under"]))){
        header("LOCATION: index.php");
    }
    
    $eventID = $_GET["eventID"];
    $under = $_GET["under"];

    $connection = openConnection();

    if(isset($_GET["modified"])){
        $numRow = $_GET["numRow"];
        for($i=0; $i<$numRow; $i++) {
            if($_GET["points1".$i]=="")
                $points1 = 'NULL';
            else
                $points1 = "'".$_GET["points1".$i]."'";

            if($_GET["points2".$i]=="")
                $points2 = 'NULL';
            else
                $points2 = "'".$_GET["points2".$i]."'";

            $query = "UPDATE `matches` SET `date` = '".$_GET["date".$i]."', `field` = '".$_GET["field".$i]."', `points1` = ".$points1.", `points2` = ".$points2." WHERE matchID = ".$_GET["matchID".$i];
            $result = $connection->query($query);
        }

        //Score calculation
        $query = "SELECT * FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under;
        $result = $connection->query($query);

        for($i=0; $i<$result->num_rows; $i++) {
            $lineCoupleEvent = mysqli_fetch_assoc($result);

            $query = "SELECT * FROM couples WHERE coupleID = ".$lineCoupleEvent["coupleID"];
            $resultCouple = $connection->query($query);

            $line = mysqli_fetch_assoc($resultCouple);

            $query = "SELECT * FROM matches WHERE eventID = ".$eventID." AND under = ".$under." AND (idCouple1 = ".$line["coupleID"]." OR idCouple2 = ".$line["coupleID"].")";
            $resultMatches = $connection->query($query);

            $points = 0;

            if($resultMatches && $resultMatches->num_rows != 0) {
                for ($j=0; $j < $resultMatches->num_rows; $j++) {
                    $line2 = mysqli_fetch_assoc($resultMatches);
                    if($line2["points1"]!="" && $line2["points2"]!=""){
                        if($line["coupleID"]==$line2["idCouple1"] && $line2["points1"]>$line2["points2"])
                            $points += $line2["points1"]-$line2["points2"];
                        else if($line["coupleID"]==$line2["idCouple2"] && $line2["points2"]>$line2["points1"])
                            $points += $line2["points2"]-$line2["points1"];
                        else if($line["coupleID"]==$line2["idCouple1"] && $line2["points1"]<$line2["points2"])
                            $points += $line2["points1"]-$line2["points2"];
                        else if($line["coupleID"]==$line2["idCouple2"] && $line2["points2"]<$line2["points1"])
                            $points += $line2["points2"]-$line2["points1"];
                    }
                }
            }

            $query = "UPDATE couple_event SET points = ".$points." WHERE coupleID = ".$line["coupleID"]." AND eventID = ".$eventID;
            $connection->query($query);
        }

        header("LOCATION: matches.php?eventID=".$eventID."&under=".$under);
    }
    
    $query = "SELECT * FROM events WHERE eventID = ".$eventID;

    $result = $connection->query($query);
    $line = mysqli_fetch_assoc($result);
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

    <title>Beach Tennis</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="index.php">Beach Tennis</a>
        <button class="navbar-toggler" type="button" date-toggle="collapse" date-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                    <?php echo '<a class="nav-link" href="matches.php?eventID='.$eventID.'&under='.$under.'">Annulla</a>';?>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Partite <?php echo $line["eventName"]." Under ".$under;?></h3>

    <?php
        $query = "SELECT * FROM matches WHERE eventID = ".$eventID." AND under = ".$under." ORDER BY matchID ASC";
        $resultMatches = $connection->query($query);

        if($resultMatches->num_rows==0){
            header("LOCATION: matches.php?eventID=".$eventID."&under=".$under);
        } else {
            echo '<form action="editMatches.php">';
            echo '<table class="table table-striped">
                <thead>
                    <tr>
                    <th scope="col" style="text-align:center">Coppia 1</th>
                    <th scope="col" style="text-align:center" colspan=3>Punteggio</th>
                    <th scope="col" style="text-align:center; border-right: 1px solid #dee2e6;">Coppia 2</th>
                    <th scope="col" style="text-align:center;">Data</th>
                    <th scope="col" style="text-align:center; border-right: 1px solid #dee2e6;">Campo</th>
                    </tr>
                </thead>
                <tbody>';

            $numRow = $resultMatches->num_rows;
            echo "<input type='hidden' name='numRow' value='".$numRow."'>";
            for($i=0; $i<$numRow; $i++){
                $line = mysqli_fetch_assoc($resultMatches);
                echo "  <tr style='text-align: center'>";

                echo "<input type='hidden' name='matchID".$i."' value='".$line["matchID"]."'>";

                $query = "SELECT name FROM couples WHERE coupleID = ".$line["idCouple1"];
                $resultCouple = $connection->query($query);
                $couple1 = mysqli_fetch_assoc($resultCouple)["name"];

                echo "<td><input placeholder='Coppia 1' class='form-control' type='text' style='width: 100%; text-align: center' value='".$couple1."' disabled></td>";

                echo '<td><input placeholder="Punteggio 1" class="form-control" type="number" name="points1'.$i.'" min=0 style="width: 100%; text-align: center" value="'.$line["points1"].'"></td>';

                echo "<td>-</td>";

                echo '<td><input placeholder="Punteggio 2" class="form-control" type="number" name="points2'.$i.'" min=0 style="width: 100%; text-align: center" value="'.$line["points2"].'"></td>';

                $query = "SELECT name FROM couples WHERE coupleID = ".$line["idCouple2"];
                $resultCouple = $connection->query($query);
                $couple2 = mysqli_fetch_assoc($resultCouple)["name"];

                echo "<td style='border-right: 1px solid #dee2e6;'><input placeholder='Coppia 1' class='form-control' type='text' style='width: 100%; text-align: center' value='".$couple2."' disabled></td>";

                echo '<td><input placeholder="Data" class="form-control" type="text" name="date'.$i.'" style="width: 100%; text-align: center" value="'.$line["date"].'"></td>';

                echo '<td><input placeholder="Campo" class="form-control" type="text" name="field'.$i.'" style="width: 100%; text-align: center" value="'.$line["field"].'"></td>';


                echo '</tr>';
            }
            echo '</tbody></table>';
            echo "<input type='hidden' name='eventID' value='".$eventID."'>";
            echo "<input type='hidden' name='under' value='".$under."'>";
            echo "<input type='hidden' name='modified'>";

            echo '  <div class="row" style="margin: 10px;">
                        <div class="col-sm" colspan="2"><input class="btn btn-primary" type="submit" value="Salva" style="width: 100%"></div>
                    </div>';

            echo "</form>";
        }

    ?>

</body>
</html>