<?php
class Challenge_User extends Doctrine_Record {
/*
CREATE TABLE "challenge" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "ladder_id" INTEGER, 
    "challenger" INTEGER,
    "opponent" INTEGER,
    "date" INTEGER,
    "opponent_result" INTEGER NOT NULL DEFAULT 0,
    "challenger_result" INTEGER NOT NULL DEFAULT 0 
    );*/
	
	public function setTableDefinition() {
		$this->hasColumn('challenge_id', 'integer', 4);
		$this->hasColumn('user_id', 'integer', 4);
		$this->hasColumn('result', 'integer', 1, array('default'=>0));
	}

	public function setUp() {
		$this->hasOne('User', array(
			'local' => 'user_id',
			'foreign' => 'id'
		));
		$this->hasOne('Challenge', array(
			'local' => 'challenge_id',
			'foreign' => 'id'
		));
    }
}
