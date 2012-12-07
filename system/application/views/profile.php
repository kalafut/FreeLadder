<script src="<?php echo auto_version('/js/raphael-min.js'); ?>"></script>
<script src="<?php echo auto_version('/js/g.raphael-min.js'); ?>"></script>
<script src="<?php echo auto_version('/js/g.line-min.js'); ?>"></script>
<script src="<?php echo auto_version('/js/date.format.js'); ?>"></script>
<script type="text/javascript">
var ratings_x = [<?php foreach($rating_history as $r) {echo $r->date . ","; } ?> ];
var ratings_y = [<?php foreach($rating_history as $r) {echo $r->rating . ","; } ?> ];
var ratings_none = [<?php foreach($rating_history as $r) { echo $r->rating - 50 . ","; } ?> ];
var graph_shown = false;

</script>
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
            <li><a href="#tabs-4" data-toggle="tab" data-target="tab-4">Rating History</a></li>
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
                <tr><td>Rating</td><td><?php echo intval($summary['rating']) . ' ± ' . intval($summary['rd']); ?> <a href="/instructions#ratings"><i class="icon-question-sign"></i></a></td></tr>
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
            <div id="rating_graph"></div>

        </div>
    </div>
    <div class="span5"></div>

</div>
<script>
$('a[data-target="tab-4"]').on('shown', function (e) {
    if(graph_shown) {
        return;
    }
    var r = Raphael("rating_graph"),
    txtattr = { font: "12px sans-serif" };

    var chart = r.linechart(20, 0, 500, 220, [ratings_x,ratings_x], [ratings_y,ratings_none], { nostroke: false, axis: "0 0 1 1", smooth: true, colors: [
       "#5555ee",       // the first line is red
       "transparent"    // the third line is invisible
       ] });
                // change the x-axis labels
                var axisItems = chart.axis[0].text.items
                for( var i = 0, l = axisItems.length; i < l; i++ ) {
                 var date = new Date(1000*parseInt(axisItems[i].attr("text")));
       // using the excellent dateFormat code from Steve Levithan
       axisItems[i].attr("text", dateFormat(date, "m/yy"));
    graph_shown = true;
   }
});
</script>
</body>
</html>
