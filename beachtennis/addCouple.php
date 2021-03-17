<?php
    include 'modules/db_connection.php';

    $connection = openConnection();
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

    <script type="text/javascript">
        function automaticCoupleName() {
            var select1 = document.getElementsByName("part1")[0];
            var part1 = select1.options[select1.selectedIndex].innerHTML;
            var last1 = part1.substring(part1.indexOf(" ")+1, part1.lastIndexOf("(")-1);

            var select2 = document.getElementsByName("part2")[0];
            var part2 = select2.options[select2.selectedIndex].innerHTML;
            var last2 = part2.substring(part2.indexOf(" ")+1, part2.lastIndexOf("(")-1);

            document.getElementsByName("name")[0].value = last1 + "-" + last2;
        }

        function automaticUnder() {

            var select1 = document.getElementsByName("part1")[0];
            var part1 = select1.options[select1.selectedIndex].innerHTML;
            var date1 = part1.substring(part1.lastIndexOf("(")+1, part1.lastIndexOf(")"));

            var select2 = document.getElementsByName("part2")[0];
            var part2 = select2.options[select2.selectedIndex].innerHTML;
            var date2 = part2.substring(part2.lastIndexOf("(")+1, part2.lastIndexOf(")"));

            var under = document.getElementsByName("under")[0];

            if(date1<date2) {
                var birthday = new Date(date1)
            } else {
                var birthday = new Date(date2)
            }

            var ageDifMs = Date.now() - birthday.getTime();
            var ageDate = new Date(ageDifMs); // miliseconds from epoch
            var age = Math.abs(ageDate.getUTCFullYear() - 1970);

            under.value = age;
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
                    <a class="nav-link" href="players.php">Tutti gli Iscritti</a>
                </li>
            </ul>
            <ul class="navbar-nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="couples.php">Tutte le Coppie</a>
                </li>
            </ul>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="events.php">Eventi</a>
                </li>
            </ul>
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item">
                    <a class="nav-link" href="addCouple.php">Aggiungi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="editCouples.php">Modifica</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="deleteCouple.php">Cancella</a>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Inserisci nuova coppia</h3>

    <form action="actionCouple.php">
        <table class="table table-striped">
            <thead>
                <tr style="text-align: center;">
                <th scope="col">Nome</th>
                <th scope="col">Partecipante 1</th>
                <th scope="col">Partecipante 2</th>
                <th scope="col">Under</th>
                <th scope="col"></th>
                <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope='row' style="width: 20%">
                        <div class="col-sm"><input placeholder="Nome coppia" class="form-control" type="text" name="name" required style="width: 100%; text-align: center;"></div>
                    </th>
                    <?php
                        
                        echo '  <td style="width: 20%"><div class="form-group">
                                  <select required class="form-control" name="part1">
                                    <option value="" selected>Partecipante 1</option>';

                        $resultPlayers = $connection->query("SELECT * FROM players ORDER BY name");

                        for ($j=0; $j < $resultPlayers->num_rows; $j++) {
                            $linePlayers = mysqli_fetch_assoc($resultPlayers);
                            echo "<option value=".$linePlayers["playerID"].">".$linePlayers["name"]." (".$linePlayers["birthdayDate"].")</option>";
                        }
                        echo '    </select>
                                </div></td> ';

                        echo '  <td style="width: 20%"><div class="form-group">
                                  <select required class="form-control" name="part2">
                                    <option value="">Partecipante 2</option>';

                        mysqli_data_seek($resultPlayers, 0);
                        for ($j=0; $j < $resultPlayers->num_rows; $j++) {
                            $linePlayers = mysqli_fetch_assoc($resultPlayers);
                            if($line["part2"] == $linePlayers["playerID"]) {
                                echo "<option selected value=".$linePlayers["playerID"].">".$linePlayers["name"]." (".$linePlayers["birthdayDate"].")</option>";
                            } else {
                                echo "<option value=".$linePlayers["playerID"].">".$linePlayers["name"]." (".$linePlayers["birthdayDate"].")</option>";
                            }
                        }
                        echo '    </select>
                                </div></td> ';
                    ?>
                    <td style="width: 20%">
                        <input required placeholder="Under" class="form-control" type="number" name="under" min=0 style="width: 100%; text-align: center;">
                        </div>
                    </td>
                    <input type="hidden" name="action" value="add">
                    <div class="row" style="margin-top: 20px; margin-bottom: 20px; text-align: center">
                        <div class="col-sm" colspan="2">
                            <td style="text-align: center; width: 8%"><button style="width: 100%" class="btn btn-primary" onclick="automaticCoupleName(); return false;">Nome auto</button></td>
                            <td style="text-align: center; width: 8%"><button style="width: 100%" class="btn btn-primary" onclick="automaticUnder(); return false;">Under auto</button></td>
                        </div>
                    </div>
                </tr>
            </tbody>
        </table>

        <div class="row" style="margin: 10px;">
            <div class="col-sm" colspan="2"><input class="btn btn-primary" type="submit" value="Inserisci" style="width: 100%"></div>
        </div>
    </form>

</body>
</html>