<?php
class Scheduler extends Controller {
	public function __construct() {
		parent::Controller();
    }

    public function addChallenge($opponent_id) {
        $user = Current_User::user();

        $valid = ($user != false);
       
        $valid = $valid && ($user->id != $opponent_id);

		$q = Doctrine_Query::create()
			->select('c.id')
			->from('Challenge c')
            ->where('c.challenger_id = ? AND c.opponent_id = ?', array($user->id, $opponent_id))
            ->orWhere('c.challenger_id = ? AND c.opponent_id = ?', array($opponent_id, $user->id));

        $valid = $valid && $q->count()==0;

        if($valid)
        {
            $challenge = new Challenge();
            $challenge->challenger_id = $user->id;
            $challenge->opponent_id = $opponent_id;

            $challenge->save();
        }

        redirect('/');
    }
}
