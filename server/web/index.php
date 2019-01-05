<?php

require_once "../config/app.php";
require_once "../vendor/autoload.php";

use App\Models\Database;
use App\Models\Log;

// Make log file error.log clear
Log::clear();

$sql = sprintf("SELECT * FROM channel");

if(isset($_GET['data']) && ($_GET['data'] == 'channels')) {
    $rows = Database::connect()->selectAll($sql);
    echo json_encode($rows);
    exit;
}


