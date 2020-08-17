<?php
    if(!(isset($_GET) && isset($_GET["codEvento"]) && isset($_GET["under"])))
        header("LOCATION: index.php");
    
    $codEvento = $_GET["codEvento"];
    $under = $_GET["under"];

    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");
    
    $nomeEvento = mysqli_fetch_assoc($connessione->query("SELECT * FROM Eventi WHERE codEvento = ".$codEvento))["nomeEvento"];

    $query = "SELECT * FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under." ORDER BY punt DESC";
    $result = $connessione->query($query);

    if($result->num_rows==0)
        header("LOCATION: selezionaCoppiePerEvento.php?codEvento=".$codEvento."&under=".$under);

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
                    <?php echo '<a class="nav-link" href="esportaCsv.php?source=coppieUnder&codEvento='.$codEvento.'&under='.$under.'">Esporta coppie</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="partitePerEvento.php?codEvento='.$codEvento.'">Tutte le Partite</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="selezionaUnder.php?codEvento='.$codEvento.'">Cambia Under</a>';?>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="eventi.php">Cambia Evento</a>
                </li>
            </ul>
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="selezionaCoppiePerEvento.php?codEvento='.$codEvento.'&under='.$under.'">Modifica</a>';?>
                </li>
            </ul>
        </div>
    </nav>
    
    <h3 align="center" style="margin-top: 10px;">Coppie <?php echo $nomeEvento." Under ".$under;?></h3>

    <table class="table table-striped">
        <thead>
            <tr style="text-align: center;">
            <th scope="col">Nome</th>
            <th scope="col">Partecipante 1</th>
            <th scope="col">Partecipante 2</th>
            <th scope="col">Punteggio</th>
            </tr>
        </thead>
        <tbody>
            
            <?php
                $numRow = $result->num_rows;
                for($i=0; $i<$numRow; $i++){

                    $lineCoppiaEvento = mysqli_fetch_assoc($result);

                    $query = "SELECT * FROM coppie WHERE codCoppia = ".$lineCoppiaEvento["codCoppia"];
                    $resultCoppia = $connessione->query($query);

                    $line = mysqli_fetch_assoc($resultCoppia);
                    $query = "SELECT nome FROM giocatori WHERE codGiocatore = ".$line["part1"];
                    $part1 = mysqli_fetch_assoc($connessione->query($query))["nome"];
                    $query = "SELECT nome FROM giocatori WHERE codGiocatore = ".$line["part2"];
                    $part2 = mysqli_fetch_assoc($connessione->query($query))["nome"];

                    echo "  <tr style='text-align: center;'>
                                <th scope='row'>".$line["nome"]."</th>
                                <td>".$part1."</td>
                                <td>".$part2."</td>";

                    $punteggio = $lineCoppiaEvento["punt"];

                    if($punteggio!=0)
                        echo "<td><b style='color:#007bff'>".$punteggio."</b></td>";
                    else
                        echo "<td>".$punteggio."</td>";

                    echo "  </tr>";
                }
            ?>
        </tbody>
    </table>

</body>
</html>