<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<title>FreeLadder</title>
<?php require_once("includes.php"); ?>

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

</body>
</html>

