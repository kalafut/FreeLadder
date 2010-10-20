<?php

class User extends MY_Model 
{
    const ACTIVE   = 0;
    const INACTIVE = 1;
    const DISABLED = 2;

    private static $user;
    private static $_instance;

    static public function instance()
    {
        if ( !isset(self::$_instance) ) {
            self::$_instance = new self(); 
        }

        return self::$_instance;
    }

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


    public function login($email, $password) 
    {
        $u = $this->get_by('email', $email);

		if ($u) {
			if ($u->password == $this->_encrypt_password($password)) {
				$CI =& get_instance();
				$CI->load->library('session');
                session_regenerate_id();
				$CI->session->set_userdata('user_id',$u->id);
				self::$user = $u;

				return TRUE;
			}
		}

		// login failed
		return FALSE;
    }

    function add_user($user)
    {
        return $this->insert($user);
    }

    function set_ladder($user_id, $ladder_id)
    {
        $this->update($user_id, array('ladder_id', $ladder_id));
    }

    public function max_challenges($user)
    {
        return 255;
    }


    public static function logout() {
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->session->sess_destroy();
    }

	public static function _encrypt_password($value) {
		$salt = self::instance()->config->item('salt');
		return md5($salt . $value);
	}
    
}
