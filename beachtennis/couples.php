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
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="public/unknown/js/jquery-3.5.1.slim.min.js"></script>
    <script src="public/unknown/js/popper.min.js"></script>
    <script src="public/bootstrap/js/bootstrap.min.js"></script>

    <title>Beach Tennis</title>
</head>
<body>

<?php require 'templates/navbar.php' ?>

<div class="container">

    <h3 align="center" style="margin-top: 10px;">Coppie</h3>

    <div>
        <div class="content" style="float: left; margin: 1em;">
            <a href="exportCSV.php?source=couples">
                <button type="button" class="btn btn-success">Esporta Coppie</button>
            </a>
        </div>

        <div class="content" style="float: right; margin: 1em;">
            <a href="addCouple.php">
                <button type="button" class="btn btn-success">Aggiungi</button>
            </a>
            <a href="editCouples.php">
                <button type="button" class="btn btn-warning" onclick="">Modifica</button>
            </a>
            <a href="deleteCouple.php">
                <button type="button" class="btn btn-danger" onclick="">Cancella</button>
            </a>
        </div>
    </div>

    <?php
    $numRow = $result->num_rows;

    if($numRow == 0){
        echo "<h4 style='margin: 20px; text-align: center;'>Nessuna coppia presente. Vai in \"Aggiungi coppia\" per crearne una</h4>";
        echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"addCouple.php\";'>Aggiungi coppia</button></div>";
    } else {
        echo '  <table class="table table-striped">
                        <thead>
                            <tr style="text-align: center;">
                            <th scope="col">name</th>
                            <th scope="col">Partecipante 1</th>
                            <th scope="col">Partecipante 2</th>
                            <th scope="col">Under</th>
                            </tr>
                        </thead>
                        <tbody>';
        for($i=0; $i<$numRow; $i++){
            $line = mysqli_fetch_assoc($result);

            $query = "SELECT * FROM players WHERE playerID = ".$line["part1"];
            $linePlayer = mysqli_fetch_assoc($connection->query($query));
            $part1 = $linePlayer["name"];
            $year1 = $linePlayer["birthdayDate"];

            $query = "SELECT * FROM players WHERE playerID = ".$line["part2"];
            $linePlayer = mysqli_fetch_assoc($connection->query($query));
            $part2 = $linePlayer["name"];
            $year2 = $linePlayer["birthdayDate"];

            echo "  <tr style='text-align: center'>
                            <th scope='row'>".$line["name"]."</th>
                            <td>".$part1." (".$year1.")</td>
                            <td>".$part2." (".$year2.")</td>
                            <td>".$line["under"]."</td>
                        </tr>";
        }
        echo '      </tbody>
                    </table>';
    }
    ?>

    <script type="text/javascript">
        var rows = document.getElementsByTagName('tr');
        for (var i = 1; i < rows.length; i++) {
            var colonne = rows[i].getElementsByTagName('td');
            var date1 = colonne[0].innerHTML.substring(colonne[0].innerHTML.lastIndexOf("(")+1,colonne[0].innerHTML.lastIndexOf(")"));
            var date2 = colonne[1].innerHTML.substring(colonne[1].innerHTML.lastIndexOf("(")+1,colonne[1].innerHTML.lastIndexOf(")"));

            if(date1<date2) {
                var birthday = new Date(date1)
            } else {
                var birthday = new Date(date2)
            }

            var ageDifMs = Date.now() - birthday.getTime();
            var ageDate = new Date(ageDifMs); // miliseconds from epoch
            var age = Math.abs(ageDate.getUTCFullYear() - 1970);

            if(age>colonne[2].innerHTML)
                rows[i].style = "text-align: center; color: red";
        }
    </script>

</div>

</body>
</html>