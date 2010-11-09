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
    foreach($ladder as $row) {
        $isUser = ($row->id == $user->id);
        if( $isUser  ) {
            echo "<tr class='user'>";
        } else {
		echo "<tr>";
        }

		echo "<td>$ranking</td>";
        echo "<td>" . anchor("/profile/user/{$row->id}", $row->name) . "</td>";
        $wins = $row->wins;
        $losses = $row->losses;
        echo "<td class='record'>$wins-$losses (" . compute_win_pct($wins, $losses) . ")</td>";
        $challenges = $row->challenge_count;
        $window = 2;

        if( $row->can_challenge ) {
            echo "<td><button type='button' class='challengeButton jqbutton' action='challenge' param='{$row->id}'>Challenge</button>" . "</td>";
        } elseif( $row->status == User::INACTIVE ) {
            echo "<td><i>Inactive</i></td>";
        } else {
            echo "<td></td>";
        }
		echo "</tr>";
		$ranking++;
    }
    ?>
