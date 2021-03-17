<?php
    include 'modules/db_connection.php';

    $connection = openConnection();
    
    $query = "SELECT * FROM couples";

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
            <ul class="navbar-nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="players.php">Tutti gli Iscritti</a>
                </li>
            </ul>
            <ul class="navbar-nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="couples.php">Tutte le Coppie</a>
                </li>
            </ul>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="events.php">Eventi</a>
                </li>
            </ul>
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item">
                    <a class="nav-link" href="addCouple.php">Aggiungi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="editCouples.php">Modifica</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="deleteCouple.php">Cancella</a>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Cancella coppia</h3>
    <h6 align="center">Se la coppia non viene cancellata significa che è presente in un evento in corso, occorre eliminare prima tale evento</h6>

    <table class="table table-striped">
        <thead>
            <tr style="text-align: center;">
            <th scope="col">name</th>
            <th scope="col">Partecipante 1</th>
            <th scope="col">Partecipante 2</th>
            <th scope="col">Under</th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            
            <?php
                $numRow = $result->num_rows;

                if($numRow==0)
                    header("LOCATION: couples.php");
                
                for($i=0; $i<$numRow; $i++){
                    $line = mysqli_fetch_assoc($result);

                    $query = "SELECT * FROM players WHERE playerID = ".$line["part1"];
                    $linePlayer = mysqli_fetch_assoc($connection->query($query));
                    $part1 = $linePlayer["name"];
                    $year1 = $linePlayer["birthdayDate"];

                    $query = "SELECT * FROM players WHERE playerID = ".$line["part2"];
                    $linePlayer = mysqli_fetch_assoc($connection->query($query));
                    $part2 = $linePlayer["name"];
                    $year2 = $linePlayer["birthdayDate"];

                    echo "  <tr style='text-align: center'>
                                <th scope='row'>".$line["name"]."</th>
                                <td>".$part1." (".$year1.")</td>
                                <td>".$part2." (".$year2.")</td>
                                <td>".$line["under"]."</td>
                                <td><a href='actionCouple.php?action=delete&id=".$line["coupleID"]."'>Cancella</a></td>
                            </tr>";
                }
            ?>
        </tbody>
    </table>

</body>
</html>