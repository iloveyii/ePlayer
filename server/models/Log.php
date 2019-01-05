<?php

namespace App\Models;


class Log
{
    const NONE = 0;
    const INFO = 1;
    const WARN = 2;
    const CRITICAL = 3;
    const ALL = 4;

    /**
     * @var Log
     */
    protected static $instance;
    /**
     * @var string
     */
    private static $fileName = 'error.log';
    /**
     * @var array
     */
    private static $errorLevels = [
        NONE => 'NONE',
        INFO => 'INFO',
        WARN => 'WARN',
        CRITICAL => 'CRITICAL',
        ALL => 'ALL'
    ];

    /**
     * It writes to log file in web/log.txt
     *
     * @param $message - String message to output
     * @param $level - Levels as defined in config/app.php
     * @return bool
     * @throws \Exception
     */
    public static function write($message, $level)
    {
        if ($level === ERROR_LOG_LEVEL || ERROR_LOG_LEVEL === ALL) {
            return self::writeToFile($message, $level);
        }

        return false;
    }

    private static function getFilePath()
    {
        $dirPath = realpath(dirname(dirname(__FILE__)));
        // $filePath = sprintf("%s/%s/%s", $dirPath, 'web', self::$fileName);
        $filePath = sprintf("%s/%s", '.', self::$fileName);
        return $filePath;
    }

    public static function clear()
    {
        $filePath = self::getFilePath();
        if (file_exists($filePath)) {
            unlink($filePath);
            Log::write("Cleared log file", INFO);
        }
    }

    /**
     * This is the function that actually does I/O
     *
     * @param $message
     * @param $level
     * @return bool|int
     * @throws \Exception
     */
    private static function writeToFile($message, $level)
    {
        date_default_timezone_set('Europe/Stockholm');
        $levelName = self::$errorLevels[$level];
        $line = sprintf("%s\t%s\t%s%s", $levelName, date('Y-m-d h:i:s', time()), $message, PHP_EOL);

        $filePath = self::getFilePath();

        if (is_writable(dirname($filePath))) {

            try {
                if (file_exists($filePath)) {
                    $result = file_put_contents($filePath, $line, FILE_APPEND | LOCK_EX);
                } else {
                    $result = file_put_contents($filePath, $line, FILE_APPEND | LOCK_EX);
                    chmod($filePath, 0777);
                }
                return $result;

            } catch (exception $e) {
                throw new \Exception($e->getMessage());
            }

        } else {
            syslog(LOG_NOTICE, "Cannot write to directory {$filePath}");
            return false;
        }
    }
}
