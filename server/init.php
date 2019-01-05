<?php

require_once "./config/app.php";
require_once "./vendor/autoload.php";

use App\Models\Database;
use App\Models\Log;
use App\Models\Channel;

// Make log file error.log clear
Log::clear();

$channel = new Channel();
$channel->dropTable();
$channel->createTable();

// Insert data
$data = [
    ['name'=> 'TV 92', 'url'=>'http://92news.vdn.dstreamone.net/92newshd/92hd/playlist.m3u8', 'htmlClass'=>'btn btn-success btn-block'],
    ['name'=> 'Geo Taiz', 'url'=>'http://stream.jeem.tv/geo/geotezz/playlist.m3u8', 'htmlClass'=>'btn btn-warning btn-block'],
    ['name'=> 'Eye 95', 'url'=>'http://cdn20.liveonlineservices.com/hls/eye95tv.m3u8', 'htmlClass'=>'btn btn-danger btn-block'],
    ['name'=> 'Express News', 'url'=>'http://expressdigital.flashmediacast.com:1935/expressdigital/livestream/playlist.m3u8', 'htmlClass'=>'btn btn-info btn-block'],
    ['name'=> 'PTV News', 'url'=>'http://67.231.248.131:1935/live/PTVnews/chunklist_w1281891626.m3u8', 'htmlClass'=>'btn btn-success btn-block'],
    ['name'=> 'Ary News', 'url'=>'http://158.69.228.195:1935/newsmobile/myStream/playlist.m3u8', 'htmlClass'=>'btn btn-warning btn-block'],
    ['name'=> 'Geo News', 'url'=>'http://stream.jeem.tv/geo/geonews/playlist.m3u8', 'htmlClass'=>'btn btn-info btn-block'],
];
foreach ($data as $record) {
    $channel->setAttributes($record)->create();
}