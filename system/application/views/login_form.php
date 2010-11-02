<html>
    <head>
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
    <body class="login">
    <?php echo form_open('login'); ?>

    <table class="login" style="width:35%;margin-left:auto; margin-right:auto; margin-top:80px;">

    <tr><td class="label">Email:</td>
    <td class="entry"><?php echo form_input(array('name'=>'email','id'=>'email'),set_value('email')); ?> </td></tr>
    
    <tr><td class="label">Password:</td>
    <td class="entry"><?php echo form_password('password',set_value('password')); ?></td></tr>
    

    <tr id="msg_row"><td colspan="2"><?php echo validation_errors(); ?> </td></tr>
    
<tr><td colspan="2">
    <button type='button' id='login_button' class='mediumButton'>Login</button>
    <?php //echo form_submit(array('id' => 'login_button', 'class' => 'mediumButton'),'Login' ); ?>
    </td></tr>

    <tr><td colspan="2">
    <?php echo anchor('signup','Create an Account'); ?>
    </td></tr>

    </table>
    <? echo form_close(); ?>
</body>
</html>
