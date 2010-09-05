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
	
	/*
	 * Auto versioning can be optionally turned on for CSS and JS files.
	 * This is extremely useful when you're making changes to these files,
	 * as it will force the client to request a new version.  When you make a
	 * change.  To use auto versioning does require some minor URL rewrite rules.  
	 * If you cannot or do not want to make this change, be sure this is set 
	 * to 'false'.
	 *
	 * Details of this technique, including the URL rewriting, are at:
	 * http://www.derekville.net/2009/auto-versioning-javascript-and-css-files/
	 */
	const AUTO_VERSION = true;
}
?>