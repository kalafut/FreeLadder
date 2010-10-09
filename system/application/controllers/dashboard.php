<?php
class Dashboard extends Controller {
    public function __contruct() {
        parent::Controller();

        //$this->output->enable_profiler(TRUE);
    }

    public function index() {

        if(Current_User::user()) {
            $vars['content_view'] = 'dashboard';

            $vars['ladderRungs'] = $this->loadLadderData();
            $vars['challenges'] = $this->loadChallengeData();
        //$this->benchmark->mark('start');
            $this->load->view('template', $vars);
        //$this->benchmark->mark('end');
        } else {
            redirect('/login');
        }
        //echo $this->benchmark->elapsed_time('start', 'end');
    }

    public function ladderUpdate() {
        $vars['ladderRungs'] = $this->loadLadderData();
        $this->load->view('ladder', $vars);
    }

    private function loadChallengeData() {
        $ladder_id = Current_User::user()->Current_Ladder->id;
        $user_id = Current_User::user()->id;
        $q = Doctrine_Query::create()
            ->select('opp.id, opp.name, cu_user.id, cu_opp.id, cu_user.result')
            ->from('User opp')
            ->leftJoin('opp.Challenge_Users cu_opp')
            ->leftJoin('cu_opp.Challenge ch')
            ->leftJoin('ch.Challenge_Users cu_user')
            ->where('cu_user.user_id = ?', $user_id)
            ->andWhere('ch.ladder_id = ?', $ladder_id)
            ->andWhere('cu_user.challenge_id = cu_opp.challenge_id')
            ->andWhere('cu_user.id != cu_opp.id');

        //print_r($q->getSqlQuery());
        $results = $q->fetchArray();

    
        if(0) {
        echo "<pre>";
        print_r($results);
        echo "</pre>";
        }

        return $results;
    }

    private function loadLadderData() {
        $ladder_id = Current_User::user()->Current_Ladder->id;
        $q = Doctrine_Query::create()
            ->select('u.id, u.name, lu.rank, lu.wins, lu.losses')
            ->addSelect('COUNT(c1.id) AS challenge_count')
            ->from('User u')
            ->where('lu.ladder_id = ?', $ladder_id)
            ->leftJoin('u.Challenge_Users c1')
            ->leftJoin('u.Ladder_Users lu')
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

}
