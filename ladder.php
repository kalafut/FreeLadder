<?php 
/*
 * FreeLadder Ladder Server
 * http://freeladder.org
 *
 * Copyright 2010, Jim Kalafut
 * Released under the MIT license.
 * 
 */

/* Authorize first          */
include_once("auth.php");
verifyAuthorization();

/* Other includes */
include_once("config.php");
include_once("db.php");
include_once("util.php");
include_once("log.php");



$db = DB::getDB();


$users = $db->getUserList();
$challenges = $db->getChallenges();

dispatch();

//Ugly!
$users = $db->getUserList();


function generatePage()
{
	echo <<<EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
EOT;
include_once("includes.php"); 
echo '<script type="text/javascript" src="' . auto_version('/js/ladder.js') . '"></script>';

echo <<<EOT
</head>
<body>

<div class="container">
EOT;

	include_once("header.html"); 
	include_once("toolbar.html"); 
	
	echo <<<EOT
    <form id='ladder_form' name='ladder' action='ladder.php' method='post'>
	<div class="prepend-1 span-11 append-1" >

			<h2>Ladder Standings</h2>
			<table id='ladderTable'>
			</table>    
	</div>
	
	<div class="span-10 append-1 last">
			<h2>Pending Matches</h2>
			<table id="pendingTable">
			</table>    
		
			<h2>Latest Matches</h2>
			<table id="matchesTable">
			</table>
	</div>
	</form>
</div>
<div id="reviewDialog" title="Conflicting Results Review">
	<p>This match has not been saved because you and your opponent (<span id="reviewOpponent"></span>) have reported conflicting results:</p>
	<p style="text-indent:5em;">You reported that you <span class="reviewResult"></span> the match.</p>
	<p style="text-indent:5em;">They reported that they <span class="reviewResult"></span> the match.</p>
	Select one of the following options:<br><br>
	<ul>
	<li><strong>Change your answer</strong> &mdash Change your answer to agree with your opponent. The match will be recorded.</li><br>
	<li><strong>Do nothing</strong> &mdash Take no action now. The match will remain in your Pending list to address later.</li>
	</ul>
</div>

</body>
</html>
EOT;
}

function dispatch() {
	global $current_user, $challenges, $users;
	
	$db = DB::getDB();
	
	$action = (isset($_REQUEST["action"])) ? $_REQUEST["action"] : null;
	$param = (isset($_REQUEST["param"])) ? $_REQUEST["param"] : null;

	if($action == "challenge" && $param != null) {
		$db->addChallenge($current_user, $param);
		$challenges = $db->getChallenges();
		generateJSONTables();
		return;
	}

	if( ($action == "cancel") && $param != null) {
		cancelChallenge($param);
		$challenges = $db->getChallenges();
		generateJSONTables();
		return;
	} 
	
	if( ($action == "forfeit") && $param != null) {
		$challenges = $db->getChallenges();
		recordResult($param, false, false, true);
		$challenges = $db->getChallenges();
		
		
		$users = $db->getUserList();
		generateJSONTables();
		return;
	}

	if( ($action == "won" || $action == "lost") && $param != null) {
		recordResult($param, $action=="won");
		$challenges = $db->getChallenges();
		$users = $db->getUserList();
		generateJSONTables();
		return;
	}
	
	if( $action == "flip" ) {
		recordResult($param, false, true);
		$challenges = $db->getChallenges();
		$users = $db->getUserList();
		generateJSONTables();
		return;
	}

	if( $action=="updateTables") {
		generateJSONTables();
		return;
	}

	generatePage();
}
		
function generateJSONTables() {
	ob_start();
	generateLadderTable();
	$ladder=ob_get_clean();

	ob_start();
	generatePendingTable();
	$pending=ob_get_clean();


	ob_start();
	generateMatchesTable();
	$matches=ob_get_clean();

	$arr = array("ladder"=>$ladder, "pending"=>$pending, "matches"=>$matches);

	echo json_encode($arr);
}

function challengeLookup($opponent) {
	global $challenges, $current_user;

	foreach($challenges as $c) {
		$ret = null;
		if($c["opponent"]==$opponent && $c["challenger"]==$current_user) {
			$ret = array("type"=>"sent", "date"=>$c['date'], "id"=>$c["id"]);
			$a = "challenger_result";
			$b = "opponent_result";
		} elseif($c["opponent"]==$current_user && $c["challenger"]==$opponent ) {
			$ret = array("type"=>"received", "date"=>$c['date'], "id"=>$c["id"]);
			$b = "challenger_result";
			$a = "opponent_result";
		}
		
		if($ret) {		
			if($c[$a]!=0 && $c[$b]==0) {
				$ret["status"]="waitingForConfirmation";
			} elseif ($c[$a]==0 && $c[$b]!=0) {
				$ret["status"]="needToConfirm";
			} elseif ($c[$a] * $c[$b] == 1) {
				$ret["status"]="conflictingResults";
				$ret["challenger_result"]=$c["challenger_result"];
			} else {	
				$ret["status"]="noResults";
			}
			
			return $ret;
		}	
	}

	return null;
}

function redirectAndExit() 
{
	echo "<script type='text/javascript'>window.location = 'ladder.php'</script>";
	exit();
}

function recordResult($challengeId, $won, $flip=false, $forfeit=false) 
{
	global $users, $challenges, $current_user;
	$waitingForConfirmation=false;	
		
	$db = DB::getDB();
	
	// If $flip then bypass the 

	if(!$flip) {
		$db->updateReportedResult($challengeId, $current_user, $won);
	}
	
	$c = $db->getChallenge($challengeId);	
		
	if($c["challenger"]==$current_user) {
		$opponent = $c["opponent"];
	} else {
		$opponent = $c["challenger"];
	}
	
	// Stop here if the results are either partial or in conflict
	if(!$flip && !$forfeit) {
		$prod = $c["challenger_result"] * $c["opponent_result"];
		if( $prod==0 || $prod==1 ) {
			return;
		} 
	} 
	
	$oldUserRank = array();
	
	// Back up old rankings
	foreach($users as $u) {
		$oldUserRank[$u['id']]=$u['rank'];
	}

	if(!isset($challenges[$challengeId])) return;

	// Set up $won if we're flipping
	if($flip) {
		if($c["challenger"]==$current_user) {
			$userResult = $c["challenger_result"];
		} else {
			$userResult = $c["opponent_result"];
		}

		$won = ($userResult==-1);
	}

	$winner = $users[$won ? $current_user : $opponent]["id"];
	$loser = $users[$won ? $opponent : $current_user]["id"];
	$winnerRank = $users[$winner]["rank"];
	$loserRank = $users[$loser]["rank"];

	// Update win-loss record
	$users[$winner]["wins"] = $db->getUserResultCount($winner, true)+1;
	$users[$loser]["losses"] = $db->getUserResultCount($loser, false)+1;

	//print_r($userRank . "  " . $opponentRank);
	if($winnerRank > $loserRank) {
		// Adjust rankings
		foreach($users as &$user) {
			if($user["id"]==$winner) {
				$user["rank"]=$loserRank;
			} elseif ($user["rank"]>=$loserRank && $user["rank"]<$winnerRank) {
				$user["rank"]++;
			}
		}

	}
	

	$matchId = $db->addMatch($winner, $loser, $forfeit);
	$db->updateRankings($users, $oldUserRank, $matchId);  
	$db->cancelChallenge($challengeId);
		// TODO record challenge
}


			
function generateLadderTable()
{
	global $users, $current_user, $challengeWindow;
	
	$db = DB::getDB();

	echo <<<EOT
		<colgroup>
		<col width="10%"/>
		<col width="40%"/>
		<col width="30%"/>
		<col width="20%"/>
		</colgroup>
		<tr>
		<th>Ranking</th>
		<th>Name</th>
		<th>Record</th>
		<th></th>
		</tr>				
EOT;

	$ranking=1;
	foreach($users as $user) {    
		$user_id=$user["id"];

		if($user["id"] == $current_user) {
			echo "<tr class='user'>";
		} else {
			echo "<tr>";
		}

		echo "<td >$ranking</></td>";
		echo "<td><a href='profile.php?pid={$user['id']}'>" . $user["name"] . "</a></td>";

		// Record
		echo "<td>{$user['wins']}-{$user['losses']} (".computeWinPct($user).")</td>";

		// Challenge buttons
		if($user["id"] != $current_user) {
			// See if there is an open challenge against this person
			$alreadyChallenged = challengeLookup($user_id);
			
			// See if user is with the ladder windows
			$oRnk = $user['rank'];
			$uRnk = $users[$current_user]['rank'];
			
			//error_log("oRnk: $oRnk  uRnk:$uRnk\n", 3, "debug.log");

			$inWindow = false;
			if( ($uRnk-$oRnk) <= Config::CHALLENGE_WINDOW && $uRnk > $oRnk) {
				$inWindow = true;
			} 

			if($alreadyChallenged==null && $inWindow) {
				echo "<td><button type='button' class='challengeButton' action='challenge' param='$user_id'>Challenge</button>" . "</td>";
			} else {
				echo "<td>&nbsp;</td>";
			}
		} else {
			echo "<td>&nbsp;</td>";
		}

		echo "</tr>";

		$ranking++;
	}

}


			
function generatePendingTable()
{
	$db = DB::getDB();
	
	global $users, $current_user;
	$noPending = true;

	echo <<<EOT
		<colgroup>
		<col width="40%"/>
		<col width="40%"/>
		<col width="20%"/>
		</colgroup>
EOT;
				
	foreach($users as $user) {    
		$user_id=$user["id"];

		if($user["id"] != $current_user) {
			// See if there are open challenges against this person
			$c = challengeLookup($user_id);
			
			if($c != null) {
				$noPending = false;
				
				echo "<tr>";
				echo "<td>" . $user["name"] . "</td>";

				if( ($c["type"]=="sent" || $c["type"]=="received") && ($c["status"]=="noResults" || $c["status"]=="needToConfirm")) {
					echo "<td><div class='won_lost' id='radio{$c['id']}'>";
					echo "<button type='button' class='resultButton' action='won' param='{$c["id"]}'>I Won</button>";
					echo "<button type='button' class='resultButton' action='lost' param='{$c["id"]}'>I Lost</button>";
					echo "</div></td>";
					echo "<td><button type='button' class='forfeitButton' action='forfeit' param='{$c["id"]}'>Forfeit</button></td>";
				} elseif(($c["type"]=="sent" || $c["type"]=="received") && $c["status"]=="waitingForConfirmation") {
					echo "<td>Waiting for<br>confirmation</td>";
					echo "<td><button type='button' class='forfeitButton' action='forfeit' param='{$c["id"]}'>Forfeit</button></td>";
				} 	elseif(($c["type"]=="sent" || $c["type"]=="received") && $c["status"]=="conflictingResults") {
						if($c["challenger_result"]==-1) {
							$resultString = "lost";
						} else {
							$resultString = "won";
						}
						
						echo "<td><span class='ui-state-error'>&nbsp;Review Needed&nbsp;</span></td>";
						echo "<td><button type='button' class='reviewButton' action='review' param='{$c["id"]}' opponent='{$user["name"]}' result='$resultString' }'>Review</button></td>";
					}
				

				echo "</tr>";
			}

		}
	}

	if($noPending) {
		echo "<tr><td colspan='3'><i class='large'>You have no pending matches.</i></td></tr>";
	}
}

function generateMatchesTable()
{
	global $users, $current_user;
	
	$db = DB::getDB();

	$matches = $db->getMatches(5);

	foreach($matches as $match) {
		$winner = $users[$match['winner']]['name'];
		$loser = $users[$match['loser']]['name'];
		$forfeit = $match['forfeit']==1 ? "(f)":"";

		echo "<tr>";
		echo "<td >4:45pm</td>";
		echo "<td>$winner</td><td>def.</td><td>$loser $forfeit</td>";
		
		echo "</tr>";
	}
}

?>

            

