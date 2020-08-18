<?php
    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");
    
    $query = "SELECT * FROM Giocatori ORDER BY nome";

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
                    <a class="nav-link" href="iscritti.php">Tutti gli Iscritti</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="coppie.php">Tutte le Coppie</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="eventi.php">Eventi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="esportaCsv.php?source=iscritti">Esporta iscritti</a>
                </li>
            </ul>
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item">
                    <a class="nav-link" href="inserisciIscritto.php">Aggiungi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="modificaIscritti.php">Modifica</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="eliminaIscritto.php">Cancella</a>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Tutti gli Iscritti</h3>

    <?php
        $numRow = $result->num_rows;

        if($numRow == 0){
            echo "<h4 style='margin: 20px; text-align: center;'>Nessuna iscritto presente. Vai in \"Aggiungi iscritti\" per crearne uno</h4>";
            echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"inserisciIscritto.php\";'>Aggiungi iscritto</button></div>";
        } else {
            echo '  <table class="table table-striped">
                        <thead>
                            <tr style="text-align: center;">
                            <th scope="col">Nome</th>
                            <th scope="col">Anno di Nascita</th>
                            <th scope="col">Numero di Telefono</th>
                            <th scope="col">Iscritto</th>
                            </tr>
                        </thead>
                        <tbody>';
            for($i=0; $i<$numRow; $i++){
                $line = mysqli_fetch_assoc($result);
                echo "  <tr style='text-align: center'>
                            <th scope='row'>".$line["nome"]."</th>
                            <td>".$line["annoNascita"]."</td>
                            <td>".$line["numeroTelefono"]."</td>
                            <td>".$line["iscritto"]."</td>
                        </tr>";
            }
            echo '      </tbody>
                    </table>';
        }
    ?>
</body>
</html>