<script type='text/javascript'>
	$(document).ready(function(){
        $("#inactive_warning").hide();

        setInterval(function() {
			var pw1 = $("#pw1").val();
			var pw2 = $("#pw2").val();

			if(pw1 != "" || pw2 != "") {
				if(pw1.length < 6) {
					setPasswordMessage("#pw1_gp", "error");
					setPasswordMessage("#pw2_gp", "none");
					$("#save_btn").attr('disabled', 'disabled');
				} else {
					if(pw1 != pw2) {
						setPasswordMessage("#pw1_gp", "success");
						setPasswordMessage("#pw2_gp", "error");
						$("#save_btn").attr('disabled', 'disabled');
					} else {
						setPasswordMessage("#pw1_gp", "success");
						setPasswordMessage("#pw2_gp", "success");
						$("#save_btn").removeAttr('disabled');
					}
				}
			} else {
				setPasswordMessage("#pw1_gp", "none");
				setPasswordMessage("#pw2_gp", "none");
				$("#save_btn").removeAttr('disabled');
			}
		}, 200);

        $("#status").change(function() {
            if( $("#status option:selected").text() == "Inactive" ) {
                $("#inactive_warning").show();
            } else {
                $("#inactive_warning").hide();
            }
        });

        function setPasswordMessage(id, state) {
			switch(state) {
				case "none":
					$(id).removeClass("success").removeClass("error");
					$(id + " .help-inline").hide();
					break;
				case "error":
					$(id).removeClass("success").addClass("error");
					$(id + " .help-inline").show();
					break;
				case "success":
					$(id).removeClass("error").addClass("success");
					$(id + " .help-inline").hide();
					break;
			}
        }

	});
</script>
<div class="row">
	<div class="span12">
		<h2>User Settings</h2>
	</div>
</div>

   <div class="row">
   	<div class="span12">
		<?php echo form_open('settings/submit', array('id'=>'settings_form', 'class'=>'form-horizontal')); ?>
			<div class="control-group">
				<label for="email" class="control-label">
					Email address
				</label>
					<div class="controls">
						<?php echo form_input(array('name'=>'email','value'=>set_value('email',$user->email ), 'class'=>'settings_tf')); ?>
					</div>
			</div>
			<div class="control-group">
				<label for="status" class="control-label">
					Status
				</label>
				<div class="controls">
					<?php $options = array(User::ACTIVE => 'Active', User::INACTIVE => 'Inactive');
				 	   		   	   echo form_dropdown('status', $options, $user->status, "id='status'"); ?>
					<span id="inactive_warning" class="help-block">Important: If you change to inactive status, any of your current challenges will be cancelled.</span>
				</div>
			</div>
			<div class="control-group">
				<label for="limit" class="control-label">
   		   				Limit pending challenges
				</label>
				<div class="controls">
   		             <?php
	                 $options = array('255'=>'No limit', '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6');
	                 echo form_dropdown('max_challenges', $options, $user->max_challenges); ?>
				</div>
			</div>
			<div id="pw1_gp" class="control-group">
				<label for="pw1" class="control-label">
					New password
				</label>
				<div class="controls">
   		   			<input class="settings_tf" id="pw1" type="password" name="password1">
   		   			<span class="help-inline" style="display:none">Password too short</span>
				</div>
			</div>
			<div id="pw2_gp" class="control-group">
				<label for="pw2" class="control-label">
					Password confirmation
				</label>
				<div class="controls">
					<input class="settings_tf" id="pw2" type="password" name="password2">
					<span class="help-inline" style="display:none">Passwords do not match</span>
				</div>
			</div>
			<div class="controls">
				<button id="save_btn" class="btn btn-primary">Save</button>
			</div>


    <tr id="msg_row"><td colspan="2" style="text-align:center"><?php echo validation_errors(); ?> </td></tr>
	</div>

	<input type=hidden name="settings_submit" />

    <? echo form_close(); ?>
