<html>
    <head>
        <?php require_once("includes.php"); ?>

<script type="text/javascript">
$(document).ready(function() {
    $("#login_button").button();
    $("#email").focus();				
});
</script>
    </head>
    <body class="login">
	<?php echo form_open('login/submit'); ?>

    <table class="login" style="width:35%;margin-left:auto; margin-right:auto; margin-top:80px;">

    <tr><td>Email address:</td>
    <td><?php echo form_input('email',set_value('email')); ?> </td></tr>
    
    <tr><td>Password:</td>
    <td><?php echo form_input('password',set_value('password')); ?></td></tr>
    
	<?php echo validation_errors('<p class="error">','</p>'); ?>

    <tr id="msg_row"><td colspan="2"><span class="ui-state-error">&nbsp;	<?php echo validation_errors('<p class="error">','</p>'); ?>
&nbsp;</span></td></tr>
    <tr><td colspan="2">	<?php echo form_submit(array('id' => 'login_button'),'Login' ); ?>
</td></tr>
    <tr><td colspan="2"><a style="font-size:0.7em;" href="signup.php">Sign Up Now</a></td></tr>

    </table>
	<?php echo form_close(); ?>

    </form>
</body>
</html>
