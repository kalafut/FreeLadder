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
}
?>
