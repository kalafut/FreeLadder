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

class Challenge extends MY_Model 
{
    const STATUS_NORMAL  = 0;
    const STATUS_WAITING = 1;
    const STATUS_REVIEW  = 2;

    private static $_instance;
    private static $user;
    private static $user_id;
    private static $ladder_id;

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
            ->from('challenges c')
            ->join('users u1', 'u1.id = c.player1_id')
            ->join('users u2', 'u2.id = c.player2_id')
            ->where('c.ladder_id', $ladder_id)
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
                $c->user_challenge = TRUE;
            } elseif( $c->player2_id == $user_id ) {
                $c->user_result = $c->player2_result;
                $c->opp_name = $c->name1;
                $c->opp_result = $c->player1_result;
                $c->opp_id = $c->player1_id;
                $c->user_challenge = TRUE;
            } else {
                $c->user_challenge = FALSE;
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

            $ladder = Ladder::instance();
            $user = User::instance()->current_user();
            $ladder->update_challenge_count($c->player1_id, $user->ladder_id);
            $ladder->update_challenge_count($c->player2_id, $user->ladder_id);
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
            if($c->user_challenge) {
                array_push($challenged_ids, $c->opp_id);
            }
        }

        return $challenged_ids;
    }

    public function total_challenges($user_id)
    {
        $this->db->select('COUNT(*)')
            ->from('challenges c')
            ->where('c.player1_id', $user_id)
            ->or_where('c.player2_id', $user_id);

        $result = $this->db->get();
    }

    public function delete_challenges($user_id, $ladder_id)
    {
        $this->db->where('player1_id', $user_id)
            ->or_where('player2_id', $user_id);

        $this->db->delete('challenges');
        Ladder::instance()->update_challenge_count($user_id, $ladder_id);
    }

    /* Delete challenges by invalid or disabled users */
    public function cleanup_challenges($ladder_id)
    {
        $sql =  "DELETE challenges FROM challenges
                 INNER JOIN users u1 ON challenges.player1_id = u1.id
                 INNER JOIN users u2 ON challenges.player2_id = u2.id
                 WHERE challenges.ladder_id = ? AND (u1.status = 2 OR u2.status = 2)";
        
        $this->db->query($sql, array($ladder_id));
    }

}
