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

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: GET, POST, PUT');

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


// Add new channel

if(isset($_GET['name'])) {
    $buttons = ['default', 'info', 'warning', 'danger', 'success'];
    $attributes = [
        'name' => $_GET['name'],
        'icon' => $_GET['icon'],
        'url' => $_GET['url'],
        'poster' => $_GET['poster'],
        'htmlClass' => 'btn btn-block btn-' . $buttons[rand(0, count($buttons) - 1)]
    ];
    Log::write('Adding channel with data ' . print_r($attributes, true), Log::INFO);

    $channel = new \App\Models\Channel();
    $result['status'] = $channel->setAttributes($attributes)->validate();
    if($result['status'] === true) {
        $channel->create();
    } else {
        $result['errors'] = $channel->getErrors();
    }

    echo json_encode($result);
    exit;
}

