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

    </style>

    <title>Beach Tennis</title>
</head>
<body>

    <?php require 'templates/navbar.php' ?>

    <a href="players.php">
        <div class="container-fluid">
            <div class="rounded divWebsite">
                <div style="height: 300px; vertical-align: middle; line-height: 300px" class="rounded-bottom divSiteName">TUTTI GLI ISCRITTI</div>
            </div>
        </div>
    </a>

    <a href="couples.php">
        <div class="container-fluid">
            <div class="rounded divWebsite">
                <div style="height: 300px; vertical-align: middle; line-height: 300px" class="rounded-bottom divSiteName">TUTTE LE COPPIE</div>
            </div>
        </div>
    </a>
    
    <a href="events.php">
        <div class="container-fluid">
            <div class="rounded divWebsite">
                <div style="height: 300px; vertical-align: middle; line-height: 300px" class="rounded-bottom divSiteName">EVENTI</div>
            </div>
        </div>
    </a>

</body>
</html>