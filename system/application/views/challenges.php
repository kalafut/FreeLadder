<colgroup>
<col width="40%"/>
<col width="40%"/>
<col width="20%"/>
</colgroup>

<?php

if(count($challenges)==0) {
	echo "<tr><td colspan='3'><i class='large'>You have no pending matches.</i></td></tr>";
} else {
    foreach($challenges as $challenge) {
        echo '<tr>';
        echo '<td>' .  '</td>';
    }
}
