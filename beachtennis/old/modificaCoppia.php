<?php
    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");
    
    $query = "SELECT * FROM Coppie WHERE codCoppia = ".$_GET["cod"];

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

    <h3 align="center" style="margin-top: 10px;">Modifica coppia</h3>


    <form action="azioneCoppia.php">
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col" style="text-align: center">Nome</th>
                <th scope="col" style="text-align: center">Partecipante 1</th>
                <th scope="col" style="text-align: center">Partecipante 2</th>
                <th scope="col" style="text-align: center">Under</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope='row'><div class="col-sm" width="50%"></div><div class="col-sm" width="50%">
                        
                        <?php
                            echo '<input placeholder="Nome coppia" class="form-control" type="text" name="nome" required style="width: 100%" value="'.$line["nome"].'">'
                        ?>
                        
                    </div></th>
                    <td><div class="col-sm" width="50%">
                        <?php
                            echo '<input placeholder="Partecipante 1" class="form-control" type="text" name="part1" style="width: 100%" value="'.$line["part1"].'">'
                        ?>
                    </div></td>
                    <td><div class="col-sm" width="50%">
                        <?php
                            echo '<input placeholder="Partecipante 2" class="form-control" type="text" name="part2" style="width: 100%" value="'.$line["part2"].'">'
                        ?>
                    </div></td>
                    <td><div class="input-group mb-3"><select class="custom-select" id="inputGroupSelect02" name="under" required>
                        <?php
                            echo '<option value="">Under</option>';
                            echo '<option value="12"'; if($line["under"]==12) echo 'selected'; echo '>12</option>';
                            echo '<option value="14"'; if($line["under"]==14) echo 'selected'; echo '>14</option>';
                            echo '<option value="16"'; if($line["under"]==16) echo 'selected'; echo '>16</option>';
                        ?>
                    </select></div></td>
                    
                    <input type="hidden" name="azione" value="modifica">
                    <?php
                        echo '<input type="hidden" name="cod" value="'.$_GET["cod"].'">'
                    ?>
                </tr>
            </tbody>
        </table>

        <div class="row" style="margin: 10px;">
            <div class="col-sm" colspan="2"><input class="btn btn-primary" type="submit" value="Modifica" style="width: 100%"></div>
        </div>
    </form>

</body>
</html>