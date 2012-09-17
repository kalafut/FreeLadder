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

class Password_reset extends Controller {
    var $uModel;

    const RESET_EXPIRE = 7200; // 2 hours

	public function __construct() {
		parent::Controller();

		$this->load->helper(array('form','url','html'));
		$this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="ui-state-error">', '</div>');
        $this->load->model('User');

        $this->uModel = new User();
	}

	public function index() {
        $this->load->view('password_reset', array('init' => true));
	}

	public function submit() {
		/*
        if ($this->_submit_validate() === FALSE) {
            $this->load->view('login_form');
			return;
		}*/
        $email = $this->input->post('email');

        if($email) {
            $result = $this->uModel->get_by('email', $email);
            if($result) {
                $reset_key = mt_rand();
                $this->uModel->update_by('email', $email, array('pw_reset' => $reset_key, 'pw_reset_expire' => time() + Password_reset::RESET_EXPIRE));

                mail($email, "FreeLadder password reset request", "A password reset has been requested for your FreeLadder.org account. Use the following link to confirm this request:\n\n" . site_url("/password_reset/confirm/$reset_key") . "  (expires in two hours)\n\nIf you did not request the reset, you may disregard this message.", 'From: no-reply@freeladder.org' . "\r\n");

                $this->load->view('password_reset', array('init' => false, 'message' => 'A confirmation email has been sent to you.'));
            } else {
                $this->load->view('password_reset', array('init' => false, 'message' => 'Email address not found'));
            }
        } else {
            redirect('/');
        }
	}

    public function confirm($reset_key) {
        $result = $this->uModel->get_by('pw_reset', $reset_key);
        if($reset_key > 0 && $result) {
            $new_pw = substr(str_shuffle(str_repeat('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnopqrstuvwxyz123456789',5)),0,8);
            if( time() < $result->pw_reset_expire) {
                $this->uModel->update_by('pw_reset', $reset_key, array('pw_reset' => "", "pw_reset_expire" => 0, 'password' => User::_encrypt_password($new_pw)));
                mail($result->email, "FreeLadder password changed", "Your password has been reset to: $new_pw\n\n", 'From: no-reply@freeladder.org' . "\r\n");

                $this->load->view('password_reset', array('init' => false, 'message' => 'An email with your new password has been sent.'));
            } else {
                $this->uModel->update_by('pw_reset', $reset_key, array('pw_reset' => "", 'pw_reset_expire' => 0));
                $this->load->view('password_reset', array('init' => false, 'message' => 'Password reset link has expired.'));
            }
        } else {
            redirect('/');
        }
    }

	private function _submit_validate() {
		$this->form_validation->set_rules('email', 'Email',
			'trim|required|callback_authenticate');

		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		$this->form_validation->set_message('authenticate','Invalid login. Please try again.');

		return $this->form_validation->run();

	}

    public function logout() {
        $this->uModel->logout();

        redirect('/');
    }

	public function authenticate() {
		return $this->uModel->login($this->input->post('email'),
									$this->input->post('password'));
    }

    public function m($route)
    {
        if($route == 'login') {
		    $success = $this->uModel->login($this->input->post('email'),
									$this->input->post('password'));

            if( $success ) {
                $out = array(
                    'didSuccess' => TRUE,
                    'userID' => User::instance()->current_user()->id,
                    'name' => User::instance()->current_user()->name
                );
            } else {
                $out = array(
                    'didSucceed' => FALSE,
                );
            }
            $json_out = json_encode($out);
        }

        echo $json_out;
    }

}
?>
