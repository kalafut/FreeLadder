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
    public $create_date;

    function __construct()
    {
        $this->add();
    }

    function update()
    {
        $db = DB::getDB();
        
        $stmt = $db->prepare('UPDATE user SET name=:name, email=:email, password=:password WHERE id=:id');

        $stmt->bindValue(':name',$this->name,PDO::PARAM_STR);
	    $stmt->bindValue(':email',$this->email,PDO::PARAM_STR);
	    $stmt->bindValue(':password',$this->password,PDO::PARAM_STR);
	    $stmt->bindValue(':id',$this->id,PDO::PARAM_STR);

	    $stmt->execute();
    }
    
    private function add()
    {
        $db = DB::getDB();
        
        $this->rank = $db->getHighestRank() + 1;
        
        $stmt = $db->prepare('INSERT INTO users(name, email, password, rank, create_date) VALUES(:name, :email, :password, :rank, strftime("%s","now", "localtime"))');

        $stmt->bindValue(':name',$this->name,PDO::PARAM_STR);
	    $stmt->bindValue(':email',$this->email,PDO::PARAM_STR);
	    $stmt->bindValue(':password',$this->password,PDO::PARAM_STR);
	    $stmt->bindValue(':rank',$this->rank,PDO::PARAM_STR);

	    $stmt->execute();
	    $this->id = $db->lastInsertId();
	    
	    // Add current rank as initial rank history
	    $stmt = $db->prepare('INSERT INTO rank_history(date, user_id, rank) VALUES(strftime("%s","now", "localtime"), :user_id, :rank)');

        $stmt->bindValue(':user_id',$this->id,PDO::PARAM_STR);
        $stmt->bindValue(':rank',$this->rank,PDO::PARAM_STR);
        $stmt->execute();
    }
}

?>
