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
		$this->hasColumn('access_key', 'string', 255, array('unique' => 'true'));
		$this->hasColumn('type', 'integer', 255);
		$this->hasColumn('status', 'string', 255);
		$this->hasColumn('site_admin', 'boolean', array('default' => 'false'));
	}

	public function setUp() {
		$this->actAs('Timestampable');
        $this->hasMutator('password', '_encrypt_password');

        $this->hasMany('Challenge as Challenges', array(
			'local' => 'id',
            'foreign' => 'challenger_id'
        ));
        $this->hasMany('Challenge as Challenges', array(
			'local' => 'id',
            'foreign' => 'opponent_id'
        ));

	}

    protected function _encrypt_password($value) {
		$salt = '#*seCrEt!@-*%';
		$this->_set('password', md5($salt . $value));
	}

}


