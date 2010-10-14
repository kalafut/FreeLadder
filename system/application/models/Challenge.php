<?php

class Challenge extends MY_Model 
{
    public function load_challenges($user_id, $ladder_id)  
    {
        $this->db->select('c.*, u1.name AS name1, u2.name AS name2')
            ->from('Challenges c')
            ->join('Users u1', 'u1.id = c.player1_id')
            ->join('Users u2', 'u2.id = c.player2_id')
            ->where('c.ladder_id', $ladder_id)
            ->where('c.player1_id', $user_id)
            ->or_where('c.player2_id', $user_id);

        $q = $this->db->get();

        $results = $q->result_array();

        if(0) {
        echo "<pre>";
        print_r($results);
        echo "</pre>";
        }

        return $results;
    }
}
