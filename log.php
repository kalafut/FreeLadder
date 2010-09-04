<?php
require_once("config.php");

class Log
{
    public static function debug($msg)
    {
        file_put_contents(Config::LOGFILE, "DEBUG: " . print_r($msg, true) . "\n", FILE_APPEND);
    }
    
}

?>