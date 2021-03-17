<?php
    $connection = new mysqli("localhost","root","","beachtennis");

    if($connection->connect_errno)
        die("<h1>Errore connessione al database</h1>");
    
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
                    <a class="nav-link" href="players.php">Tutti gli Iscritti</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="couples.php">Tutte le Coppie</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="events.php">Eventi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="exportCSV.php?source=events">Esporta eventi</a>
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

    <h3 align="center" style="margin-top: 10px;">Eventi</h3>
            
    <?php
        $numRow = $result->num_rows;

        if($numRow == 0){
            echo "<h4 style='margin: 20px; text-align: center;'>Nessun evento presente. Vai in \"Aggiungi evento\" per crearne uno</h4>";
            echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"addEvent.php\";'>Aggiungi evento</button></div>";
        } else {
            echo '  <table class="table table-striped">
                        <thead>
                            <tr style="text-align: center;">
                            <th scope="col">Nome Evento</th>
                            <th scope="col">Data Inizio</th>
                            <th scope="col">Data Fine</th>
                            <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>';
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

                echo "      <td><a href='selectUnder.php?eventID=".$line["eventID"]."'>Visualizza</a></td>
                        </tr>";
            }
            echo '      </tbody>
                    </table>';
        }
    ?>
</body>
</html>