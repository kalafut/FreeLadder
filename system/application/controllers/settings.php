<?php
class Settings extends Controller {
    private static $user;
    private static $user_id;
    private static $ladder_id;

    public function __construct() {
        parent::Controller();
		$this->load->helper(array('form', 'html'));
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="ui-state-error">', '</div>');
        $this->load->model('User');
        $this->load->model('Ladder');

        /* Assign some convenience variables used everywhere */
        $this->user = User::instance()->current_user();
        if( !$this->user ) {
            redirect('/login');
        }
        $this->user_id = $this->user->id;
        $this->ladder_id = $this->user->ladder_id;
    }

    public function index() {
        $user = $this->user; 

        if($user) {
            $vars['content_view'] = 'settings';
            $vars['user'] = $user;
            $this->load->view('template', $vars);
        } else {
            redirect('/login');
        }
    }

    public function submit() {
        if($this->user) {
            if ($this->submit_validate() == false) {
                $this->index();
                return;
            }

            $this->user->email = $this->input->post('email');
            $this->user->status = $this->input->post('status');
            $this->user->max_challenges = $this->input->post('max_challenges');
            $password = $this->input->post('password1');
            if($password != '') {
                $this->user->password = User::_encrypt_password($password);
            }

            User::instance()->update($this->user->id, $this->user);
        }

        redirect('/dashboard');
    }

    private function submit_validate() {
		$this->form_validation->set_rules('email', 'Email', 
			'trim|required|valid_email|callback_email_check');

		$this->form_validation->set_rules('password1', 'Password',
			'min_length[6]|max_length[12]');

		$this->form_validation->set_message('email_check','Email address already in use.');
		return $this->form_validation->run();
    }

    public function email_check() {
        $email = $this->input->post('email');

        if( $email == $this->user->email ) {
            return true;
        }

        if( User::instance()->count_by('email',$email) > 0 )
        {
            return false;
        }

        return true;
    }
}

