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

class Instructions extends Controller 
{
    static $user;

    public function __construct() 
    {
        parent::Controller();
        $this->load->model('Ladder');
        $this->load->model('User');
		$this->load->helper('html');

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
