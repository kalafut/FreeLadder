/*
 * FreeLadder Ladder Server
 * http://freeladder.org
 *
 * Copyright 2010, Jim Kalafut
 * Released under the MIT license.
 * 
 */

$(document).ready(function() {
    $(".jqbutton").button();
	updateTables();
	
	var refresher;
	
	$(window).focus(function() {
//	    updateTables();
//	    refresher = setInterval("updateTables();",1000);
	});
	$(window).blur(function() {
//`	    clearInterval(refresher)
	});
	
	
	//setInterval("updateTables();", 5000);
    
	$("#reviewDialog").dialog({
		autoOpen:false,
		resizable: false,
		height:480,
		width:500,
		modal: true,
		buttons: {
		    'Do nothing': function() {
				$(this).dialog('close');
			},
            'Change my answer': function() {
                $("#ladder_form").append("<input class='appendedField' type='hidden' name='action' value='flip'>");
			    $("#ladder_form").append("<input class='appendedField' type='hidden' name='param' value='" +$(this).attr("param")+"'>");
				$.post("ladder.php", $("#ladder_form").serialize(), function(data){
                    processUpdate(data);
                   });
				$(this).dialog('close');
            }
		}
	});
});

function updateTables() {
    //$.get("ladder.php", { 'action': 'updateTables'}, function(data){
	//	processUpdate(data);
    //} );
    $.get("dashboard/ladderUpdate", function(data){
        $("#ladderTable").empty().append(data);
        $(".challengeButton").button();
        //processUpdate(data);
    registerButtons();
    } );
}


function registerButtons() {
    $(".challengeButton, .resultButton, .forfeitButton").click(function(event){
        $("#ladder_form").append("<input class='appendedField' type='hidden' name='action' value='" +$(this).attr("action")+"'>");
        $("#ladder_form").append("<input class='appendedField' type='hidden' name='param' value='" +$(this).attr("param")+"'>");

        var url = $("#ladder_form").attr("action");
        $.post(url, $("#ladder_form").serialize(), function(data){
            //processUpdate(data);
           });
    });
    
    $(".reviewButton").click(function(event){
        var opponent = $(this).attr("opponent");
        var result = $(this).attr("result");
        $("#reviewOpponent").text(opponent);
        $(".reviewResult").text(result);
        $("#reviewDialog").attr("param",$(this).attr("param"));
        
        $("#reviewDialog").dialog("open");
    });
    
}

function processUpdate(dataJSON) {
    $(".appendedField").remove();
    $(".challengeButton, .resultButton, .forfeitButton").unbind();
    
    var data=JSON.parse(dataJSON);
    
    $("#ladderTable").empty().append(data.ladder);
    $("#pendingTable").empty().append(data.pending);
    $("#matchesTable").empty().append(data.matches);
    
    $(".challengeButton").button();
    $(".resultButton").button();
    $(".reviewButton").button({ icons: {primary:'ui-icon-pencil'} });
    $(".forfeitButton").button({ icons: {primary:'ui-icon-closethick'} });
    //$(".forfeitButton").button({ icons: {primary:'ui-icon-circle-arrow-s'} });
    
    $(".won_lost").buttonset();
    
    registerButtons();
}


