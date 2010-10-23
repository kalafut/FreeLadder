<?php

class Challenge extends MY_Model 
{
    const STATUS_NORMAL  = 0;
    const STATUS_WAITING = 1;
    const STATUS_REVIEW  = 2;

    private static $_instance;

    static public function instance()
    {
        if ( !isset(self::$_instance) ) {
            self::$_instance = new self(); 
        }

        return self::$_instance;
    }

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
        array_print($results,0);

        foreach($results as &$c) {
            if( $c->player1_id == $user_id ) {
                $c->user_result = $c->player1_result;
                $c->opp_name = $c->name2;
                $c->opp_result = $c->player2_result;
                $c->opp_id = $c->player2_id;
            } else {
                $c->user_result = $c->player2_result;
                $c->opp_name = $c->name1;
                $c->opp_result = $c->player1_result;
                $c->opp_id = $c->player1_id;
            }
        }

        return $results;
    }

    public function add_challenge($challenger_id, $target_id, $ladder_id)
    {
        // TODO: Validate first!

        $this->insert( 
            array('player1_id'=>$challenger_id, 'player2_id'=>$target_id, 'ladder_id'=>$ladder_id) 
        );

        $ladder = Ladder::instance();
        $ladder->update_challenge_count($challenger_id, $ladder_id);
        $ladder->update_challenge_count($target_id, $ladder_id);
    }

    public function add_result($challenge_id, $player_id, $result)
    {
        $complete = false;

        $c = $this->get($challenge_id);
        array_print($c, 0);

        if($c->player1_id == $player_id) {
            $column = "player1_result";
            if( ($c->player2_result != Match::NO_RESULT && $c->player2_result != $result) || $result == Match::FORFEIT ) {
                $complete = true;
            }
        } elseif($c->player2_id == $player_id) {
            $column = "player2_result";
            if( ($c->player1_result != Match::NO_RESULT && $c->player1_result != $result) || $result == Match::FORFEIT ) {
                $complete = true;
            }
        } else {
            return;
        }
        
        $insert_id = null;
        /* If both results are in an make a valid match, create a match an delete the challenge */
        if( $complete ) {
            $c->$column = $result;
            array_print($c, 0);
            $insert_id = Match::instance()->add_match($c);
            $this->delete($c->id);
        } else {
            $data = array($column => $result);
            $this->update($challenge_id, $data);
        }

        return $insert_id;
    }

    public function challenged_ids($user_id, $ladder_id)
    {
        $challenges = $this->load_challenges($user_id, $ladder_id);

        $challenged_ids = array();
        foreach($challenges as $c) {
            array_push($challenged_ids, $c->opp_id);
        }

        return $challenged_ids;
    }

    public function total_challenges($user_id)
    {
        $this->db->select('COUNT(*)')
            ->from('Challenges c')
            ->where('c.player1_id', $user_id)
            ->or_where('c.player2_id', $user_id);

        $result = $this->db->get();
    }
}
