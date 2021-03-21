<?php
    include 'modules/db_connection.php';
    include "templates/hotkeys.php";

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
            <?php exportButton("exportCSV.php?source=events"); ?>
        </div>

        <div class="content" style="float: right; margin: 1em;">
            <?php
            addButton("addEvent.php");
            editButton("editEvents.php");
            deleteButton("deleteEvent.php");
            ?>
        </div>
    </div>

    <?php
    $numRows = $result->num_rows;

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

    if ($numRows == 0) {
        echo "<tr><td colspan='4' style='text-align: center'>Nessun risultato</td></tr>";
    }

    for($i=0; $i<$numRows; $i++){
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

    ?>

</div>

</body>
</html>