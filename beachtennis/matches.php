<?php
    include 'modules/db_connection.php';

    if(!(isset($_GET) && isset($_GET["eventID"]) && isset($_GET["under"]))){
        header("LOCATION: index.php");
    }
    
    $eventID = $_GET["eventID"];
    $under = $_GET["under"];

    $connection = openConnection();
    
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
                    <?php echo '<a class="nav-link" href="exportCSV.php?source=matches&eventID='.$eventID.'&under='.$under.'">Esporta partite</a>';?>
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
                    <?php echo '<a class="nav-link" href="editMatches.php?eventID='.$eventID.'&under='.$under.'">Modifica</a>';?>
                </li>
                <li class="nav-item">
                    <a style="cursor: pointer" class="nav-link" onclick="resetMatches();">Reset partite</a>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Partite <?php echo $line["eventName"]." Under ".$under;?></h3>

    <?php
        $query = "SELECT * FROM matches WHERE eventID = ".$eventID." AND under = ".$under." ORDER BY matchID ASC";
        $resultMatches = $connection->query($query);

        if($resultMatches->num_rows==0){
            echo "<h4 style='margin: 20px; text-align: center;'>Le partite non sono ancora state create. Vai in \"Gironi\" per generarle</h4>";
            echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"rounds.php?eventID=".$eventID."&under=".$under."\";'>Gironi</button></div>";
        } else {
            echo'<table class="table table-striped">
                <thead>
                    <tr>
                    <th scope="col" style="text-align:center">Coppia 1</th>
                    <th scope="col" style="text-align:center" colspan=3>Punteggio</th>
                    <th scope="col" style="text-align:center; border-right: 1px solid #dee2e6;">Coppia 2</th>
                    <th scope="col" style="text-align:center;">Data</th>
                    <th scope="col" style="text-align:center; border-right: 1px solid #dee2e6;">Campo</th>
                    <th scope="col" style="text-align:center">Vincitore</th>
                    <th scope="col" style="text-align:center">Differenza</th>
                    </tr>
                </thead>
                <tbody>';

            $numRow = $resultMatches->num_rows;
            for($i=0; $i<$numRow; $i++){
                $line = mysqli_fetch_assoc($resultMatches);


                if($line["final"]=="1")
                    echo "  <tr style='font-weight: bold'>";
                else
                    echo "  <tr>";

                $query = "SELECT name FROM couples WHERE coupleID = ".$line["idCouple1"];
                $resultCouples = $connection->query($query);
                $couple1 = mysqli_fetch_assoc($resultCouples)["name"];

                echo "<td style='text-align: center'>".$couple1."</td>";

                if($line["points1"]=="")
                    echo "<td style='text-align: center'>Da definire</td>";
                else
                    echo "<td style='text-align: center'>".$line["points1"]."</td>";

                echo "<td style='text-align: center'>-</td>";

                if($line["points2"]=="")
                    echo "<td style='text-align: center'>Da definire</td>";
                else
                    echo "<td style='text-align: center'>".$line["points2"]."</td>";

                $query = "SELECT name FROM couples WHERE coupleID = ".$line["idCouple2"];
                $resultCouples = $connection->query($query);
                $couple2 = mysqli_fetch_assoc($resultCouples)["name"];

                echo "<td style='text-align: center; border-right: 1px solid #dee2e6;'>".$couple2."</td>";

                if($line["date"]=="")
                    echo "<td style='text-align: center;'>Da definire</td>";
                else
                    echo "<td style='text-align: center;'>".$line["date"]."</td>";

                if($line["field"]=="")
                    echo "<td style='text-align: center; border-right: 1px solid #dee2e6;'>Da definire</td>";
                else
                    echo "<td style='text-align: center; border-right: 1px solid #dee2e6;'>".$line["field"]."</td>";

                if($line["points1"]=="" || $line["points2"]=="")
                    echo "<td style='text-align: center'>Da definire</td>";
                else{
                    if($line["points1"] > $line["points2"])
                        echo "<td style='text-align: center'><b style='color:#007bff'>".$couple1."</b></td>";
                    else if($line["points1"] < $line["points2"])
                        echo "<td style='text-align: center'><b style='color:#007bff'>".$couple2."</b></td>";
                    else
                        echo "<td style='text-align: center'><b style='color:#007bff'>Pareggio</b></td>";
                }
                
                if($line["points1"]=="" || $line["points2"]=="")
                    echo "<td style='text-align: center'>Da definire</td>";
                else
                    echo "<td style='text-align: center'><b style='color:#007bff'>".(abs($line["points1"]-$line["points2"]))."</b></td>";

                echo '</tr>';
            }
            echo '</tbody></table>';
        }

    ?>

    <script type="text/javascript">
        function resetMatches(){
            if (confirm("Sei sicuro di voler cancellare tutte le partite di questo evento?"))
                window.location.href = '<?php echo "deleteRoundsMatches.php?eventID=".$eventID."&under=".$under."&reset=matches&source=matches";?>';
        }
    </script>

</body>
</html>