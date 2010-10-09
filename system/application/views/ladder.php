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
		echo "<tr class='live'>";
		echo "<td>$ranking</td>";
        echo "<td>{$rung['name']}</td>";
        $wins = $rung['Ladder_Users'][0]['wins'];
        $losses = $rung['Ladder_Users'][0]['losses'];
        echo "<td>$wins-$losses</td>";
        $challenges = $rung['challenge_count'];
        $window = 2;
        $alreadyChallenge = false;

        //if(!$alreadyChallenged && 
        echo "<td><button type='button' class='challengeButton' action='challenge' param='{rung['id']}'>Challenge</button>" . "</td>";
		echo "</tr>";
		$ranking++;
    }
    ?>
