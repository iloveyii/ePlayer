<?php

require_once "../config/app.php";
require_once "../vendor/autoload.php";

use App\Models\Database;
use App\Models\Log;
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Make log file error.log clear
Log::clear();


if(isset($_GET['data']) && ($_GET['data'] == 'channel')) {
    $sql = sprintf("SELECT * FROM channel");

    $rows = Database::connect()->selectAll($sql);
    echo json_encode($rows);
    exit;
}


if(isset($_GET['data']) && ($_GET['data'] == 'command')) {
    $sql = sprintf("SELECT * FROM command WHERE status > 0");

    $rows = Database::connect()->selectAll($sql);
    echo json_encode($rows);
    exit;
}


if(isset($_GET['command'])) {
    $id = $_GET['command'];
    $status = $_GET['status'];

    $attributes = [
        'id' => $id,
        'status' => $status
    ];
    $command = new \App\Models\Command();
    $result = $command->setAttributes($attributes)->setStatus();
    echo json_encode($result);
    exit;
}


