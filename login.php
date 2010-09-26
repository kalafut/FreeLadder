<?php
/*
 * FreeLadder Ladder Server
 * http://freeladder.org
 *
 * Copyright 2010, Jim Kalafut
 * Released under the MIT license.
 * 
 */
include_once("auth.php");
include_once("db.php");
include_once("util.php");
	

	
$email = (isset($_REQUEST["email"])) ? $_REQUEST["email"] : null;
$password = (isset($_REQUEST["password"])) ? $_REQUEST["password"] : null;
$logout = (isset($_REQUEST["logout"])) ? $_REQUEST["logout"] : null;

if( $logout ) {
    deauthorize();
    header("Location: login.php"); 
    return;
}

if( $email ) {
    $db = DB::getDB();	
    $id = $db->validateLogin($email, $password);
    if($id == DB::USER_NOT_FOUND) {
        $msg =  "Email address not found";
    } elseif ($id == DB::INCORRECT_PASSWORD) {
        $msg =  "Incorrect password";
    } else {
        authorize($id);
        header("Location: ladder.php");
        return;
    }
}

?>


	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
		"http://www.w3.org/TR/html4/strict.dtd">
	<html>
	<head>
		<?php include_once("includes.php"); ?>

		<script type="text/javascript">
			$(document).ready(function() {
				$("#login_button").button();
				$("#email").focus();				
			});
		</script>
	</head>
	<body class="login">
	<form id='ladder_form' name='ladder' action='login.php' method='post'>  
	<table class="login" style="width:35%;margin-left:auto; margin-right:auto; margin-top:80px;">

	<tr><td>Email address:</td><td><input id="email" type="text" name="email"></td></tr>
	<tr><td>Password:</td><td><input type="password" name="password"></td></tr>
	<?php if(isset($msg)) {?>
	<tr id="msg_row"><td colspan="2"><span class="ui-state-error">&nbsp;<?php echo $msg; ?>&nbsp;</span></td></tr>
	<?php }?>
	<tr><td colspan="2"><input style="font-size:0.7em;" id="login_button" type="submit" value="Login"></td></tr>
	<tr><td colspan="2"><a style="font-size:0.7em;" href="signup.php">Sign Up Now</a></td></tr>
	
	</table>
		
	</form>
</body>
</html>
