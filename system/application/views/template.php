<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<link rel="stylesheet" href="/css/blueprint/screen.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="/css/blueprint/print.css" type="text/css" media="print">	
<!--[if lt IE 8]><link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->

<link type="text/css" href="<?php echo auto_version('/css/sunny/jquery-ui-1.8.2.custom.css'); ?>" rel="stylesheet">	
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo auto_version('/js/jquery-ui-1.8.2.custom.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo auto_version('/js/json2.min.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo auto_version('/css/ladder.css'); ?>" type="text/css" media="screen, projection">
</head>
<body>

<div class="container">
    <!-- Banner -->
    <div class="span-24 last">
        <div class="prepend-22 span-2 last"><?php echo anchor('login/logout','Logout'); ?></div>
        <div class="prepend-1 span-14" >
            <span id="header_title" style="font-size: 250%; vertical-align:bottom"><?php echo Ladder::current_ladder_name(); ?></span>
        </div>
        <div class="span-4 append-5 last" > </div>
        <div class="span-24 last">&nbsp;</div>

    <!-- Toolbar -->
        <div class="span-24 toolbar append-bottom last">
            <div class="prepend-2 span-7">
    <?php echo anchor('dashboard','Ladder'); ?>
            </div>
            <div class="prepend-1 span-7">
    <?php echo anchor('settings','User Settings'); ?>
            </div>
            <div class="prepend-1 span-6 last">
    <?php echo anchor('instructions','Instructions'); ?>
            </div>
        </div>

    <!-- Page Content -->
        <div class="span-24 last">
        <?php 
            $this->load->view($content_view); 
        ?>
        </div>
    </div>
</div>
<div id="footer">
<p><a href="http://groups.google.com/group/freeladder">Mailing List</a> | <a href="http://bitbucket.org/kalafut/freeladder/wiki/Home">Project Page</a></p>
</div>
</body>
</html>

