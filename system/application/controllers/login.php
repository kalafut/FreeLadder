<?php
class Login extends Controller {

	public function __construct() {
		parent::Controller();

		$this->load->helper(array('form','url'));
		$this->load->library('form_validation');
	}

	public function index() {
		$this->load->view('login_form');
	}

	public function submit() {
		
		if ($this->_submit_validate() === FALSE) {
			$this->index();
			return;
		}
		
		redirect('/');
		
	}
	
	private function _submit_validate() {
		
		$this->form_validation->set_rules('email', 'Email', 
			'trim|required|callback_authenticate');
		
		$this->form_validation->set_rules('password', 'Password',
			'trim|required');
	
		$this->form_validation->set_message('authenticate','Invalid login. Please try again.');
	
		return $this->form_validation->run();

	}

    public function logout() {
        Current_User::logout();

        redirect('/');
    }
	
	public function authenticate() {
		return Current_User::login($this->input->post('email'), 
									$this->input->post('password'));
	}

}
?>
