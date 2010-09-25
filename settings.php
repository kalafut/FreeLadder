<?php
/*
 * FreeLadder Ladder Server
 * http://freeladder.org
 *
 * Copyright 2010, Jim Kalafut
 * Released under the MIT license.
 * 
 */

/* Authorize first */
include_once("auth.php");
verifyAuthorization(); 


/* Other includes */
include_once("db.php");
include_once("util.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');


$db = DB::getDB();

$users = $db->getUserList();
$user=$users[$current_user];

dispatch();


function dispatch() {
	global $current_user, $CURRENT_VERSION, $user;
	
	$db = DB::getDB();
		
	//print_r ($_POST);
	if(!isset($_REQUEST["settings_submit"])) return;
	
	$email = (isset($_REQUEST["email"])) ? $_REQUEST["email"] : null;
	$email_notification = (isset($_REQUEST["email_notification"])) ? $_REQUEST["email_notification"] : null;
	$status = (isset($_REQUEST["status"])) ? $_REQUEST["status"] : null;
	$max_challenges = (isset($_REQUEST["max_challenges"])) ? $_REQUEST["max_challenges"] : null;
	$password = (isset($_REQUEST["password1"])) ? $_REQUEST["password1"] : null;


	
	if($email != null) {
		$user['email'] = strtolower($email);
	}
	
	if($email_notification == "1") {
		$user['email_notification']="1";
	} else {
		$user['email_notification']="0";
	}
	
	if($status != null) {
		$user['status']=$status;
	}
	
	if($max_challenges != null) {
		$user['max_challenges']=$max_challenges;
	}

	if($password != null) {
		$user['password']=md5(Config::SALT . $password);
		
		$expire = time()+60*60*24*(30);
		setcookie("ladder_id", $current_user, $expire);
	    setcookie("ladder_hash", md5(Config::SALT . $password), $expire);
		setcookie("ladder_version", $CURRENT_VERSION, $expire);
	}
	
	$db->updateUser($user);

	
	echo "<script type='text/javascript'>window.location = 'ladder.php'</script>";
	exit(0);
} 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php include_once("includes.php"); ?>
</head>
<body>

<div class="container">
	<?php include_once("header.html"); 
	include_once("toolbar.html");?>
    <form id='settings_form' name='settings' action='settings.php' method='post'>
	<div class="prepend-5 span-6 append-13 last">
			<h2>User Settings</h2>
	</div>
	
	<div class="prepend-5 span-14 append-5 last">
		<table class="settings" >
		    <col width="40%"/>
		    <col width="60%"/>
			<tr>
				<td>Email Address</td>
				<td><input class="settings_tf" type="text" name="email" value="<?php echo $user['email'] ?>"></td>
			</tr>
			<tr>
				<td>Receive email notifications *</td>
				<td><input type="checkbox" name="email_notification" value="1" <?php if($user['email_notification']) echo "checked='checked'"?> ></td>
			</tr>
			<tr>
				<td>Status *</td>
				<td><select name="status">
				  <option value="active" <?php if($user['status']=="active") echo "selected='selected'"?>>Active</option>
				  <option value="inactive" <?php if($user['status']=="inactive") echo "selected='selected'"?>>Inactive</option>
				  <option value="disabled" <?php if($user['status']=="disabled") echo "selected='selected'"?>>Disabled</option>
				</select></td>
			</tr>
			<tr>
				<td>Limit pending challenges *</td>
				<td><select name="max_challenges">
				  <option value="999" <?php if($user['max_challenges']=="999") echo "selected='selected'"?>>No limit</option>
				  <option value="1" <?php if($user['max_challenges']=="1") echo "selected='selected'"?>>1</option>
				  <option value="2" <?php if($user['max_challenges']=="2") echo "selected='selected'"?>>2</option>
				  <option value="3" <?php if($user['max_challenges']=="3") echo "selected='selected'"?>>3</option>
				  <option value="4" <?php if($user['max_challenges']=="4") echo "selected='selected'"?>>4</option>
				</select></td>
			</tr>
			<tr>
				<td>Change password (w/confirm)</td>
				<td><input class="settings_tf" id="pw1" type="password" name="password1"></td>
			</tr>
			<tr>
				<td></td>
				<td><input class="settings_tf" id="pw2" type="password" name="password2"></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center"><button type="button" id="settings_submit" style="font-size:1.1em;">Save</button></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:left">* These features have not been implemented yet and the settings do nothing.</td>
			</tr>
			
			</table>
	</div>
	
	<input type=hidden name="settings_submit" />
	
	</form>
</div>

<script type='text/javascript'>
	$(document).ready(function(){
	  	$("#settings_submit").button();
		
		$("#settings_submit").click(function() {
			var pw1 = $("#pw1").val();
			var pw2 = $("#pw2").val();
			if(pw1 != pw2) {
				alert("Passwords don't match!");
			} else {
				document.settings.submit();
			}
		})
	});		
</script>
</body>
</html>
