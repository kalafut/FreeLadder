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
    <td><?php echo form_password('password',set_value('password')); ?></td></tr>
    

    <tr id="msg_row"><td colspan="2"><?php echo validation_errors('<p class="error">','</p>'); ?> </td></tr>
    
<tr><td colspan="2">
    <?php echo form_submit(array('id' => 'login_button'),'Login' ); ?>
    </td></tr>

    <tr><td colspan="2">
    <?php echo anchor('signup','Create an Account'); ?>
    </td></tr>

    </table>
    <? echo form_close(); ?>
</body>
</html>
