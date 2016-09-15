<div class="medewerkers form">
<?php echo $this->Form->create('Medewerker');?>
	<fieldset>
		<legend><?php __('Add Medewerker'); ?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('ldapid');
		echo $this->Form->input('voornaam');
		echo $this->Form->input('tussenvoegsel');
		echo $this->Form->input('achternaam');
		echo $this->Form->input('eerste_bezoek');
		echo $this->Form->input('laatste_bezoek');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Medewerkers', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Intakes', true), array('controller' => 'intakes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Intake', true), array('controller' => 'intakes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Klanten', true), array('controller' => 'klanten', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Notities', true), array('controller' => 'notities', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Notitie', true), array('controller' => 'notities', 'action' => 'add')); ?> </li>
	</ul>
</div>
