<?php echo form_open('dashboard/submit', array('id'=>'ladder_form')); ?>
	<div class="prepend-1 span-11 append-1" >
        <h2>Ladder Standings</h2>
        <table id='ladderTable'>
            <?php $this->load->view('ladder'); ?>
        </table>    
	</div>
	
	<div class="span-10 append-1 last">
			<h2>Pending Matches</h2>
			<table id="challengesTable">
            <?php $this->load->view('challenges'); ?>
			</table>    
		
			<h2>Latest Matches</h2>
			<table id="matchesTable">
			</table>
	</div>
	<?php echo form_close(); ?>
