<?php
/*
CREATE TABLE "ladder_user" (
    "user_id" INTEGER,
    "ladder_id" INTEGER,
    "admin" INTEGER DEFAULT 0,
    "rank" INTEGER,
    "max_challenges" INTEGER DEFAULT 999,
    "status" TEXT DEFAULT "active",
    "losses" INTEGER DEFAULT 0,
    "wins" INTEGER DEFAULT 0
);*/
class Ladder_User extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('user_id', 'integer', 4);
        $this->hasColumn('ladder_id', 'integer', 4);
        $this->hasColumn('admin', 'boolean', array('default' => false));
        $this->hasColumn('rank', 'integer', 4);
        $this->hasColumn('max_challenges', 'integer', 4, array('default' => 999));
        $this->hasColumn('wins', 'integer', 1, array('default' => 0));
        $this->hasColumn('losses', 'integer', 4, array('default' => 0));
    }

    public function setUp() {
		$this->actAs('Timestampable');

        $this->hasOne('User', array(
            'local' => 'user_id',
            'foreign' => 'id',
        ));

        $this->hasOne('Ladder', array(
            'local' => 'ladder_id',
            'foreign' => 'id',
        ));
    }
}
