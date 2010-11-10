<colgroup>
<col width="40%"/>
<col width="40%"/>
<col width="20%"/>
</colgroup>

<?php

function c_count($c, $v)
{
    if($v->user_challenge) {
        $c += 1;
    }
    return $c;
}

$user_challenge_count = array_reduce($challenges, "c_count", 0);

if( count($challenges) == 0) {
	echo "<tr><td colspan='3'><i class='large'>There are no pending matches.</i></td></tr>";
} else {
    array_print($challenges,0);
    foreach($challenges as $c) {
        if($c->user_challenge) {
            echo '<tr>';
            echo '<td>' . $c->opp_name . '</td>';
            if( $c->mode == Challenge::STATUS_NORMAL ) {
                echo "<td><div class='won_lost'>";
                echo "<button type='button' class='resultButton jqbutton' action='won' param='{$c->id}'>I Won</button>";
                echo "<button type='button' class='resultButton jqbutton' action='lost' param='{$c->id}'>I Lost</button>";
                echo "</div></td>";
                echo "<td><button type='button' class='forfeitButton jqbutton' action='forfeit' param='{$c->id}'>Forfeit</button></td>";
            } elseif( $c->mode == Challenge::STATUS_WAITING ) {
                echo "<td>Waiting for<br>confirmation</td>";
                echo "<td><button type='button' class='forfeitButton jqbutton' action='forfeit' param='{$c->id}'>Forfeit</button></td>";
            } elseif( $c->mode == Challenge::STATUS_REVIEW ) {
                if($c->user_result == Match::WON) {
                    $result = 'won';
                } else {
                    $result = 'lost';
                }
                echo "<td><span class='ui-state-error'>&nbsp;Review Needed&nbsp;</span></td>";
                echo "<td><button type='button' class='reviewButton' action='review' param='{$c->id}' opponent='{$c->opp_name}' result='$result' }'>Review</button></td>";
            }
            echo '</tr>';
        }
    }
}

foreach($challenges as $c) {
    if(!$c->user_challenge) {
        echo "<tr class='other_challenges'><td>{$c->name1}</td><td>{$c->name2}</td><td></td></tr>";
    }
}
