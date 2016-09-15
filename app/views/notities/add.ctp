<div class="notities form">
<?php echo $this->Form->create('Notitie');?>
	<fieldset>
		<legend><?php __('Add Notitie'); ?></legend>
	<?php
		echo $this->Form->input('klant_id');
		echo $this->Form->input('medewerker_id');
		echo $this->Form->input('datum');
		echo $this->Form->input('opmerking');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Notities', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Klanten', true), array('controller' => 'klanten', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Medewerkers', true), array('controller' => 'medewerkers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Medewerker', true), array('controller' => 'medewerkers', 'action' => 'add')); ?> </li>
	</ul>
</div>
