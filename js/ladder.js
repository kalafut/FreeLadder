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
    $(".reviewButton").button({ icons: {primary:'ui-icon-pencil'} });
    $(".forfeitButton").button({ icons: {primary:'ui-icon-closethick'} });
    $(".won_lost").buttonset();

	updateTables();
	
	var refresher;
	
	$(window).focus(function() {
	    updateTables();
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
                var url = $("#ladder_form").attr("action");
				$.post(url, $("#ladder_form").serialize(), function(data){
                    updateTables();
                   });
				$(this).dialog('close');
            }
		}
	});

    setupChallengeWindow();
});

function updateTables() {
    // This needs to be POST or else IE caches the Ajax.
    $.post("dashboard/json", function(data){
        processUpdate(data);
    } );
}


function registerButtons() {
    $(".jqbutton").click(function(event){
        $("#ladder_form").append("<input class='appendedField' type='hidden' name='action' value='" +$(this).attr("action")+"'>");
        $("#ladder_form").append("<input class='appendedField' type='hidden' name='param' value='" +$(this).attr("param")+"'>");

        var url = $("#ladder_form").attr("action");
        $.post(url, $("#ladder_form").serialize(), function(data){
            updateTables();
           });
    });
    
    $(".reviewButton").unbind();
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
    
    try
    {
        var data=JSON.parse(dataJSON);
    }

    catch(err) 
    {
        if( dataJSON.indexOf("<html>") != -1 && dataJSON.indexOf("login") != -1 ) {
            // we're reasonbly certain that we're at the login screen
            window.location.replace("login");
        } else {
            return;
        }
    }
    
    $("#ladderTable").empty().append(data.ladder);
    $("#challengesTable").empty().append(data.challenges);
    $("#matchesTable").empty().append(data.matches);
    
    $(".challengeButton").button();
    $(".resultButton").button();
    $(".reviewButton").button({ icons: {primary:'ui-icon-pencil'} });
    $(".forfeitButton").button({ icons: {primary:'ui-icon-closethick'} });
    
    $(".won_lost").buttonset();
    
    registerButtons();
    setupChallengeWindow();
}

function setupChallengeWindow()
{
    $("#show_other").click(function() {
        $(".other_challenges").show();
        $("#hide_other").show();
        $("#show_other").hide();
        $.cookie("freeladder_show_hide", "show", { expires: 10000} );
    });
    $("#hide_other").click(function() {
        $(".other_challenges").hide();
        $("#show_other").show();
        $("#hide_other").hide();
        $.cookie("freeladder_show_hide", "hide", { expires: 10000} );
    });

    var ck = $.cookie("freeladder_show_hide");
    if( !ck || ck=='hide') {
        $("#hide_other").hide();
        $(".other_challenges").hide();
    } else {
        $("#show_other").hide();
    }
}
