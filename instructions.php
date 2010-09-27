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
            FreeLadder let's you easily participate in a competitive ladder. Ladders are popular in clubs of many disciplines: tennis, squash, ping-pong, chess, etc. A ladder is a ranking, not a rating.  Your ranking is determine solely by the matches you and others have played recently.  The rankings change on FreeLadder based on some simple rules:<br><br>
<ul>
<li>You challenge a player ranked higher than you on the ladder. (How many rungs ahead depends on the ladder setup.)
<li>If you lose, the rankings are unchanged.
<li>If you win, you take your opponent's spot on the ladder. Your opponent and those below him move down one spot. 
</ul> 
<h2>Using FreeLadder</h2>
FreeLadder is designed to be simple and intuitive. Unlike other ladder programs, you will not be bombarded with dozens of settings, options and statistics when you use FreeLadder.  The most common uses--challenging others and reporting results--are one-click operations right off the home page.  But while the technology is simple enough, there are some basics you should understand for the ladder as a whole to run smoothly:
<ol>
<li>If you are "Active", you are active.  If your status (set in User Profile) is set to "Active" for a given ladder, then you are saying to everyone that you're accepting challenges and ready to play.  If you don't want to play or won't be around, set your status to "Inactive".
<li>Challenges aren't cancelled. Once you challenge someone or have been challenge, there is no "undo". You are expected to play the match and record the result. If for some reason you can't complete the match, then you should Forfeit. 
<li>Agree on results.  The results mechanism on FreeLadder is pretty simple: you enter whether you won or lost, and if you're opponent enters the opposite then the match is saved.  If you do not agree (i.e. you both say you won or lost), then you will prompted to either change your answer or wait for your opponent to.  Like challenges, there is no cancelling of the completed match. It is expected that both sides will come to an agreed upon result.
<li>The match is final when it's saved.  In an active ladder, players are constantly moving up and down in ranking and the same time other are placing challenges and completing matches.  The rule for determining the new rankings is: when both players agree on results, the match is final and the rankings are updated based on the ladder at that instant.  You might have challenged someone 3 spots ahead of you and beat them, but if before your results were in they fell below you (or you jumped ahead of them due to another match), then the positions won't change.  
</ol> 
With an understand of the these ground rules, you'll have no trouble using FreeLadder.
	</div>
</div>
</body>
</html>
