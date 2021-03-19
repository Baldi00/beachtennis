<?php
    include 'modules/db_connection.php';

    $connection = openConnection();
    
    $query = "SELECT * FROM events";

    $result = $connection->query($query);
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
                    <a class="nav-link" href="addEvent.php">Aggiungi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="editEvents.php">Modifica</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="deleteEvent.php">Cancella</a>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Cancella evento</h3>
    <h6 align="center">Cancellando un evento cancelli anche tutte le partite e i gironi ad esso relativo</h6>

    <table class="table table-striped">
        <thead>
            <tr style="text-align: center;">
            <th scope="col">Nome Evento</th>
            <th scope="col">Data Inizio</th>
            <th scope="col">Data Fine</th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            
            <?php
                $numRow = $result->num_rows;

                if($numRow==0)
                    header("LOCATION: events.php");
                
                for($i=0; $i<$numRow; $i++){
                    $line = mysqli_fetch_assoc($result);
                    echo "  <tr style='text-align: center'>
                                <th scope='row'>".$line["eventName"]."</th>";
                                
                    if($line["startDate"]=="")
                        echo "  <td>Da definire</td>";
                    else
                        echo "  <td>".$line["startDate"]."</td>";

                    if($line["endDate"]=="")
                        echo "  <td>Da definire</td>";
                    else
                        echo "  <td>".$line["endDate"]."</td>";

                    echo "      <td><a href='actionEvent.php?action=delete&id=".$line["eventID"]."'>Cancella</a></td>
                            </tr>";
                }
            ?>
        </tbody>
    </table>

</body>
</html>