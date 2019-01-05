<?php

require_once "../config/app.php";
require_once "../vendor/autoload.php";

use App\Models\Database;
use App\Models\Log;
use App\Models\Command;
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


if(isset($_GET['cmdstatus'])) {
    $id = $_GET['cmdid'];
    $status = $_GET['cmdstatus'];

    $attributes = [
        'id' => $id,
        'status' => $status
    ];
    $command = new Command();
    $result = $command->setAttributes($attributes)->setStatus();
    echo json_encode($result);
    exit;
}


if(isset($_GET['cmdadd'])) {
    $cmd = $_GET['cmdadd'];

    $attributes = [
        'cmd' => $cmd,
        'status' => 1
    ];
    $command = new Command();
    $result['status'] = $command->setAttributes($attributes)->create();

    $result['cmd'] = $cmd;
    $result['obj'] = print_r($command, 1);
    echo json_encode($result);
    exit;
}


