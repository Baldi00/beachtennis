<?php
    if(!(isset($_GET) && isset($_GET["eventID"]) && isset($_GET["under"])))
        header("LOCATION: index.php");
    
    $eventID = $_GET["eventID"];
    $under = $_GET["under"];

    $connection = new mysqli("localhost","root","","beachtennis");

    if($connection->connect_errno)
        die("<h1>Errore connessione al database</h1>");

    $query = "SELECT * FROM matches WHERE eventID = ".$eventID." AND under = ".$under." ORDER BY matchID ASC";
    $resultMatches = $connection->query($query);

    if($resultMatches->num_rows!=0){
        echo "<script>window.alert('Sono già presenti delle partite relative a questo evento. Non è più possibile modificare le coppie partecipanti'); window.location.href = \"couplesForEvent.php?eventID=".$eventID."&under=".$under."\";</script>";
    }

    if(isset($_GET["modified"])){
        $numRow = $_GET["numRow"];
        for ($i=0; $i < $numRow; $i++) { 
            if(isset($_GET["customCheck".$i])){
                $query = "INSERT INTO `couple_event` (`coupleID`, `eventID`, `under`) VALUES ('".$_GET["coupleID".$i]."','".$eventID."','".$under."')";
                $result = $connection->query($query);
            }else{
                $query = "DELETE FROM couple_event WHERE coupleID = ".$_GET["coupleID".$i]." AND eventID = ".$eventID;
                $result = $connection->query($query);
            }
        }
        header("LOCATION: couplesForEvent.php?eventID=".$eventID."&under=".$under);
    }
    
    $nameEvento = mysqli_fetch_assoc($connection->query("SELECT * FROM events WHERE eventID = ".$eventID))["eventName"];

    $query = "SELECT * FROM couples WHERE under <= ".$under;
    $result = $connection->query($query);

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
                    <?php echo '<a class="nav-link" href="matchesForEvent.php?eventID='.$eventID.'">Tutte le Partite</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="selectUnder.php?eventID='.$eventID.'">Cambia Under</a>';?>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="events.php">Cambia Evento</a>
                </li>
            </ul>
        </div>
    </nav>
    
    <h3 align="center" style="margin-top: 10px;">Seleziona le coppie Under <?php echo $under;?> che parteciperanno a <?php echo $nameEvento;?></h3>
    <form action="selectCouplesForEvent.php">
        <table class="table table-striped">
            <thead>
                <tr style="text-align: center;">
                <th scope="col">Nome</th>
                <th scope="col">Partecipante 1</th>
                <th scope="col">Partecipante 2</th>
                <th scope="col">Under</th>
                <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                
                <?php
                    $numRow = $result->num_rows;

                    echo "<input type='hidden' name='numRow' value='".$numRow."'>";
                    echo "<input type='hidden' name='eventID' value='".$eventID."'>";
                    echo "<input type='hidden' name='under' value='".$under."'>";
                    echo "<input type='hidden' name='modified'>";

                    for($i=0; $i<$numRow; $i++){
                        $line = mysqli_fetch_assoc($result);

                        $query = "SELECT name FROM players WHERE playerID = ".$line["part1"];
                        $part1 = mysqli_fetch_assoc($connection->query($query))["name"];
                        $query = "SELECT name FROM players WHERE playerID = ".$line["part2"];
                        $part2 = mysqli_fetch_assoc($connection->query($query))["name"];


                        $query = "SELECT * FROM couple_event WHERE eventID = '".$eventID."' AND coupleID = '".$line["coupleID"]."' AND under = '".$under."'";
                        $result2 = $connection->query($query);

                        echo "  <tr style='text-align: center;'>
                                    <th scope='row'>".$line["name"]."</th>
                                    <td>".$part1."</td>
                                    <td>".$part2."</td>
                                    <td>".$line["under"]."</td>
                                    <td><div class='custom-control custom-checkbox'>
                                        <input type='hidden' name='coupleID".$i."' value='".$line["coupleID"]."'>";
                        if($result2->num_rows==0)
                            echo "          <input type='checkbox' class='custom-control-input' id='customCheck".$i."' name='customCheck".$i."'>";
                        else
                            echo "          <input type='checkbox' class='custom-control-input' id='customCheck".$i."' name='customCheck".$i."' checked>";


                        echo "          <label class='custom-control-label' for='customCheck".$i."'></label></div>
                                    </td>";
                        echo "  </tr>";
                    }
                ?>
            </tbody>
        </table>

        <div class="row" style="margin: 10px;">
            <div class="col-sm" colspan="2"><input class="btn btn-primary" type="submit" value="Salva" style="width: 100%"></div>
        </div>
    </form>

</body>
</html>