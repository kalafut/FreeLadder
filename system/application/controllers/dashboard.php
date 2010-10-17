<?php
class Dashboard extends Controller 
{
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
    }

    public function index($mode=null) 
    {
        $user = User::instance()->current_user();

        if( !$user ) {
            redirect('/login');
        }

        $vars['content_view'] = 'dashboard';
        $vars['user'] = $user;

        $vars['ladder'] = $this->load_ladder_data();
        $vars['challenges'] = Challenge::instance()->load_challenges($user->id, $user->ladder_id);
        $vars['matches'] = Match::instance()->load_matches($user->ladder_id, 5);

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

    public function ladder_update() {
        $user = User::instance()->current_user();
        $vars['ladder'] = $this->load_ladder_data();
        $vars['user'] = $user;
        $vars['challenges'] = Challenge::instance()->load_challenges($user->id, $user->ladder_id);
        //$vars['challengedIds'] = $this->getChallengedIds($vars['challenges']);
        $this->load->view('ladder', $vars);
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
    /*    $user = $this->uModel->current_user();
        $ladder_id = $user->ladder;
        $q = Doctrine_Query::create()
            ->select('u.id, u.name, lu.rank, lu.wins, lu.losses, lu.challenge_count')
            ->from('User u')
            ->leftJoin('u.Ladder_Users lu')
            ->where('lu.ladder_id = ?', $ladder_id)
            ->groupBy('u.id')
            ->orderBy('lu.rank');
  
        $results = $q->fetchArray();


        if(0) {
        echo "<pre>";
        print_r($results);
        echo "</pre>";
        }
     */

        return $results;
    }

    public function submit()
    {
        if( $this->input->post('action')=='challenge' ) {
           $this->processChallenge(); 
        } elseif ($this->input->post('action')=='won') {
            $this->process_result($this->input->post('param'), Match::WON);
        } elseif ($this->input->post('action')=='lost') {
            $this->process_result($this->input->post('param'), Match::LOST);
        } elseif ($this->input->post('action')=='forfeit') {
            $this->process_result($this->input->post('param'), Match::FORFEIT);
        }  
    }

    private function process_result($id, $result)
    {
        Challenge::instance()->add_result($id, User::instance()->current_user()->id, $result);
    //    Match::instance()->add_match(
    }
    
    private function processChallenge()
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
