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
                    <a class="nav-link" href="addPlayer.php">Aggiungi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="editPlayer.php">Modifica</a>
                </li>
            </ul>
        </div>
    </nav>

    <h3 align="center" style="margin-top: 10px;">Inserisci nuovo iscritto</h3>


    <form action="actionPlayer.php">
        <table class="table table-striped">
            <thead>
                <tr style="text-align: center;">
                <th scope="col">Nome</th>
                <th scope="col">Data di Nascita</th>
                <th scope="col">Numero di Telefono</th>
                <th scope="col">Iscritto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope='row'>
                        <div class="col-sm" width="50%"><input placeholder="Nome iscritto" class="form-control" type="text" name="name" required style="width: 100%; text-align: center;"></div>
                    </th>
                    <td>
                        <input required placeholder="Data di Nascita" class="form-control" type="date" name="date" value="2010-01-01" style="width: 100%; text-align: center;">
                        </div>
                    </td>
                    <td>
                        <input placeholder="Numero di Telefono" class="form-control" type="text" name="number" style="width: 100%; text-align: center;">
                        </div>
                    </td>
                    <td>
                        <input placeholder="Iscritto" class="form-control" type="text" name="subscribed" style="width: 100%; text-align: center;">
                        </div>
                    </td>
                    <input type="hidden" name="action" value="add">
                </tr>
            </tbody>
        </table>

        <div class="row" style="margin: 10px;">
            <div class="col-sm" colspan="2"><input class="btn btn-primary" type="submit" value="Inserisci" style="width: 100%"></div>
        </div>
    </form>

</body>
</html>