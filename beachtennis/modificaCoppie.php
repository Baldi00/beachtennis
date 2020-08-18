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

    <script type="text/javascript">
        function nomeCoppiaAutomatico(index) {
            var select1 = document.getElementsByName("part1"+index)[0];
            var part1 = select1.options[select1.selectedIndex].innerHTML;
            var cogn1 = part1.substring(part1.indexOf(" ")+1, part1.lastIndexOf("(")-1);

            var select2 = document.getElementsByName("part2"+index)[0];
            var part2 = select2.options[select2.selectedIndex].innerHTML;
            var cogn2 = part2.substring(part2.indexOf(" ")+1, part2.lastIndexOf("(")-1);

            document.getElementsByName("nome"+index)[0].value = cogn1 + "-" + cogn2;
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

    <h3 align="center" style="margin-top: 10px;">Modifica coppie</h3>

    <form action="azioneCoppia.php">
        
        <input type="hidden" name="azione" value="modifica">

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
                        $query = "SELECT nome FROM giocatori WHERE codGiocatore = ".$line["part1"];
                        $part1 = mysqli_fetch_assoc($connessione->query($query))["nome"];
                        $query = "SELECT nome FROM giocatori WHERE codGiocatore = ".$line["part2"];
                        $part2 = mysqli_fetch_assoc($connessione->query($query))["nome"];

                        

                        echo "  <tr style='text-align: center'>
                                    <input type='hidden' name='codCoppia".$i."' value='".$line["codCoppia"]."'>
                                    <th scope='row'><input placeholder='Nome' class='form-control' type='text' style='width: 100%; text-align: center' name='nome".$i."' value='".$line["nome"]."' required></th>";

                        echo '  <td><div class="form-group">
                                  <select required class="form-control" name="part1'.$i.'">
                                    <option value="">Partecipante 1</option>';

                        $resultGiocatori = $connessione->query("SELECT * FROM giocatori");

                        for ($j=0; $j < $resultGiocatori->num_rows; $j++) {
                            $lineGiocatori = mysqli_fetch_assoc($resultGiocatori); 
                            if($line["part1"] == $lineGiocatori["codGiocatore"]) {
                                echo "<option selected value=".$lineGiocatori["codGiocatore"].">".$lineGiocatori["nome"]." (".$lineGiocatori["annoNascita"].")</option>";
                            } else {
                                echo "<option value=".$lineGiocatori["codGiocatore"].">".$lineGiocatori["nome"]." (".$lineGiocatori["annoNascita"].")</option>";
                            }
                        }
                        echo '    </select>
                                </div></td> ';

                        echo '  <td><div class="form-group">
                                  <select required class="form-control" name="part2'.$i.'">
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

                        echo "  <td><input required placeholder='Under' class='form-control' type='number' min=0 style='width: 100%; text-align: center' name='under".$i."' value='".$line["under"]."'></td>";

                        echo "  <td><a style='cursor: pointer; color: #007bff' onclick='nomeCoppiaAutomatico(".$i.")'>Nome automatico</a></td>
                                </tr>";
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