<script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    //var data=<?php $history_graph_data ?>;

    //$.plot($("#plot"), [data], { xaxis: { mode: "time" } });
    $("#tabs").tabs();
});
</script>

<div class="prepend-5 span-14 append-5 last">
<?php
echo "<span style='font-size: 200%;'>" . $user->name . "'s Profile</span>";
?>
<div id="tabs">
<ul>
    <li><a href="#tabs-1">Summary</a></li>
    <li><a href="#tabs-2">Ranking History</a></li>
    <li><a href="#tabs-3">Matches Played</a></li>
    <li><a href="#tabs-4">Records</a></li>
</ul>
<div id="tabs-1">
    <table style='border-width: 0px; width:85%; margin-left:auto; margin-right:auto;'>
        <tr><td>Dates Active</td><td>
<?php
if($summary['date_first']) {
    echo $summary['date_first'] ." - ". $summary['date_last']; 
} else {
    echo "No matches played";
}
?>
        </td></tr>
        <tr><td>Total matches played</td><td>
<?php echo $summary['matchesPlayed']; ?>
        </td></tr>
        <tr><td>Overall Record</td><td><?php echo $summary['wins'] . '-' . $summary['losses'] . " (".compute_win_pct($summary['wins'],$summary['losses']). ")" ?></td></tr>
        <tr><td>Best Ranking Ever</td><td><?php echo $summary['best_rank']; ?></td></tr>
<!--
                        <tr><td>Best Ranking (last " . Config::BEST_RANK_WINDOW . " days)</td><td>$bestRankRecent</td></tr>-->
                    </table>
                </div>
                <div id="tabs-2">
                    <center><i><strong>Sorry, this is still under development.</strong></i></center>
                    <!--Ladder Position History-->
                    <div id="plot" style="width:500px; height:300px;"></div>
                </div>
                <div id="tabs-3">
                <table style='width:85%; margin-left:auto; margin-right:auto;'>
                <tr><th>Date</th><th>Opponent</th><th>Result</th></tr>

                <?php

                foreach($matches as $match) {
                    $isWinner = ($match->winner_id == $user->id);
                    $forfeit = $match->forfeit == 1 ? "(f)":"";
                    if($isWinner) {
                        $result = "Won";
                        $opponent = $match->loser_name;
                    } else {
                        $result = "Lost";
                        $opponent = $match->winner_name;
                    }

                    $date = date("n/j/Y", $match->date);

                    echo "<tr><td>$date</td><td>$opponent</td><td>$result $forfeit</td></tr>";
                }
                ?>
        </table>
                </div>
                <div id="tabs-4">
                    <table style='width:85%; margin-left:auto; margin-right:auto;'>
                        <tr><th>Opponent</th><th>Record</th></tr>
                            <?php
                            foreach($records as $result) {
                                echo "<tr><td>{$result['name']}</td><td>{$result['wins']}-{$result['losses']}</td></tr>";
                            }
                            ?>
                    </table>
                </div>
            </div>
    </div>

</div>

</body>
</html>
