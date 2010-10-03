	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
		"http://www.w3.org/TR/html4/strict.dtd">
	<html>
	<head>
		<?php require_once("includes.php"); ?>
		<script type="text/javascript" src="/js/signup.js"></script>

	</head>
	<body class="login">
	
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
	<tr><td>Ladder Name:</td><td><input type="text" name="ladder_name"></td></tr>
	
	<?php if(isset($msg)) {?>
	<tr id="msg_row"><td colspan="2"><span class="ui-state-error">&nbsp;<?php echo $msg; ?>&nbsp;</span></td></tr>
	<?php }?>
	
	<tr><td colspan="2"><input style="font-size:0.7em;" id="signup_button" type="submit" value="Create Account"></td></tr>
	
	</table>
	<input type="hidden" name="form_submit">	
	</form>
</body>
</html>
