<div class="locaties form">
<?php echo $this->Form->create('Locatie');?>
	<fieldset>
		<legend><?php __('Edit Locatie'); ?></legend>
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

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Locatie.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Locatie.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Locaties', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Registraties', true), array('controller' => 'registraties', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Registratie', true), array('controller' => 'registraties', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Schorsingen', true), array('controller' => 'schorsingen', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Schorsing', true), array('controller' => 'schorsingen', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Intakes', true), array('controller' => 'intakes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Intake1', true), array('controller' => 'intakes', 'action' => 'add')); ?> </li>
	</ul>
</div>
