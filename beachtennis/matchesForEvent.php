<?php
    if(!(isset($_GET) && isset($_GET["eventID"]))){
        header("LOCATION: index.php");
    }
    
    $eventID = $_GET["eventID"];

    $connection = new mysqli("localhost","root","","beachtennis");

    if($connection->connect_errno)
        die("<h1>Errore connessione al datebase</h1>");
    
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

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="matchesForEvent.php?eventID='.$eventID.'">Tutte le partite</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="exportCSV.php?source=matchesEvent&eventID='.$eventID.'">Esporta partite</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="selectUnder.php?eventID='.$eventID.'">Seleziona Under</a>';?>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="events.php">Cambia Evento</a>
                </li>
            </ul>
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="editMatchesForEvent.php?eventID='.$eventID.'">Modifica</a>';?>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Partite <?php echo $line["eventName"];?></h3>

    <?php
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
            echo "<h4 style='margin: 20px; text-align: center;'>Le partite non sono ancora state create. Seleziona un Under e vai in \"Aggiungi Partite\" per generarle</h4>";
            echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"selectUnder.php?eventID=".$eventID."\";'>Seleziona Under</button></div>";
        } else {
            
            $maxRows = $matchesForUnder[0]->num_rows;
            for ($i=1; $i < $numUnder; $i++) { 
                if($matchesForUnder[$i]->num_rows > $maxRows)
                    $maxRows = $matchesForUnder[$i]->num_rows;
            }

            echo'<table class="table table-striped">
                    <thead>
                        <tr>
                        <th scope="col" style="text-align:center; border-right: 1px solid #dee2e6;">Under</th>
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

            for ($i=0; $i < $maxRows; $i++) { 
                for ($j=0; $j < $numUnder; $j++) { 
                    if($i < $matchesForUnder[$j]->num_rows){

                        $line = mysqli_fetch_assoc($matchesForUnder[$j]);

                        if($line["final"]=="1")
                            echo "  <tr style='font-weight: bold'>";
                        else
                            echo "  <tr>";

                        echo "<td style='text-align: center; border-right: 1px solid #dee2e6;'>".$line["under"]."</td>";

                        $query = "SELECT name FROM couples WHERE coupleID = ".$line["idCouple1"];
                        $resultCoppia = $connection->query($query);
                        $coppia1 = mysqli_fetch_assoc($resultCoppia)["name"];

                        echo "<td style='text-align: center'>".$coppia1."</td>";

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
                        $resultCoppia = $connection->query($query);
                        $coppia2 = mysqli_fetch_assoc($resultCoppia)["name"];

                        echo "<td style='text-align: center; border-right: 1px solid #dee2e6;'>".$coppia2."</td>";

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
                                echo "<td style='text-align: center'><b style='color:#007bff'>".$coppia1."</b></td>";
                            else if($line["points1"] < $line["points2"])
                                echo "<td style='text-align: center'><b style='color:#007bff'>".$coppia2."</b></td>";
                            else
                                echo "<td style='text-align: center'><b style='color:#007bff'>Pareggio</b></td>";
                        }
                        
                        if($line["points1"]=="" || $line["points2"]=="")
                            echo "<td style='text-align: center'>Da definire</td>";
                        else
                            echo "<td style='text-align: center'><b style='color:#007bff'>".(abs($line["points1"]-$line["points2"]))."</b></td>";

                        echo '</tr>';
                    }
                }
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