<?php echo doctype('xhtml1-strict'); ?>
    <html>
	<head>
		<?php require_once("includes.php"); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#signup_button").button();
        $("#back_button").button();
        $('input[name="email"]').focus();
        $("#signup_button").click(doSubmit);

        $("input").keypress(function(event) {
            if (event.keyCode == '13') {
                doSubmit();
            }
        });

        function doSubmit() {
			var pw1 = $("#password").val();
			var pw2 = $("#password2").val();
			if(pw1 != pw2) {
				alert("Passwords don't match!");
			} else {
                $("form:first").submit();
			}
		}
    });
</script>
	</head>
	<body class="login">

	<?php echo form_open('password_reset/submit'); ?>

    <table class="login" style="width:35%;margin-left:auto; margin-right:auto; margin-top:80px;">
        <?php if($init) { ?>
            <tr><td class="label">Email address:</td>
            <td><?php echo form_input('email'); ?> </td></tr>
            <tr><td colspan="2">
            <button type='button' id='signup_button' class='mediumButton'>Reset Password</button>
            </td></tr>

            <tr><td colspan="2">
            <?php echo anchor('login','Return to Login'); ?>
            </td></tr>
        <?php } else { ?>
            <tr><td colspan="2"><?php echo $message ?></td></tr>

            <tr><td colspan="2">
            <?php echo anchor('login','Return to Login'); ?>
            </td></tr>

        <?php }; ?>
	
	</table>

	<?php echo form_close(); ?>
</body>
</html>
