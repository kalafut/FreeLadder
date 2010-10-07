Users:<br>
<?php
    foreach($Users as $user) {
        echo $user->email . " " . $user->id . "<br>";
        foreach($user->Ladders as $ladder) {
            echo "----" . $ladder->name . '<br>';
        }
    }
?>
<p>
Challenges:<br/>
<?php
    foreach($challenges as $challenge) {
        echo $challenge['Challenger']['name'];
        echo " ";
        echo $challenge['Opponent']['name'];
        echo "<br>";
    }
?>
