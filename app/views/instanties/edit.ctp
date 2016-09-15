<div class="instanties form">
<?php echo $this->Form->create('Instantie');?>
	<fieldset>
		<legend><?php __('Edit Instantie'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('naam');
		echo $this->Form->input('datum_van');
		echo $this->Form->input('datum_tot');
		echo $this->Form->input('Intake');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Instanties', true), array('action' => 'index'));?></li>
	</ul>
</div>
