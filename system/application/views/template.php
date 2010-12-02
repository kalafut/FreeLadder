<?php echo doctype('xhtml1-strict'); ?>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>

<html>
<head>
<title>FreeLadder</title>
<link rel="stylesheet" type="text/css" href="/css/blueprint/screen.css" media="screen, projection" />
<link rel="stylesheet" type="text/css" href="/css/blueprint/print.css" media="print" />	
<!--[if lt IE 8]><link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo auto_version('/css/sunny/jquery-ui-1.8.6.custom.css'); ?>" />	
<link rel="stylesheet" type="text/css" href="<?php echo auto_version('/css/ladder.css'); ?>"  media="screen, projection"/>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo auto_version('/js/json2.min.js'); ?>"></script>
</head>
<body <?php if($this->config->site_url() == "http://dev.freeladder.org/") echo 'class="dev_mode"'; ?> >
    <div class="container">
        <div id="main" class="span-24 last">
        <!-- Banner -->
            <div class="span-24 last">
                <div class="prepend-22 span-2 last"><?php echo anchor('login/logout','Logout'); ?></div>
                <div class="prepend-1 span-14" >
                    <!--<span id="header_title" style="font-size: 250%; vertical-align:bottom"><?php echo Ladder::current_ladder_name(); ?></span>-->
                    <?php echo anchor('dashboard',Ladder::current_ladder_name(), array('style'=>"font-size: 250%; vertical-align:bottom; text-decoration:none; color: black;")); ?></span>
                </div>
                <div class="span-4 append-5 last" > </div>
                <div class="span-24 last">&nbsp;</div>

                <!-- Toolbar -->
                <div class="span-24 toolbar append-bottom last">
                    <div class="prepend-1 span-2">
                        <?php echo anchor('dashboard','Home'); ?>
                    </div>
                    <div class="prepend-1 span-2">
                        <a id="rules_link" href="#">Rules</a> 
                    </div>
                    <div class="prepend-11 span-2">
                        <?php echo anchor('settings','Settings'); ?>
                    </div>
                    <div class="prepend-1 span-3 append-1 last">
                        <?php echo anchor('instructions','Instructions'); ?>
                    </div>
                </div>

                <!-- Page Content -->
                <div class="span-24 last">
                    <?php $this->load->view($content_view); ?>
                </div>
            </div>
        </div>
        <div class="span-24 last">
            <p style="text-align:center;"><a href="http://groups.google.com/group/freeladder">Mailing List</a> | <a href="http://bitbucket.org/kalafut/freeladder/wiki/Home">Project Page</a></p>
        </div>
    </div> <!--container-->
    <script type="text/javascript"> 
        $(document).ready(function() {
            $("#rulesDialog").dialog({
                autoOpen:false,
                resizable: false,
                height:480,
                width:500,
                modal: true,
                buttons: {
                    'Close': function() {
                        $(this).dialog('close');
                    }
                }
            });

            $("#rules_link").click(function() {
                $("#rulesDialog").dialog("open");
                $("#usatt").blur(); 
            });
        });
    </script>

    <div id="rulesDialog" title="Ladder Rules">
        <!-- This whole section will eventually be populated from the database -->
        <?php $this->load->view('rules'); ?>
    </div>
</body>
</html>

