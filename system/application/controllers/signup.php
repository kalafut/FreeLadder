<?php

class Signup extends Controller {
    static private $uModel;

	public function __construct() {
		parent::Controller();
        $this->load->helper(array('form','url', 'util'));
        $this->load->library(array('form_validation','session'));
        $this->load->plugin('recaptchalib');
        $this->load->model('User');

        $this->uModel = new User();
	}

	public function index() {
		$this->load->view('signup_form');
	}
    
	public function submit() {
		if ($this->_submit_validate() === FALSE) {
			$this->index();
			return;
		}

        $u = array( 
            'name' => $this->input->post('name'),
            'email'=> $this->input->post('email'),
            'password' => User::_encrypt_password($this->input->post('password'))
        );

        $this->session->set_userdata('pending_user', $u);

        redirect('/signup/verify');
	}

    public function verify()
    {
        $this->load->view('signup_verify');
    }

    public function verify2()
    {
        if($this->input->post('back')) {
            redirect('/signup');
        }

        $u = $this->session->userdata('pending_user');

        $privatekey = $this->config->item('recaptcha_private_key');
        $resp = recaptcha_check_answer ($privatekey,
            $_SERVER["REMOTE_ADDR"],
            $_POST["recaptcha_challenge_field"],
            $_POST["recaptcha_response_field"]);

        if (!$resp->is_valid) {
            // What happens when the CAPTCHA was entered incorrectly
            die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
                "(reCAPTCHA said: " . $resp->error . ")");
        } else {
            if( $this->uModel->add_user($u) ) {
                $this->load->view('signup_success');
                $this->session->sess_destroy();
            }
        }
    }

    

	private function _submit_validate() {

		// validation rules
		$this->form_validation->set_rules('name', 'Name', 'required');

		$this->form_validation->set_rules('email', 'Email',
			'required|valid_email|unique[users.email]');

		$this->form_validation->set_rules('password', 'Password',
			'required|min_length[6]|max_length[12]');

		$this->form_validation->set_rules('ladder_name', 'Ladder Name',
			'required|alpha_numeric');

		return $this->form_validation->run();
    }
}


