<?php

class BaseModel extends MY_Model
{
    static private $instance;

    static public function instance()
    {
        if ( !isset(self::$instance) ) {
            self::$instance = new self(); 
        }

        return self::$instance;
    }
}
