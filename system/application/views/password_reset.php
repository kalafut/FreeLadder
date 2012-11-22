<!DOCTYPE html>
<html>
    <head>
        <title>FreeLadder</title>
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
            $("form:first").submit();
        }
    });
</script>
	</head>
	<body class="login">
    <div class="container">
        <div class="row">
            <div class="span12">
                <div id="signup_box">
                    <?php if($init) { ?>
                 <?php echo form_open('password_reset/submit', array('class'=>'form-horizontal')); ?>
                    <div class="control-group">
                        <label for="name" class="control-label">
                            Email address:
                        </label>
                        <div class="controls">
                            <?php echo form_input(array('id'=>'email', 'name'=>'email', 'autofocus'=>'autofocus')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <?php echo form_submit(array('id' => 'signup_button', 'class' => 'btn btn-primary', 'tabindex'=>'3'), 'Reset Password' ); ?>
                            <?php echo anchor('login','Return to Login'); ?>
                        </div>
                    </div>

                    <? echo form_close(); ?>
                    <?php } else { ?>
                    <div class="control-group">
                    <?php echo $message ?>

                    </div>
                    <div class="control-group">
                    <?php echo anchor('login','Return to Login'); ?>

                    </div>
                    <?php }; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
