<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<link rel="stylesheet" href="/css/blueprint/screen.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="/css/blueprint/print.css" type="text/css" media="print">	
<!--[if lt IE 8]><link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->

<link type="text/css" href="/css/sunny/jquery-ui-1.8.2.custom.css" rel="stylesheet">	
<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="/js/json2.min.js"></script>

<link rel="stylesheet" href="/css/ladder.css" type="text/css" media="screen, projection">
</head>
<body>

<div class="container">
    <!-- Banner -->
    <div class="span-24 last">
    <div class="prepend-22 span-2 last"><?php echo anchor('login/logout','Logout')?></div>
    <div class="prepend-1 span-10" >
        <span id="header_title" style="font-size: 250%; vertical-align:bottom">Salem Table Tennis Ladder</span>
    </div>
    <div class="span-4 append-9 last" >
        <?php 
$u = Current_User::user();
$u->loadReference('Current_Ladder');
echo "Ladder: " . $u->Current_Ladder->name; ?>
    </div>
    <div class="span-24 last">&nbsp;</div>

    <!-- Toolbar -->
    <div class="span-24 toolbar append-bottom last">
    	<div class="prepend-2 span-7">
    		<a href="ladder.php">Ladder</a>
    	</div>
    	<div class="prepend-1 span-7">
    		<a href="settings.php">User Settings</a>
    	</div>
    	<div class="prepend-1 span-6 last">
    		<a href="instructions.php">Instructions</a>
    	</div>
    </div>

    <!-- Page Content -->
    <?php $this->load->view($content_view); ?>

</div>

<?php

// Footer
