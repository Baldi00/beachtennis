<?php
require "modules/db_connection.php";
require "modules/export/export_csv.php";

// TODO: create a file for every export type?

if (isset($_GET["source"])) {
    $fileName = "";

    switch ($_GET["source"]) {
        case "couples":
            $fileName = exportCouples();
            break;
        case "players":
            $fileName = exportPlayers();
            break;
        case "events":
            $fileName = exportEvents();
            break;
        case "couplesUnder":
            $fileName = exportCouplesUnder();
            break;
        case "matches":
            $fileName = exportMatches();
            break;
        // TODO: should be eventMatches?
        case "matchesEvent":
            $fileName = exportEventMatches();
            break;
        case "roundsUnder":
            $fileName = exportRoundsUnder();
            break;
    }

    download($fileName);
}

function download($fileName) {
    $file = $fileName.".csv";
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: '.filesize($file));
    header("Content-Type: text/csv");
    readfile($file);
}