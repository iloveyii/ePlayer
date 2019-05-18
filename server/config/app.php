<?php

/**
 * Set error reporting in dev mode
 */
// error_reporting(E_ALL);
// ini_set('display_errors', 1);


/**
 * Set DB credentials here
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'tv');
define('DB_USER', 'root');
define('DB_PASS', 'root1@3M');

/**
 * Set log level here for Log class
 */
define('NONE', 0);
define('INFO', 1);
define('WARN', 2);
define('CRITICAL', 3);
define('ALL', 4);

define('ERROR_LOG_LEVEL', ALL);
