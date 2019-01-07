<?php

require_once "./config/app.php";
require_once "./vendor/autoload.php";

use App\Models\Log;
use App\Models\Channel;

// Make log file error.log clear
Log::clear();

/**
 * Create channel table and insert sample data
 */
$channel = new Channel();
$channel->dropTable();
$channel->createTable();

// Insert data
$data = [
    ['name'=> 'name of channel', 'url'=>'link to .m3u8', 'icon'=>'icon for channel logo', 'poster'=>'url to poster of channel', 'htmlClass'=>'btn btn-success btn-block'],
];
foreach ($data as $record) {
    $channel->setAttributes($record)->create();
}

/**
 * Create command table and insert sample data
 */
$command = new \App\Models\Command();
$command->dropTable();
$command->createTable();

// Insert data
$data = [
    ['cmd'=> 'NEXT 1', 'status'=>1],
    ['cmd'=> 'NEXT 1', 'status'=>1],
    ['cmd'=> 'PRE 1', 'status'=>1],
];
foreach ($data as $record) {
    $command->setAttributes($record)->create();
}