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
        $this->db->select('m.*, m.date as date, uw.name as winner_name, ul.name as loser_name')
            ->from('matches m')
            ->join('users uw','uw.id = m.winner_id')
            ->join('users ul','ul.id = m.loser_id')
            ->where('m.ladder_id', $ladder_id)
            ->order_by('date desc');

        if($limit != -1) {
            $this->db->limit($limit);
        }

        $results = $this->db->get()->result();

        array_print($results,0);
        return $results;
    }

    public function matches_by_user($user_id, $ladder_id)
    {
        $this->db->select('m.winner_id, w.name AS winner_name, m.loser_id, l.name AS loser_name, m.date AS date, 
            m.forfeit')
            ->from('matches m')
            ->join('users w', 'w.id = m.winner_id')
            ->join('users l', 'l.id = m.loser_id')
            ->where('m.ladder_id', $ladder_id)
            ->where('m.winner_id', $user_id)
            ->or_where('m.loser_id', $user_id)
            ->order_by('date DESC');

        $results = $this->db->get()->result();

        array_print($results,0);
        return $results;
    }
    
    public function add_match($challenge)
    {
        $p1r = $challenge->player1_result;
        $p2r = $challenge->player2_result;

        $data = array(
            'ladder_id' => $challenge->ladder_id,
            'date' => time(),
        );

        /* Forfeits trump losses */
        if( $p1r == MATCH::FORFEIT ) {
            $data['winner_id'] = $challenge->player2_id;
            $data['loser_id'] = $challenge->player1_id;
            $data['forfeit'] = 1;
         } elseif( $p2r == MATCH::FORFEIT ) {
            $data['winner_id'] = $challenge->player1_id;
            $data['loser_id'] = $challenge->player2_id;
            $data['forfeit'] = 1;
         } elseif( $p1r == Match::WON && $p2r == Match::LOST ) {
            $data['winner_id'] = $challenge->player1_id;
            $data['loser_id'] = $challenge->player2_id;
         } elseif( $p2r == Match::WON && $p1r == Match::LOST ) {
            $data['winner_id'] = $challenge->player2_id;
            $data['loser_id'] = $challenge->player1_id;
         } else {
             return null;
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
    
