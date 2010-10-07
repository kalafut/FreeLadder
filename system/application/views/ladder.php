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
        echo "<td>Record</td>";
        $challenges = $rung['c_cnt'] + $rung['rc_cnt'];
        $window = 2;
        $alreadyChallenge = false;

        //if(!$alreadyChallenged && 
        echo "<td><button type='button' class='challengeButton' action='challenge' param='{rung['id']}'>Challenge</button>" . "</td>";
		echo "</tr>";
		$ranking++;
    }
    ?>
