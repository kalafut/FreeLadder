$(document).ready(function() {	
    setInterval("testPasswords();", 200);
});


function testPasswords() {
    var p1 = $("#password").val();
    var p2 = $("#password_confirm").val();
    
    if(p1 != p2 && !(p1 == "" && p2 == "")) {
        $("#password_confirm").css("background", "red");
    } else {
        $("#password_confirm").css("background", "white");
    }
}