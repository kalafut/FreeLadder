<?php
class Dashboard extends Controller 
{
    static private $uModel;
    static private $cModel;
    static private $lModel;

    public function __construct() 
    {
        parent::Controller();
		$this->load->helper('form');
		$this->load->helper('util');
        $this->load->scaffolding('users');
        $this->load->model('User');
        $this->load->model('Challenge');
        $this->load->model('Ladder');

        $this->uModel = new User();
        $this->cModel = new Challenge();
        $this->lModel = new Ladder();
    }

    public function index() 
    {
        $user = $this->uModel->current_user();

        if( !$user ) {
            redirect('/login');
        }
        
        $vars['content_view'] = 'dashboard';
        $vars['user'] = $user;

        $vars['challenges'] = $this->load_challenge_data($user->id, $user->ladder_id);
        $vars['ladder'] = $this->load_ladder_data();
        $vars['challengedIds'] = array();//$this->getChallengedIds($vars['challenges']);

        $this->load->view('template', $vars);
    }

    public function logout() 
    {
        User::logout();
    }
        
    private function load_challenge_data($user_id, $ladder_id) 
    {
        $results = $this->cModel->load_challenges($user_id, $ladder_id);

        foreach($results as &$c) {
            if( $c->player1_id == $user_id ) {
                $c->user_result = $c->player1_result;
                $c->opp_name = $c->name2;
                $c->opp_result = $c->player2_result;
                $c->opp_id = $c->player2_id;
            } else {
                $c->user_result = $c->player2_result;
                $c->opp_name = $c->name1;
                $c->opp_result = $c->player1_result;
                $c->opp_id = $c->player1_id;
            }
        }

        array_print($results, 0);

        return $results;
    }

    public function ladder_update() {
        $user = $this->uModel->current_user();
        $vars['ladder'] = $this->load_ladder_data();
        $vars['user'] = $user;
        $vars['challenges'] = $this->load_challenge_data($user->id, $user->ladder_id);
        //$vars['challengedIds'] = $this->getChallengedIds($vars['challenges']);
        $this->load->view('ladder', $vars);
    }

    private function load_ladder_data() {
        $user = $this->uModel->current_user();
        $results = $this->lModel->load_ladder($user->ladder_id);
        
        if(0) {
        echo "<pre>";
        print_r($results);
        echo "</pre>";
        }
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
        } 
    }
    
    private function processChallenge()
    {
        $user = $this->uModel->current_user();

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
