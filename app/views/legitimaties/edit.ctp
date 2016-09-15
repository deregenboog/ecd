<div class="legitimaties form">
<?php echo $this->Form->create('Legitimatie');?>
	<fieldset>
		<legend><?php __('Edit Legitimatie'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('naam');
		echo $this->Form->input('datum_van');
		echo $this->Form->input('datum_tot');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Legitimaties', true), array('action' => 'index'));?></li>
	</ul>
</div>
