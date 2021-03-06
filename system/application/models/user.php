<?php
/*
    FreeLadder
    Copyright (C) 2010  Jim Kalafut

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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

			if (!$user_id = $CI->session->userdata('user_id')) {
				return FALSE;
			}

			if (!$u = $this->get($user_id)) {
				return FALSE;
			}

            $this->update_last_visit($user_id);

			self::$user = $u;
		}

		return self::$user;
	}

    public function set_test_user()
    {
        self::$user = $this->get(1);
    }

    public function login($email, $password)
    {
        $u = $this->get_by('email', $email);

		if ($u && $u->status != User::DISABLED) {
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
        $user['created_at'] = time();
        return $this->insert($user);
    }

    function set_ladder($user_id, $ladder_id)
    {
        $this->update($user_id, array('ladder_id', $ladder_id));
    }

    public function max_challenges($user_id, $ladder_id)
    {
        static $results = null;
        static $last_ladder_id = null;

        if( !$results || $last_ladder_id != $ladder_id ) {
            $last_ladder_id = $ladder_id;
            $this->db->select('id, max_challenges')
                ->from('users');
                //->from('ladder_users')
                //->where('ladder_id', $ladder_id);

            $q = $this->db->get();
            $results = $q->result();
        }

        // Replace this with an array indexed by id;
        for($i=0; $i < count($results); $i++) {
            if( $results[$i]->id == $user_id ) {
                return $results[$i]->max_challenges;
            }
        }

        return 255;
    }

    public function inactivate_idle($ladder_id)
    {
        $timeout = $this->db->get_where('ladders', array('id' => $ladder_id))->row()->inactive_timeout;

        if($timeout > 0) {
            $sql = "UPDATE users SET status = ? WHERE status = ? AND last_visit < ?";
            $idle_cutoff = time() - $timeout;
            $this->db->query($sql, array(User::INACTIVE, User::ACTIVE, $idle_cutoff));
        }
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

    public function update_last_visit($user_id) {
        //$update_interval = 3600; // Only update once an hour
        $update_interval = 10; // TODO testing only

        $sql = "UPDATE users SET last_visit = ? WHERE id = ? AND last_visit < ?";
        $this->db->query($sql, array(time(), $user_id, time() - $update_interval));
    }
}
