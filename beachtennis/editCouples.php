<?php
    include 'modules/db_connection.php';

    $connection = openConnection();
    
    $query = "SELECT * FROM couples";

    $result = $connection->query($query);
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
        function automaticCoupleName(index) {
            var select1 = document.getElementsByName("part1"+index)[0];
            var part1 = select1.options[select1.selectedIndex].innerHTML;
            var last1 = part1.substring(part1.indexOf(" ")+1, part1.lastIndexOf("(")-1);

            var select2 = document.getElementsByName("part2"+index)[0];
            var part2 = select2.options[select2.selectedIndex].innerHTML;
            var last2 = part2.substring(part2.indexOf(" ")+1, part2.lastIndexOf("(")-1);

            document.getElementsByName("name"+index)[0].value = last1 + "-" + last2;
        }

        function automaticUnder(index) {

            var select1 = document.getElementsByName("part1"+index)[0];
            var part1 = select1.options[select1.selectedIndex].innerHTML;
            var date1 = part1.substring(part1.lastIndexOf("(")+1, part1.lastIndexOf(")"));

            var select2 = document.getElementsByName("part2"+index)[0];
            var part2 = select2.options[select2.selectedIndex].innerHTML;
            var date2 = part2.substring(part2.lastIndexOf("(")+1, part2.lastIndexOf(")"));

            var under = document.getElementsByName("under"+index)[0];

            if(date1<date2) {
                var birthday = new Date(date1)
            } else {
                var birthday = new Date(date2)
            }

            var ageDifMs = Date.now() - birthday.getTime();
            var ageDate = new Date(ageDifMs); // miliseconds from epoch
            var age = Math.abs(ageDate.getUTCFullYear() - 1970);

            under.value = age;

            checkUnders();
        }

        function automaticAllUnders() {
            var rows = document.getElementsByTagName('tr');
            for (var i = 0; i < rows.length-1; i++) {
                automaticUnder(i);
            }
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

    <h3 align="center" style="margin-top: 10px;">Modifica coppie</h3>

    <form action="actionCouple.php">
        
        <input type="hidden" name="action" value="edit">

        <table class="table table-striped">
            <thead>
                <tr style="text-align: center;">
                <th scope="col">Nome</th>
                <th scope="col">Partecipante 1</th>
                <th scope="col">Partecipante 2</th>
                <th scope="col">Under</th>
                <th scope="col" colspan="2"><button class="btn btn-primary" style="width: 100%" onclick="automaticAllUnders(); return false;">Under auto tutti</button></th>
                </tr>
            </thead>
            <tbody>
                
                <?php
                    $numRow = $result->num_rows;

                    if($numRow==0)
                        header("LOCATION: couples.php");

                    for($i=0; $i<$numRow; $i++){
                        $line = mysqli_fetch_assoc($result);
                        $query = "SELECT name FROM players WHERE playerID = ".$line["part1"];
                        $part1 = mysqli_fetch_assoc($connection->query($query))["name"];
                        $query = "SELECT name FROM players WHERE playerID = ".$line["part2"];
                        $part2 = mysqli_fetch_assoc($connection->query($query))["name"];

                        

                        echo "  <tr style='text-align: center'>
                                    <input type='hidden' name='coupleID".$i."' value='".$line["coupleID"]."'>
                                    <th scope='row'><input placeholder='Nome' class='form-control' type='text' style='width: 100%; text-align: center' name='name".$i."' value='".$line["name"]."' required></th>";

                        echo '  <td><div class="form-group">
                                  <select required class="form-control" name="part1'.$i.'">
                                    <option value="">Partecipante 1</option>';

                        $resultPlayers = $connection->query("SELECT * FROM players");

                        for ($j=0; $j < $resultPlayers->num_rows; $j++) {
                            $linePlayers = mysqli_fetch_assoc($resultPlayers);
                            if($line["part1"] == $linePlayers["playerID"]) {
                                echo "<option selected value=".$linePlayers["playerID"].">".$linePlayers["name"]." (".$linePlayers["birthdayDate"].")</option>";
                            } else {
                                echo "<option value=".$linePlayers["playerID"].">".$linePlayers["name"]." (".$linePlayers["birthdayDate"].")</option>";
                            }
                        }
                        echo '    </select>
                                </div></td> ';

                        echo '  <td><div class="form-group">
                                  <select required class="form-control" name="part2'.$i.'">
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

                        echo "  <td><input required placeholder='Under' class='form-control' type='number' min=0 style='width: 100%; text-align: center' onchange='checkUnders(); return false;' name='under".$i."' value='".$line["under"]."'></td>";

                        echo "  <td style='width: 8%'><button style='width: 100%' class='btn btn-primary' onclick='automaticCoupleName(" .$i."); return false;'>Nome auto</button></td>";

                        echo "  <td style='width: 8%'><button style='width: 100%' class='btn btn-primary' onclick='automaticUnder(" .$i."); return false;'>Under auto</button></td>
                                </tr>";
                    }
                ?>
            </tbody>
        </table>

        <div class="row" style="margin: 10px;">
            <div class="col-sm" colspan="2"><input class="btn btn-primary" type="submit" value="Salva" style="width: 100%"></div>
        </div>

    </form>

    <script type="text/javascript">
        function checkUnders() {
            var rows = document.getElementsByTagName('tr');
            for (var i = 1; i < rows.length; i++) {
                var cols = rows[i].getElementsByTagName('td');
                var value1 = cols[0].getElementsByTagName('select')[0].options[cols[0].getElementsByTagName('select')[0].selectedIndex].text;
                var value2 = cols[1].getElementsByTagName('select')[0].options[cols[1].getElementsByTagName('select')[0].selectedIndex].text;
                var under = cols[2].getElementsByTagName('input')[0].value;

                var date1 = value1.substring(value1.lastIndexOf("(")+1,value1.lastIndexOf(")"));
                var date2 = value2.substring(value2.lastIndexOf("(")+1,value2.lastIndexOf(")"));
                
                if(date1<date2) {
                    var birthday = new Date(date1)
                } else {
                    var birthday = new Date(date2)
                }

                var ageDifMs = Date.now() - birthday.getTime();
                var ageDate = new Date(ageDifMs); // miliseconds from epoch
                var age = Math.abs(ageDate.getUTCFullYear() - 1970);

                if(age>under)
                    rows[i].style = "text-align: center; background-color: #ffcccc;";
                else
                    rows[i].style = "text-align: center;";
            }
        }

        checkUnders();
    </script>

</body>
</html>