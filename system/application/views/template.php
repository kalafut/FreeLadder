<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>

<html>
<head>
<title>FreeLadder</title>
<?php require_once("includes.php"); ?>
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
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

