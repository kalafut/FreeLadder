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

class Signup extends Controller {
	public function __construct() {
		parent::Controller();
        $this->load->helper(array('form','html','url', 'util'));
        $this->load->library(array('form_validation','session'));
        $this->form_validation->set_error_delimiters('<div style="margin-top: 0.5em; margin-bottom: 0.5em;"><span class="label label-important">', '</span></div>');
        $this->load->plugin('recaptchalib');
        $this->load->model('User');
        $this->load->model('Ladder');
	}

	public function index() {
		$this->load->view('signup_form');
	}

	public function submit() {
		if ($this->_submit_validate() == false) {
			$this->index();
			return;
		}

        $u = array(
            'name' => $this->input->post('name'),
            'email'=> $this->input->post('email'),
            'password' => User::_encrypt_password($this->input->post('password')),
            'ladder_code'=> $this->input->post('ladder_code')
        );

        $ladder = Ladder::instance()->get_by('code', $u['ladder_code']);

        unset($u['ladder_code']);
        $u['ladder_id'] = $ladder->id;
        $user_id = User::instance()->add_user($u);

        Ladder::instance()->add_user($user_id, $ladder->id);
        $this->load->view('signup_success');

        $this->session->sess_destroy();
	}

    public function verify()
    {
        if(!($u =$this->session->userdata('pending_user')) || $this->input->post('back')) {
            $this->session->sess_destroy();
            redirect('/signup');
        }

        if($this->session->userdata('verify_phase') == 0)
        {
            $this->load->view('signup_verify');
            $this->session->set_userdata('verify_phase', 1);
            return;
        }

        if($this->session->userdata('verify_phase') == 1)
        {
           if(!isset($_SERVER["REMOTE_ADDR"]) ||
              !isset($_POST["recaptcha_challenge_field"]) ||
              !isset($_POST["recaptcha_response_field"])) {
                $this->session->sess_destroy();
                redirect("/signup");
              }


            $privatekey = $this->config->item('recaptcha_private_key');
            $resp = recaptcha_check_answer ($privatekey,
                $_SERVER["REMOTE_ADDR"],
                $_POST["recaptcha_challenge_field"],
                $_POST["recaptcha_response_field"]);

            if (!$resp->is_valid) {
                $this->load->view('signup_verify',array('invalid_captcha'=>true));
            } else {
                $ladder = Ladder::instance()->get_by('code', $u['ladder_code']);

                unset($u['ladder_code']);
                $u['ladder_id'] = $ladder->id;
                $user_id = User::instance()->add_user($u);

                Ladder::instance()->add_user($user_id, $ladder->id);
                $this->load->view('signup_success');

                $this->session->sess_destroy();
            }
        }
    }



    private function _submit_validate()
    {
		// validation rules
		$this->form_validation->set_rules('name', 'Name', 'required');

		$this->form_validation->set_rules('email', 'Email',
			'required|valid_email|unique[users.email]');

		$this->form_validation->set_rules('password', 'Password',
			'required|min_length[6]|max_length[12]');

		$this->form_validation->set_rules('ladder_code', 'Ladder Code',
			'required|alpha_numeric|callback_verify_ladder');

		return $this->form_validation->run();
    }

    function verify_ladder($code)
    {
        if( Ladder::instance()->count_by('code', $code) == 0 ) {
            $this->form_validation->set_message('verify_ladder', 'Invalid ladder code.');
            return false;
        } else {
            return true;
        }
    }
}


