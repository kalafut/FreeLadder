<?php
require_once("db.php");

class Ladder 
{
    public $id;
    public $name;
    public $access_name;
    public $type;
    public $status;

    public static function getLadderByAccess($access)
    {
        $db = DB::getDB();
        
	    $stmt = $db->prepare('SELECT * FROM ladder WHERE access_name=:access_name COLLATE NOCASE');
	    $stmt->bindValue(':access_name',$access,PDO::PARAM_STR);
	    $stmt->execute();		
	    $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if( $result ) {
            $obj = new Ladder();
            $obj->id = $result['ladder_id'];
            $obj->name = $result['ladder_name'];
            $obj->access_name = $result['access_name'];
            $obj->type = $result['type'];
            $obj->status = $result['status'];

            return $obj;
        } else {
            return null;
        }
    }

    function addUser($user)
    {
        $db = DB::getDB();
        $rank = $this->getHighestRank() + 1;

        $stmt = $db->prepare('INSERT INTO ladder_user(user_id, ladder_id) VALUES(:user_id, :ladder_id)');

        $stmt->bindValue(':user_id',$this->user_id,PDO::PARAM_STR);
	    $stmt->bindValue(':ladder_id',$this->ladder_id,PDO::PARAM_STR);

	    $stmt->execute();       
    }

	function getHighestRank() {
	    $stmt = $this->db->prepare('SELECT MAX(rank) AS max_rank FROM ladder_user WHERE ladder_id=:ladder_id');
        $stmt->bindValue(':ladder_id',$this->ladder_id,PDO::PARAM_STR);
	    $stmt->execute();       

	    $result = $stmt->fetch(PDO::FETCH_ASSOC);
	    return $result['max_rank'];
	}
}

?>
