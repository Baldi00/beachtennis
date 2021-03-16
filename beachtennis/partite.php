<?php
    if(!(isset($_GET) && isset($_GET["codEvento"]) && isset($_GET["under"]))){
        header("LOCATION: index.php");
    }
    
    $codEvento = $_GET["codEvento"];
    $under = $_GET["under"];

    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");
    
    $query = "SELECT * FROM Eventi WHERE codEvento = ".$codEvento;

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
                    <?php echo '<a class="nav-link" href="esportaCsv.php?source=partite&codEvento='.$codEvento.'&under='.$under.'">Esporta partite</a>';?>
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
                    <?php echo '<a class="nav-link" href="modificaPartite.php?codEvento='.$codEvento.'&under='.$under.'">Modifica</a>';?>
                </li>
                <li class="nav-item">
                    <a style="cursor: pointer" class="nav-link" onclick="resetPartite();">Reset partite</a>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Partite <?php echo $line["nomeEvento"]." Under ".$under;?></h3>

    <?php
        $query = "SELECT * FROM partite WHERE codEvento = ".$codEvento." AND under = ".$under." ORDER BY codPartita ASC";
        $resultPartite = $connessione->query($query);

        if($resultPartite->num_rows==0){
            echo "<h4 style='margin: 20px; text-align: center;'>Le partite non sono ancora state create. Vai in \"Gironi\" per generarle</h4>";
            echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"gironi.php?codEvento=".$codEvento."&under=".$under."\";'>Gironi</button></div>";
        } else {
            echo'<table class="table table-striped">
                <thead>
                    <tr>
                    <th scope="col" style="text-align:center">Coppia 1</th>
                    <th scope="col" style="text-align:center" colspan=3>Punteggio</th>
                    <th scope="col" style="text-align:center; border-right: 1px solid #dee2e6;">Coppia 2</th>
                    <th scope="col" style="text-align:center;">Data</th>
                    <th scope="col" style="text-align:center; border-right: 1px solid #dee2e6;">Campo</th>
                    <th scope="col" style="text-align:center">Vincitore</th>
                    <th scope="col" style="text-align:center">Differenza</th>
                    </tr>
                </thead>
                <tbody>';

            $numRow = $resultPartite->num_rows;
            for($i=0; $i<$numRow; $i++){
                $line = mysqli_fetch_assoc($resultPartite);


                if($line["finale"]=="1")
                    echo "  <tr style='font-weight: bold'>";
                else
                    echo "  <tr>";

                $query = "SELECT nome FROM coppie WHERE codCoppia = ".$line["codCoppia1"];
                $resultCoppia = $connessione->query($query);
                $coppia1 = mysqli_fetch_assoc($resultCoppia)["nome"];

                echo "<td style='text-align: center'>".$coppia1."</td>";

                if($line["punt1"]=="")
                    echo "<td style='text-align: center'>Da definire</td>";
                else
                    echo "<td style='text-align: center'>".$line["punt1"]."</td>";

                echo "<td style='text-align: center'>-</td>";

                if($line["punt2"]=="")
                    echo "<td style='text-align: center'>Da definire</td>";
                else
                    echo "<td style='text-align: center'>".$line["punt2"]."</td>";

                $query = "SELECT nome FROM coppie WHERE codCoppia = ".$line["codCoppia2"];
                $resultCoppia = $connessione->query($query);
                $coppia2 = mysqli_fetch_assoc($resultCoppia)["nome"];

                echo "<td style='text-align: center; border-right: 1px solid #dee2e6;'>".$coppia2."</td>";

                if($line["data"]=="")
                    echo "<td style='text-align: center;'>Da definire</td>";
                else
                    echo "<td style='text-align: center;'>".$line["data"]."</td>";

                if($line["campo"]=="")
                    echo "<td style='text-align: center; border-right: 1px solid #dee2e6;'>Da definire</td>";
                else
                    echo "<td style='text-align: center; border-right: 1px solid #dee2e6;'>".$line["campo"]."</td>";

                if($line["punt1"]=="" || $line["punt2"]=="")
                    echo "<td style='text-align: center'>Da definire</td>";
                else{
                    if($line["punt1"] > $line["punt2"])
                        echo "<td style='text-align: center'><b style='color:#007bff'>".$coppia1."</b></td>";
                    else if($line["punt1"] < $line["punt2"])
                        echo "<td style='text-align: center'><b style='color:#007bff'>".$coppia2."</b></td>";
                    else
                        echo "<td style='text-align: center'><b style='color:#007bff'>Pareggio</b></td>";
                }
                
                if($line["punt1"]=="" || $line["punt2"]=="")
                    echo "<td style='text-align: center'>Da definire</td>";
                else
                    echo "<td style='text-align: center'><b style='color:#007bff'>".(abs($line["punt1"]-$line["punt2"]))."</b></td>";

                echo '</tr>';
            }
            echo '</tbody></table>';
        }

    ?>

    <script type="text/javascript">
        function resetPartite(){
            if (confirm("Sei sicuro di voler cancellare tutte le partite di questo evento?"))
                window.location.href = '<?php echo "eliminaGironiPartite.php?codEvento=".$codEvento."&under=".$under."&reset=partite&source=partite";?>';
        }
    </script>

</body>
</html>