<?php
    include 'modules/db_connection.php';

    if(!(isset($_GET) && isset($_GET["eventID"])))
        header("LOCATION: index.php");
    
    $eventID = $_GET["eventID"];

    $connection = openConnection();
    
    $query = "SELECT * FROM events WHERE eventID = ".$eventID;

    $result = $connection->query($query);
    $line = mysqli_fetch_assoc($result);
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

    <style type="text/css">

        @media (max-width: 1200px) {
          .divWebsite{
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
          .divWebsite{
                width: 31.33%;
                margin: 1%;
                float: left;
                box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
                transition-duration: 0.3s;
                background-color: #e6f7ff;
            }
        }

        .divWebsite:hover {
            transition-duration: 0.3s;
            box-shadow: 0px 0px 10px rgba(0,0,0,.2);
        }

        .divSiteName{
            width: 100%;
            height: 100%;
            text-align: center;
            padding: 5px;
            color: black;
            font-weight: bold;
        }

        .divAllMathces{
            width: 98%;
            margin-left: 1%;
            margin-right: 1%;
            margin-top: 20px;
            float: left;
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
            transition-duration: 0.3s;
            background-color: #e6f7ff;
        }

        .divAllMathces:hover {
            transition-duration: 0.3s;
            box-shadow: 0px 0px 10px rgba(0,0,0,.2);
        }


    </style>

    <title>Beach Tennis</title>
</head>
<body>

    <?php require "templates/navbar.php"; ?>
    
    <h3 align="center" style="margin-top: 10px;">Seleziona under per <?php echo $line["eventName"];?> o visualizzane le partite</h3>

    <?php

        $query = "SELECT under FROM couples GROUP BY under ORDER BY under";
        $result = $connection->query($query);

        if($result->num_rows==0){
            echo "<h4 style='margin: 20px; text-align: center;'>Non è presente nessuna coppia. Vai in \"Tutte le coppie\" per aggiungerle</h4>";
            echo "<div width='100%' align='center'><button class='btn btn-primary' onclick='window.location.href = \"addCouple.php\";'>Aggiungi coppia</button></div>";
        } else {

            echo '  <a href="matchesForEvent.php?eventID='.$_GET["eventID"].'">';
            echo '      <div class="container-fluid">
                            <div class="rounded divAllMathces">
                                <div style="height: 200px; vertical-align: middle; line-height: 200px" class="rounded-bottom divSiteName">TUTTE LE PARTITE</div>
                            </div>
                        </div>
                    </a>';

            for ($i=0; $i < $result->num_rows; $i++) {
                $line = mysqli_fetch_assoc($result);
                echo '  <a href="couplesForEvent.php?eventID='.$_GET["eventID"].'&under='.$line["under"].'">';
                echo '      <div class="container-fluid">
                                <div class="rounded divWebsite">
                                    <div style="height: 300px; vertical-align: middle; line-height: 300px" class="rounded-bottom divSiteName">UNDER ' .$line["under"].'</div>
                                </div>
                            </div>
                        </a>';
            }
        }
    ?>

</body>
</html>