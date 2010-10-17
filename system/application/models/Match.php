<?php

class Match extends MY_Model
{
    const WON  = 1;
    const LOST = -1;
    const FORFEIT = -2;
    const NO_RESULT = 0;
    private static $_instance;

    static public function instance()
    {
        if ( !isset(self::$_instance) ) {
            self::$_instance = new self(); 
        }

        return self::$_instance;
    }

    public function __construct()
    {
        parent::__construct();
        $this->_table = "matches";
    }

    public function load_matches($ladder_id, $limit = -1)
    {
        $this->db->select('m.*, UNIX_TIMESTAMP(m.date) as date, uw.name as winner_name, ul.name as loser_name')
            ->from('matches m')
            ->join('Users uw','uw.id = m.winner_id')
            ->join('Users ul','ul.id = m.loser_id')
            ->where('m.ladder_id', $ladder_id)
            ->order_by('date desc');

        if($limit != -1) {
            $this->db->limit($limit);
        }

        $results = $this->db->get()->result();

        array_print($results,0);
        return $results;
    }

    public function add_match($challenge)
    {
        $data = array(
            'ladder_id' => $challenge->ladder_id,
            'date' => date('YmdHis'),
        );

        if($challenge->player1_result == Match::WON) {
            $data['winner_id'] = $challenge->player1_id;
            $data['loser_id'] = $challenge->player2_id;
            if($challenge->player2_result == MATCH::FORFEIT) {
                $data['forfeit'] = 1;
            }
        } else {
            $data['winner_id'] = $challenge->player2_id;
            $data['loser_id'] = $challenge->player1_id;
            if($challenge->player1_result == MATCH::FORFEIT) {
                $data['forfeit'] = 1;
            }
        }

        $insert_id = $this->insert( $data );

        Ladder::instance()->update_win_loss($challenge->ladder_id, array($challenge->player1_id, $challenge->player2_id));

        return $insert_id;
    }

    public function get_match_result($id)
    {
        return $this->get($id);
    }
}
    
