<?php
    if(!(isset($_GET) && isset($_GET["codEvento"]) && isset($_GET["under"])))
        header("LOCATION: index.php");
    
    $codEvento = $_GET["codEvento"];
    $under = $_GET["under"];

    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");

    $query = "SELECT * FROM Eventi WHERE codEvento = ".$codEvento;

    $resultEventi = $connessione->query($query);
    $lineEventi = mysqli_fetch_assoc($resultEventi);
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

    <script>
        function validate(form) {

            document.getElementById("params").value;
            var boxes = document.getElementsByClassName("box");

            document.getElementById("params").value = "";
            for (var i = 0; i < boxes.length; i++) {
              document.getElementById("params").value += boxes[i].id + ",";
            }

            var valid = true;

            var ids = document.getElementById("params").value.split(",");
            var numCoppie = parseInt(document.getElementById("numCoppie").value);
            var numPerGirone = parseInt(document.getElementById("numPerGirone").value);
            var numGironi = parseInt(document.getElementById("numGironi").value);

            for(var i=numCoppie; i < numCoppie+(numGironi*numPerGirone) && valid; i++)
                if(ids[i]==0)
                    valid = false;

            if(!valid) {
                alert("Completa i gironi prima di generare le partite");
                return false;
            }
            return true;
        }
    </script>

    <link rel="stylesheet" href="css/draggableboxes.css">
    <script src="js/draganddrop.js"></script>

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
                    <a style="cursor: pointer" class="nav-link" onclick="resetPartite();"></a>
                </li>
                <li class="nav-item">
                    <a style="cursor: pointer" class="nav-link" onclick="resetGironi();"></a>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Tabellone <?php echo $lineEventi["nomeEvento"]." Under ".$under;?></h3>


    <?php

        $query = "SELECT * FROM partite WHERE codEvento = ".$codEvento." AND under = ".$under." ORDER BY codPartita ASC";
        $resultPartite = $connessione->query($query);

        if($resultPartite->num_rows==0){
            echo "<h4 style='margin: 20px; text-align: center;'>Le partite non sono ancora state create. Vai in \"Gironi\" per generarle</h4>";
            echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"gironi.php?codEvento=".$codEvento."&under=".$under."\";'>Gironi</button></div>";
        } else {
        
            $query = "SELECT * FROM partite WHERE codEvento = ".$codEvento." AND under = ".$under." AND finale = 0 ORDER BY codPartita ASC";
            $resultPartite = $connessione->query($query);

            $completo = true;
            for ($i=0; $i < $resultPartite->num_rows && $completo; $i++) { 
                $line = mysqli_fetch_assoc($resultPartite);
                if($line["punt1"]=="" || $line["punt2"]=="")
                    $completo = false;

            }

            if(!$completo){
                echo "<h4 style='margin: 20px; text-align: center;'>Le partite non si sono ancora svolte tutte. Vai in \"Partite\" e completa i punteggi</h4>";
                echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"partite.php?codEvento=".$codEvento."&under=".$under."\";'>Partite</button></div>";
            } else {
                $query = "SELECT * FROM vincitori WHERE codEvento = ".$codEvento." AND under = ".$under;
                $resultVincitori = $connessione->query($query);

                $query = "SELECT * FROM Gironi WHERE codEvento = ".$codEvento." AND under = ".$under." AND numCoppie=2";
                $resultGironi = $connessione->query($query);

                if($resultVincitori->num_rows == 0) {
                    echo "<h4 style='margin: 20px; text-align: center;'>Le partite sono state completate</h4>";
                    echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"calcolaVincitori.php?codEvento=".$codEvento."&under=".$under."\";'>Calcola vincitori</button></div>";
                } else {
                    //Get & set some preliminar infos
                    $numCoppie = $resultVincitori->num_rows;
                    $numPerGirone = 2;
                    $numGironi = $resultVincitori->num_rows/2;

                    echo "<input type='hidden' id='numCoppie' value='".$numCoppie."'>";
                    echo "<input type='hidden' id='numPerGirone' value='".$numPerGirone."'>";
                    echo "<input type='hidden' id='numGironi' value='".$numGironi."'>";

                    //Continue...
                    $query = "SELECT * FROM coppia_girone WHERE codEvento = ".$codEvento." AND under = ".$under." AND numCoppie=2 ORDER BY codGirone,pos ASC";
                    $resultCoppiaGirone = $connessione->query($query);

                    $query = "SELECT * FROM coppia_girone WHERE codEvento = ".$codEvento." AND under = ".$under." AND numCoppie>2";
                    $resultTemp = $connessione->query($query);
                    $primoGirone = mysqli_fetch_assoc($resultTemp)["codGirone"];

                    //Check if gironi had already been set
                    if($resultCoppiaGirone->num_rows == 0) {

                        $query = "SELECT * FROM vincitori WHERE codEvento = ".$codEvento." AND under = ".$under;
                        $vincitori = $connessione->query($query);

                        echo '  <div class="draggablecontainer" style="width: 40%; float: left; text-align: center">
                                    <h3 style="margin-left: 10px;">Coppie vincitrici</h3>';
                        for ($i=0; $i < $vincitori->num_rows; $i++) {
                            $lineCoppiaEvento = mysqli_fetch_assoc($vincitori);
                            $query = "SELECT * FROM coppie WHERE codCoppia = ".$lineCoppiaEvento["codCoppia"];
                            $resultCoppia = $connessione->query($query);
                            $lineCoppia = mysqli_fetch_assoc($resultCoppia);

                            $query = "SELECT * FROM giocatori WHERE codGiocatore = ".$lineCoppia["part1"];
                            $resultPart1 = $connessione->query($query);
                            $linePart1 = mysqli_fetch_assoc($resultPart1);

                            $query = "SELECT * FROM giocatori WHERE codGiocatore = ".$lineCoppia["part2"];
                            $resultPart2 = $connessione->query($query);
                            $linePart2 = mysqli_fetch_assoc($resultPart2);

                            $query = "SELECT * FROM coppia_girone WHERE codEvento = ".$codEvento." AND under = ".$under." AND codCoppia = ".$lineCoppia["codCoppia"]." AND numCoppie>2";
                            $resultTemp = $connessione->query($query);
                            $codGirone = mysqli_fetch_assoc($resultTemp)["codGirone"];

                            echo '<div draggable="true" id="'.$lineCoppia["codCoppia"].'" class="box">'.$lineCoppia["nome"].' ('.$linePart1["annoNascita"].' - '.$linePart2["annoNascita"].') [G'.($codGirone-$primoGirone+1).']</div>';
                        }
                        echo '  </div>
                                <div class="draggablecontainer" style="width: 60%; float: left; text-align: center">';

                        for ($i=0; $i < $resultGironi->num_rows; $i++) {
                            echo '<h3 style="margin-left: 10px;">Finale '.($i+1).'</h3>';
                            for ($j=0; $j < $numPerGirone; $j++) {
                                echo '<div draggable="true" id="0" class="box"></div>';
                            }
                        }
                        echo '</div>';

                    } else {

                        $query = "SELECT * FROM vincitori WHERE codEvento = ".$codEvento." AND under = ".$under;
                        $resultCoppie = $connessione->query($query);

                        echo '  <div class="draggablecontainer" style="width: 40%; float: left; text-align: center">
                                    <h3 style="margin-left: 10px;">Coppie vincitori</h3>';

                        for ($i=0; $i < $resultCoppie->num_rows; $i++) {

                            $lineCoppia = mysqli_fetch_assoc($connessione->query("SELECT * FROM coppie WHERE codCoppia = ".mysqli_fetch_assoc($resultCoppie)["codCoppia"]));
                            mysqli_data_seek($resultCoppiaGirone, 0);
                            $used = false;

                            for($j=0; $j < $resultCoppiaGirone->num_rows && !$used; $j++){
                                $lineCoppiaGirone = mysqli_fetch_assoc($resultCoppiaGirone);
                                if($lineCoppia["codCoppia"]==$lineCoppiaGirone["codCoppia"])
                                    $used = true;
                            }

                            if($used) {
                                echo '<div draggable="true" id="0" class="box"></div>';
                            } else {

                                $query = "SELECT * FROM giocatori WHERE codGiocatore = ".$lineCoppia["part1"];
                                $resultPart1 = $connessione->query($query);
                                $linePart1 = mysqli_fetch_assoc($resultPart1);

                                $query = "SELECT * FROM giocatori WHERE codGiocatore = ".$lineCoppia["part2"];
                                $resultPart2 = $connessione->query($query);
                                $linePart2 = mysqli_fetch_assoc($resultPart2);

                                $query = "SELECT * FROM coppia_girone WHERE codEvento = ".$codEvento." AND under = ".$under." AND codCoppia = ".$lineCoppia["codCoppia"]." AND numCoppie>2";
                                $resultTemp = $connessione->query($query);
                                $codGirone = mysqli_fetch_assoc($resultTemp)["codGirone"];

                                echo '<div draggable="true" id="'.$lineCoppia["codCoppia"].'" class="box">'.$lineCoppia["nome"].' ('.$linePart1["annoNascita"].' - '.$linePart2["annoNascita"].') [G'.($codGirone-$primoGirone+1).']</div>';
                            }
                        }

                        echo '  </div>
                                <div class="draggablecontainer" style="width: 60%; float: left; text-align: center">';
                        
                        mysqli_data_seek($resultCoppiaGirone, 0);
                        for ($i=0; $i < $resultGironi->num_rows; $i++) {
                            echo '<h3 style="margin-left: 10px;">Finale '.($i+1).'</h3>';
                            for ($j=0; $j < $numPerGirone; $j++) {
                                $lineCoppiaGirone = mysqli_fetch_assoc($resultCoppiaGirone);
                                $query = "SELECT * FROM coppie WHERE codCoppia = ".$lineCoppiaGirone["codCoppia"];
                                $resultCoppia = $connessione->query($query);
                                $lineCoppia = mysqli_fetch_assoc($resultCoppia);

                                $query = "SELECT * FROM giocatori WHERE codGiocatore = ".$lineCoppia["part1"];
                                $resultPart1 = $connessione->query($query);
                                $linePart1 = mysqli_fetch_assoc($resultPart1);

                                $query = "SELECT * FROM giocatori WHERE codGiocatore = ".$lineCoppia["part2"];
                                $resultPart2 = $connessione->query($query);
                                $linePart2 = mysqli_fetch_assoc($resultPart2);

                                $query = "SELECT * FROM coppia_girone WHERE codEvento = ".$codEvento." AND under = ".$under." AND codCoppia = ".$lineCoppia["codCoppia"]." AND numCoppie>2";
                                $resultTemp = $connessione->query($query);
                                $codGirone = mysqli_fetch_assoc($resultTemp)["codGirone"];
                                
                                echo '<div draggable="true" id="'.$lineCoppiaGirone["codCoppia"].'" class="box">'.$lineCoppia["nome"].' ('.$linePart1["annoNascita"].' - '.$linePart2["annoNascita"].') [G'.($codGirone-$primoGirone+1).']</div>';
                            }
                        }
                        echo '</div>';
                    }
                    echo '  <form action="generaPartite.php" onsubmit="return validate(this);" style="text-align: center; width: 100%; float: left;">
                                <input type="hidden" name="ids" id="params" value="">';
                    echo '      <input type="hidden" name="codEvento" value="'.$codEvento.'">';
                    echo '      <input type="hidden" name="under" value="'.$under.'">';
                    echo '      <input type="hidden" name="source" value="tabellone">';
                    echo '      <div class="row" style="margin: 10px">
                                    <div class="col-sm" colspan="2">
                                        <input class="btn btn-primary" type="submit" value="Genera partite" style="width: 100%;">
                                    </div>
                                </div>
                            </form>';
                }
            }
        }
    ?>

    <script type="text/javascript">
        function resetGironi(){
            if (confirm("Sei sicuro di voler cancellare tutti i gironi di questo evento?"))
                window.location.href = '<?php echo "eliminaGironiPartite.php?codEvento=".$codEvento."&under=".$under."&reset=gironi&source=gironi";?>';
        }

        function resetPartite(){
            if (confirm("Sei sicuro di voler cancellare tutte le partite di questo evento?"))
                window.location.href = '<?php echo "eliminaGironiPartite.php?codEvento=".$codEvento."&under=".$under."&reset=partite&source=gironi";?>';
        }
    </script>

</body>
</html>