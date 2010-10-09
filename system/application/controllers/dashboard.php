<?php
class Dashboard extends Controller {
    public function __contruct() {
        parent::Controller();

        //$this->output->enable_profiler(TRUE);
    }

    public function index() {

        if(Current_User::user()) {
            $vars['content_view'] = 'dashboard';

            $vars['ladderRungs'] = $this->generateLadderTable();
        //$this->benchmark->mark('start');
            $this->load->view('template', $vars);
        //$this->benchmark->mark('end');
        } else {
            redirect('/login');
        }
        //echo $this->benchmark->elapsed_time('start', 'end');
    }

    public function ladderUpdate() {
        $vars['ladderRungs'] = $this->generateLadderTable();
        $this->load->view('ladder', $vars);
    }

    private function generateLadderTable() {
        $ladder_id = Current_User::user()->Current_Ladder->id;
        $q = Doctrine_Query::create()
            ->select('u.id, u.name, lu.rank')
            ->addSelect('COUNT(c1.id) AS c_cnt')
            ->addSelect('COUNT(c2.id) AS rc_cnt')
            ->from('User u')
            ->where('lu.ladder_id = ?', $ladder_id)
            ->orderBy('lu.rank')
            ->leftJoin('u.Challenges c1')
            ->leftJoin('u.Received_Challenges c2')
            ->leftJoin('u.Ladder_Users lu')
            ->groupBy('u.id');

        //print_r($q->getSqlQuery());
        $results = $q->fetchArray();

        //echo "<pre>";
        //print_r($results);
        //echo "</pre>";

        return $results;
    }

}
