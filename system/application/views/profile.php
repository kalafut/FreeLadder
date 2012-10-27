<script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
            //var data=<?php $history_graph_data ?>;

            //$.plot($("#plot"), [data], { xaxis: { mode: "time" } });
            //$("#tabs").tabs();
            $(".tab_pane").hide();
            $("#tab-1").show();
            //$("a[data-toggle='tab']").bind("click", function() { alert("hi");});
            $("ul.nav-pills li a").bind("click", function() {
                var id = $(this).attr("data-target");
                $(".tab_pane").hide();
                $("#"+id).show();
            });
        });
</script>
<div class="row">
    <div class="offset1 span6">
        <h2><?php echo $user->name . "'s Profile";?></h3>
    </div>
    <div class="span5"></div>
</div>

<div class="row">
    <div class="offset1 span6">
        <ul class="nav nav-pills">
            <li class="active"><a href="#tabs-1" data-toggle="tab" data-target="tab-1">Summary</a></li>
            <li><a href="#tabs-3" data-toggle="tab" data-target="tab-2">Records</a></li>
            <li><a href="#tabs-2" data-toggle="tab" data-target="tab-3">All Matches Played</a></li>
            <li><a href="#tabs-4" data-toggle="tab" data-target="tab-4">Ranking History</a></li>
        </ul>
        <div class="tab_pane" id="tab-1">
            <table class="table">
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
                <tr><td>Rating</td><td><?php echo intval($summary['rating']) . ' / ' . intval($summary['rd']); ?></td></tr>
                <tr><td>Overall Record</td><td><?php echo $summary['wins'] . '-' . $summary['losses'] . " (".compute_win_pct($summary['wins'],$summary['losses']). ")" ?></td></tr>
                <tr><td>Best Ranking Ever</td><td><?php echo $summary['best_rank']; ?></td></tr>
                <!--
                <tr><td>Best Ranking (last " . Config::BEST_RANK_WINDOW . " days)</td><td>$bestRankRecent</td></tr>-->
            </table>
        </div>
         <div class="tab_pane" id="tab-2">
            <table class="table">
                <tr><th>Opponent</th><th>Record</th></tr>
                <?php
                foreach($records as $result) {
                    echo "<tr><td>";
                    echo anchor("/profile/user/{$result['id']}", $result['name'], array('style'=>'color:#0074C7'));
                    echo "</td><td>{$result['wins']}-{$result['losses']}</td></tr>";
                }
                ?>
            </table>
        </div>
        <div class="tab_pane" id="tab-3">
            <table class="table">
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

        <div class="tab_pane" id="tab-4">
            <center><i><strong>Sorry, this is still under development.</strong></i></center>
            <!--Ladder Position History-->
            <div id="plot" style="width:500px; height:300px;"></div>
        </div>
    </div>
    <div class="span5"></div>

</div>
</body>
</html>
