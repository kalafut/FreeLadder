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
            ->or_where('c.player2_id', $user_id)
            ->order_by('created_at');

        $q = $this->db->get();

        $results = $q->result();

        if(0) {
        echo "<pre>";
        print_r($results);
        echo "</pre>";
        }

        return $results;
    }

    public function add_challenge($challenger_id, $target_id, $ladder_id)
    {
        // TODO: Validate first!

        $this->insert( 
            array('player1_id'=>$challenger_id, 'player2_id'=>$target_id, 'ladder_id'=>$ladder_id) 
        );

        $ladder = new Ladder();
        $ladder->update_challenge_count($challenger_id, $ladder_id);
        $ladder->update_challenge_count($target_id, $ladder_id);
    }
}
