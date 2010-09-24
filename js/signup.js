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
		
    setInterval("testPasswords();", 300);
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
        $("#signup_button").button("disable");
        return false;
    } else {
        $("#password_confirm").css("background", "white");
        if(p1 == "" && p2 == "") {
            $("#signup_button").button("disable");
        } else {
            $("#signup_button").button("enable");
        }
        return true;
    }
}