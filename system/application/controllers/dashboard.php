<?php
class Dashboard extends Controller {
    public function __contruct() {
        parent::Controller();
    }

    public function index() {
        if(Current_User::user()) {
            $vars['Users'] = Doctrine_Query::create()
                ->select('u.id, u.email')
                ->from('User u')
                ->execute();

            $q = Doctrine_Query::create()
                ->select('c.id, u1.name, u2.name')
                ->from('Challenge c')
                ->leftJoin('c.Challenger u1')
                ->leftJoin('c.Opponent u2');

            $vars['challenges'] = $q->fetchArray();
            $vars['content_view'] = 'dashboard';

            $this->load->view('template', $vars);
        } else {
            redirect('/login');
        }
    }

    private function generateLadderTable() {

    }

}
