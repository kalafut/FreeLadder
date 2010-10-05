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
		$this->hasColumn('challenger_id', 'integer', 4);
		$this->hasColumn('opponent_id', 'integer', 4);
	}

	public function setUp() {
        $this->actAs('Timestampable');
		$this->hasOne('User as Challenger', array(
			'local' => 'challenger_id',
			'foreign' => 'id'
		));
		$this->hasOne('User as Opponent', array(
			'local' => 'opponent_id',
			'foreign' => 'id'
		));
    }
}
