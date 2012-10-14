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

class Ladder extends MY_Model
{
    const UNRANKED = 9999999;

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

    public function load_ladder($ladder_id, $skip_cleanup = false, $include_disabled = false)
    {
        if(!$skip_cleanup) {
            $this->ladder_cleanup($ladder_id);
        }

        if($include_disabled) {
            $status_test = 1000; // Include everything
        } else {
            $status_test = User::DISABLED;
        }

        $this->db->select('u.id, u.name, u.status, lu.rank, lu.wins, lu.losses, lu.challenge_count')
            ->from('users u')
            ->join('ladder_users lu', 'lu.user_id = u.id')
            ->where('lu.ladder_id', $ladder_id)
            ->where('u.status !=', $status_test)
            ->order_by('lu.rank');

        $q = $this->db->get();

        return $q->result();
    }

    public function get_user_rank($user_id, $ladder_id)
    {
        $r = $this->get_user($user_id, $ladder_id);

        if( $r ) {
            return $r->rank;
        } else {
            return null;
        }
    }

    public function get_user($user_id, $ladder_id)
    {
        $this->db->select('*')
            ->from('ladder_users lu')
            ->where('ladder_id', $ladder_id)
            ->where('user_id', $user_id);

        if( $r = $this->db->get()->row() ) {
            return $r;
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

    public function update_all_challenge_counts($ladder_id)
    {
        $this->db->select('user_id')
            ->from('ladder_users')
            ->where('ladder_id', $ladder_id);

        foreach($this->db->get()->result() as $row) {
            $this->update_challenge_count($row->user_id, $ladder_id);
        }
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

        /* Add to rank history if the rank has changed or doesn't exist. */
        if( !$old_rank || $old_rank != $new_rank ) {
            $this->db->insert('rank_history', array('user_id' => $player_id, 'ladder_id' => $ladder_id, 'rank'=>$new_rank, 'date'=>time()));
        }
    }

    function add_user($user_id, $ladder_id)
    {
        $q = $this->db->select_max('rank')
            ->from('ladder_users')
            ->where('ladder_id', $ladder_id);

        //$result = $q->get()->row();

        //$rank = $result->rank + 1;
        $rank = self::UNRANKED;

        $this->db->insert('ladder_users', array('user_id' => $user_id, 'ladder_id' => $ladder_id, 'rank' => $rank ));
        //$this->db->insert('rank_history', array('user_id' => $user_id, 'ladder_id' => $ladder_id, 'rank' => $rank, 'date'=>time()));
    }

    public function lowest_ranking($ladder_id)
    {
        $q = $this->db->select_max('rank')
            ->from('ladder_users')
            ->where('ladder_id', $ladder_id)
            ->where('rank !=', self::UNRANKED);

        $result = $q->get()->row();

        return $result->rank;
    }


    /*
        - Set disabled user ranks to UNRANKED
        - Delete pending matches for disabled users
        - Set users to inactive who:
           - haven't completed any match within one month
           - aren't part of any challenge
           - haven't reactivated themselves within one month
         - Fix entire ranking
           - Newly UNRANKED players go to the bottom (and everyone below them goes up)
           - A newly INACTIVE player in the first place is replaced by the first ACTIVE player
    */
    public function ladder_cleanup($ladder_id)
    {
        $found_active = false;

        // $q = $this->db->select('u.id, lu.rank')
        //     ->from('users u')
        //     ->join('ladder_users lu', 'lu.user_id = u.id')
        //     ->where('lu.ladder_id', $ladder_id)
        //     ->where('u.status', User::DISABLED);

        // $q = $this->db->get();
        // $disabled_users = $q->result();

        // $new_rank = array();
        // $old_rank = array();

        // foreach($disabled_users as &$du) {
        //     // update rank
        //     if($du->rank != Ladder::UNRANKED) {
        //         $this->update_rankings($du->id, $ladder_id, Ladder::UNRANKED);
        //     }
        // }
// new
        $ladder = Ladder::instance()->load_ladder($ladder_id, true, true);

        if($ladder[0]->status == User::INACTIVE) {
            $demote_top = true;
        }

        $rank = 1;
        foreach($ladder as $player) {
            $old_rank[$player->id] = $player->rank;

            if($player->status == User::DISABLED || $player->rank == Ladder::UNRANKED) {
                // change player to unranked and move everyone else up.
                $new_rank[$player->id] = Ladder::UNRANKED;
            } else {
                // The top stop cannot be inactive (unless there are no active
                // player). Set new rank to rank + 1 in preparation for putting
                // the first active player into the top spot.
                if(!$found_active) {
                    if($player->status == User::INACTIVE) {
                        $new_rank[$player->id] = $rank + 1;
                    } else {
                        $new_rank[$player->id] = 1;
                        $found_active = true;
                    }
                } else {
                    $new_rank[$player->id] = $rank;
                }
                $rank++;
            }
        }

        foreach($new_rank as $id => $newrank) {
            // If we found no active players, shift them all up one since
            // the previous block would have left the ranks at 2..n.
            if(!$found_active) {
                $newrank = $newrank - 1;
            }
            if($old_rank[$id] != $newrank) {
                Ladder::instance()->update_rankings($id, $ladder_id, $newrank);
            }
        }

        User::instance()->inactivate_idle($ladder_id);
        Challenge::instance()->cleanup_challenges($ladder_id);
    }
}
