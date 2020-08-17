<?php
    if(!(isset($_GET) && isset($_GET["codEvento"]) && isset($_GET["under"])))
            header("LOCATION: index.php");

    $codEvento = $_GET["codEvento"];
    $under = $_GET["under"];

    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");

    if(isset($_GET["modified"])){
        if($_GET["punt1"]=="")
            $punt1 = 'NULL';
        else
            $punt1 = "'".$_GET["punt1"]."'";

        if($_GET["punt2"]=="")
            $punt2 = 'NULL';
        else
            $punt2 = "'".$_GET["punt2"]."'";

        $query = "UPDATE `partite` SET `data` = '".$_GET["data"]."', `campo` = '".$_GET["campo"]."', `punt1` = ".$punt1.", `punt2` = ".$punt2." WHERE codPartita = ".$_GET["codPartita"]."";
        $result = $connessione->query($query);
        echo $result;
        header("LOCATION: partite.php?codEvento=".$codEvento."&under=".$under);
    }
    
    $query = "SELECT * FROM Partite WHERE codPartita = ".$_GET["codPartita"];

    $result = $connessione->query($query);
    $line = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.5.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

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
                    <?php echo '<a class="nav-link" href="coppiePerEvento.php?codEvento='.$codEvento.'&under='.$under.'">Coppie</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="gironi.php?codEvento='.$codEvento.'&under='.$under.'">Gironi</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="partite.php?codEvento='.$codEvento.'&under='.$under.'">Partite</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="tabellone.php?codEvento='.$codEvento.'&under='.$under.'">Tabellone</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="selezionaUnder.php?codEvento='.$codEvento.'">Cambia Under</a>';?>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="eventi.php">Cambia Evento</a>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Modifica partita</h3>

    <form action="modificaPartita.php">
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col" style="text-align:center">Coppia 1</th>
                <th scope="col" style="text-align:center">Punteggio 1</th>
                <th scope="col" style="text-align:center">Punteggio 2</th>
                <th scope="col" style="text-align:center">Coppia 2</th>
                <th scope="col" style="text-align:center">Data</th>
                <th scope="col" style="text-align:center">Campo</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><div class="col-sm" width="50%">
                        <?php
                            $query = "SELECT nome FROM coppie WHERE codCoppia = ".$line["codCoppia1"];
                            $resultCoppia = $connessione->query($query);
                            $coppia1 = mysqli_fetch_assoc($resultCoppia)["nome"];

                            echo '<input placeholder="Coppia 1" class="form-control" type="text" style="width: 100%" value="'.$coppia1.'" disabled>';
                        ?>
                    </div></td>
                    <td><div class="col-sm" width="50%">
                        <?php
                            echo '<input placeholder="Punteggio 1" class="form-control" type="number" name="punt1" min=0 style="width: 100%" value="'.$line["punt1"].'">';
                        ?>
                    </div></td>
                    <td><div class="col-sm" width="50%">
                        <?php
                            echo '<input placeholder="Punteggio 2" class="form-control" type="number" name="punt2" min=0 style="width: 100%" value="'.$line["punt2"].'">';
                        ?>
                    </div></td>
                    <td><div class="col-sm" width="50%">
                        <?php
                            $query = "SELECT nome FROM coppie WHERE codCoppia = ".$line["codCoppia2"];
                            $resultCoppia = $connessione->query($query);
                            $coppia1 = mysqli_fetch_assoc($resultCoppia)["nome"];

                            echo '<input placeholder="Coppia 2" class="form-control" type="text" style="width: 100%" value="'.$coppia1.'" disabled>';
                        ?>
                    </div></td>
                    <th scope='row'><div class="col-sm" width="50%">
                        <?php
                            echo '<input placeholder="Data" class="form-control" type="text" name="data" style="width: 100%" value="'.$line["data"].'">';
                        ?>
                    </div></th>
                    <th scope='row'><div class="col-sm" width="50%">
                        <?php
                            echo '<input placeholder="Campo" class="form-control" type="text" name="campo" style="width: 100%" value="'.$line["campo"].'">';
                        ?>
                    </div></th>
                    <input type="hidden" name="modified">
                    <?php
                        echo '<input type="hidden" name="codEvento" value="'.$codEvento.'">';
                        echo '<input type="hidden" name="under" value="'.$under.'">';
                        echo '<input type="hidden" name="codPartita" value="'.$_GET["codPartita"].'">';
                    ?>
                </tr>
            </tbody>
        </table>

        <div class="row" style="margin: 10px;">
            <div class="col-sm" colspan="2"><input class="btn btn-primary" type="submit" value="Modifica" style="width: 100%"></div>
        </div>
    </form>

</body>
</html>