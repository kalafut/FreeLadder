<?php

class User extends MY_Model 
{
    var $name;

    public function __construct() {
    }

	protected function _encrypt_password($value) {
		$salt = '#*seCrEt!@-*%';
		$this->_set('password', md5($salt . $value));
	}
    
}
