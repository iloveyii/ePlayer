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
    ['name'=> 'TV 92', 'url'=>'http://92news.vdn.dstreamone.net/92newshd/92hd/playlist.m3u8'],
    ['name'=> 'Geo Taiz', 'url'=>'http://stream.jeem.tv/geo/geotezz/playlist.m3u8'],
    ['name'=> 'Big Bunny', 'url'=>'https://video-dev.github.io/streams/x36xhzz/x36xhzz.m3u8'],
    ['name'=> 'Eye 95', 'url'=>'http://cdn20.liveonlineservices.com/hls/eye95tv.m3u8'],
    ['name'=> 'Express News', 'url'=>'http://expressdigital.flashmediacast.com:1935/expressdigital/livestream/playlist.m3u8'],
];
foreach ($data as $record) {
    $channel->setAttributes($record)->create();
}