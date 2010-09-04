<?php
/*
 * FreeLadder Ladder Server
 * http://freeladder.org
 *
 * Copyright 2010, Jim Kalafut
 * Released under the MIT license.
 * 
 */

class Config
{
	const CHALLENGE_WINDOW = 3;
	const BEST_RANK_WINDOW = 30;  # In days
	
	const DB_LOCATION = "/usr/local/www/ladder/";
	const LOGFILE = "/usr/local/www/ladder/log.txt";
	const SALT = "rY4Py97sVAN8akOyInsq";
}
?>