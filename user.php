<?php
/*
 * FreeLadder Ladder Server
 * http://freeladder.org
 *
 * Copyright 2010, Jim Kalafut
 * Released under the MIT license.
 * 
 */
 
include_once("db.php");

class User {
    public $id;
    public $name;
    public $login;
    public $password;
    public $rank;
    public $email;
    public $maxChallenges;
    public $status;
    public $losses;
    public $wins;
    public $emailNotification;
    public $admin;
    
    function add()
    {
        $db = DB::getDB();
        
        $stmt = $db->prepare('INSERT INTO users(name, email, password, create_date) VALUES(:name, :email, :password, strftime("%s","now", "localtime"))');

        $stmt->bindValue(':name',$this->name,PDO::PARAM_STR);
	    $stmt->bindValue(':email',$this->email,PDO::PARAM_STR);
	    $stmt->bindValue(':password',$this->password,PDO::PARAM_STR);

	    $stmt->execute();
    }
}

?>