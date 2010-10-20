	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
		"http://www.w3.org/TR/html4/strict.dtd">
    <html>
	<head>
		<?php require_once("includes.php"); ?>
		<script type="text/javascript" src="/js/signup.js"></script>
	</head>
	<body class="login">
	
	<?php echo form_open('signup/submit'); ?>

    <table class="login" style="width:40%;margin-left:auto; margin-right:auto; margin-top:80px;">
	<tr><td>Your full name:</td>
    <td><?php echo form_input('name',set_value('name')); ?> </td></tr>

    <tr><td>Email address:</td>
    <td><?php echo form_input('email',set_value('email')); ?> </td></tr>

    <tr><td>Password:</td>
    <td><?php echo form_password('password'); ?></td></tr>

	<tr><td>Ladder Code:</td>
    <td><?php echo form_input('ladder_code',set_value('ladder_code')); ?></td></tr>
	

    <tr><td colspan="2">
    <?php echo validation_errors('<p class="ui-state-error">','</p>'); ?>
    </td></tr>

    <tr><td colspan="2">
    <?php echo form_submit(array('id' => 'signup_button'),'Next' ); ?>
    </td></tr>

    <tr><td colspan="2">
    <?php echo anchor('login','Return to Login'); ?>
    </td></tr>
<!--<input style="font-size:0.7em;" id="signup_button" type="submit" value="Create Account"></td></tr>-->
	
	</table>

	<?php echo form_close(); ?>
</body>
</html>
