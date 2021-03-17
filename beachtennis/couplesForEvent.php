<?php
    include 'modules/db_connection.php';

    if(!(isset($_GET) && isset($_GET["eventID"]) && isset($_GET["under"])))
        header("LOCATION: index.php");
    
    $eventID = $_GET["eventID"];
    $under = $_GET["under"];

    $connection = openConnection();
    
    $eventName = mysqli_fetch_assoc($connection->query("SELECT * FROM events WHERE eventID = ".$eventID))["eventName"];

    $query = "SELECT * FROM couple_event WHERE eventID = ".$eventID." AND under = ".$under." ORDER BY points DESC";
    $result = $connection->query($query);

    if($result->num_rows==0)
        header("LOCATION: selectCouplesForEvent.php?eventID=".$eventID."&under=".$under);

?>

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
                    <?php echo '<a class="nav-link" href="exportCSV.php?source=couplesUnder&eventID='.$eventID.'&under='.$under.'">Esporta coppie</a>';?>
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
                    <?php echo '<a class="nav-link" href="selectCouplesForEvent.php?eventID='.$eventID.'&under='.$under.'">Modifica</a>';?>
                </li>
            </ul>
        </div>
    </nav>
    
    <h3 align="center" style="margin-top: 10px;">Coppie <?php echo $eventName." Under ".$under;?></h3>

    <table class="table table-striped">
        <thead>
            <tr style="text-align: center;">
            <th scope="col">Nome</th>
            <th scope="col">Partecipante 1</th>
            <th scope="col">Partecipante 2</th>
            <th scope="col">Punteggio</th>
            </tr>
        </thead>
        <tbody>
            
            <?php
                $numRow = $result->num_rows;
                for($i=0; $i<$numRow; $i++){

                    $lineCoupleEvent = mysqli_fetch_assoc($result);

                    $query = "SELECT * FROM couples WHERE coupleID = ".$lineCoupleEvent["coupleID"];
                    $resultCouple = $connection->query($query);

                    $line = mysqli_fetch_assoc($resultCouple);
                    $query = "SELECT name FROM players WHERE playerID = ".$line["part1"];
                    $part1 = mysqli_fetch_assoc($connection->query($query))["name"];
                    $query = "SELECT name FROM players WHERE playerID = ".$line["part2"];
                    $part2 = mysqli_fetch_assoc($connection->query($query))["name"];

                    echo "  <tr style='text-align: center;'>
                                <th scope='row'>".$line["name"]."</th>
                                <td>".$part1."</td>
                                <td>".$part2."</td>";

                    $points = $lineCoupleEvent["points"];

                    if($points!=0)
                        echo "<td><b style='color:#007bff'>".$points."</b></td>";
                    else
                        echo "<td>".$points."</td>";

                    echo "  </tr>";
                }
            ?>
        </tbody>
    </table>

</body>
</html>