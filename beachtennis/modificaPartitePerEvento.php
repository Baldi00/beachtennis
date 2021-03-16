<?php
    if(!(isset($_GET) && isset($_GET["codEvento"]))){
        header("LOCATION: index.php");
    }
    
    $codEvento = $_GET["codEvento"];

    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");

    if(isset($_GET["modified"])){
        $numRow = $_GET["numRow"];
        for($i=0; $i<$numRow; $i++) {
            if(isset($_GET["punt1".$i])) {
                if($_GET["punt1".$i]=="")
                    $punt1 = 'NULL';
                else
                    $punt1 = "'".$_GET["punt1".$i]."'";

                if($_GET["punt2".$i]=="")
                    $punt2 = 'NULL';
                else
                    $punt2 = "'".$_GET["punt2".$i]."'";

                $query = "UPDATE `partite` SET `data` = '".$_GET["data".$i]."', `campo` = '".$_GET["campo".$i]."', `punt1` = ".$punt1.", `punt2` = ".$punt2." WHERE codPartita = ".$_GET["codPartita".$i];
                $result = $connessione->query($query);
            }
        }

        //TODO

        //Score calculation
        $query = "SELECT * FROM coppia_evento WHERE codEvento = ".$codEvento;
        $result = $connessione->query($query);

        for($i=0; $i<$result->num_rows; $i++) {
            $lineCoppiaEvento = mysqli_fetch_assoc($result);

            $query = "SELECT * FROM coppie WHERE codCoppia = ".$lineCoppiaEvento["codCoppia"];
            $resultCoppia = $connessione->query($query);

            $line = mysqli_fetch_assoc($resultCoppia);

            $query = "SELECT * FROM partite WHERE codEvento = ".$codEvento." AND (codCoppia1 = ".$line["codCoppia"]." OR codCoppia2 = ".$line["codCoppia"].")";
            $resultPartite = $connessione->query($query);

            $punteggio = 0;

            if($resultPartite && $resultPartite->num_rows != 0) {
                for ($j=0; $j < $resultPartite->num_rows; $j++) {
                    $line2 = mysqli_fetch_assoc($resultPartite);
                    if($line2["punt1"]!="" && $line2["punt2"]!=""){
                        if($line["codCoppia"]==$line2["codCoppia1"] && $line2["punt1"]>$line2["punt2"])
                            $punteggio += $line2["punt1"]-$line2["punt2"];
                        else if($line["codCoppia"]==$line2["codCoppia2"] && $line2["punt2"]>$line2["punt1"])
                            $punteggio += $line2["punt2"]-$line2["punt1"];
                        else if($line["codCoppia"]==$line2["codCoppia1"] && $line2["punt1"]<$line2["punt2"])
                            $punteggio += $line2["punt1"]-$line2["punt2"];
                        else if($line["codCoppia"]==$line2["codCoppia2"] && $line2["punt2"]<$line2["punt1"])
                            $punteggio += $line2["punt2"]-$line2["punt1"];
                    }
                }
            }

            $query = "UPDATE coppia_evento SET punt = ".$punteggio." WHERE codCoppia = ".$line["codCoppia"]." AND codEvento = ".$codEvento;
            $connessione->query($query);
        }

        header("LOCATION: partitePerEvento.php?codEvento=".$codEvento);
    }
    
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
                    <?php echo '<a class="nav-link" href="partitePerEvento.php?codEvento='.$codEvento.'">Tutte le Partite</a>';?>
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
                    <?php echo '<a class="nav-link" href="partitePerEvento.php?codEvento='.$codEvento.'">Annulla</a>';?>
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

        for ($i=0; $i < $numUnder; $i++) { 
            $query = "SELECT * FROM partite WHERE codEvento = ".$codEvento." AND under = ".mysqli_fetch_assoc($resultUnder)["under"]." ORDER BY codPartita ASC";
            array_push($partitePerUnder, $connessione->query($query));
        }

        $maxRows = $partitePerUnder[0]->num_rows;
        for ($i=1; $i < $numUnder; $i++) { 
            if($partitePerUnder[$i]->num_rows > $maxRows)
                $maxRows = $partitePerUnder[$i]->num_rows;
        }

        echo '<form action="modificaPartitePerEvento.php">';
        echo'<table class="table table-striped">
                <thead>
                    <tr>
                    <th scope="col" style="text-align:center; border-right: 1px solid #dee2e6;">Under</th>
                    <th scope="col" style="text-align:center">Coppia 1</th>
                    <th scope="col" style="text-align:center" colspan=3>Punteggio</th>
                    <th scope="col" style="text-align:center; border-right: 1px solid #dee2e6;">Coppia 2</th>
                    <th scope="col" style="text-align:center;">Data</th>
                    <th scope="col" style="text-align:center; border-right: 1px solid #dee2e6;">Campo</th>
                    </tr>
                </thead>
                <tbody>';


        $numRow = 0;
        for ($i=0; $i < $numUnder; $i++) { 
            $numRow += $partitePerUnder[$i]->num_rows;
        }

        echo "<input type='hidden' name='numRow' value='".$numRow."'>";

        $numRiga = 0;
        for ($i=0; $i < $maxRows; $i++) { 
            for ($j=0; $j < $numUnder; $j++) { 
                if($i < $partitePerUnder[$j]->num_rows){

                    $line = mysqli_fetch_assoc($partitePerUnder[$j]);

                    echo "<input type='hidden' name='codPartita".$numRiga."' value='".$line["codPartita"]."'>";

                    echo "<td style='text-align: center; border-right: 1px solid #dee2e6;'><input placeholder='Coppia 1' class='form-control' type='text' style='width: 100%; text-align: center' value='".$line["under"]."' disabled></td>";

                    $query = "SELECT nome FROM coppie WHERE codCoppia = ".$line["codCoppia1"];
                    $resultCoppia = $connessione->query($query);
                    $coppia1 = mysqli_fetch_assoc($resultCoppia)["nome"];

                    echo "<td><input placeholder='Coppia 1' class='form-control' type='text' style='width: 100%; text-align: center' value='".$coppia1."' disabled></td>";

                    echo '<td><input placeholder="Punteggio 1" class="form-control" type="number" name="punt1'.$numRiga.'" min=0 style="width: 100%; text-align: center" value="'.$line["punt1"].'"></td>';

                    echo "<td>-</td>";

                    echo '<td><input placeholder="Punteggio 2" class="form-control" type="number" name="punt2'.$numRiga.'" min=0 style="width: 100%; text-align: center" value="'.$line["punt2"].'"></td>';

                    $query = "SELECT nome FROM coppie WHERE codCoppia = ".$line["codCoppia2"];
                    $resultCoppia = $connessione->query($query);
                    $coppia2 = mysqli_fetch_assoc($resultCoppia)["nome"];

                    echo "<td style='border-right: 1px solid #dee2e6;'><input placeholder='Coppia 1' class='form-control' type='text' style='width: 100%; text-align: center' value='".$coppia2."' disabled></td>";

                    echo '<td><input placeholder="Data" class="form-control" type="text" name="data'.$numRiga.'" style="width: 100%; text-align: center" value="'.$line["data"].'"></td>';

                    echo '<td><input placeholder="Campo" class="form-control" type="text" name="campo'.$numRiga.'" style="width: 100%; text-align: center" value="'.$line["campo"].'"></td>';


                    echo '</tr>';

                    $numRiga++;
                }
            }
        }

        echo '</tbody></table>';
        echo "<input type='hidden' name='codEvento' value='".$codEvento."'>";
        echo "<input type='hidden' name='modified'>";

        echo '  <div class="row" style="margin: 10px;">
                    <div class="col-sm" colspan="2"><input class="btn btn-primary" type="submit" value="Salva" style="width: 100%"></div>
                </div>';

        echo "</form>";

    ?>

</body>
</html>