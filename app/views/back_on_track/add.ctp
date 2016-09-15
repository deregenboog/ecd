<div class="backOnTracks form">
<?php echo $this->Form->create('BackOnTrack');?>
	<fieldset class="twoDivs">
		<legend><?php __('Add Back On Track'); ?></legend>
		<div class="leftDiv">
<?php

	echo $this->Form->hidden('klant_id');
	
	echo $this->Form->input('startdatum');
	
	echo $this->Form->input('einddatum');
?>
	</div>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
