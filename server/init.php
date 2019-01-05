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
    ['name'=> 'TV 92', 'url'=>'http://92news.vdn.dstreamone.net/92newshd/92hd/playlist.m3u8', 'icon'=>'https://upload.wikimedia.org/wikipedia/en/e/e1/92_News_HD_Plus_logo.png', 'poster'=>'http://www.journalismpakistan.com/news/92%20News%20Media%20Group%20prepapres%20to%20launch%20newspapers.jpg', 'htmlClass'=>'btn btn-success btn-block'],
    ['name'=> 'Geo Tez', 'url'=>'http://stream.jeem.tv/geo/geotezz/playlist.m3u8', 'icon'=>'./app/assets/img/geo-tez-icon.jpeg', 'poster'=>'https://dnd.com.pk/wp-content/uploads/2014/05/BoFzI1nIgAADAka.jpg', 'htmlClass'=>'btn btn-warning btn-block'],
    ['name'=> 'Express News', 'url'=>'http://expressdigital.flashmediacast.com:1935/expressdigital/livestream/playlist.m3u8', 'icon'=>'https://lh3.googleusercontent.com/Fd4Fk_D17i-kZPQmjwBuoJQt426gz_sLJ2Glx0nse6A5LDWMTGbjBwEyap6FP1edjA', 'poster'=>'https://pakistani.pk/uploads/reviews/photos/original/45/9d/16/LIVE-EXPRESS-NEWS-30-1457464500.jpg', 'htmlClass'=>'btn btn-info btn-block'],
    ['name'=> 'PTV News', 'url'=>'http://67.231.248.131:1935/live/PTVnews/chunklist_w1281891626.m3u8', 'icon'=>'./app/assets/img/ptv-news-icon.jpeg', 'poster'=>'http://www.gharana.pk/wp-content/uploads/2017/10/PTV-News-Live-HD-Streaming-24x7-LIVE-HD-GharanaPK.jpg', 'htmlClass'=>'btn btn-success btn-block'],
    ['name'=> 'Ary News', 'url'=>'http://158.69.228.195:1935/newsmobile/myStream/playlist.m3u8', 'icon'=>'https://seeklogo.com/images/A/ary-news-logo-F2E62D53D8-seeklogo.com.png', 'poster'=>'http://live.arynews.tv/pk/LIVE.jpg', 'htmlClass'=>'btn btn-warning btn-block'],
    ['name'=> 'Geo News', 'url'=>'http://stream.jeem.tv/geo/geonews/playlist.m3u8', 'icon'=>'https://vignette.wikia.nocookie.net/logopedia/images/e/e9/Geo_News.png/revision/latest?cb=20111002115646', 'poster'=>'https://live.geo.tv/images/live_geonews.jpg', 'htmlClass'=>'btn btn-info btn-block'],
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