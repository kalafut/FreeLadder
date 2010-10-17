<colgroup>
<col width="40%"/>
<col width="40%"/>
<col width="20%"/>
</colgroup>

<?php

if(count($challenges)==0) {
	echo "<tr><td colspan='3'><i class='large'>You have no pending matches.</i></td></tr>";
} else {
    foreach($challenges as $c) {
        echo '<tr>';
        echo '<td>' . $c->opp_name . '</td>';
        echo "<td><div class='won_lost' id=''>";
        echo "<button type='button' class='resultButton jqbutton' action='won' param='{$c->id}'>I Won</button>";
        echo "<button type='button' class='resultButton jqbutton' action='lost' param='{$c->id}'>I Lost</button>";
        echo "</div></td>";
        echo "<td><button type='button' class='forfeitButton jqbutton' action='forfeit' param='{$c->id}'>Forfeit</button></td>";
    }
}
