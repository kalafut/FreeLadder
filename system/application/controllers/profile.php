<?php
class Profile extends Controller
{
    private static $user;
    private static $user_id;
    private static $ladder_id;

    public function __construct() 
    {
        parent::Controller();
		$this->load->helper('util');
        $this->load->model('User');
        $this->load->model('Challenge');
        $this->load->model('Ladder');
        $this->load->model('Match');

        /* Assign some convenience variables used everywhere */
        $this->user = User::instance()->current_user();
        if( !$this->user ) {
            redirect('/login');
        }
        $this->user_id = $this->user->id;
        $this->ladder_id = $this->user->ladder_id;
    }

    function user($id)
    {
        if( ! $user = User::instance()->get($id) ) {
            redirect('/dashboard');
        }

        $var['content_view'] = 'profile';
        $var['user'] = $user;
        $var['summary'] = $this->generate_summary($id);
        $var['matches'] = $this->generate_match_history($id);

        array_print($var,0);

        $this->load->view('template', $var);
    }


    function generate_match_history($id)
    {
        $this->db->select('m.winner_id, m.loser_id, m.id, m.date AS date, 
            m.forfeit, w.name AS winner_name, l.name AS loser_name')
            ->from('matches m')
            ->join('users w', 'w.id = m.winner_id')
            ->join('users l', 'l.id = m.loser_id')
            ->where('m.ladder_id', $this->ladder_id)
            ->where('m.winner_id', $id)
            ->or_where('m.loser_id', $id)
            ->order_by('date DESC');
            
        $matches = $this->db->get()->result();
        array_print($matches,0);

        return $matches;
    }

    function generate_summary($id)
    {
        $summary = array();

        $this->db->select('m.winner_id, m.loser_id, m.date AS date, 
            m.forfeit')
            ->from('matches m')
            ->join('users w', 'w.id = m.winner_id')
            ->join('users l', 'l.id = m.loser_id')
            ->where('m.ladder_id', $this->ladder_id)
            ->where('m.winner_id', $id)
            ->or_where('m.loser_id', $id)
            ->order_by('date DESC');
            
        $matches = $this->db->get()->result();
        array_print($matches,0);
        $matchesPlayed = count($matches);

        if( $matchesPlayed > 0) {
            $date_last = date("n/j/Y", $matches[0]->date);
            $t = end($matches);
            $date_first = date("n/j/Y", $t->date);
        } else {
            $date_first = $date_last = NULL;
        }

        $summary['matchesPlayed'] = $matchesPlayed;
        $summary['date_first'] = $date_first;
        $summary['date_last'] = $date_last;


        $this->db
            ->select('wins, losses')
            ->from('ladder_users lu')
            ->where('lu.ladder_id', $this->ladder_id)
            ->where('lu.user_id', $id);
            
        $win_loss = $this->db->get()->row();

        $summary['wins'] = $win_loss->wins;
        $summary['losses'] = $win_loss->losses;
        
        $this->db
            ->select_min('rank')
            ->from('rank_history')
            ->where('ladder_id', $this->ladder_id)
            ->where('user_id', $id);

        $summary['best_rank'] = $this->db->get()->row()->rank;


/*
        $data = $db->getRankHistory($user['id']);


        $firstDate = $data[0]['date'];
        $tmp = end($data);
        $lastDate = $tmp['date'];
        $duration = $lastDate-$firstDate;

        $bestRankEver = $tmp['rank'];
        $bestRankRecent = $bestRankEver;
        foreach($data as $row) {
            $bestRankEver = min($bestRankEver, $row['rank']);
            if($row['date'] > time() - Config::BEST_RANK_WINDOW * (60*60*24)) {
                $bestRankRecent = min($bestRankRecent, $row['rank']);
            }
        }
 */
        return $summary;

    }
/*
    function generateResultsByOpponent()
    {
        $matches = $db->getUserMatches($user['id']);
        $records = array();

        foreach($matches as $match) {
            $isWinner = ($match['winner']==$user['id']);
            if($isWinner) {
                if(!isset($records[$match['loser']])) {
                    $records[$match['loser']] = array("wins"=>0, "losses"=>0);
                }
                $records[$match['loser']]['wins']+=1;
            } else {
                if(!isset($records[$match['winner']])) {
                    $records[$match['winner']] = array("wins"=>0, "losses"=>0);
                }
                $records[$match['winner']]['losses']+=1;
            }
        }
        $opponent="";

        echo "<table style='width:85%; margin-left:auto; margin-right:auto;'>";
        echo "<tr><th>Opponent</th><th>Record</th></tr>";

        foreach($records as $id=>$result) {
            echo "<tr><td>{$users[$id]['name']}</td><td>{$result['wins']}-{$result['losses']}</td></tr>";
        }
        echo "</table>";
    }

    function generateHistoryGraph_old()
    {
        global $users, $user;

        $data = $db->getRankHistory($user['id']);


        $firstDate = $data[0]['date'];
        $tmp = end($data);
        $lastDate = $tmp['date'];
        $duration = $lastDate-$firstDate;

        $totalPoints = 100;

        $na = array_fill(0,$totalPoints+1,-1);


        foreach($data as $row) {
            $frac = ($row['date']-$firstDate)/$duration;
            //echo $row['date']-$firstDate . "  ";
            $idx = round($totalPoints*$frac);
            $na[$idx]=max($row['rank'], $na[$idx]);
            //echo $idx . "  ";
        }

        $last=$na[0];
        for($i=0; $i<=$totalPoints; $i++) {
            if($na[$i]==-1) {
                $na[$i]=$last;
            } else {
                $last=$na[$i];
            }
        }                         

        $data = $na;

        $max=0;
        $min=9999;
        foreach($data as $row) {
            $max = max($max, $row['rank']);
            $min = min($min, $row['rank']);
        }
        $range = max(3,$max-$min);


        $d = "&amp;";
        $core_query = "http://chart.apis.google.com/chart?";
        $core_query .= "cht=lc" . $d;
        $core_query .= "chxt=y" . $d;
        $core_query .= "chxr=0,$max,$min,1" . $d;
        $core_query .= "chs=500x200" . $d;
        $core_query .= "chf=bg,s,feeebd" . $d;
        $core_query .= "chls=3" . $d;
        $core_query .= "chxs=0,4c3000,13,0,lt" . $d;
        $core_query .= "chxt=y". $d;
        //$core_query .= "chd=t:";  // No $d!
        $core_query .= "chd=s:";  // No $d!

        foreach($data as $rank) {
            //$rank = $row['rank'];
            $scaled_rank = ($rank-$min)*(100/$range);
            $inverted_scaled_rank = 100-$scaled_rank;

            //$core_query .= $inverted_scaled_rank . ',';
            $core_query .= simpleEncode($inverted_scaled_rank);
        }

        $core_query = trim($core_query, ",");
        echo "<img src='$core_query'>";

        //error_log("Graph URL Length: ".strlen($core_query)."\n", 3, "debug.log");
        //print_r($core_query);
    }

    function generateHistoryGraph()
    {
        global $users, $user;
        $db = DB::getDB();
        $data = $db->getRankHistory($user['id']);

        echo "[";
        foreach($data as $row)
        {
            echo "[". $row['date']*1000 . ",{$row['rank']}],";  
        }
        $t = time()*1000;
        echo "[" . $t . ",{$row['rank']}]";
        echo "]";

    }


    function simpleEncode($v, $max = 100, $min = 0){
        $simple_table =
            'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $delta = $max - $min;
        $size = (strlen($simple_table)-1);


        if($v >= $min && $v <= $max){
            $chardata = $simple_table[round($size * ($v - $min) / $delta)];
        }else{
            $chardata = '_';
        }

        return($chardata);
    }
*/
}
