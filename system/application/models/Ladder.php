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
            ->order_by('lu.rank');

        $q = $this->db->get();

        return $q->result();
    }

    public function get_user_rank($user_id, $ladder_id)
    {
        $ladder = $this->load_ladder($ladder_id);

        foreach($ladder as $row) {
            if( $row->id == $user_id ) {
                return $row->rank;
            }
        }

        return 0;
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
}
