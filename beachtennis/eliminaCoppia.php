<?php
    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");
    
    $query = "SELECT * FROM Coppie";

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
            <ul class="navbar-nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="iscritti.php">Tutti gli Iscritti</a>
                </li>
            </ul>
            <ul class="navbar-nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="coppie.php">Tutte le Coppie</a>
                </li>
            </ul>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="eventi.php">Eventi</a>
                </li>
            </ul>
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item">
                    <a class="nav-link" href="inserisciCoppia.php">Aggiungi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="modificaCoppie.php">Modifica</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="eliminaCoppia.php">Cancella</a>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Cancella coppia</h3>
    <h6 align="center">Se la coppia non viene cancellata significa che è presente in un evento in corso, occorre eliminare prima tale evento</h6>

    <table class="table table-striped">
        <thead>
            <tr style="text-align: center;">
            <th scope="col">Nome</th>
            <th scope="col">Partecipante 1</th>
            <th scope="col">Partecipante 2</th>
            <th scope="col">Under</th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            
            <?php
                $numRow = $result->num_rows;

                if($numRow==0)
                    header("LOCATION: coppie.php");
                
                for($i=0; $i<$numRow; $i++){
                    $line = mysqli_fetch_assoc($result);

                    $query = "SELECT * FROM giocatori WHERE codGiocatore = ".$line["part1"];
                    $lineGiocatore = mysqli_fetch_assoc($connessione->query($query));
                    $part1 = $lineGiocatore["nome"];
                    $anno1 = $lineGiocatore["dataNascita"];

                    $query = "SELECT * FROM giocatori WHERE codGiocatore = ".$line["part2"];
                    $lineGiocatore = mysqli_fetch_assoc($connessione->query($query));
                    $part2 = $lineGiocatore["nome"];
                    $anno2 = $lineGiocatore["dataNascita"];

                    echo "  <tr style='text-align: center'>
                                <th scope='row'>".$line["nome"]."</th>
                                <td>".$part1." (".$anno1.")</td>
                                <td>".$part2." (".$anno2.")</td>
                                <td>".$line["under"]."</td>
                                <td><a href='azioneCoppia.php?azione=elimina&cod=".$line["codCoppia"]."'>Cancella</a></td>
                            </tr>";
                }
            ?>
        </tbody>
    </table>

</body>
</html>