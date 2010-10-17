<?php
	foreach($matches as $match) {
		$forfeit = $match->forfeit==1 ? "(f)":"";

		echo "<tr>";
		echo "<td class='vtop'>{$match->winner_name}</td><td class='vtop'><div style='line-height:100%'>def.<br><span class='muted_time'>" . fb_time($match->date) . "</span></div></td><td class='vtop'>{$match->loser_name} $forfeit</td>";
		echo "</tr>";
	}
