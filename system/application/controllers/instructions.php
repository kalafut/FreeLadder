<?php
class Instructions extends Controller 
{
    static $user;

    public function __construct() 
    {
        parent::Controller();
        $this->load->model('Ladder');
        $this->load->model('User');

        /* Assign some convenience variables used everywhere */
        $this->user = User::instance()->current_user();
        if( !$this->user ) {
            redirect('/login');
        }
    }

    public function index() 
    {
        $vars['content_view'] = 'instructions';
        $this->load->view('template', $vars);
    }
}
