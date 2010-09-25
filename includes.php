<?php
/* Authorize first */
include_once("auth.php");
verifyAuthorization(false);
?>

<link rel="stylesheet" href="<?php echo auto_version('/css/blueprint/screen.css')?>" type="text/css" media="screen, projection">
<link rel="stylesheet" href="<?php echo auto_version('/css/blueprint/print.css')?>" type="text/css" media="print">	
<!--[if lt IE 8]><link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->

<link type="text/css" href="<?php echo auto_version('/css/sunny/jquery-ui-1.8.2.custom.css')?>" rel="stylesheet">	
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo auto_version('/js/jquery-ui-1.8.2.custom.min.js')?>"></script>
<script type="text/javascript" src="js/json2.min.js"></script>

<link rel="stylesheet" href="<?php echo auto_version('/css/ladder.css')?>" type="text/css" media="screen, projection">