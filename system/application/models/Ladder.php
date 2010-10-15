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
        $q = $this->get_by('id', $user->ladder_id);

        return $q->name;
    }

    public function load_ladder($ladder_id)
    {
        $this->db->select('u.id, u.name, lu.rank, lu.wins, lu.losses, lu.challenge_count')
            ->from('users u')
            ->join('ladder_users lu', 'lu.user_id = u.id')
            ->where('lu.ladder_id', $ladder_id)
            //->group_by('u.id')
            ->order_by('lu.rank');

        $q = $this->db->get();

        return $q->result();
    }

    public function update_challenge_count($user_id, $ladder_id)
    {
        $this->db->select('c.id')
            ->from('challenges c')
            ->where('c.ladder_id', $ladder_id)
            ->where('c.player1_id', $user_id)
            ->or_where('c.player2_id', $user_id);

        $count = $this->db->count_all_results();

        $this->db->where('id', $user_id)
            ->where('ladder_id', $ladder_id)
            ->update('ladder_users', array('challenge_count'=> $count));
    }
}
