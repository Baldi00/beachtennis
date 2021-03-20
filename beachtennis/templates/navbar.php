<?php

function addNavItem($title, $href) {
    echo '  <li class="nav-item">
                <a class="nav-link" href="'.$href.'">'.$title.'<span class="sr-only">(current)</span></a>
            </li>';
}

?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">

    <!-- TODO: this should be just a Title not a link -->
    <a class="navbar-brand" href="#">Beach Tennis</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">

            <?php
            addNavItem("Home", "index.php");
            addNavItem("Iscritti", "players.php");
            addNavItem("Coppie", "couples.php");
            addNavItem("Eventi", "events.php");
            ?>

            <!-- DROPDOWN MENU -->
<!--            <li class="nav-item dropdown">-->
<!--                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                    Dropdown-->
<!--                </a>-->
<!--                <div class="dropdown-menu" aria-labelledby="navbarDropdown">-->
<!--                    <a class="dropdown-item" href="#">Action</a>-->
<!--                    <a class="dropdown-item" href="#">Another action</a>-->
<!--                    <div class="dropdown-divider"></div>-->
<!--                    <a class="dropdown-item" href="#">Something else here</a>-->
<!--                </div>-->
<!--            </li>-->

            <!-- ITEM DISABLED -->
<!--            <li class="nav-item">-->
<!--                <a class="nav-link disabled" href="#">Disabled</a>-->
<!--            </li>-->

        </ul>

        <!-- OPTIONAL SEARCH BAR -->
<!--        <form class="form-inline my-2 my-lg-0">-->
<!--            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">-->
<!--            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>-->
<!--        </form>-->

    </div>
</nav>