<?php

class User extends BaseModel 
{
    private static $user;

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
				$CI->session->set_userdata('user_id',$u->id);
				self::$user = $u;

				return TRUE;
			}
		}

		// login failed
		return FALSE;
    }

    public function add_user($user)
    {
        $user['password'] = $this->_encrypt_password($user['password']);
        return $this->insert($user);
    }

    public static function logout() {
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->session->sess_destroy();
    }

	protected function _encrypt_password($value) {
		$salt = $this->config->item('salt');
		return md5($salt . $value);
	}
    
}
