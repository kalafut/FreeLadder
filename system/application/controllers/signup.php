<?php

class Signup extends Controller {
	public function __construct() {
		parent::Controller();
        $this->load->helper(array('form','url', 'util'));
        $this->load->library(array('form_validation','session'));
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

        $this->session->set_userdata('pending_user', $u);
        $this->session->set_userdata('verify_phase', 0);

        redirect('/signup/verify');
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
			'required|');

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


