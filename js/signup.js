/*
 * FreeLadder Ladder Server
 * http://freeladder.org
 *
 * Copyright 2010, Jim Kalafut
 * Released under the MIT license.
 * 
 */
 
$(document).ready(function() {
    $("#signup_button").button();
	$("#login").focus();
		
    setInterval("testPasswords();", 200);
    $("#msg_row").delay(5000).fadeOut(2000);
    
    $('#signup_form').submit(function() {
        if(!testPasswords()) {
            alert("Passwords don't match!");
            return false;
        } 
        
        //return true;
    });
});


function testPasswords() {
    var p1 = $("#password").val();
    var p2 = $("#password_confirm").val();
    
    if(p1 != p2 && !(p1 == "" && p2 == "")) {
        $("#password_confirm").css("background", "orange");
        return false;
    } else {
        $("#password_confirm").css("background", "white");
        return true;
    }
}