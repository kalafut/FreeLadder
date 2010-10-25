    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
		"http://www.w3.org/TR/html4/strict.dtd">
    <html>
    <head>
		<?php require_once("includes.php"); ?>
<script type="text/javascript">
$(document).ready(function() {
        $("button").button();
        $("#signup_button").click(function() { $("form:first").submit(); });
        $("#back_button").click(function() { 
            $("form").append("<input type='hidden' name='back' value='1'>");
            $("form:first").submit(); 
        });
        $("#recaptcha_area, #recaptcha_table").css('margin', 'auto');
});
</script>
        
	</head>
	<body class="login">
	
	<?php echo form_open('signup/verify'); ?>

    <table class="login" style="width:40%;margin-left:auto; margin-right:auto; margin-top:80px;">

    <tr><td>Type the two security words in the box below:</td></tr>

    <tr><td>
    <?php echo recaptcha_get_html($this->config->item('recaptcha_public_key')); ?>
    </td></tr>

    <tr><td>
<?php
    if( isset($invalid_captcha) ) {
        echo '<p class="ui-state-error">The words you typed didn\'t match.  Please try again.</p>';
    }
?>
    </td></tr>

    <tr><td>
    <button type='button' id='back_button' class='mediumButton'>Back</button>
    <button type='button' id='signup_button' class='mediumButton'>Create Account</button>
    </td></tr>
	
	</table>

	<?php echo form_close(); ?>
</body>
</html>
