<?php

class BaseModel extends MY_Model
{
    static private instance;

    static public function instance()
    {
        if ( !isset(self::$instance) ) {
            self::$instance = new __CLASS__;
        }

        return self::$instance;
    }
}
