<!DOCTYPE html>
<html>
    <head>
        <title>FreeLadder</title>
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

    <div class="container">
        <div class="row">
            <div class="span12">
                <div id="signup_box">
                    <?php echo form_open('signup/submit', array('class'=>'form-horizontal')); ?>
                    <div class="control-group">
                        <label for="name" class="control-label">
                            Your full name:
                        </label>
                        <div class="controls">
                            <?php echo form_input(array('id'=>'name', 'name'=>'name', 'autofocus'=>'autofocus'),set_value('name')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="email" class="control-label">
                            Email address:
                        </label>
                        <div class="controls">
                            <?php echo form_input(array('name'=>'email','id'=>'email', 'tabindex'=>'1', 'autofocus'=>'autofocus'), set_value('email')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="password" class="control-label">
                            Password:
                        </label>
                        <div class="controls">
                            <?php echo form_password(array('name'=>'password', 'id'=>'password'), set_value('password')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="password" class="control-label">
                            Password confirmation:
                        </label>
                        <div class="controls">
                            <?php echo form_password(array('name'=>'password2', 'id'=>'password2'), set_value('password2')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="ladder_code" class="control-label">
                            Ladder Code:
                        </label>
                        <div class="controls">
                            <?php echo form_input('ladder_code',set_value('ladder_code')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <?php echo form_submit(array('id' => 'signup_button', 'class' => 'btn btn-primary', 'tabindex'=>'3'), 'Sign-up' ); ?>
                            <?php echo anchor('login','Return to Login'); ?>
                        </div>
                    </div>
                    <? echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>



    <tr><td colspan="2">
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
