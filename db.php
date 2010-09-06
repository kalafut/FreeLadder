<?php
require_once("config.php");
require_once("user.php");
require_once("log.php");

class DB {
	private static $instance;
	
	private $db;
	
	function __construct() {
		$this->db = new PDO('sqlite:' . Config::DB_LOCATION . '/ladder.db');
	}
	
	public static function getDB() {
		if(!isset($instance)) {
			$instance = new DB();
		}
		
		return $instance;
	}

	function getUserList()
	{  
	    $stmt = $this->db->prepare('SELECT * FROM users ORDER BY rank');
	    $stmt->execute();		
    
	    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user = new User();
            $user->id = $row["id"];
            $user->name = $row["name"];
            $user->email = $row["email"];
            $user->password = $row["password"];
            $user->rank = $row["rank"];
            $user->status = $row["status"];
            $user->wins = $row["wins"];
            $user->losses = $row["losses"];
            $user->maxChallenges = $row["max_challenges"];
            $user->emailNotification = $row["email_notification"];
            $user->admin = $row["admin"] ? true:false ;
            
	        $result[$row["id"]]=$row;
	    }
    
	    return $result;
	}

    
	function updateUser($user) 
	{
	    $stmt = $this->db->prepare('UPDATE users SET email=:email, email_notification=:email_notification, status=:status, max_challenges=:max_challenges, password=:password WHERE id=:id');

	    $stmt->bindValue(':email',$user['email'],PDO::PARAM_STR);
	    $stmt->bindValue(':email_notification',$user['email_notification'],PDO::PARAM_STR);
	    $stmt->bindValue(':status',$user['status'],PDO::PARAM_STR);
	    $stmt->bindValue(':max_challenges',$user['max_challenges'],PDO::PARAM_STR);
	    $stmt->bindValue(':password',$user['password'],PDO::PARAM_STR);
	    $stmt->bindValue(':id',$user['id'],PDO::PARAM_STR);

	    $stmt->execute();
	}

	function lastInsertId()
	{
	    return $this->db->lastInsertId();
	}

	function getChallenges()
	{	    
	    $stmt = $this->db->prepare('SELECT * FROM challenges');
	    $stmt->execute();       

	    $result = array();

	    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	        $result[$row["id"]]=$row;
	    }

	    return $result;
	}

	function getChallenge($challengeID)
	{
	    $stmt = $this->db->prepare('SELECT * FROM challenges WHERE id=:id');
		$stmt->bindValue(':id',$challengeID,PDO::PARAM_STR);
	    $stmt->execute();       

	    return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    function prepare($query)
    {
        return $this->db->prepare($query);
    }

	function addChallenge($challenger, $opponent) 
	{
	    global $challenges;

	    foreach($challenges as $c) {
	        if($c["challenger"]==$challenger && $c["opponent"]==$opponent) {
	            return;
	        }
	    }	      
	
	    $stmt = $this->db->prepare('INSERT INTO challenges(date, challenger, opponent) VALUES (strftime("%s","now", "localtime"),:challenger, :opponent)');

	    $stmt->bindValue(':challenger',$challenger,PDO::PARAM_STR);
	    $stmt->bindValue(':opponent',$opponent,PDO::PARAM_STR);
	
	    $stmt->execute();
	}

	function cancelChallenge($id)
	{      
	    $stmt = $this->db->prepare('DELETE FROM challenges WHERE id=:id');

	    $stmt->bindValue(':id',$id,PDO::PARAM_STR);

	    $stmt->execute();    
	}

	function addMatch($winner, $loser, $forfeit)
	{  
	    $stmt = $this->db->prepare('INSERT INTO matches(date, winner, loser, forfeit) VALUES (strftime("%s","now", "localtime"),:winner, :loser, :forfeit)');

	    $stmt->bindValue(':winner',$winner,PDO::PARAM_STR);
	    $stmt->bindValue(':loser',$loser,PDO::PARAM_STR);
		$stmt->bindValue(':forfeit',$forfeit ? 1:0,PDO::PARAM_STR);

	    $stmt->execute();

		$stmt = $this->db->prepare('SELECT last_insert_rowid() AS id FROM matches LIMIT 1');
		$stmt->execute();
	    $row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row["id"];
	}

	function getMatches($count) {
		  
	    $stmt = $this->db->prepare('SELECT * FROM matches ORDER BY date DESC LIMIT :limit' );

	    $stmt->bindValue(':limit',$count,PDO::PARAM_STR);
	    $stmt->execute();       

	    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    return $result;
	}

	function getUserMatches($userId) {
	    $stmt = $this->db->prepare('SELECT * FROM matches WHERE loser=:id OR winner=:id ORDER BY date DESC' );

	    $stmt->bindValue(':id',$userId,PDO::PARAM_STR);
	    $stmt->execute();       

	    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    return $result;
	}

	function getRankHistory($userId) {	  
	    $stmt = $this->db->prepare('SELECT rank, date FROM rank_history WHERE user_id=:user_id ORDER BY date' );

	    $stmt->bindValue(':user_id',$userId,PDO::PARAM_STR);
	    $stmt->execute();       

	    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    return $result;	
	}

	function getUserResultCount($userId, $wins) {
		if($wins) {
	    	$stmt = $this->db->prepare('SELECT COUNT(*) AS count FROM matches WHERE winner=:id' );
		} else {
			$stmt = $this->db->prepare('SELECT COUNT(*) AS count FROM matches WHERE loser=:id' );
		}

	    $stmt->bindValue(':id',$userId,PDO::PARAM_STR);
	    $stmt->execute();       

	    $result = $stmt->fetch(PDO::FETCH_ASSOC);

	    return $result['count'];
	}
	
	function getHighestRank() {
	    $stmt = $this->db->prepare('SELECT MAX(rank) AS max_rank FROM users');
	    $stmt->execute();       

	    $result = $stmt->fetch(PDO::FETCH_ASSOC);
	    return $result['max_rank'];
	}
	
	function emailExists($email) {
	    
	    $stmt = $this->db->prepare('SELECT COUNT(*) AS count FROM users WHERE email=:email' );
	    $stmt->bindValue(':email',$email,PDO::PARAM_STR);
	    $stmt->execute();       

	    $result = $stmt->fetch(PDO::FETCH_ASSOC);
	    if( $result['count'] > 0) {
	        return true;
	    } else {
	        return false;
	    }
	}


	function updateRankings($users, $oldUsers, $matchId) {
	    foreach($users as $user) {
			// Update users table with latest ranking
	        $stmt = $this->db->prepare('UPDATE users SET rank=:rank, wins=:wins, losses=:losses WHERE id=:id');
	        $stmt->bindValue(':id',$user['id'],PDO::PARAM_STR);
	        $stmt->bindValue(':rank',$user['rank'],PDO::PARAM_STR);
	        $stmt->bindValue(':wins',$user['wins'],PDO::PARAM_STR);
	        $stmt->bindValue(':losses',$user['losses'],PDO::PARAM_STR);

	        $stmt->execute();

			// Update ranking history only if rank has changed
			if($user['rank'] != $oldUsers[$user['id']]['rank']) {
				//error_log($user['rank']."\n", 3, "debug.log");
				//error_log($oldUsers[$user['id']]['rank']."\n", 3, "debug.log");
			
		        $stmt = $this->db->prepare('INSERT INTO rank_history (date, user_id, rank) VALUES (strftime("%s","now", "localtime"), :user_id, :rank)');
		        $stmt->bindValue(':user_id',$user['id'],PDO::PARAM_STR);
		        $stmt->bindValue(':rank',$user['rank'],PDO::PARAM_STR);
	       
		        $stmt->execute();			
			}
	    }
	}

	function updateReportedResult($challengeId, $current_user, $won) 
	{	
		$challenges = $this->getChallenges();
	
		$c = $challenges[$challengeId];
	
		if($c["challenger"]==$current_user) {
			$field = "challenger_result";
		} else {
			$field = "opponent_result";
		}
	
		$result = $won ? 1 : -1;
	
		$stmt = $this->db->prepare("UPDATE challenges SET $field=:result WHERE id=:id");
		$stmt->bindValue(':result',$result,PDO::PARAM_STR);
		$stmt->bindValue(':id',$challengeId,PDO::PARAM_STR);
	
		$stmt->execute();
	}

	function validateLogin($email, $password) {
	    $stmt = $this->db->prepare('SELECT id, password FROM users WHERE email=:email');
	    $stmt->bindValue(':email',$email,PDO::PARAM_STR);
	    $stmt->execute();       

	    $row = $stmt->fetch(PDO::FETCH_ASSOC);

	    if($row==null) {
	        return -1;
	    } elseif ($row['password'] != md5(Config::SALT . $password)) {
	        return -2;
	    } else {
	        return $row['id'];
	    }
	}

	function validateSession($id, $hash)
	{  
	    $stmt = $this->db->prepare('SELECT id, password FROM users WHERE id=:id AND password=:password');
	    $stmt->bindValue(':id',$id,PDO::PARAM_STR);
	    $stmt->bindValue(':password',$hash,PDO::PARAM_STR);
	    $stmt->execute();       

	    $row = $stmt->fetch(PDO::FETCH_ASSOC);

	    if($row==null) {
	        return 0;
	    } else {
	        return 1;
	    }
	}
}

?>
