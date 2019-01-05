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
    ['name'=> 'TV 92', 'url'=>'http://92news.vdn.dstreamone.net/92newshd/92hd/playlist.m3u8', 'poster'=>'http://www.journalismpakistan.com/news/92%20News%20Media%20Group%20prepapres%20to%20launch%20newspapers.jpg', 'htmlClass'=>'btn btn-success btn-block'],
    ['name'=> 'Geo Tez', 'url'=>'http://stream.jeem.tv/geo/geotezz/playlist.m3u8', 'poster'=>'https://dnd.com.pk/wp-content/uploads/2014/05/BoFzI1nIgAADAka.jpg', 'htmlClass'=>'btn btn-warning btn-block'],
    ['name'=> 'Express News', 'url'=>'http://expressdigital.flashmediacast.com:1935/expressdigital/livestream/playlist.m3u8', 'poster'=>'https://pakistani.pk/uploads/reviews/photos/original/45/9d/16/LIVE-EXPRESS-NEWS-30-1457464500.jpg', 'htmlClass'=>'btn btn-info btn-block'],
    ['name'=> 'PTV News', 'url'=>'http://67.231.248.131:1935/live/PTVnews/chunklist_w1281891626.m3u8', 'poster'=>'http://www.gharana.pk/wp-content/uploads/2017/10/PTV-News-Live-HD-Streaming-24x7-LIVE-HD-GharanaPK.jpg', 'htmlClass'=>'btn btn-success btn-block'],
    ['name'=> 'Ary News', 'url'=>'http://158.69.228.195:1935/newsmobile/myStream/playlist.m3u8', 'poster'=>'http://live.arynews.tv/pk/LIVE.jpg', 'htmlClass'=>'btn btn-warning btn-block'],
    ['name'=> 'Geo News', 'url'=>'http://stream.jeem.tv/geo/geonews/playlist.m3u8', 'poster'=>'https://live.geo.tv/images/live_geonews.jpg', 'htmlClass'=>'btn btn-info btn-block'],
];
foreach ($data as $record) {
    $channel->setAttributes($record)->create();
}




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