<script type='text/javascript'>
	$(document).ready(function(){
        $("#inactive_warning").hide();    
		$("#settings_submit").button().click(function() {
			var pw1 = $("#pw1").val();
			var pw2 = $("#pw2").val();
			if(pw1 != pw2) {
				alert("Passwords don't match!");
			} else {
				$("#settings_form").submit();
			}
		});

        $("#status").change(function() {
            if( $("#status option:selected").text() == "Inactive" ) {
                $("#inactive_warning").show();    
            } else {
                $("#inactive_warning").hide();    
            }


        });

	});		
</script>
<?php echo form_open('settings/submit', array('id'=>'settings_form')); ?>
<div class="prepend-5 span-6 append-13 last">
		<h2>User Settings</h2>
</div>
	
   <div class="prepend-5 span-14 append-5 last">
		<table class="settings bottom_box" >
		    <col width="40%"/>
		    <col width="60%"/>
			<tr>
				<td>Email Address</td>
				<td><?php echo form_input(array('name'=>'email','value'=>set_value('email',$user->email ), 'class'=>'settings_tf')); ?></td>
			</tr>
			<tr>
				<td>Receive email notifications *</td>
                <td><?php echo form_checkbox('email_notification', '1', 0); ?></td>
			</tr>
			<tr>
				<td>Status</td>
                <td><?php 
                    $options = array(User::ACTIVE => 'Active', User::INACTIVE => 'Inactive'); 
                    echo form_dropdown('status', $options, $user->status, "id='status'");
                    ?>
				</td>
			</tr>
            <tr id="inactive_warning">
                <td></td>
                <td>Important: If you change to inactive status, any of your current challenges will be cancelled. </td>
            </tr>
			<tr>
				<td>Limit pending challenges</td>
                <td><?php 
                    $options = array('255'=>'No limit', '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6'); 
                    echo form_dropdown('max_challenges', $options, $user->max_challenges);
                    ?>
				</td>
			</tr>
			<tr>
				<td>New password</td>
				<td><input class="settings_tf" id="pw1" type="password" name="password1"></td>
			</tr>
			<tr>
				<td>Password confirmation</td>
				<td><input class="settings_tf" id="pw2" type="password" name="password2"></td>
			</tr>
    <tr id="msg_row"><td colspan="2" style="text-align:center"><?php echo validation_errors(); ?> </td></tr>
			<tr>
				<td colspan="2" style="text-align:center"><button type="button" id="settings_submit" class="jqbutton" style="font-size:1.1em;">Save</button></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:left">* These features have not been implemented yet and the settings do nothing.</td>
			</tr>
			
			</table>
	</div>
	
	<input type=hidden name="settings_submit" />
	
    <? echo form_close(); ?>
