<?php
/*
    FreeLadder
    Copyright (C) 2010  Jim Kalafut

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
        $this->load->helper('glicko');

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

        $this->update_ratings($insert_id);

        return $insert_id;
    }

    public function update_ratings($match_id) {
        $match = $this->get($match_id);

        if(intval($match->forfeit) != 1) {
            // update ratings
            $winner = Ladder::instance()->get_user($match->winner_id, $match->ladder_id);
            $loser = Ladder::instance()->get_user($match->loser_id, $match->ladder_id);

            $winner_glicko = new Glicko2Player($winner->rating, $winner->rd);
            $loser_glicko = new Glicko2Player($loser->rating, $loser->rd);

            $winner_glicko->AddWin($loser_glicko);
            $loser_glicko->AddLoss($winner_glicko);
            $winner_glicko->Update();
            $loser_glicko->Update();

            // Update current ratings
            $sql = "UPDATE ladder_users SET rating = ?, rd = ? WHERE id = ?";
            $this->db->query($sql, array($winner_glicko->rating, $winner_glicko->rd, $winner->id));
            $sql = "UPDATE ladder_users SET rating = ?, rd = ? WHERE id = ?";
            $this->db->query($sql, array($loser_glicko->rating, $loser_glicko->rd, $loser->id));

            // Update ratings history
            $now = time();
            $sql = "INSERT INTO rating_history(ladder_user_id, rating, date) VALUES(?,?,?)";
            $this->db->query($sql, array($winner->id, $winner_glicko->rating, $match->date));
            $this->db->query($sql, array($loser->id, $loser_glicko->rating, $match->date));
        }
    }

    public function get_match_result($id)
    {
        return $this->get($id);
    }
}

