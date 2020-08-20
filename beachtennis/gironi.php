<?php
    if(!(isset($_GET) && isset($_GET["codEvento"]) && isset($_GET["under"])))
        header("LOCATION: index.php");
    
    $codEvento = $_GET["codEvento"];
    $under = $_GET["under"];

    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");
    
    if(isset($_POST) && isset($_POST["numGironi"]) && isset($_POST["coppiePerGirone"])){
        $query = "INSERT INTO `gironi` (`codEvento`, `under`, `numCoppie`) VALUES ('".$codEvento."', '".$under."', '".$_POST["coppiePerGirone"]."')";

        for ($i=0; $i < $_POST["numGironi"]; $i++) {
            $connessione->query($query);
        }

        header("LOCATION: gironi.php?codEvento=".$codEvento."&under=".$under);
    }

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
                    <?php echo '<a class="nav-link" href="esportaCsv.php?source=gironiUnder&codEvento='.$codEvento.'&under='.$under.'">Esporta gironi</a>';?>
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
                    <a style="cursor: pointer" class="nav-link" onclick="resetPartite();">Reset partite</a>
                </li>
                <li class="nav-item">
                    <a style="cursor: pointer" class="nav-link" onclick="resetGironi();">Reset gironi</a>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Gironi <?php echo $lineEventi["nomeEvento"]." Under ".$under;?></h3>


    <?php
    
        $query = "SELECT * FROM Gironi WHERE codEvento = ".$codEvento." AND under = ".$under." AND numCoppie>2";
        $resultGironi = $connessione->query($query);

        //Check if gironi for this evento is empty

        if($resultGironi->num_rows == 0) {

            echo '<form action="gironi.php?codEvento='.$codEvento.'&under='.$under.'" method="post">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td><div class="col-sm" width="50%"></div><div class="col-sm" width="50%"><input placeholder="Numero di gironi" class="form-control" type="number" required name="numGironi" min=1 style="width: 100%"></div></td>
                            <td><div class="col-sm" width="50%"></div><div class="col-sm" width="50%"><input placeholder="Coppie per girone" class="form-control" type="number" min="3" max="5" name="coppiePerGirone" style="width: 100%"></div></td>
                        </tr>
                    </tbody>
                </table>

                <div class="row" style="margin: 10px;">
                    <div class="col-sm" colspan="2"><input class="btn btn-primary" type="submit" value="Crea gironi" style="width: 100%"></div>
                </div>
            </form>';
        } else {

            echo '
                <div class="row" style="margin-top: 30px; margin-bottom: 20px; text-align: center">
                    <div class="col-sm" colspan="2">
                        <button class="btn btn-primary" onclick="generaCasuale();" style="width: 30%;">Assegnazione casuale</button>
                    </div>
                </div>';

            //Get & set some preliminar infos
            $numCoppie = 0;
            $numPerGirone = 0;
            $numGironi = 0;

            $query = "SELECT * FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under;
            $resultTemp = $connessione->query($query);
            $numCoppie = $resultTemp->num_rows;

            $query = "SELECT * FROM Gironi WHERE codEvento = ".$codEvento." AND under = ".$under." AND numCoppie>2";
            $resultTemp = $connessione->query($query);
            $numGironi = $resultTemp->num_rows;
            $numPerGirone = mysqli_fetch_assoc($resultTemp)["numCoppie"];

            echo "<input type='hidden' id='numCoppie' value='".$numCoppie."'>";
            echo "<input type='hidden' id='numPerGirone' value='".$numPerGirone."'>";
            echo "<input type='hidden' id='numGironi' value='".$numGironi."'>";


            //Continue...
            $query = "SELECT * FROM coppia_girone WHERE codEvento = ".$codEvento." AND under = ".$under." AND numCoppie>2 ORDER BY codGirone,pos ASC";
            $resultCoppiaGirone = $connessione->query($query);


            //Check if gironi had already been set
            if($resultCoppiaGirone->num_rows == 0) {

                $query = "SELECT * FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under;
                $resultCoppiaEvento = $connessione->query($query);

                echo '  <div class="draggablecontainer" style="width: 40%; float: left; text-align: center">
                            <h3 style="margin-left: 10px;">Coppie</h3>';
                for ($i=0; $i < $resultCoppiaEvento->num_rows; $i++) {
                    $lineCoppiaEvento = mysqli_fetch_assoc($resultCoppiaEvento);
                    $query = "SELECT * FROM coppie WHERE codCoppia = ".$lineCoppiaEvento["codCoppia"];
                    $resultCoppia = $connessione->query($query);
                    $lineCoppia = mysqli_fetch_assoc($resultCoppia);

                    $query = "SELECT * FROM giocatori WHERE codGiocatore = ".$lineCoppia["part1"];
                    $resultPart1 = $connessione->query($query);
                    $linePart1 = mysqli_fetch_assoc($resultPart1);

                    $query = "SELECT * FROM giocatori WHERE codGiocatore = ".$lineCoppia["part2"];
                    $resultPart2 = $connessione->query($query);
                    $linePart2 = mysqli_fetch_assoc($resultPart2);

                    echo '<div draggable="true" id="'.$lineCoppia["codCoppia"].'" class="box">'.$lineCoppia["nome"].' ('.$linePart1["dataNascita"].' - '.$linePart2["dataNascita"].')</div>';
                }
                echo '  </div>
                        <div class="draggablecontainer" style="width: 60%; float: left; text-align: center">';

                for ($i=0; $i < $resultGironi->num_rows; $i++) {
                    echo '<h3 style="margin-left: 10px;">Girone '.($i+1).'</h3>';
                    for ($j=0; $j < $numPerGirone; $j++) {
                        echo '<div draggable="true" id="0" class="box"></div>';
                    }
                }
                echo '</div>';

            } else {

                $query = "SELECT * FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under;
                $resultCoppie = $connessione->query($query);

                echo '  <div class="draggablecontainer" style="width: 40%; float: left; text-align: center">
                            <h3 style="margin-left: 10px;">Coppie</h3>';

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

                        echo '<div draggable="true" id="'.$lineCoppia["codCoppia"].'" class="box">'.$lineCoppia["nome"].' ('.$linePart1["dataNascita"].' - '.$linePart2["dataNascita"].')</div>';
                    }
                }

                echo '  </div>
                        <div class="draggablecontainer" style="width: 60%; float: left; text-align: center">';
                
                mysqli_data_seek($resultCoppiaGirone, 0);
                for ($i=0; $i < $resultGironi->num_rows; $i++) {
                    echo '<h3 style="margin-left: 10px;">Girone '.($i+1).'</h3>';
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

                        echo '<div draggable="true" id="'.$lineCoppiaGirone["codCoppia"].'" class="box">'.$lineCoppia["nome"].' ('.$linePart1["dataNascita"].' - '.$linePart2["dataNascita"].')</div>';
                    }
                }
                echo '</div>';
            }

            echo '  <form action="generaPartite.php" onsubmit="return validate(this);" style="text-align: center; width: 100%; float: left;">
                        <input type="hidden" name="ids" id="params" value="">';
            echo '      <input type="hidden" name="codEvento" value="'.$codEvento.'">';
            echo '      <input type="hidden" name="under" value="'.$under.'">';
            echo '      <input type="hidden" name="source" value="gironi">';
            echo '      <div class="row" style="margin: 10px">
                            <div class="col-sm" colspan="2">
                                <input class="btn btn-primary" type="submit" value="Genera partite" style="width: 100%;">
                            </div>
                        </div>
                    </form>';
        }
    ?>

    <script type="text/javascript">
        function generaCasuale(){
            document.getElementById("params").value;
            var boxes = document.getElementsByClassName("box");

            var idCoppie = new Array();

            var numEmptyBox = 0;

            for(var i=0; i<boxes.length; i++)
                if(boxes[i].id == 0)
                    numEmptyBox++;
                else {
                    idCoppie.push(boxes[i].id);
                }

            if(numEmptyBox > idCoppie.length){
                window.alert("Non ci sono coppie sufficienti per creare i gironi");
                return;
            }
            
            var randomized = shuffle(idCoppie);

            var nomeCoppia = new Array();

            console.log(document.getElementsByClassName("box"));

            for(var i=0; i<numEmptyBox; i++) {
                var trovato = false;
                for(var j=0; j<boxes.length && !trovato; j++) {
                    if(boxes[j].id == randomized[i]) {
                        nomeCoppia.push(boxes[j].innerHTML);
                        document.getElementsByClassName("box")[j].innerHTML = "";
                        document.getElementsByClassName("box")[j].id = 0;
                        trovato = true;
                    }
                }
            }

            for(var i=0; i<numEmptyBox; i++) {
                document.getElementsByClassName("box")[document.getElementsByClassName("box").length-numEmptyBox+i].innerHTML = nomeCoppia[i];
                document.getElementsByClassName("box")[document.getElementsByClassName("box").length-numEmptyBox+i].id = randomized[i];
            }
        }

        function shuffle(array) {
            var currentIndex = array.length, temporaryValue, randomIndex;

            // While there remain elements to shuffle...
            while (0 !== currentIndex) {

                // Pick a remaining element...
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex -= 1;

                // And swap it with the current element.
                temporaryValue = array[currentIndex];
                array[currentIndex] = array[randomIndex];
                array[randomIndex] = temporaryValue;
            }

            return array;
        }

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