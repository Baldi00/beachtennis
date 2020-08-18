<?php
    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");
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

    <script type="text/javascript">
        function nomeCoppiaAutomatico() {
            var select1 = document.getElementsByName("part1")[0];
            var cogn1 = select1.options[select1.selectedIndex].innerHTML.split(" ")[0];

            var select2 = document.getElementsByName("part2")[0];
            var cogn2 = select2.options[select2.selectedIndex].innerHTML.split(" ")[0];

            document.getElementsByName("nome")[0].value = cogn1 + "-" + cogn2;
        }

    </script>

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

    <h3 align="center" style="margin-top: 10px;">Inserisci nuova coppia</h3>


    <form action="azioneCoppia.php">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th scope='row'>
                        <div class="col-sm" width="50%"><input placeholder="Nome coppia" class="form-control" type="text" name="nome" required style="width: 100%; text-align: center;"></div>
                    </th>
                    <?php
                        
                        echo '  <td><div class="form-group">
                                  <select required class="form-control" name="part1">
                                    <option value="" selected>Partecipante 1</option>';

                        $resultGiocatori = $connessione->query("SELECT * FROM giocatori");

                        for ($j=0; $j < $resultGiocatori->num_rows; $j++) {
                            $lineGiocatori = mysqli_fetch_assoc($resultGiocatori); 
                            echo "<option value=".$lineGiocatori["codGiocatore"].">".$lineGiocatori["nome"]." (".$lineGiocatori["annoNascita"].")</option>";
                        }
                        echo '    </select>
                                </div></td> ';

                        echo '  <td><div class="form-group">
                                  <select required class="form-control" name="part2">
                                    <option value="">Partecipante 2</option>';

                        mysqli_data_seek($resultGiocatori, 0);
                        for ($j=0; $j < $resultGiocatori->num_rows; $j++) {
                            $lineGiocatori = mysqli_fetch_assoc($resultGiocatori); 
                            if($line["part2"] == $lineGiocatori["codGiocatore"]) {
                                echo "<option selected value=".$lineGiocatori["codGiocatore"].">".$lineGiocatori["nome"]." (".$lineGiocatori["annoNascita"].")</option>";
                            } else {
                                echo "<option value=".$lineGiocatori["codGiocatore"].">".$lineGiocatori["nome"]." (".$lineGiocatori["annoNascita"].")</option>";
                            }
                        }
                        echo '    </select>
                                </div></td> ';
                    ?>
                    <td>
                        <input required placeholder="Under" class="form-control" type="number" name="under" min=0 style="width: 100%; text-align: center;">
                        </div>
                    </td>
                    <input type="hidden" name="azione" value="aggiungi">
                </tr>
            </tbody>
        </table>
        <div class="row" style="margin-top: 20px; margin-bottom: 20px; text-align: center">
            <div class="col-sm" colspan="2">
                <button class="btn btn-primary" onclick="nomeCoppiaAutomatico();" style="width: 30%;">Nome coppia automatico</button>
            </div>
        </div>

        <div class="row" style="margin: 10px;">
            <div class="col-sm" colspan="2"><input class="btn btn-primary" type="submit" value="Inserisci" style="width: 100%"></div>
        </div>
    </form>

</body>
</html>