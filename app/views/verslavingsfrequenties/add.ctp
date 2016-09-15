<div class="verslavingsfrequenties form">
<?php echo $this->Form->create('Verslavingsfrequentie');?>
	<fieldset>
		<legend><?php __('Add Verslavingsfrequentie'); ?></legend>
	<?php
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
		<li><?php echo $this->Html->link(__('List Verslavingsfrequenties', true), array('action' => 'index'));?></li>
	</ul>
</div>
