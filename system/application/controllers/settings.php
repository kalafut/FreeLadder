
<?php
class Settings extends Controller {
    private $user;

    public function __construct() {
        parent::Controller();
		$this->load->helper(array('form'));
        $this->load->library('form_validation');

        $this->user = Current_User::user();
    }

    public function index() {
        $user = Current_User::user();

        if($user) {
            $vars['content_view'] = 'settings';
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
            $password = $this->input->post('password1');
            if($password != '') {
                $this->user->password = $password;
            }
            $this->user->save();
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

        if( Doctrine::getTable('User')->findOneByEmail($email) )
        {
            return false;
        }

        return true;
    }
}

