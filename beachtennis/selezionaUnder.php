<?php
    if(!(isset($_GET) && isset($_GET["codEvento"])))
        header("LOCATION: index.php");
    
    $codEvento = $_GET["codEvento"];

    $connessione = new mysqli("localhost","root","","beachtennis");

    if($connessione->connect_errno)
        die("<h1>Errore connessione al database</h1>");
    
    $query = "SELECT * FROM Eventi WHERE codEvento = ".$codEvento;

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

    <style type="text/css">

        @media (max-width: 1200px) {
          .divSito{
                width: 98%;
                margin-left: 1%;
                margin-right: 1%;
                margin-top: 20px;
                float: left;
                box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
                transition-duration: 0.3s;
                background-color: #e6f7ff;
            }
        }

        @media (min-width: 1200px) {
          .divSito{
                width: 31.33%;
                margin: 1%;
                float: left;
                box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
                transition-duration: 0.3s;
                background-color: #e6f7ff;
            }
        }

        .divSito:hover {
            transition-duration: 0.3s;
            box-shadow: 0px 0px 10px rgba(0,0,0,.2);
        }

        .divNomeSito{
            width: 100%;
            height: 100%;
            text-align: center;
            padding: 5px;
            color: black;
            font-weight: bold;
        }

        .divTuttePartite{
            width: 98%;
            margin-left: 1%;
            margin-right: 1%;
            margin-top: 20px;
            float: left;
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
            transition-duration: 0.3s;
            background-color: #e6f7ff;
        }

        .divTuttePartite:hover {
            transition-duration: 0.3s;
            box-shadow: 0px 0px 10px rgba(0,0,0,.2);
        }


    </style>

    <title>Beach Tennis</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="index.php">Beach Tennis</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
    
    <h3 align="center" style="margin-top: 10px;">Seleziona under per <?php echo $line["nomeEvento"];?> o visualizzane le partite</h3>

    <?php

        $query = "SELECT under FROM Coppie GROUP BY under ORDER BY under";
        $result = $connessione->query($query);

        if($result->num_rows==0){
            echo "<h4 style='margin: 20px; text-align: center;'>Non è presente nessuna coppia. Vai in \"Tutte le coppie\" per aggiungerle</h4>";
            echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"inserisciCoppia.php\";'>Aggiungi coppia</button></div>";
        } else {

            echo '  <a href="partitePerEvento.php?codEvento='.$_GET["codEvento"].'">';
            echo '      <div class="container-fluid">
                            <div class="rounded divTuttePartite">
                                <div style="height: 200px; vertical-align: middle; line-height: 200px" class="rounded-bottom divNomeSito">TUTTE LE PARTITE</div>
                            </div>
                        </div>
                    </a>';

            for ($i=0; $i < $result->num_rows; $i++) {
                $line = mysqli_fetch_assoc($result);
                echo '  <a href="coppiePerEvento.php?codEvento='.$_GET["codEvento"].'&under='.$line["under"].'">';
                echo '      <div class="container-fluid">
                                <div class="rounded divSito">
                                    <div style="height: 300px; vertical-align: middle; line-height: 300px" class="rounded-bottom divNomeSito">UNDER '.$line["under"].'</div>
                                </div>
                            </div>
                        </a>';
            }
        }
    ?>

</body>
</html>