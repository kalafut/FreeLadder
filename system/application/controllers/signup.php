<?php

class Signup extends Controller {
    static var $uModel;

	public function __construct() {
		parent::Controller();
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');

        $uModel = new User();
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
            'email'=> $this->input->post('email'),
            'password' => $this->input->post('password')
        );

        if( $uModel->addUser($u) ) {
            $this->load->view('signup_success');
        } else {

        }
	}

	private function _submit_validate() {

		// validation rules
		$this->form_validation->set_rules('name', 'Name', 'required');

		$this->form_validation->set_rules('email', 'E-mail',
			'required|valid_email|unique[User.email]');

		$this->form_validation->set_rules('password', 'Password',
			'required|min_length[6]|max_length[12]');

		$this->form_validation->set_rules('password_confirm', 'Confirm Password',
			'required|matches[password]');


		$this->form_validation->set_rules('ladder_name', 'Ladder Name',
			'required|alpha_numeric');

		return $this->form_validation->run();
    }
    

}


