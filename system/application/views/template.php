<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>

<html>
<head>
<title>FreeLadder</title>
<link href="/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo auto_version('/css/sunny/jquery-ui-1.8.6.custom.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo auto_version('/css/ladder.css'); ?>"  media="screen, projection"/>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo auto_version('/js/json2.min.js'); ?>"></script>
<script src="/js/bootstrap.min.js"></script>    
</head>

<body <?php if($this->config->site_url() == "http://dev.freeladder.org/") echo 'class="dev_mode"'; ?> >
        <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <?php echo anchor('dashboard',Ladder::current_ladder_name(), array('class'=>"brand")); ?>
          <div class="nav-collapse collapse">
            <ul class="nav pull-right">
              <li><a id="rules_link" href="#" data-toggle="modal" data-target="#rulesDialog">Rules</a></li>
              <li><?php echo anchor('settings','Settings'); ?></li>
              <li><?php echo anchor('instructions','Instructions'); ?></li>
              <li><?php echo anchor('login/logout','Logout'); ?></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    <div class="container">
        <div class="row">
            <!-- Page Content -->
            <div class="span12">
                <?php $this->load->view($content_view); ?>
            </div>
        </div>
        <div class="row">
            <div class="span12">
                <p style="text-align:center; color:gray;">rev: <!--commit--></p>
            </div>
        </div>
    </div>

</div> <!--container-->


<div id="rulesDialog" title="Ladder Rules" class="modal hide fade">
    <div class="modal-header">
        <h3>Ladder Rules</h3>
    </div>
    <div class="modal-body">
        <?php $this->load->view('rules'); ?>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Close</a>
    </div>
    
</div>

</body>
</html>

