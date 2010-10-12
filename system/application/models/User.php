<?php

class User extends MY_Model 
{
    private static $user;

    var $name;
    var $email;
    var $password;
    var $site_admin;
    var $ladder_id;


	public function current_user() {
		if(!isset(self::$user)) {

			$CI =& get_instance();
			$CI->load->library('session');

			if (!$user_id = $CI->session->userdata('user_id')) {
				return FALSE;
			}

			if (!$u = $this->get($user_id)) {
				return FALSE;
			}

			self::$user = $u;
		}

		return self::$user;
	}

    public static function test($user_id)
    {
        $u = new User();

        //print_r($u);
        print_r($u->get($user_id));
        
    }
    

    public function login($email, $password) 
    {
        $u = $this->get_by('email', $email);

		if ($u) {

			if ($u->password == $password) {
				$CI =& get_instance();
				$CI->load->library('session');
				$CI->session->set_userdata('user_id',$u->id);
				self::$user = $u;

				return TRUE;
			}
		}

		// login failed
		return FALSE;
    }

    public static function logout() {
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->session->sess_destroy();
    }

	protected function _encrypt_password($value) {
		$salt = '#*seCrEt!@-*%';
		$this->_set('password', md5($salt . $value));
	}
    
}
