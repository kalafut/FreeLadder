<?php

class Ladder extends MY_Model 
{
    private static $_instance;

    static public function instance()
    {
        if ( !isset(self::$_instance) ) {
            self::$_instance = new self(); 
        }

        return self::$_instance;
    }
    
    public static function current_ladder_name()
    {
        return self::instance()->_current_ladder_name();
    }

    public function _current_ladder_name()
    {
        $user = User::instance()->current_user();
        $q = $this->get_by('id', $user->ladder_id);

        return $q->name;
    }

    public function current_ladder_info()
    {
        $user = User::instance()->current_user();
        $q = $this->get_by('id', $user->ladder_id);

        return $q;
    }

    public function load_ladder($ladder_id)
    {
        $this->db->select('u.id, u.name, u.status, lu.rank, lu.wins, lu.losses, lu.challenge_count')
            ->from('users u')
            ->join('ladder_users lu', 'lu.user_id = u.id')
            ->where('lu.ladder_id', $ladder_id)
            ->order_by('lu.rank');

        $q = $this->db->get();

        return $q->result();
    }

    public function get_user_rank($user_id, $ladder_id)
    {
        $this->db->select('lu.rank')
            ->from('ladder_users lu')
            ->where('ladder_id', $ladder_id)
            ->where('user_id', $user_id);

        if( $r = $this->db->get()->row() ) {
            return $r->rank;
        } else {
            return null;
        }
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

    /**
     * Update the win/loss records for a given ladder.  By default
     * all users will be updated. Pass in an array of user ids as
     * the second parameter to limit the operation to just those ids
     */
    public function update_win_loss($ladder_id, $user_ids=null)
    {
        /* Get list of users if not provided */
        if( !isset($user_ids) ) {
            $this->db->select('lu.user_id')
                ->from('ladder_users lu')
                ->where('ladder_id', $ladder_id);
            $q = $this->db->get();

            $user_ids = array();

            array_print($q->result(), 0);
            foreach($q->result() as $row) {
                array_push($user_ids, $row->user_id);
            }
        }

        $data = array();
        foreach($user_ids as $user_id) {
            $this->db->from('matches m')
                ->where('winner_id', $user_id);

            $data['wins'] = $this->db->count_all_results();

            $this->db->from('matches m')
                ->where('loser_id', $user_id);

            $data['losses'] = $this->db->count_all_results();

            $this->db->where('ladder_id', $ladder_id)
                ->where('user_id', $user_id);

            $this->db->update('ladder_users', $data);
        }
    }

    public function set($user_id, $ladder_id, $data)
    {
        $this->db->where('ladder_id', $ladder_id)
            ->where('user_id', $user_id);
        
        $this->db->update('ladder_users', $data);
    }

    function update_rankings($player_id, $ladder_id, $new_rank)
    {
        $old_rank = $this->get_user_rank($player_id, $ladder_id);
        $this->set($player_id, $ladder_id, array('rank' => $new_rank));
        
        /* Add to rank history if the rank has changed.
         */

        if( !$old_rank || $old_rank != $new_rank ) {
            $this->db->insert('rank_history', array('user_id' => $player_id, 'ladder_id' => $ladder_id, 'rank'=>$new_rank, 'date'=>time()));
        }
    }

    function add_user($user_id, $ladder_id)
    {
        $q = $this->db->select_max('rank')
            ->from('ladder_users')
            ->where('ladder_id', $ladder_id);

        $result = $q->get()->row();

        $rank = $result->rank + 1;

        $this->db->insert('ladder_users', array('user_id' => $user_id, 'ladder_id' => $ladder_id, 'rank' => $rank )); 
        $this->db->insert('rank_history', array('user_id' => $user_id, 'ladder_id' => $ladder_id, 'rank' => $rank, 'date'=>time()));
    }
}
