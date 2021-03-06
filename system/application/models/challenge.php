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
        /* First check whether this challenge already exists */
        $sql = "SELECT id FROM challenges WHERE ladder_id = ? AND ((player1_id = ? AND player2_id = ?)
            OR (player1_id = ? AND player2_id = ?))";

        $query = $this->db->query($sql, array($ladder_id, $challenger_id, $target_id, $target_id, $challenger_id));

        if(count($query->result()) == 0) {
            $this->insert(
                array('player1_id'=>$challenger_id, 'player2_id'=>$target_id, 'ladder_id'=>$ladder_id, 'created_at'=>time(), 'updated_at'=>time())
            );

            $ladder = Ladder::instance();
            $ladder->update_challenge_count($challenger_id, $ladder_id);
            $ladder->update_challenge_count($target_id, $ladder_id);

            /* Send email to challengee if they want to receive them */
            $challengee = User::instance()->get($target_id);

            if($challengee->notifications == 1) {
                $challenger = User::instance()->get($challenger_id);

                mail($challengee->email, "New FreeLadder challenge",
                    "$challenger->name has challenged you to a match.  Coordinate with them to schedule the match, and don't forget to enter your results afterwards.\n\n\n\n(You can disable these notifications in your user settings: " . site_url("/settings") . ")", "From: no-reply@freeladder.org");
            }
        }
    }

    public function add_result($challenge_id, $player_id, $result)
    {
        $complete = false;

        $c = $this->get($challenge_id);
        $ladder_id = $c->ladder_id;
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
            $ladder->update_challenge_count($c->player1_id, $ladder_id);
            $ladder->update_challenge_count($c->player2_id, $ladder_id);
        } else {
            $data = array($column => $result, "updated_at" => time());
            $this->update($challenge_id, $data);
        }


        /* Update rankings if a match has been completed. */
        if( $insert_id ) {
            //print ($this->ladder_id);
            $match = Match::instance()->get_match_result($insert_id);
            array_print($match,0);
            $ladder = Ladder::instance()->load_ladder($ladder_id);

            /* Create version keyed by id */
            $ladder_by_id = key_array($ladder, "id");

            $winner = $match->winner_id;
            $loser = $match->loser_id;
            $winnerRank = $ladder_by_id[$winner]->rank;
            $loserRank = $ladder_by_id[$loser]->rank;
            $user_rank = $ladder_by_id[$player_id]->rank;
            $lowest_ranking = Ladder::instance()->lowest_ranking($ladder_id);

            /*
             * Handle the case of two unranked users
             *
             * Update: this shouldn't normally occur but we're leaving it just in
             * case (e.g. database or code change ends up leaving two ranked players
             * in a challenge)
             */
            if( $winnerRank == Ladder::UNRANKED && $loserRank == Ladder::UNRANKED ) {
                Ladder::instance()->update_rankings($winner, $ladder_id, $lowest_ranking + 1);
                Ladder::instance()->update_rankings($loser, $ladder_id, $lowest_ranking + 2);
            }

            /*
             * If an unranked player loses to a ranked player, they move to
             * the bottom of the ranked list.
             */
            elseif( $loserRank == Ladder::UNRANKED ) {
                Ladder::instance()->update_rankings($loser, $ladder_id, $lowest_ranking + 1);
            }

            /*
             * Adjust rankings if the winner had a higher numbers (i.e. worse)
             * ranking than the loser.
             */
            elseif( $winnerRank > $loserRank) {
                /* Start at the top of the ladder */
                foreach($ladder as $player) {
                    /* Update the winner's ranking */
                    if($player->id == $winner) {
                        Ladder::instance()->update_rankings($winner, $ladder_id, $loserRank);

                    /* Update everyone's rank between the winner and loser (including the loser) */
                    } elseif ( $player->rank != Ladder::UNRANKED &&
                        $player->rank >= $loserRank && $player->rank < $winnerRank) {
                        Ladder::instance()->update_rankings($player->id, $ladder_id, $player->rank + 1);
                    }
                }
            }
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
        // If a challenge timeout is set, delete challenges that haven't been updated
        // within the timeout window.

        $timeout = $this->db->get_where('ladders', array('id' => $ladder_id))->row()->challenge_timeout;

        if($timeout > 0) {
            // Delete old challenges with no results or those with disputed matches
            $sql =  "DELETE challenges FROM challenges
                     WHERE ladder_id = ?
                     AND ( (created_at + ? < ? AND player1_result = 0 AND player2_result = 0)
                       OR  (updated_at + ? < ? AND player1_result = player2_result) ) ";
            $this->db->query($sql, array( $ladder_id, $timeout, time(), $timeout, time() ));

            // Record matches with only one result that are either too old
            // or one (or both) players are not longer active.
            $sql =  "SELECT challenges.id, player1_id, player2_id, player1_result, player2_result FROM challenges
                     INNER JOIN users u1 ON challenges.player1_id = u1.id
                     INNER JOIN users u2 ON challenges.player2_id = u2.id
                     WHERE challenges.ladder_id = ?
                     AND (player1_result = 0 OR player2_result = 0)
                     AND ( (updated_at + ? < ?)
                        OR (u1.status != ? OR u2.status != ?) )";
            $query = $this->db->query($sql, array( $ladder_id, $timeout, time(), User::ACTIVE, User::ACTIVE ));

            foreach($query->result() as $row) {
                if($row->player1_result == 0) {
                    $player_id = $row->player1_id;
                    $result = -1 * $row->player2_result;
                } else {
                    $player_id = $row->player2_id;
                    $result = -1 * $row->player1_result;
                }
                $this->add_result($row->id, $player_id, $result);
            }
        }

        // Delete challenges involving inactive or retired players
        // that weren't recorded as matches above.
        $sql =  "DELETE challenges FROM challenges
                 INNER JOIN users u1 ON challenges.player1_id = u1.id
                 INNER JOIN users u2 ON challenges.player2_id = u2.id
                 WHERE challenges.ladder_id = ? AND (u1.status != ? OR u2.status != ?)";

        $this->db->query($sql, array($ladder_id, User::ACTIVE, User::ACTIVE));

        Ladder::instance()->update_all_challenge_counts($ladder_id);
    }

}
