	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
		"http://www.w3.org/TR/html4/strict.dtd">
    <html>
	<head>
		<?php require_once("includes.php"); ?>
		<script type="text/javascript" src="/js/signup.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$("#recaptcha_area, #recaptcha_table").css('margin', 'auto');
});
</script>
        
	</head>
	<body class="login">
	
	<?php echo form_open('signup/verify2'); ?>

    <table class="login" style="width:40%;margin-left:auto; margin-right:auto; margin-top:80px;">

    <tr><td>Please answer the signup verification question:</td></tr>

    <tr><td>
    <?php echo recaptcha_get_html($this->config->item('recaptcha_public_key')); ?>
    </td></tr>

    <tr><td>
    <?php echo validation_errors('<p class="error">','</p>'); ?>
    </td></tr>

    <tr><td>
    <?php echo form_submit(array('id' => 'back_button','name'=>'back'),'Back' ); ?>
    <?php echo form_submit(array('id' => 'signup_button', 'name'=>'create'),'Create Account' ); ?>
    </td></tr>
	
	</table>

	<?php echo form_close(); ?>
</body>
</html>
