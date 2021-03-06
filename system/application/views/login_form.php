<!DOCTYPE html>
<html>
    <head>
        <title>FreeLadder</title>
        <?php require_once("includes.php"); ?>

<script type="text/javascript">
$(document).ready(function() {
    $("#login_button").button();
    $("#email").focus();

    $("#login_button").click(doSubmit);
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
    <body class="login" >

    <div class="container">
        <div class="row">
            <div class="span12">
                <div id="login_box">
                    <?php echo form_open('login', array('class'=>'form-horizontal')); ?>
                    <div class="control-group">
                        <label for="email" class="control-label">
                            Email:
                        </label>
                        <div class="controls">
                            <?php echo form_input(array('name'=>'email','id'=>'email', 'tabindex'=>'1', 'autofocus'=>'autofocus'), set_value('email')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="password" class="control-label">
                            Password:<br><a href="/password_reset" tabindex="4">forgot?</a>
                        </label>
                        <div class="controls">
                            <?php echo form_password(array('name'=>'password', 'tabindex'=>'2'), set_value('password')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                    <?php echo validation_errors(); ?>

                        </div>
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <?php echo form_submit(array('id' => 'login_button', 'class' => 'btn btn-primary', 'tabindex'=>'3'),'Login' ); ?>
                            <a href="/signup"> or sign-up</a>
                        </div>
                    </div>
                    <? echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>

<!--
    <tr id="msg_row"><td colspan="2"><?php echo validation_errors(); ?> </td></tr>

<tr><td colspan="2">
    <button type='button' id='login_button' class='mediumButton'>Login</button>
    <?php //echo form_submit(array('id' => 'login_button', 'class' => 'mediumButton'),'Login' ); ?>
    </td></tr>

    <tr><td colspan="2">
    <?php echo anchor('signup','Create an Account'); ?>
    </td></tr>
 -->

</body>
</html>
