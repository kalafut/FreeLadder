<?php
class User extends Doctrine_Record {

	public function setTableDefinition() {
		$this->hasColumn('email', 'string', 255, array('unique' => 'true'));
		$this->hasColumn('password', 'string', 255);
	}

	public function setUp() {
		$this->setTableName('user');
		$this->actAs('Timestampable');
	}
}


