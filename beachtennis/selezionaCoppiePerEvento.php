<?php
    if(!(isset($_GET) && isset($_GET["codEvento"]) && isset($_GET["under"])))
        header("LOCATION: index.php");
    
    $codEvento = $_GET["codEvento"];
    $under = $_GET["under"];

    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");

    $query = "SELECT * FROM partite WHERE codEvento = ".$codEvento." AND under = ".$under." ORDER BY codPartita ASC";
    $resultPartite = $connessione->query($query);

    if($resultPartite->num_rows!=0){
        echo "<script>window.alert('Sono già presenti delle partite relative a questo evento. Non è più possibile modificare le coppie partecipanti'); window.location.href = \"coppiePerEvento.php?codEvento=".$codEvento."&under=".$under."\";</script>";
    }

    if(isset($_GET["modified"])){
        $numRow = $_GET["numRow"];
        for ($i=0; $i < $numRow; $i++) { 
            if(isset($_GET["customCheck".$i])){
                $query = "INSERT INTO `coppia_evento` (`codCoppia`, `codEvento`, `under`) VALUES ('".$_GET["codCoppia".$i]."','".$codEvento."','".$under."')";
                $result = $connessione->query($query);
            }else{
                $query = "DELETE FROM coppia_evento WHERE codCoppia = ".$_GET["codCoppia".$i]." AND codEvento = ".$codEvento;
                $result = $connessione->query($query);
            }
        }
        header("LOCATION: coppiePerEvento.php?codEvento=".$codEvento."&under=".$under);
    }
    
    $nomeEvento = mysqli_fetch_assoc($connessione->query("SELECT * FROM Eventi WHERE codEvento = ".$codEvento))["nomeEvento"];

    $query = "SELECT * FROM Coppie WHERE under = ".$under;
    $result = $connessione->query($query);

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
                    <?php echo '<a class="nav-link" href="partitePerEvento.php?codEvento='.$codEvento.'">Tutte le Partite</a>';?>
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
    
    <h3 align="center" style="margin-top: 10px;">Seleziona le coppie Under <?php echo $under;?> che parteciperanno a <?php echo $nomeEvento;?></h3>
    <form action="selezionaCoppiePerEvento.php">
        <table class="table table-striped">
            <thead>
                <tr style="text-align: center;">
                <th scope="col">Nome</th>
                <th scope="col">Partecipante 1</th>
                <th scope="col">Partecipante 2</th>
                <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                
                <?php
                    $numRow = $result->num_rows;

                    echo "<input type='hidden' name='numRow' value='".$numRow."'>";
                    echo "<input type='hidden' name='codEvento' value='".$codEvento."'>";
                    echo "<input type='hidden' name='under' value='".$under."'>";
                    echo "<input type='hidden' name='modified'>";

                    for($i=0; $i<$numRow; $i++){
                        $line = mysqli_fetch_assoc($result);

                        $query = "SELECT nome FROM giocatori WHERE codGiocatore = ".$line["part1"];
                        $part1 = mysqli_fetch_assoc($connessione->query($query))["nome"];
                        $query = "SELECT nome FROM giocatori WHERE codGiocatore = ".$line["part2"];
                        $part2 = mysqli_fetch_assoc($connessione->query($query))["nome"];


                        $query = "SELECT * FROM coppia_evento WHERE codEvento = '".$codEvento."' AND codCoppia = '".$line["codCoppia"]."' AND under = '".$under."'";
                        $result2 = $connessione->query($query);

                        echo "  <tr style='text-align: center;'>
                                    <th scope='row'>".$line["nome"]."</th>
                                    <td>".$part1."</td>
                                    <td>".$part2."</td>
                                    <td><div class='custom-control custom-checkbox'>
                                        <input type='hidden' name='codCoppia".$i."' value='".$line["codCoppia"]."'>";
                        if($result2->num_rows==0)
                            echo "          <input type='checkbox' class='custom-control-input' id='customCheck".$i."' name='customCheck".$i."'>";
                        else
                            echo "          <input type='checkbox' class='custom-control-input' id='customCheck".$i."' name='customCheck".$i."' checked>";


                        echo "          <label class='custom-control-label' for='customCheck".$i."'></label></div>
                                    </td>";
                        echo "  </tr>";
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