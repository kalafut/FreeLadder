<?php
/*
 * FreeLadder Ladder Server
 * http://freeladder.org
 *
 * Copyright 2010, Jim Kalafut
 * Released under the MIT license.
 * 
 */
include_once("db.php");
include_once("util.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');

checkLogin();
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php include_once("includes.php"); ?>
</head>
<body>

<div class="container">
	<?php include_once("header.html"); 
	include_once("toolbar.html");?>
    <form id='settings_form' name='settings' action='settings.php' method='post'>
	<div class="prepend-3 span-18 append-3 last">
			<h2>Introduction</h2>
            FreeLadder let's you easily participate in a competitive ladder system. These have been popular in clubs of many disciplines: tennis, squash, ping-pong, chess, etc. A ladder is a ranking, not a rating.  Your ranking is determine solely by the games you've played recently and their outcomes.  The rankings change on FreeLadder based on some simple rules:<br><br>
<ol>
<li>You challenge a player ranked higher than you on the ladder. (How many rungs ahead depends on the ladder setup.)
<li>If you lose, the ranks are unchanged.
<li>If you win, you take your opponent's spot on the ladder. Your opponent and those below him move down one spot. 
</ol> 
	</div>
</div>
</body>
</html>
