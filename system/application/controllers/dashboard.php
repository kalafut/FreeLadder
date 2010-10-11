<?php
class Dashboard extends Controller {

    public function __construct() {
        parent::Controller();
		$this->load->helper('form');
    }

    public function index() {

        if(Current_User::user()) {
            $vars['content_view'] = 'dashboard';

            $vars['challenges'] = $this->loadChallengeData();
            $vars['ladderRungs'] = $this->loadLadderData();
            $vars['challengedIds'] = $this->getChallengedIds($vars['challenges']);

        //$this->benchmark->mark('start');
            $this->load->view('template', $vars);
        //$this->benchmark->mark('end');
        } else {
            redirect('/login');
        }
        //echo $this->benchmark->elapsed_time('start', 'end');
    }

    public function ladderUpdate() {
        $vars['challenges'] = $this->loadChallengeData();
        $vars['ladderRungs'] = $this->loadLadderData();
        $vars['challengedIds'] = $this->getChallengedIds($vars['challenges']);
        $this->load->view('ladder', $vars);
    }


    private function loadLadderData() {
        $ladder_id = Current_User::user()->Current_Ladder->id;
        $q = Doctrine_Query::create()
            ->select('u.id, u.name, lu.rank, lu.wins, lu.losses, lu.challenge_count')
            ->from('User u')
            ->leftJoin('u.Ladder_Users lu')
            ->where('lu.ladder_id = ?', $ladder_id)
            ->groupBy('u.id')
            ->orderBy('lu.rank');

        //print_r($q->getSqlQuery());
        $results = $q->fetchArray();


        if(0) {
        echo "<pre>";
        print_r($results);
        echo "</pre>";
        }

        return $results;
    }

    private function loadChallengeData() {
        $ladder_id = Current_User::user()->Current_Ladder->id;
        $user_id = Current_User::user()->id;

        $q = Doctrine_Query::create()
            ->select('c.*, u1.name, u2.name')
            ->from('Challenge c')
            ->leftJoin('c.Player1 u1')
            ->leftJoin('c.Player2 u2')
            ->where('c.ladder_id = ?', $ladder_id)
            ->andWhere('c.player1_id = ? OR c.player2_id = ?', array($user_id, $user_id)) ;

        //print_r($q->getSqlQuery());
        $results = $q->fetchArray();

        foreach($results as &$c) {
            if( $c['player1_id'] == $user_id ) {
                $c['user_result'] = $c['player1_result'];
                $c['opp_name'] = $c['Player2']['name'];
                $c['opp_result'] = $c['player2_result'];
                $c['opp_id'] = $c['player2_id'];
            } else {
                $c['user_result'] = $c['player2_result'];
                $c['opp_name'] = $c['Player1']['name'];
                $c['opp_result'] = $c['player1_result'];
                $c['opp_id'] = $c['player1_id'];
            }
        }


        if(0) {
        echo "<pre>";
        print_r($results);
        echo "</pre>";
        }

        return $results;
    }

    private function getChallengedIds($challengeData)
    {
        $result = array();

        foreach($challengeData as $c) {
            array_push($result, $c['opp_id']);
        }

        return $result;
    }

    function submit()
    {
        if( $this->input->post('action')=='challenge' ) {
           $this->processChallenge(); 
        } 
    }
    
    private function processChallenge()
    {
        if(!Current_User::user()) { redirect('/login'); }

        $target = $this->input->post('param');
        $user_id = Current_User::user()->id;
        $ladder_id = Current_User::user()->Current_Ladder->id;

        if($target) {
            $c = new Challenge();
            $c->player1_id = $user_id;
            $c->player2_id = $target;
            $c->ladder_id  = $ladder_id;
            $c->save();

            $this->updateChallengeCount($user_id, $ladder_id);
            $this->updateChallengeCount($target, $ladder_id);
        }
    }

    private function updateChallengeCount($user_id, $ladder_id)
    {
        $q = Doctrine_Query::create()
            ->select('c.id')
            ->from('Challenge c')
            ->where('c.ladder_id = ?', $ladder_id)
            ->andWhere('c.player1_id = ? OR c.player2_id = ?', array($user_id, $user_id));

        $count = $q->count();

        $q = Doctrine_Query::create()
            ->select('lu.*')
            ->from('Ladder_User lu')
            ->where('lu.ladder_id = ?', $ladder_id)
            ->andWhere('lu.user_id = ?', $user_id);

        $lu = $q->fetchOne();

        if($lu) {
            $lu->challenge_count = $count;
            $lu->save();
        }
    }
}
