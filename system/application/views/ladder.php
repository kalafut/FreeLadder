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
    <?php
    $ranking = 1;
    foreach($ladderRungs as $rung) {
        $isUser = ($rung['id'] == Current_User::user()->id);
        if( $isUser  ) {
            echo "<tr class='user'>";
        } else {
		echo "<tr class>";
        }

		echo "<td>$ranking</td>";
        echo "<td>{$rung['name']}</td>";
        $wins = $rung['Ladder_Users'][0]['wins'];
        $losses = $rung['Ladder_Users'][0]['losses'];
        echo "<td>$wins-$losses</td>";
        $challenges = $rung['Ladder_Users'][0]['challenge_count'];
        $window = 2;

        $alreadyChallenged = in_array($rung['id'], $challengedIds);

        if( !$isUser && !$alreadyChallenged) {
            echo "<td><button type='button' class='challengeButton jqbutton' action='challenge' param='{$rung['id']}'>Challenge</button>" . "</td>";
        } else {
            echo "<td></td>";
        }
		echo "</tr>";
		$ranking++;
    }
    ?>
