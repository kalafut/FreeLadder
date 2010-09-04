<?php
	include_once("db.php");
	include_once("util.php");
	
	$db = new DB();	
	
	$login = (isset($_REQUEST["login"])) ? $_REQUEST["login"] : null;
    $password = (isset($_REQUEST["password"])) ? $_REQUEST["password"] : null;
    $logout = (isset($_REQUEST["logout"])) ? $_REQUEST["logout"] : null;
    
    if($logout != null) {
        setcookie("ladder_id", "", time()-3600);
        setcookie("ladder_hash", "", time()-3600);
        echo "<script type='text/javascript'>window.location = 'login.php'</script>";
                
        exit();
    }

    if($login != null) {
        $id = $db->validateLogin($login, $password);
        if($id == -1) {
            $msg =  "Username not found";
        } elseif ($id == -2) {
            $msg =  "Incorrect password";
        } else {
            $expire = time()+60*60*24*(30);
            setcookie("ladder_id", $id, $expire);
            setcookie("ladder_hash", md5(Config::SALT . $password), $expire);
			setcookie("ladder_version", $CURRENT_VERSION, $expire);
            
            echo "<script type='text/javascript'>window.location = 'ladder.php'</script>";
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
				
				$("#login").focus();
				
				
			});
			
		
		    // FB.Event.subscribe('auth.sessionChange', function(response) {
		    // if (response.session) {
		    //   alert("Set!");
		    // } else {
		    //   alert("Cleared!");
		    // }			
		</script>
	</head>
	<body class="login">
	<form id='ladder_form' name='ladder' action='login.php' method='post'>  
	<table class="login" style="width:25%;margin-left:auto; margin-right:auto; margin-top:80px;">

	<tr><td>Username:</td><td><input id="login" type="text" name="login"></td></tr>
	<tr><td>Password:</td><td><input type="password" name="password"></td></tr>
	<?php if(isset($msg)) {?>
	<tr id="msg_row"><td colspan="2"><span class="ui-state-error">&nbsp;<?php echo $msg; ?>&nbsp;</span></td></tr>
	<?php }?>
	<tr><td colspan="2"><input style="font-size:0.7em;" id="login_button" type="submit" value="Login"></td></tr>
	<tr><td colspan="2"><a style="font-size:0.7em;" href="signup.php">Sign Up Now</a></td></tr>
	
	</table>
		
	</form>
	<!-- <div id="fb-root"></div>
			<script src="http://connect.facebook.net/en_US/all.js"></script>
		<script type="text/javascript">
	FB.init({appId: '133838603315771', status: true, cookie: true, xfbml: true},"xd_receiver.htm");
	
	FB.login(function(response) {
	  if (response.session) {
	    alert("Success");
	  } else {
		alert("Cancel");
	  }
	});
	</script> -->

</body>
</html>
