<?php

class Ladder extends MY_Model 
{
    public static function current_ladder_name()
    {
        $m = new Ladder();
        return $m->_current_ladder_name();
    }

    public function _current_ladder_name()
    {
        $uModel = new User();

        $user = $uModel->current_user();
        $q = $this->db->get_where('ladders',  array('id'=>$user['ladder_id']));

        return $q->row()->name;
        

    }
}
