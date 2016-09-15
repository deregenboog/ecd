<div class="verslavingsperiodes form">
<?php echo $this->Form->create('Verslavingsperiode');?>
	<fieldset>
		<legend><?php __('Add Verslavingsperiode'); ?></legend>
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
		<li><?php echo $this->Html->link(__('List Verslavingsperiodes', true), array('action' => 'index'));?></li>
	</ul>
</div>
