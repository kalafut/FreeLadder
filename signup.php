<?php
/*
 * FreeLadder Ladder Server
 * http://freeladder.org
 *
 * Copyright 2010, Jim Kalafut
 * Released under the MIT license.
 * 
 */
 
    include_once("config.php");
    include_once("db.php");
	include_once("user.php");
	include_once("util.php");	
	
	$db = DB::getDB();
	
	
	$action = (isset($_REQUEST["action"])) ? $_REQUEST["action"] : null;
	$param = (isset($_REQUEST["param"])) ? $_REQUEST["param"] : null;
	$email = (isset($_REQUEST["email"])) ? $_REQUEST["email"] : null;
	$name = (isset($_REQUEST["name"])) ? $_REQUEST["name"] : null;
	$password = (isset($_REQUEST["password"])) ? $_REQUEST["password"] : null;
	
	/* If we're here for the first time, we can't be adding a user */
	$success = isset($_REQUEST["form_submit"]);
	
	
	if($success && $name == null) {
        $msg = "Your full name is required";
        $success = false;
    }
    
    if($success && $email == null) {
        $msg = "An email address is required";
        $success = false;
    }
    
    if($success && $db->emailExists($email)) {
        $msg = "Email address already in use";
        $success = false;
    } 
    
    if($success) {
        $user = new User();
        $user->email = $email;
        $user->name = $name;
        $user->password = md5(Config::SALT . $password);
        
        $user->add();
    }
    


            //     if($login != null) {
            //         $id = validateLogin($login, $password);
            //         if($id == -1) {
            //             $msg =  "Username not found";
            //         } elseif ($id == -2) {
            //             $msg =  "Incorrect password";
            //         } else {
            //             $expire = time()+60*60*24*(30);
            //             setcookie("ladder_id", $id, $expire);
            //             setcookie("ladder_hash", md5(Config::SALT . $password), $expire);
            // setcookie("ladder_version", $CURRENT_VERSION, $expire);
            //             
            //             echo "<script type='text/javascript'>window.location = 'ladder.php'</script>";
            //         }
            //     }

	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
		"http://www.w3.org/TR/html4/strict.dtd">
	<html>
	<head>
		<?php include_once("includes.php"); ?>
		<?php if(!$success) { ?>
		<script type="text/javascript" src="<?php echo auto_version('/js/signup.js')?>"></script>
        <?php } ?>

	</head>
	<body class="login">
	
	<?php if(!$success) { ?>
	<form id='signup_form' name='ladder' action='signup.php' method='post'>  
	<table class="login" style="width:40%;margin-left:auto; margin-right:auto; margin-top:80px;">
	<tr><td>Your full name:</td><td><input id="name" type="text" name="name" 
	value="<?php echo $name ? $name : '' ?>"
	></td></tr>
	<tr><td>Email address:</td><td><input id="email" type="text" name="email"
	value="<?php echo $email ? $email : '' ?>"
	></td></tr>
	<tr><td>Password:</td><td><input type="password" id="password" name="password"></td></tr>
	<tr><td>Password (confirm):</td><td><input type="password" id="password_confirm" name="password_confirm"></td></tr>
	<tr><td>Ladder Name:</td><td><input type="password" name="ladder_name"></td></tr>
	
	<?php if(isset($msg)) {?>
	<tr id="msg_row"><td colspan="2"><span class="ui-state-error">&nbsp;<?php echo $msg; ?>&nbsp;</span></td></tr>
	<?php }?>
	
	<tr><td colspan="2"><input style="font-size:0.7em;" id="signup_button" type="submit" value="Create Account"></td></tr>
	
	</table>
	<input type="hidden" name="form_submit">	
	</form>
	<?php } else { ?>
	    <table class="login" style="width:40%;margin-left:auto; margin-right:auto; margin-top:80px;">
    	<tr><td>Your account has been successfully created!</td></tr>
    	<tr><td><a style="font-size:0.7em;" href="login.php">Return to login page</a></td></tr>
    	</table>
	<?php } ?>    
</body>
</html>
