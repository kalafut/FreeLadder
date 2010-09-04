<?php
/*
 * FreeLadder Ladder Server
 * http://freeladder.org
 *
 * Copyright 2010, Jim Kalafut
 * Released under the MIT license.
 * 
 */
 
require_once("config.php");

class Log
{
    public static function debug($msg)
    {
        file_put_contents(Config::LOGFILE, "DEBUG: " . print_r($msg, true) . "\n", FILE_APPEND);
    }
    
}

?>