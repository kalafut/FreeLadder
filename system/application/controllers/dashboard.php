<?php
class Dashboard extends Controller 
{
    private static $user;
    private static $user_id;
    private static $ladder_id;

    public function __construct() 
    {
        parent::Controller();
		$this->load->helper('form');
		$this->load->helper('util');
        $this->load->scaffolding('users');
        $this->load->model('User');
        $this->load->model('Challenge');
        $this->load->model('Ladder');
        $this->load->model('Match');

        /* Assign some convenience variables used everywhere */
        $this->user = User::instance()->current_user();
        if( !$this->user ) {
            redirect('/login');
        }
        $this->user_id = $this->user->id;
        $this->ladder_id = $this->user->ladder_id;
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
/*
    public function ladder_update() {
        $user = User::instance()->current_user();
        $vars['ladder'] = $this->load_ladder_data();
        $vars['user'] = $user;
        $vars['challenges'] = Challenge::instance()->load_challenges($user->id, $user->ladder_id);
        //$vars['challengedIds'] = $this->getChallengedIds($vars['challenges']);
        $this->load->view('ladder', $vars);
    }
 */
    private function load_challenge_data() {
        $c = Challenge::instance()->load_challenges($this->user_id, $this->ladder_id);
        return $c;
    }

    private function load_ladder_data() {
        $user = User::instance()->current_user();
        $user_id = $user->id;
        $ladder_id = $user->ladder_id;
        $results = Ladder::instance()->load_ladder($ladder_id);

        $challenged_ids = Challenge::instance()->challenged_ids($user_id, $ladder_id);

        $user_rank = Ladder::instance()->get_user_rank($user_id, $ladder_id);
        foreach($results as &$row) {
            //print_r($challenged_ids);

            if( $row->id == $user_id ||
                $row->rank > $user_rank ||
                in_array($row->id, $challenged_ids) ||
                $row->challenge_count >= User::instance()->max_challenges($row->id, $ladder_id)
                /* player is not active */
            ) {
                $row->can_challenge = false;
            } else {
                $row->can_challenge = true;
            }
        }
        
        array_print($results,0);

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
        }  
    }

    private function process_result($challenge_id, $result)
    {
        /* The challenge model will also create a match if both results have
         * been submitted and make a valid match. In this case, the id of the 
         * new match is returned
         */ 
        $insert_id = Challenge::instance()->add_result($challenge_id, $this->user_id, $result);

        echo "IID $insert_id";

        /* Update rankings if a match has been completed. */
        if( $insert_id ) {
            $match = Match::instance()->get_match_result($insert_id);
            array_print($match,1);
            $ladder = Ladder::instance()->load_ladder($this->ladder_id);

            /* Create version keyed by id */
            $ladder_by_id = key_array($ladder, "id");

            $winner = $match->winner_id;
            $loser = $match->loser_id;
            $winnerRank = $ladder_by_id[$winner]->rank;
            $loserRank = $ladder_by_id[$loser]->rank;

            if( $winnerRank > $loserRank) {
                // Adjust rankings
                foreach($ladder as $player) {
                    if($player->id == $winner) {
                        Ladder::instance()->set($winner, $this->ladder_id, array('rank' => $loserRank));
                    } elseif ($player->rank >= $loserRank && $player->rank < $winnerRank) {
                        Ladder::instance()->set($player->id, $this->ladder_id, array('rank' => $player->rank + 1));
                    }
                }
            }
        }
    }
    
    private function process_challenge()
    {
        $user = User::instance()->current_user();

        if( !$user ) {
            redirect('/login');
        }

        $user_id = $user->id;
        $target_id = $this->input->post('param');
        $ladder_id = $user->ladder_id;

        if($target_id) {
            $c = new Challenge();
            $c->add_challenge($user_id, $target_id, $ladder_id);
        }
    }

    public function generateJSONTables() {
        $user = User::instance()->current_user();
        $vars['user'] = $user;

        $vars['ladder'] = $this->load_ladder_data();
        $vars['challenges'] = Challenge::instance()->load_challenges($user->id, $user->ladder_id);
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

        $arr = array("ladder"=>$ladder, "challenges"=>$challenges, "matches"=>$matches);

        echo json_encode($arr);
    }

/*
    private function getChallengedIds($challengeData)
    {
        $result = array();

        foreach($challengeData as $c) {
            array_push($result, $c['opp_id']);
        }

        return $result;
    }

 */
}
