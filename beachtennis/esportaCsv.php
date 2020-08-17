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
    </nav>

    <?php
        $connessione = new mysqli("localhost","root","","beachtennis");

        if($connessione->connect_errno)
            die("<h1>Errore connessione al database</h1>");

        if(isset($_GET["source"])){
            if($_GET["source"]=="coppie"){
                $result = $connessione->query("SELECT * FROM `coppie`");

                $file = fopen("csv/coppie.csv", 'w');

                fwrite($file, "Nome;Partecipante 1;Partecipante 2;Under;\r\n");

                for ($i=0; $i < $result->num_rows; $i++) {
                    $line = mysqli_fetch_array($result); 

                    $query = "SELECT * FROM giocatori WHERE codGiocatore = ".$line["part1"];
                    $lineGiocatore = mysqli_fetch_assoc($connessione->query($query));
                    $part1 = $lineGiocatore["nome"];
                    $anno1 = $lineGiocatore["annoNascita"];

                    $query = "SELECT * FROM giocatori WHERE codGiocatore = ".$line["part2"];
                    $lineGiocatore = mysqli_fetch_assoc($connessione->query($query));
                    $part2 = $lineGiocatore["nome"];
                    $anno2 = $lineGiocatore["annoNascita"];

                    fwrite($file, $line["nome"].";".$part1." (".$anno1.");".$part2." (".$anno2.");".$line["under"].";\r\n");
                }

                fclose($file);

                echo "<h4 style='margin: 20px; text-align: center;'>Coppie esportate con successo</h4>";
                echo "<div width='100%' align='center'><button style='margin-right: 10px;' class='btn btn-primary' onclick='window.location.href = \"csv/coppie.csv\";'>Scarica</button>";
                echo "<button style='margin-left: 10px;' class='btn btn-primary' onclick='window.location.href = \"coppie.php\";'>Torna indietro</button></div>";

            } else if($_GET["source"]=="iscritti"){
                $result = $connessione->query("SELECT * FROM `giocatori`");

                $file = fopen("csv/iscritti.csv", 'w');

                fwrite($file, "Nome;Anno di Nascita;Numero di Telefono;Iscritto;\r\n");

                for ($i=0; $i < $result->num_rows; $i++) {
                    $line = mysqli_fetch_array($result); 
                    fwrite($file, $line["nome"].";".$line["annoNascita"].";".$line["numeroTelefono"].";".$line["iscritto"].";\r\n");
                }

                fclose($file);

                echo "<h4 style='margin: 20px; text-align: center;'>Iscritti esportati con successo</h4>";
                echo "<div width='100%' align='center'><button style='margin-right: 10px;' class='btn btn-primary' onclick='window.location.href = \"csv/iscritti.csv\";'>Scarica</button>";
                echo "<button style='margin-left: 10px;' class='btn btn-primary' onclick='window.location.href = \"iscritti.php\";'>Torna indietro</button></div>";

            } else if($_GET["source"]=="eventi"){

                $result = $connessione->query("SELECT * FROM `eventi`");

                $file = fopen("csv/eventi.csv", 'w');

                fwrite($file, "Nome Evento;Data Inizio;Data Fine;\r\n");

                for ($i=0; $i < $result->num_rows; $i++) {
                    $line = mysqli_fetch_array($result); 
                    fwrite($file, $line["nomeEvento"].";".$line["dataInizio"].";".$line["dataFine"].";\r\n");
                }

                fclose($file);

                echo "<h4 style='margin: 20px; text-align: center;'>Eventi esportati con successo</h4>";
                echo "<div width='100%' align='center'><button style='margin-right: 10px;' class='btn btn-primary' onclick='window.location.href = \"csv/eventi.csv\";'>Scarica</button>";
                echo "<button style='margin-left: 10px;' class='btn btn-primary' onclick='window.location.href = \"eventi.php\";'>Torna indietro</button></div>";

            } else if($_GET["source"]=="coppieUnder"){
                if(!(isset($_GET) && isset($_GET["codEvento"]) && isset($_GET["under"]))){
                    header("LOCATION: index.php");
                }
                
                $codEvento = $_GET["codEvento"];
                $under = $_GET["under"];

                $nomeEvento = mysqli_fetch_assoc($connessione->query("SELECT * FROM Eventi WHERE codEvento = ".$codEvento))["nomeEvento"];

                $file = fopen("csv/coppie ".$nomeEvento." Under ".$under.".csv", 'w');
                fwrite($file, "Nome;Partecipante 1;Partecipante 2;Punteggio;\r\n");

                $query = "SELECT * FROM coppia_evento WHERE codEvento = ".$codEvento." AND under = ".$under." ORDER BY punt DESC";
                $result = $connessione->query($query);

                $numRow = $result->num_rows;
                for($i=0; $i<$numRow; $i++){

                    $lineCoppiaEvento = mysqli_fetch_assoc($result);

                    $query = "SELECT * FROM coppie WHERE codCoppia = ".$lineCoppiaEvento["codCoppia"];
                    $resultCoppia = $connessione->query($query);

                    $line = mysqli_fetch_assoc($resultCoppia);

                    $query = "SELECT * FROM giocatori WHERE codGiocatore = ".$line["part1"];
                    $lineGiocatore = mysqli_fetch_assoc($connessione->query($query));
                    $part1 = $lineGiocatore["nome"];
                    $anno1 = $lineGiocatore["annoNascita"];

                    $query = "SELECT * FROM giocatori WHERE codGiocatore = ".$line["part2"];
                    $lineGiocatore = mysqli_fetch_assoc($connessione->query($query));
                    $part2 = $lineGiocatore["nome"];
                    $anno2 = $lineGiocatore["annoNascita"];

                    $punteggio = $lineCoppiaEvento["punt"];

                    fwrite($file, $line["nome"].";".$part1." (".$anno1.");".$part2." (".$anno2.");".$punteggio.";\r\n");

                }

                fclose($file);

                echo "<h4 style='margin: 20px; text-align: center;'>Coppie esportate con successo</h4>";
                echo "<div width='100%' align='center'><button style='margin-right: 10px;' class='btn btn-primary' onclick='window.location.href = \"csv/coppie ".$nomeEvento." Under ".$under.".csv\";'>Scarica</button>";
                echo "<button style='margin-left: 10px;' class='btn btn-primary' onclick='window.location.href = \"coppiePerEvento.php?codEvento=".$codEvento."&under=".$under."\";'>Torna indietro</button></div>";

            } else if($_GET["source"]=="partite"){
                if(!(isset($_GET) && isset($_GET["codEvento"]) && isset($_GET["under"]))){
                    header("LOCATION: index.php");
                }
                
                $codEvento = $_GET["codEvento"];
                $under = $_GET["under"];

                $nomeEvento = mysqli_fetch_assoc($connessione->query("SELECT * FROM Eventi WHERE codEvento = ".$codEvento))["nomeEvento"];

                $file = fopen("csv/partite ".$nomeEvento." Under ".$under.".csv", 'w');
                fwrite($file, "Coppia 1;Coppia 2;Punteggio Coppia 1;Punteggio Coppia 2;Data;Campo;Finale;Vincitore;Differenza\r\n");

                $query = "SELECT * FROM partite WHERE codEvento = ".$codEvento." AND under = ".$under." ORDER BY codPartita ASC";
                $resultPartite = $connessione->query($query);

                $numRow = $resultPartite->num_rows;
                for($i=0; $i<$numRow; $i++){
                    $line = mysqli_fetch_assoc($resultPartite);

                    $query = "SELECT nome FROM coppie WHERE codCoppia = ".$line["codCoppia1"];
                    $resultCoppia = $connessione->query($query);
                    $coppia1 = mysqli_fetch_assoc($resultCoppia)["nome"];

                    $query = "SELECT nome FROM coppie WHERE codCoppia = ".$line["codCoppia2"];
                    $resultCoppia = $connessione->query($query);
                    $coppia2 = mysqli_fetch_assoc($resultCoppia)["nome"];

                    if($line["punt1"] > $line["punt2"])
                        $vincitore = $coppia1;
                    else if($line["punt1"] < $line["punt2"])
                        $vincitore = $coppia2;
                    else
                        $vincitore = "Pareggio";
                    
                    $differenza = abs($line["punt1"]-$line["punt2"]);

                    fwrite($file, $coppia1.";".$coppia2.";".$line["punt1"].";".$line["punt2"].";".$line["data"].";".$line["campo"].";".$line["finale"].";".$vincitore.";".$differenza."\r\n");
                }

                fclose($file);

                echo "<h4 style='margin: 20px; text-align: center;'>Partite esportate con successo</h4>";
                echo "<div width='100%' align='center'><button style='margin-right: 10px;' class='btn btn-primary' onclick='window.location.href = \"csv/partite ".$nomeEvento." Under ".$under.".csv\";'>Scarica</button>";
                echo "<button style='margin-left: 10px;' class='btn btn-primary' onclick='window.location.href = \"partite.php?codEvento=".$codEvento."&under=".$under."\";'>Torna indietro</button></div>";

            } else if($_GET["source"]=="partiteEvento"){
                if(!(isset($_GET) && isset($_GET["codEvento"]))){
                    header("LOCATION: index.php");
                }
                
                $codEvento = $_GET["codEvento"];

                $nomeEvento = mysqli_fetch_assoc($connessione->query("SELECT * FROM Eventi WHERE codEvento = ".$codEvento))["nomeEvento"];

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
                    header("LOCATION: index.php");
                } else {
                    
                    $maxRows = $partitePerUnder[0]->num_rows;
                    for ($i=1; $i < $numUnder; $i++) { 
                        if($partitePerUnder[$i]->num_rows > $maxRows)
                            $maxRows = $partitePerUnder[$i]->num_rows;
                    }

                    $file = fopen("csv/partite ".$nomeEvento.".csv", 'w');
                    fwrite($file, "Under;Coppia 1;Coppia 2;Punteggio Coppia 1;Punteggio Coppia 2;Data;Campo;Finale;Vincitore;Differenza\r\n");

                    for ($i=0; $i < $maxRows; $i++) { 
                        for ($j=0; $j < $numUnder; $j++) { 
                            if($i < $partitePerUnder[$j]->num_rows){

                                $line = mysqli_fetch_assoc($partitePerUnder[$j]);

                                $query = "SELECT nome FROM coppie WHERE codCoppia = ".$line["codCoppia1"];
                                $resultCoppia = $connessione->query($query);
                                $coppia1 = mysqli_fetch_assoc($resultCoppia)["nome"];

                                $query = "SELECT nome FROM coppie WHERE codCoppia = ".$line["codCoppia2"];
                                $resultCoppia = $connessione->query($query);
                                $coppia2 = mysqli_fetch_assoc($resultCoppia)["nome"];

                                if($line["punt1"] > $line["punt2"])
                                    $vincitore = $coppia1;
                                else if($line["punt1"] < $line["punt2"])
                                    $vincitore = $coppia2;
                                else
                                    $vincitore = "Pareggio";
                                
                                $differenza = abs($line["punt1"]-$line["punt2"]);

                                fwrite($file, $line["under"].";".$coppia1.";".$coppia2.";".$line["punt1"].";".$line["punt2"].";".$line["data"].";".$line["campo"].";".$line["finale"].";".$vincitore.";".$differenza."\r\n");
                            }
                        }
                    }
                }

                fclose($file);

                echo "<h4 style='margin: 20px; text-align: center;'>Partite esportate con successo</h4>";
                echo "<div width='100%' align='center'><button style='margin-right: 10px;' class='btn btn-primary' onclick='window.location.href = \"csv/partite ".$nomeEvento.".csv\";'>Scarica</button>";
                echo "<button style='margin-left: 10px;' class='btn btn-primary' onclick='window.location.href = \"partitePerEvento.php?codEvento=".$codEvento."\";'>Torna indietro</button></div>";

            } else if($_GET["source"]=="gironiUnder"){

                if(!(isset($_GET) && isset($_GET["codEvento"]) && isset($_GET["under"]))){
                    header("LOCATION: index.php");
                }
                
                $codEvento = $_GET["codEvento"];
                $under = $_GET["under"];

                $nomeEvento = mysqli_fetch_assoc($connessione->query("SELECT * FROM Eventi WHERE codEvento = ".$codEvento))["nomeEvento"];

                $file = fopen("csv/gironi ".$nomeEvento." Under ".$under.".csv", 'w');
                fwrite($file, "Girone;Coppia;Posizione\r\n");

                $query = "SELECT * FROM coppia_girone WHERE codEvento = ".$codEvento." AND under = ".$under." AND numCoppie>2";
                $resultCoppiaGirone = $connessione->query($query);

                $numRow = $resultCoppiaGirone->num_rows;
                $lineCoppiaGirone = mysqli_fetch_assoc($resultCoppiaGirone);

                $numPerGirone = $lineCoppiaGirone["numCoppie"];
                $numGironi = $numRow / $numPerGirone;

                mysqli_data_seek($resultCoppiaGirone,0);

                for($i=0; $i<$numGironi; $i++){
                    for ($j=0; $j < $numPerGirone; $j++) {
                        $line = mysqli_fetch_assoc($resultCoppiaGirone);

                        $query = "SELECT * FROM coppie WHERE codCoppia = ".$line["codCoppia"];
                        $resultCoppia = $connessione->query($query);
                        $coppia = mysqli_fetch_assoc($resultCoppia)["nome"];

                        fwrite($file, ($i+1).";".$coppia.";".($line["pos"]+1).";\r\n");
                    }
                }

                fclose($file);

                echo "<h4 style='margin: 20px; text-align: center;'>Gironi esportati con successo</h4>";
                echo "<div width='100%' align='center'><button style='margin-right: 10px;' class='btn btn-primary' onclick='window.location.href = \"csv/gironi ".$nomeEvento." Under ".$under.".csv\";'>Scarica</button>";
                echo "<button style='margin-left: 10px;' class='btn btn-primary' onclick='window.location.href = \"gironi.php?codEvento=".$codEvento."&under=".$under."\";'>Torna indietro</button></div>";

            }
        }
    ?>

</body>
</html>

