<?php
class Challenge extends Doctrine_Record {
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
		$this->hasColumn('ladder_id', 'integer', 4);
	}

	public function setUp() {
        $this->actAs('Timestampable');
		$this->hasMany('Challenge_User ', array(
			'local' => 'id',
			'foreign' => 'challenge_id'
		));
		$this->hasOne('User as Opponent', array(
			'local' => 'opponent_id',
			'foreign' => 'id'
		));
		$this->hasOne('Ladder', array(
			'local' => 'ladder_id',
			'foreign' => 'id'
		));
    }
}

