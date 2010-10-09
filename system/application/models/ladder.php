<?php
class Ladder extends Doctrine_Record {
    /*
CREATE TABLE "ladder" (
    "ladder_id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "ladder_name" TEXT,
    "access_name" TEXT,
    "type" TEXT,
    "status" TEXT
    );
*/

	public function setTableDefinition() {
		$this->hasColumn('name', 'string', 255);
		$this->hasColumn('access_key', 'string', 255);
		$this->hasColumn('type', 'integer', 255);
		$this->hasColumn('status', 'string', 255);
	}

	public function setUp() {
		$this->actAs('Timestampable');

        $this->hasMany('User as Users', array(
            'local' => 'ladder_id',
            'foreign' => 'user_id',
            'refClass' => 'Ladder_User'
        ));

        $this->hasMany('User as Current_Users', array(
            'local' => 'id',
            'foreign' => 'current_ladder_id',
        ));

        $this->hasMany('Ladder_User as Ladder_Users', array(
            'local' => 'id',
            'foreign' => 'ladder_id',
        ));

        $this->hasMany('Challenge as Challenges', array(
            'local' => 'id',
            'foreign' => 'ladder_id',
        ));
	}
}


