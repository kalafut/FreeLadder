<?php
class Profile extends Controller
{
    private static $user;
    private static $user_id;
    private static $ladder_id;

    public function __construct() 
    {
        parent::Controller();
		$this->load->helper('util');
        $this->load->model('User');
        $this->load->model('Challenge');
        $this->load->model('Ladder');
        $this->load->model('Match');

        /* Assign some convenience variables used everywhere */
        $this->user = User::instance()->current_user();
        if( !$this->user ) {
            redirect('/login');
        }
        $this->user_id = $this->user->id;
        $this->ladder_id = $this->user->ladder_id;
    }

    public function index($id)
    {


    }

