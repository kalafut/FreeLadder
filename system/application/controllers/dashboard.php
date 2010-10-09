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
            ->select('u.id, u.name')
            ->from('User u')
            ->leftJoin('u.Challenge_Users cu1')
            ->leftJoin('cu1.Challenge ch1')
            ->leftJoin('ch1.Challenge_Users cu2')
            ->where('cu2.user_id = ?', $user_id)
            ->andWhere('ch1.ladder_id = ?', $ladder_id)
            ->andWhere('cu1.challenge_id = cu2.challenge_id')
            ->andWhere('cu1.id != cu2.id');

        print_r($q->getSqlQuery());
        $results = $q->fetchArray();

    
        if(1) {
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
