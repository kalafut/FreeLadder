<?php
include_once("db.php");
include_once("util.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');

checkLogin();
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<?php include_once("includes.php"); ?>
</head>
<body>

<div class="container">
	<?php include_once("header.html"); 
	include_once("toolbar.html");?>
    <form id='settings_form' name='settings' action='settings.php' method='post'>
	<div class="prepend-1 span-1 append-22 last">
			<h2>TBD</h2>
	</div>
</div>
</body>
</html>
