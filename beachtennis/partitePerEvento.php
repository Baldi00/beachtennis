<?php
    if(!(isset($_GET) && isset($_GET["codEvento"]))){
        header("LOCATION: index.php");
    }
    
    $codEvento = $_GET["codEvento"];

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
                    <?php echo '<a class="nav-link" href="partitePerEvento.php?codEvento='.$codEvento.'">Tutte le partite</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="esportaCsv.php?source=partiteEvento&codEvento='.$codEvento.'">Esporta partite</a>';?>
                </li>
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="selezionaUnder.php?codEvento='.$codEvento.'">Seleziona Under</a>';?>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="eventi.php">Cambia Evento</a>
                </li>
            </ul>
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item">
                    <?php echo '<a class="nav-link" href="modificaPartitePerEvento.php?codEvento='.$codEvento.'">Modifica</a>';?>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Partite <?php echo $line["nomeEvento"];?></h3>

    <?php
        $query = "SELECT under FROM partite WHERE codEvento = ".$codEvento." GROUP BY under";
        $resultUnder = $connessione->query($query);

        $numUnder = $resultUnder->num_rows;

        $partitePerUnder = array();

        $ciSonoPartite = false;

        for ($i=0; $i < $numUnder; $i++) { 
            $query = "SELECT * FROM partite WHERE codEvento = ".$codEvento." AND under = ".mysqli_fetch_assoc($resultUnder)["under"]." ORDER BY codPartita ASC";

            $resultPartite = $connessione->query($query);

            if($resultPartite->num_rows != 0)
                $ciSonoPartite = true;

            array_push($partitePerUnder, $resultPartite);
        }

        if(!$ciSonoPartite){
            echo "<h4 style='margin: 20px; text-align: center;'>Le partite non sono ancora state create. Seleziona un Under e vai in \"Aggiungi Partite\" per generarle</h4>";
            echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"selezionaUnder.php?codEvento=".$codEvento."\";'>Seleziona Under</button></div>";
        } else {
            
            $maxRows = $partitePerUnder[0]->num_rows;
            for ($i=1; $i < $numUnder; $i++) { 
                if($partitePerUnder[$i]->num_rows > $maxRows)
                    $maxRows = $partitePerUnder[$i]->num_rows;
            }

            echo'<table class="table table-striped">
                    <thead>
                        <tr>
                        <th scope="col" style="text-align:center; border-right: 1px solid #dee2e6;">Under</th>
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

            for ($i=0; $i < $maxRows; $i++) { 
                for ($j=0; $j < $numUnder; $j++) { 
                    if($i < $partitePerUnder[$j]->num_rows){

                        $line = mysqli_fetch_assoc($partitePerUnder[$j]);

                        if($line["finale"]=="1")
                            echo "  <tr style='font-weight: bold'>";
                        else
                            echo "  <tr>";

                        echo "<td style='text-align: center; border-right: 1px solid #dee2e6;'>".$line["under"]."</td>";

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
                }
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