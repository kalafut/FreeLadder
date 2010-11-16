<script type="text/javascript" src="<?php echo auto_version('/js/ladder.js');?>"></script>
<script type="text/javascript" src="/js/jquery.cookie.js"></script>
<?php echo form_open('dashboard/submit', array('id'=>'ladder_form')); ?>

<?php if($show_intro_message) { ?>
<div class="prepend-1 span-22 append-1 last">
    <div class="ui-widget">
        <div class="ui-state-highlight ui-corner-all" >
            <p style="margin: 0.5em 0.5em 0.5em">
            <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
            <strong>Welcome to FreeLadder!</strong> &mdash; Get started by challenging any ranked player in the ladder. See <?php echo anchor('instructions','Instructions', array("style"=>"color:#0074C7; font-weight:bold")); ?> to learn more. 
            </p>
        </div>
    </div>
</div>
<?php } ?>
	<div class="prepend-1 span-11 append-1 bottom_box" >
        <h2>Ladder Standings</h2>
        <table id='ladderTable'>
            <?php $this->load->view('ladder'); ?>
        </table>    
        <!--<p>* Players are not ranked until they've completed at least one match.</p>-->
	</div>
	
	<div class="span-10 append-1 last bottom_box">
			<h2>Pending Matches</h2>
			<table id="challengesTable">
            <?php $this->load->view('challenges'); ?>
			</table>    
		
			<h2>Latest Matches</h2>
			<table id="matchesTable">
            <?php $this->load->view('matches'); ?>
			</table>
	</div>
	<?php echo form_close(); ?>

<div id="reviewDialog" title="Results Review">
	<p>This match has not been saved because you and your opponent (<span id="reviewOpponent"></span>) have reported conflicting results:</p>
	<p style="text-indent:5em;">You reported that <span style="font-style:italic;">you</span> <span class="reviewResult"></span> the match.</p>
	<p style="text-indent:5em;">Your opponent reported that <span style="font-style:italic;">they</span> <span class="reviewResult"></span> the match.</p>
	You have two options:<br><br>
	<ul>
	<li><strong>Change your answer</strong> &mdash; Change your answer to agree with your opponent. The match will be recorded.</li><br>
	<li><strong>Do nothing</strong> &mdash; Take no action now. The match will remain in your Pending list to address later.</li>
	</ul>
</div>
