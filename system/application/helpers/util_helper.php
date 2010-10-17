<?php

function array_print($a, $tf)
{
    if($tf) {
        echo "<pre style='background: white'>";
        print_r($a);
        echo "</pre>";
    }
}

function compute_win_pct($wins, $losses) 
{
	if($wins + $losses == 0) return "0%";

	$pct = round(100*$wins/($wins+$losses));
	return "$pct%";
}

function fb_time($event_time)
{
    $age = time() - $event_time;

    if($age < 60) {
        $s = "seconds ago";
        return $s;
    } elseif ($age < (60*60)) {
        $v = intval($age/60);
        $u = "minute";
    } elseif ($age < (60*60*24)) {
        $v = intval($age/(60*60));
        $u = "hour";
    } elseif ($age < (60*60*24*7)) {
        $v = intval($age/(60*60*24));
        $u = "day";
    } else {
        $v = intval($age/(60*60*24*7));
        $u = "week";
    }

    if( $v==1 ) {
        $s = "1 $u ago";
    } else {
        $s = $v . " {$u}s ago";
    }

    return $s;
}
