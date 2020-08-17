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

    <a href="iscritti.php">
        <div class="container-fluid">
            <div class="rounded divSito">
                <div style="height: 300px; vertical-align: middle; line-height: 300px" class="rounded-bottom divNomeSito">TUTTI GLI ISCRITTI</div>
            </div>
        </div>
    </a>

    <a href="coppie.php">
        <div class="container-fluid">
            <div class="rounded divSito">
                <div style="height: 300px; vertical-align: middle; line-height: 300px" class="rounded-bottom divNomeSito">TUTTE LE COPPIE</div>
            </div>
        </div>
    </a>
    
    <a href="eventi.php">
        <div class="container-fluid">
            <div class="rounded divSito">
                <div style="height: 300px; vertical-align: middle; line-height: 300px" class="rounded-bottom divNomeSito">EVENTI</div>
            </div>
        </div>
    </a>

</body>
</html>