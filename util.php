<?php
/*
 * FreeLadder Ladder Server
 * http://freeladder.org
 *
 * Copyright 2010, Jim Kalafut
 * Released under the MIT license.
 * 
 */
 
	$CURRENT_VERSION="b";

$TEST_MODE = 0;
	
function checkLogin() {
	global $current_user, $CURRENT_VERSION, $TEST_MODE;
	
	$db = new DB();
	
	if($TEST_MODE) { 
		$current_user=1;
		return;
	}
	
	
//	if(isset($_GET["dbg"]) && $_GET["dbg"]=="test") {
//		$current_user=1;
//		return;
//	}
		
	$ladder_id = (isset($_COOKIE["ladder_id"])) ? $_COOKIE["ladder_id"] : "";
	$ladder_hash = (isset($_COOKIE["ladder_hash"])) ? $_COOKIE["ladder_hash"] : "";   
	$ladder_version = (isset($_COOKIE["ladder_version"])) ? $_COOKIE["ladder_version"] : "";  

	if($ladder_version==$CURRENT_VERSION && $db->validateSession($ladder_id, $ladder_hash)) {
		$current_user=$ladder_id;
	} else {
		echo "<script type='text/javascript'>window.location = 'login.php'</script>";
	}
}

/**
 *  Given a file, i.e. /css/base.css, replaces it with a string containing the
 *  file's mtime, i.e. /css/base.1221534296.css.
 *  
 *  @param $file  The file to be loaded.  Must be an absolute path (i.e.
 *                starting with slash).
 */
function auto_version($file)
{
  if(strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file))
    return $file;

  $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
  return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
}

function computeWinPct($user) {
	$wins = $user['wins'];
	$losses = $user['losses'];

	if($wins + $losses == 0) return "0%";

	$pct = round(100*$wins/($wins+$losses));
	return "$pct%";
}


?>