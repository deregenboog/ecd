<div class="verslavingsgebruikswijzen form">
<?php echo $this->Form->create('Verslavingsgebruikswijze');?>
	<fieldset>
		<legend><?php __('Edit Verslavingsgebruikswijze'); ?></legend>
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
		<li><?php echo $this->Html->link(__('List Verslavingsgebruikswijzen', true), array('action' => 'index'));?></li>
	</ul>
</div>
