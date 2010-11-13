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

class Dashboard extends Controller 
{
    private static $user;
    private static $user_id;
    private static $ladder_id;
    private $refresh_files = array('/css/ladder.css', '/js/ladder.js'); 

    public function __construct() 
    {
        parent::Controller();
		$this->load->helper('form');
		$this->load->helper('util');
		$this->load->helper('html');
        $this->load->model('User');
        $this->load->model('Challenge');
        $this->load->model('Ladder');
        $this->load->model('Match');

        if( $this->uri->segment(2) != 'm' ) { 

        /* Assign some convenience variables used everywhere */
        $this->user = User::instance()->current_user();
        if( !$this->user ) {
            redirect('/login');
        }
        $this->user_id = $this->user->id;
        $this->ladder_id = $this->user->ladder_id;
        }
    }

    public function index($mode=null) 
    {
        // TODO Remove this when it has a real home 
        Ladder::instance()->update_win_loss( $this->ladder_id );

        $vars['content_view'] = 'dashboard';
        $vars['user'] = $this->user;

        $vars['ladder'] = $this->load_ladder_data();
        $vars['challenges'] = $this->load_challenge_data();
        $vars['matches'] = Match::instance()->load_matches($this->ladder_id, 5);

        $this->load->view('template', $vars);
    }

    public function json()
    {
        $this->generateJSONTables();
    }

    public function logout() 
    {
        User::logout();
    }

    private function load_challenge_data() {
        $challenges = Challenge::instance()->load_challenges($this->user_id, $this->ladder_id);
        foreach($challenges as &$c) {
            if($c->user_challenge) {
                $c->mode = Challenge::STATUS_NORMAL;
                if( $c->user_result != Match::NO_RESULT && $c->opp_result == Match::NO_RESULT ) {
                    $c->mode = Challenge::STATUS_WAITING;
                } elseif ( ($c->user_result != Match::NO_RESULT) && ($c->user_result == $c->opp_result) ) {
                    $c->mode = Challenge::STATUS_REVIEW;
                }
            }
          
        } 
        array_print($challenges, 0);

        return $challenges;
    }

    private function load_ladder_data() {
        $user = User::instance()->current_user();
        $user_id = $user->id;
        $ladder_id = $user->ladder_id;
        $results = Ladder::instance()->load_ladder($ladder_id);

        $challenged_ids = Challenge::instance()->challenged_ids($user_id, $ladder_id);

        $user_rank = Ladder::instance()->get_user_rank($user_id, $ladder_id);
        $challenge_window = Ladder::instance()->current_ladder_info()->challenge_window;

        for( $i = count($results)-1, $challenge_count = 0; $i >= 0; $i-- ) {
            $row = &$results[$i];
            if( $user->status != User::ACTIVE || // Inactive users can't challenge others
                $row->id == $user_id ||
                $row->rank >= $user_rank ||
                $challenge_count >= $challenge_window ||
                in_array($row->id, $challenged_ids) ||
                $row->challenge_count >= User::instance()->max_challenges($row->id, $ladder_id) ||
                $row->status != User::ACTIVE
            ) {
                $row->can_challenge = false;
                if( in_array($row->id, $challenged_ids) && $row->rank < $user_rank) {
                    $challenge_count++;
                }
            } else {
                $row->can_challenge = true;
                $challenge_count++;
            }
        }
        
        return $results;
    }

    public function submit()
    {
        if( $this->input->post('action')=='challenge' ) {
           $this->process_challenge(); 
        } elseif ($this->input->post('action')=='won') {
            $this->process_result($this->input->post('param'), Match::WON);
        } elseif ($this->input->post('action')=='lost') {
            $this->process_result($this->input->post('param'), Match::LOST);
        } elseif ($this->input->post('action')=='forfeit') {
            $this->process_result($this->input->post('param'), Match::FORFEIT);
        } elseif ($this->input->post('action')=='flip') {
            $challenge_id = $this->input->post('param');
            $c = Challenge::instance()->get($challenge_id);
            $result = ($c->player1_result == Match::WON) ? Match::LOST : Match::WON;
            $this->process_result($challenge_id, $result);
        }  
    }

    private function process_result($challenge_id, $result)
    {
        /* The challenge model will also create a match if both results have
         * been submitted and make a valid match. In this case, the id of the 
         * new match is returned
         */ 
        $insert_id = Challenge::instance()->add_result($challenge_id, $this->user_id, $result);

        /* Update rankings if a match has been completed. */
        if( $insert_id ) {
            $match = Match::instance()->get_match_result($insert_id);
            array_print($match,0);
            $ladder = Ladder::instance()->load_ladder($this->ladder_id);

            /* Create version keyed by id */
            $ladder_by_id = key_array($ladder, "id");

            $winner = $match->winner_id;
            $loser = $match->loser_id;
            $winnerRank = $ladder_by_id[$winner]->rank;
            $loserRank = $ladder_by_id[$loser]->rank;

            /* 
             * Adjust rankings if the winner had a higher numbers (i.e. worse)
             * ranking than the loser
             */
            if( $winnerRank > $loserRank) {
                foreach($ladder as $player) {
                    if($player->id == $winner) {
                        Ladder::instance()->update_rankings($winner, $this->ladder_id, $loserRank);
                    } elseif ($player->rank >= $loserRank && $player->rank < $winnerRank) {
                        Ladder::instance()->update_rankings($player->id, $this->ladder_id, $player->rank + 1);
                    }
                }
            }
        }
    }
    
    private function process_challenge()
    {
        $user = User::instance()->current_user();
        $user_id = $user->id;
        $target_id = $this->input->post('param');
        $ladder_id = $user->ladder_id;

        if($target_id) {
            $c = new Challenge();
            $c->add_challenge($user_id, $target_id, $ladder_id);
        }
    }

    private function generateJSONTables() {
        $user = User::instance()->current_user();
        $vars['user'] = $user;

        $vars['ladder'] = $this->load_ladder_data();
        $vars['challenges'] = $this->load_challenge_data();
        $vars['matches'] = Match::instance()->load_matches($user->ladder_id, 5);

        ob_start();
        $this->load->view('ladder',$vars);
        $ladder=ob_get_clean();

        ob_start();
        $this->load->view('challenges',$vars);
        $challenges=ob_get_clean();


        ob_start();
        $this->load->view('matches',$vars);
        $matches=ob_get_clean();

        $refresh_time = latest_mtime( $this->refresh_files );
        $arr = array("ladder"=>$ladder, "challenges"=>$challenges, "matches"=>$matches, "refresh_time"=>$refresh_time);

        echo json_encode($arr);
    }

    public function m($rte)
    {
        $out = NULL;
        User::instance()->set_test_user();

        if( $rte=='ladder' ) {
            $ladder = $this->load_ladder_data();
            $out = array();

            foreach($ladder as $row) {
                $o = array('rank'=>$row->rank, 'name' => substr($row->name,0,strpos($row->name,' ')));
                array_push($out, $o);
            }
            $json_out = json_encode($out);
        }

        echo $json_out;
    }

}
