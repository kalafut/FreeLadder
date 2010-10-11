<?php
class Qtest extends Controller {
    public function __construct() {
        parent::Controller();

        //$this->output->enable_profiler(TRUE);
    }

    public function index() {
        $ladder_id = Current_User::user()->Current_Ladder->id;
        $user_id = Current_User::user()->id;
        $q = Doctrine_Query::create()
            ->select('opp.id, opp.name')
            ->from('User opp')
            ->leftJoin('opp.Challenge_Users cu_opp')
            ->leftJoin('cu_opp.Challenge ch')
            ->leftJoin('ch.Challenge_Users cu_user')
            ->where('cu_user.user_id = ?', $user_id)
            ->andWhere('ch.ladder_id = ?', $ladder_id)
            ->andWhere('cu_user.challenge_id = cu_opp.challenge_id')
            ->andWhere('cu_user.id != cu_opp.id');

        //$this->p($q);

        $q = Doctrine_Query::create()
            ->select('cu.*, u.name, ch.id, cu2.*')
            ->from('Challenge_User cu')
            ->innerJoin('cu.User u')
            ->innerJoin('cu.Challenge ch')
            ->innerJoin('ch.Challenge_Users cu2')
            ->where('cu.user_id != ?', $user_id)
        ;
            //->where('cu_user.user_id = ?', $user_id);

        //$this->p($q);
        $q = Doctrine_Query::create()
            ->select('c.*')
            ->from('Challenge c')
            ->where('c.player1_id = ?', $user_id)
            ->orWhere('c.player2_id = ?', $user_id)
        ;
            //->where('cu_user.user_id = ?', $user_id);

        $this->p($q);
    }

    private function p($q)
    {
        print_r($q->getSqlQuery());
        $results = $q->fetchArray();


        if(1) {
            echo "<pre>";
            print_r($results);
            echo "</pre>";
            echo "<hr>";
        }

    }
}
