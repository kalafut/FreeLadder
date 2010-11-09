<?php echo doctype('xhtml1-strict'); ?>
    <html>
	<head>
		<?php require_once("includes.php"); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#signup_button").button();
        $("#back_button").button();
        $("#name").focus();
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
	
	<?php echo form_open('signup/submit'); ?>

    <table class="login" style="width:50%;margin-left:auto; margin-right:auto; margin-top:80px;">
	<tr><td class="label vtop">Your full name:<br/><a href="#" id="why_real" style="font-size: 0.7em;">(why use my real name?)</a></td>
    <td class="vtop"><?php echo form_input(array('id'=>'name', 'name'=>'name'),set_value('name')); ?> </td></tr>

    <tr><td class="label">Email address:</td>
    <td><?php echo form_input('email',set_value('email')); ?> </td></tr>

    <tr><td class="label">Password:</td>
    <td><?php echo form_password(array('id'=>'password', 'name'=>'password')); ?></td></tr>

    <tr><td class="label">Password confirmation:</td>
    <td><?php echo form_password(array('id'=>'password2', 'name'=>'password2')); ?></td></tr>

	<tr><td class="label">Ladder Code:</td>
    <td><?php echo form_input('ladder_code',set_value('ladder_code')); ?></td></tr>
	

    <tr><td colspan="2">
    <?php echo validation_errors('<p class="ui-state-error">','</p>'); ?>
    </td></tr>

    <tr><td colspan="2">
    <button type='button' id='signup_button' class='mediumButton'>Next</button>
    </td></tr>

    <tr><td colspan="2">
    <?php echo anchor('login','Return to Login'); ?>
    </td></tr>
	
	</table>

	<?php echo form_close(); ?>
    <script type="text/javascript"> 
        $(document).ready(function() {
            $("#why_real_name").dialog({
                autoOpen:false,
                resizable: false,
                height:200,
                width:300,
                modal: true,
                buttons: {
                    'Close': function() {
                        $(this).dialog('close');
                    }
                }
            });

            $("#why_real").click(function() {
                $("#why_real_name").dialog("open");
            });
        });
    </script>
    <div id="why_real_name" title="Why use my real name?">
    Players need to be able to contact each other in order to plan matches. For this reason it's recommended that you use your real name and not a nickname on FreeLadder.
    </div>
</body>
</html>
