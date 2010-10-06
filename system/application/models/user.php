<?php
class User extends Doctrine_Record {
    /*
CREATE TABLE "user" (
    "user_id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "name" TEXT,
    "password" TEXT,
    "email" TEXT,
    "create_date" INTEGER,
    "admin" INTEGER DEFAULT 0,
    "email_notification" INTEGER DEFAULT 0
);*/

	public function setTableDefinition() {
		$this->hasColumn('name', 'string', 255);
		$this->hasColumn('email', 'string', 255, array('unique' => 'true'));
		$this->hasColumn('password', 'string', 255);
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


