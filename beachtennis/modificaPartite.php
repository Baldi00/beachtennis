<?php
    if(!(isset($_GET) && isset($_GET["codEvento"]) && isset($_GET["under"]))){
        header("LOCATION: index.php");
    }
    
    $codEvento = $_GET["codEvento"];
    $under = $_GET["under"];

    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");

    if(isset($_GET["modified"])){
        $numRow = $_GET["numRow"];
        for($i=0; $i<$numRow; $i++) {
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

        //Score calculation
        $query = "SELECT * FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under;
        $result = $connessione->query($query);

        for($i=0; $i<$result->num_rows; $i++) {
            $lineCoppiaEvento = mysqli_fetch_assoc($result);

            $query = "SELECT * FROM coppie WHERE codCoppia = ".$lineCoppiaEvento["codCoppia"];
            $resultCoppia = $connessione->query($query);

            $line = mysqli_fetch_assoc($resultCoppia);

            $query = "SELECT * FROM partite WHERE codEvento = ".$codEvento." AND under = ".$under." AND (codCoppia1 = ".$line["codCoppia"]." OR codCoppia2 = ".$line["codCoppia"].")";
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

        header("LOCATION: partite.php?codEvento=".$codEvento."&under=".$under);
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
                    <?php echo '<a class="nav-link" href="partite.php?codEvento='.$codEvento.'&under='.$under.'">Annulla</a>';?>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Partite <?php echo $line["nomeEvento"]." Under ".$under;?></h3>

    <?php
        $query = "SELECT * FROM partite WHERE codEvento = ".$codEvento." AND under = ".$under." ORDER BY codPartita ASC";
        $resultPartite = $connessione->query($query);

        if($resultPartite->num_rows==0){
            header("LOCATION: partite.php?codEvento=".$codEvento."&under=".$under);
        } else {
            echo '<form action="modificaPartite.php">';
            echo '<table class="table table-striped">
                <thead>
                    <tr>
                    <th scope="col" style="text-align:center">Coppia 1</th>
                    <th scope="col" style="text-align:center" colspan=3>Punteggio</th>
                    <th scope="col" style="text-align:center; border-right: 1px solid #dee2e6;">Coppia 2</th>
                    <th scope="col" style="text-align:center;">Data</th>
                    <th scope="col" style="text-align:center; border-right: 1px solid #dee2e6;">Campo</th>
                    </tr>
                </thead>
                <tbody>';

            $numRow = $resultPartite->num_rows;
            echo "<input type='hidden' name='numRow' value='".$numRow."'>";
            for($i=0; $i<$numRow; $i++){
                $line = mysqli_fetch_assoc($resultPartite);
                echo "  <tr style='text-align: center'>";

                echo "<input type='hidden' name='codPartita".$i."' value='".$line["codPartita"]."'>";

                $query = "SELECT nome FROM coppie WHERE codCoppia = ".$line["codCoppia1"];
                $resultCoppia = $connessione->query($query);
                $coppia1 = mysqli_fetch_assoc($resultCoppia)["nome"];

                echo "<td><input placeholder='Coppia 1' class='form-control' type='text' style='width: 100%; text-align: center' value='".$coppia1."' disabled></td>";

                echo '<td><input placeholder="Punteggio 1" class="form-control" type="number" name="punt1'.$i.'" min=0 style="width: 100%; text-align: center" value="'.$line["punt1"].'"></td>';

                echo "<td>-</td>";

                echo '<td><input placeholder="Punteggio 2" class="form-control" type="number" name="punt2'.$i.'" min=0 style="width: 100%; text-align: center" value="'.$line["punt2"].'"></td>';

                $query = "SELECT nome FROM coppie WHERE codCoppia = ".$line["codCoppia2"];
                $resultCoppia = $connessione->query($query);
                $coppia2 = mysqli_fetch_assoc($resultCoppia)["nome"];

                echo "<td style='border-right: 1px solid #dee2e6;'><input placeholder='Coppia 1' class='form-control' type='text' style='width: 100%; text-align: center' value='".$coppia2."' disabled></td>";

                echo '<td><input placeholder="Data" class="form-control" type="text" name="data'.$i.'" style="width: 100%; text-align: center" value="'.$line["data"].'"></td>';

                echo '<td><input placeholder="Campo" class="form-control" type="text" name="campo'.$i.'" style="width: 100%; text-align: center" value="'.$line["campo"].'"></td>';


                echo '</tr>';
            }
            echo '</tbody></table>';
            echo "<input type='hidden' name='codEvento' value='".$codEvento."'>";
            echo "<input type='hidden' name='under' value='".$under."'>";
            echo "<input type='hidden' name='modified'>";

            echo '  <div class="row" style="margin: 10px;">
                        <div class="col-sm" colspan="2"><input class="btn btn-primary" type="submit" value="Salva" style="width: 100%"></div>
                    </div>';

            echo "</form>";
        }

    ?>

</body>
</html>