<?php
class Login extends Controller {
    var $uModel;

	public function __construct() {
		parent::Controller();

		$this->load->helper(array('form','url','html'));
		$this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="ui-state-error">', '</div>');
        $this->load->model('User');

        $this->uModel = new User();
	}

	public function index() {
        if($this->input->post('email')) {
            $this->process_submit();
        } else { 
            $this->load->view('login_form');
        }
	}

	function process_submit() {
		if ($this->_submit_validate() === FALSE) {
            $this->load->view('login_form');
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
        $this->uModel->logout();

        redirect('/');
    }
	
	public function authenticate() {
		return $this->uModel->login($this->input->post('email'), 
									$this->input->post('password'));
	}

}
?>
