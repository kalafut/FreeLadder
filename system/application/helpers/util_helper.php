<?php

function array_print($a, $tf)
{
    if($tf) {
        echo "<pre style='background: white'>";
        print_r($a);
        echo "</pre>";
    }
}

function compute_win_pct($wins, $losses) {
	if($wins + $losses == 0) return "0%";

	$pct = round(100*$wins/($wins+$losses));
	return "$pct%";
}
