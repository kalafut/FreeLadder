<?php
class Current_User {

	private static $user;

	private function __construct() {}

	public static function user() {
		if(!isset(self::$user)) {

			$CI =& get_instance();
			$CI->load->library('session');

			if (!$user_id = $CI->session->userdata('user_id')) {
				return FALSE;
			}


			if (!$u = Doctrine::getTable('User')->find($user_id)) {
				return FALSE;
			}

			self::$user = $u;
		}

		return self::$user;
	}

	public static function login($email, $password) {
        $t = Doctrine_Query::create()
            ->from('User u')
            ->where('u.email = ?', $email)
            ->leftJoin('u.Ladders l1')
            ->leftJoin('u.Current_Ladder l2');

//        print_r($t->getSqlQuery());
        $u = $t->fetchOne();
        //print_r($u->Current_Ladder->name);
  //      print_r($u->Current_Ladder->name);

		// get User object by username
		//if ($u = Doctrine::getTable('User')->findOneByEmail($email)) {
		if ($u) {

			// this mutates (encrypts) the input password
			$u_input = new User();
			$u_input->password = $password;

			// password match (comparing encrypted passwords)
			if ($u->password == $u_input->password) {
				unset($u_input);

				$CI =& get_instance();
				$CI->load->library('session');
				$CI->session->set_userdata('user_id',$u->id);
				$CI->session->set_userdata('ladder_id',$u->Ladders[0]);
				self::$user = $u;

				return TRUE;
			}

			unset($u_input);
		}

		// login failed
		return FALSE;

	}

    public static function logout() {
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->session->sess_destroy();
    }


	public function __clone() {
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

}


