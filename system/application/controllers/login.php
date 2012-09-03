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

	private function process_submit() {
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
