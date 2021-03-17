<?php

function openConnection() {
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "beachtennis";

    $connection = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($connection->errno) {
        die("Connection failed".$connection->error);
    }

    return $connection;
}

function closeConnection($connection) {
    $connection->close();
}

