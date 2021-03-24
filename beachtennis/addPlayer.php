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

<?php include "templates/navbar.php"; ?>

<div class="container">

    <h3 align="center" style="margin-top: 10px;">Inserisci nuovo iscritto</h3>

    <form action="actionPlayer.php">

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nome</label>
            <div class="col-sm-10">
                <input class="form-control" type="text" name="name" placeholder="Nome">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Data di Nascita</label>
            <div class="col-sm-10">
                <input class="form-control" name="date" type="date">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Telefono</label>
            <div class="col-sm-10">
                <input class="form-control" name="number" placeholder="Telefono">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Data Iscrizione</label>
            <div class="col-sm-10">
                <input class="form-control" name="subscribed" placeholder="Data Iscrizione">
            </div>
        </div>

        <!-- TODO: handle add player in some other way? -->
        <input type="hidden" name="action" value="add">

        <button type="submit" class="btn btn-success">Aggiungi</button>
        <!-- TODO: add button "Cancel" -->

    </form>

</div>

</body>
</html>