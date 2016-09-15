<div class="verslavingen form">
<?php echo $this->Form->create('Verslaving');?>
	<fieldset>
		<legend><?php __('Edit Verslaving'); ?></legend>
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
		<li><?php echo $this->Html->link(__('List Verslavingen', true), array('action' => 'index'));?></li>
	</ul>
</div>
