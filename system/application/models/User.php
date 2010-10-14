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

            $q = $this->db->get_where('users', array('id'=>$user_id));
			if ( !$u = $q->row_array() ) {
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

    public function addUser($user)
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
