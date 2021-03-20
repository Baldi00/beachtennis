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

<?php require 'templates/navbar.php' ?>

<div class="container">

    <h3 align="center" style="margin-top: 10px;">Eventi</h3>

    <div>
        <div class="content" style="float: left; margin: 1em;">
            <a href="exportCSV.php?source=events">
                <button type="button" class="btn btn-success">Esporta Eventi</button>
            </a>
        </div>

        <div class="content" style="float: right; margin: 1em;">
            <a href="addEvent.php">
                <button type="button" class="btn btn-success">Aggiungi</button>
            </a>
            <a href="editEvents.php">
                <button type="button" class="btn btn-warning" onclick="">Modifica</button>
            </a>
            <a href="deleteEvent.php">
                <button type="button" class="btn btn-danger" onclick="">Cancella</button>
            </a>
        </div>
    </div>

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

</div>

</body>
</html>