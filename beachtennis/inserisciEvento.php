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
                    <a class="nav-link" href="inserisciEvento.php">Aggiungi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="modificaEventi.php">Modifica</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="eliminaEvento.php">Cancella</a>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Inserisci nuovo evento</h3>


    <form action="azioneEvento.php">
        <table class="table table-striped">
            <thead>
                <tr style="text-align: center;">
                <th scope="col">Nome Evento</th>
                <th scope="col">Data Inizio</th>
                <th scope="col">Data Fine</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope='row'><div class="col-sm" width="50%"></div><div class="col-sm" width="50%"><input placeholder="Nome evento" class="form-control" type="text" name="nome" required style="width: 100%; text-align: center;"></div></th>
                    <td><div class="col-sm" width="50%"></div><div class="col-sm" width="50%"><input placeholder="Data Inizio" class="form-control" type="text" name="dataInizio" style="width: 100%; text-align: center;"></div></td>
                    <td><div class="col-sm" width="50%"></div><div class="col-sm" width="50%"><input placeholder="Data Fine" class="form-control" type="text" name="dataFine" style="width: 100%; text-align: center;"></div></td>
                    <input type="hidden" name="azione" value="aggiungi">
                </tr>
            </tbody>
        </table>

        <div class="row" style="margin: 10px;">
            <div class="col-sm" colspan="2"><input class="btn btn-primary" type="submit" value="Inserisci" style="width: 100%"></div>
        </div>
    </form>

</body>
</html>