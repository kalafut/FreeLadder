<?php
/*
 * FreeLadder Ladder Server
 * http://freeladder.org
 *
 * Copyright 2010, Jim Kalafut
 * Released under the MIT license.
 * 
 */
require_once("auth.php");
verifyAuthorization();


require_once("db.php");
require_once("util.php");
require_once("config.php");


$db = DB::getDB();

$pid = (isset($_REQUEST["pid"])) ? $_REQUEST["pid"] : null;

$users = $db->getUserList();
$user=$users[$pid];



function generateMatchHistory()
{
	global $users, $user;
	$db = DB::getDB();
	
	$matches = $db->getUserMatches($user['id']);
	
	echo "<table style='width:85%; margin-left:auto; margin-right:auto;'>";
	echo "<tr><th>Date</th><th>Opponent</th><th>Result</th></tr>";
	
	foreach($matches as $match) {
		$isWinner = ($match['winner']==$user['id']);
		$forfeit = $match['forfeit']==1 ? "(f)":"";
		if($isWinner) {
			$result = "Won";
			$opponent = $users[$match['loser']]['name'];
		} else {
			$result = "Lost";
			$opponent = $users[$match['winner']]['name'];
		}
		
		$date = date("n/j/Y", $match['date']);
		
		
		echo "<tr><td>$date</td><td>$opponent</td><td>$result $forfeit</td></tr>";
	}
	echo "</table>";
	
}

function generateSummary()
{
	global $users, $user;
	$db = DB::getDB();
	
	$matches = $db->getUserMatches($user['id']);
	$matchesPlayed = count($matches);
    
    if( $matchesPlayed > 0) {
	    $date_last = date("n/j/Y", $matches[0]['date']);
	    $t = end($matches);
	    $date_first = date("n/j/Y", $t['date']);
	} else {
	    $date_first = $date_last = date("n/j/Y",$user['create_date']);
	}
		
	$data = $db->getRankHistory($user['id']);

	
	$firstDate = $data[0]['date'];
	$tmp = end($data);
	$lastDate = $tmp['date'];
	$duration = $lastDate-$firstDate;
	
	$bestRankEver = $tmp['rank'];
	$bestRankRecent = $bestRankEver;
	foreach($data as $row) {
		$bestRankEver = min($bestRankEver, $row['rank']);
		if($row['date'] > time() - Config::BEST_RANK_WINDOW * (60*60*24)) {
			$bestRankRecent = min($bestRankRecent, $row['rank']);
		}
	}
	
	
	echo "<table style='border-width: 0px; width:85%; margin-left:auto; margin-right:auto;'>";
	echo "<tr><td>Dates Active</td><td>$date_first - $date_last</td></tr>";
	echo "<tr><td>Total matches played</td><td>$matchesPlayed</td></tr>";
	echo "<tr><td>Overall Record</td><td>{$user['wins']}-{$user['losses']} (".computeWinPct($user).")</td></tr>";
	echo "<tr><td>Best Ranking Ever</td><td>$bestRankEver</td></tr>";
	echo "<tr><td>Best Ranking (last " . Config::BEST_RANK_WINDOW . " days)</td><td>$bestRankRecent</td></tr>";
	echo "</table>";
	
		
}

function generateResultsByOpponent()
{
	global $users, $user;
	$db = DB::getDB();
	
	$matches = $db->getUserMatches($user['id']);
	$records = array();
	
	foreach($matches as $match) {
		$isWinner = ($match['winner']==$user['id']);
		if($isWinner) {
			if(!isset($records[$match['loser']])) {
				$records[$match['loser']] = array("wins"=>0, "losses"=>0);
			}
			$records[$match['loser']]['wins']+=1;
		} else {
			if(!isset($records[$match['winner']])) {
				$records[$match['winner']] = array("wins"=>0, "losses"=>0);
			}
			$records[$match['winner']]['losses']+=1;
		}
	}
	$opponent="";
	
	echo "<table style='width:85%; margin-left:auto; margin-right:auto;'>";
	echo "<tr><th>Opponent</th><th>Record</th></tr>";
	
	foreach($records as $id=>$result) {
		echo "<tr><td>{$users[$id]['name']}</td><td>{$result['wins']}-{$result['losses']}</td></tr>";
	}
	echo "</table>";
}

function generateHistoryGraph_old()
{
	global $users, $user;
	
	$data = $db->getRankHistory($user['id']);

	
	$firstDate = $data[0]['date'];
	$tmp = end($data);
	$lastDate = $tmp['date'];
	$duration = $lastDate-$firstDate;
		
	$totalPoints = 100;
	
	$na = array_fill(0,$totalPoints+1,-1);
	

	foreach($data as $row) {
		$frac = ($row['date']-$firstDate)/$duration;
		//echo $row['date']-$firstDate . "  ";
		$idx = round($totalPoints*$frac);
		$na[$idx]=max($row['rank'], $na[$idx]);
		//echo $idx . "  ";
	}
	
	$last=$na[0];
	for($i=0; $i<=$totalPoints; $i++) {
		if($na[$i]==-1) {
			$na[$i]=$last;
		} else {
			$last=$na[$i];
		}
	}                         
	
	$data = $na;
	
	$max=0;
	$min=9999;
	foreach($data as $row) {
		$max = max($max, $row['rank']);
		$min = min($min, $row['rank']);
	}
	$range = max(3,$max-$min);
	
	
	$d = "&amp;";
	$core_query = "http://chart.apis.google.com/chart?";
	$core_query .= "cht=lc" . $d;
	$core_query .= "chxt=y" . $d;
	$core_query .= "chxr=0,$max,$min,1" . $d;
	$core_query .= "chs=500x200" . $d;
	$core_query .= "chf=bg,s,feeebd" . $d;
	$core_query .= "chls=3" . $d;
	$core_query .= "chxs=0,4c3000,13,0,lt" . $d;
	$core_query .= "chxt=y". $d;
	//$core_query .= "chd=t:";  // No $d!
	$core_query .= "chd=s:";  // No $d!
	 
	foreach($data as $rank) {
		//$rank = $row['rank'];
		$scaled_rank = ($rank-$min)*(100/$range);
		$inverted_scaled_rank = 100-$scaled_rank;
		
		//$core_query .= $inverted_scaled_rank . ',';
		$core_query .= simpleEncode($inverted_scaled_rank);
	}
	
	$core_query = trim($core_query, ",");
	echo "<img src='$core_query'>";
	
	//error_log("Graph URL Length: ".strlen($core_query)."\n", 3, "debug.log");
	//print_r($core_query);
}

function generateHistoryGraph()
{
	global $users, $user;
	$db = DB::getDB();
	$data = $db->getRankHistory($user['id']);

	echo "[";
	foreach($data as $row)
	{
		echo "[". $row['date']*1000 . ",{$row['rank']}],";  
	}
	$t = time()*1000;
	echo "[" . $t . ",{$row['rank']}]";
	echo "]";
	
}


function simpleEncode($v, $max = 100, $min = 0){
        $simple_table =
'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $delta = $max - $min;
        $size = (strlen($simple_table)-1);

        
                if($v >= $min && $v <= $max){
                        $chardata = $simple_table[round($size * ($v - $min) / $delta)];
                }else{
                        $chardata = '_';
                }
        
        return($chardata);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php include_once("includes.php"); ?>
	<script language="javascript" type="text/javascript" src="flot/jquery.flot.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		
		var data=<?php generateHistoryGraph()?>;
		
		$.plot($("#plot"), [data], { xaxis: { mode: "time" } });
		$("#tabs").tabs();
		
		
	});
	</script>
</head>
<body>

<div class="container">
	<?php include_once("header.html"); 
	include_once("toolbar.html");?>

	<div class="prepend-5 span-14 append-5 last">
		<?php global $user;
		echo "<span style='font-size: 200%;'>" . $user["name"] . "'s Profile</span>";
		?>
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Summary</a></li>

				<li><a href="#tabs-2">Ranking History</a></li>
				<li><a href="#tabs-3">Matches Played</a></li>
				<li><a href="#tabs-4">Records</a></li>
			</ul>
				<div id="tabs-1">

					<?php generateSummary() ?>
				</div>
				<div id="tabs-2">
					<center><i><strong>This doesn't work yet.</strong></i></center>
					Ladder Position History
					<div id="plot" style="width:500px; height:300px;"></div>
				</div>
				<div id="tabs-3">
					<?php generateMatchHistory() ?>
				</div>
				<div id="tabs-4">
					<?php generateResultsByOpponent() ?>
				</div>
			</div>

			
		
	</div>
	
</div>

</body>
</html>
