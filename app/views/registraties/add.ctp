<div class="registraties form">
<?php echo $this->Form->create('Registratie');?>
	<fieldset>
		<legend><?php __('Add Registratie'); ?></legend>
	<?php
		echo $this->Form->input('locatie_id');
		echo $this->Form->input('klant_id');
		echo $this->Form->input('binnen');
		echo $this->Form->input('buiten');
		echo $this->Form->input('douche');
		echo $this->Form->input('kleding');
		echo $this->Form->input('maaltijd');
		echo $this->Form->input('activering');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Registraties', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Locaties', true), array('controller' => 'locaties', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Locatie', true), array('controller' => 'locaties', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Klanten', true), array('controller' => 'klanten', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add')); ?> </li>
	</ul>
</div>
