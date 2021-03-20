<?php
    include 'modules/db_connection.php';

    $connection = openConnection();
    
    $query = "SELECT * FROM players ORDER BY name";

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

    <h3 align="center" style="margin-top: 10px;">Iscritti</h3>

    <div>
        <div class="content" style="float: left; margin: 1em;">
            <a href="exportCSV.php?source=players">
                <button type="button" class="btn btn-success">Esporta Iscritti</button>
            </a>
        </div>

        <div class="content" style="float: right; margin: 1em;">
            <a href="addPlayer.php">
                <button type="button" class="btn btn-success">Aggiungi</button>
            </a>
            <a href="editPlayer.php">
                <button type="button" class="btn btn-light" onclick="">Modifica</button>
            </a>
        </div>
    </div>

    <?php
    $numRow = $result->num_rows;

    if($numRow == 0){
        echo "<h4 style='margin: 20px; text-align: center;'>Nessuna iscritto presente. Vai in \"Aggiungi iscritti\" per crearne uno</h4>";
        echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"addPlayer.php\";'>Aggiungi iscritto</button></div>";
    } else {
        echo '  <table class="table table-striped">
                        <thead>
                            <tr style="text-align: center;">
                            <th scope="col">Nome</th>
                            <th scope="col">Data di Nascita</th>
                            <th scope="col">Numero di Telefono</th>
                            <th scope="col">Iscritto</th>
                            </tr>
                        </thead>
                        <tbody>';
        for($i=0; $i<$numRow; $i++){
            $line = mysqli_fetch_assoc($result);
            echo "  <tr style='text-align: center'>
                            <th scope='row'>".$line["name"]."</th>
                            <td>".$line["birthdayDate"]."</td>
                            <td>".$line["phoneNumber"]."</td>
                            <td>".$line["subscribed"]."</td>";
            echo "<td>";
            echo "<a href='actionPlayer.php?action=delete&id=".$line["playerID"]."'>";
            include "templates/buttons/delete.html";
            echo "</a>";
            echo "</td></tr>";
        }
        echo '      </tbody>
                    </table>';
    }
    ?>

</div>

</body>
</html>