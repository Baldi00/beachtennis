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
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="index.php">Beach Tennis</a>
        <button class="navbar-toggler" type="button" date-toggle="collapse" date-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                    <a class="nav-link" href="addPlayer.php">Aggiungi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="editPlayer.php">Modifica</a>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Modifica iscritti</h3>

    <form action="actionPlayer.php">
        
        <input type="hidden" name="action" value="edit">

        <table class="table table-striped">
            <thead>
                <tr style="text-align: center;">
                <th scope="col">Nome</th>
                <th scope="col">Data di Nascita</th>
                <th scope="col">Numero di Telefono</th>
                <th scope="col">Iscritto</th>
                </tr>
            </thead>
            <tbody>
                
                <?php
                    $numRow = $result->num_rows;

                    if($numRow==0)
                        header("LOCATION: players.php");

                    for($i=0; $i<$numRow; $i++){
                        $line = mysqli_fetch_assoc($result);

                        echo "  <tr style='text-align: center'>
                                    <input type='hidden' name='playerID".$i."' value='".$line["playerID"]."'>
                                    <th scope='row'><input placeholder='Nome' class='form-control' type='text' style='width: 100%; text-align: center' name='name".$i."' value='".$line["name"]."' required></th>";

                        echo "  <td><input required placeholder='Data di Nascita' class='form-control' type='date' style='width: 100%; text-align: center' name='date".$i."' value='".$line["birthdayDate"]."'></td>";

                        echo "  <td><input placeholder='Numero di Telefono' class='form-control' type='text' style='width: 100%; text-align: center' name='number".$i."' value='".$line["phoneNumber"]."'></td>";

                        echo "  <td><input placeholder='Iscritto' class='form-control' type='text-align' style='width: 100%; text-align: center' name='subscribed".$i."' value='".$line["subscribed"]."'></td>
                                </tr>";
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