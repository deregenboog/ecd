<div class="izDeelnemers form">
<?php echo $this->Form->create('IzDeelnemer');?>
	<fieldset>
		<legend><?php __('Add Iz Deelnemer'); ?></legend>
	<?php
		echo $this->Form->input('model');
		echo $this->Form->input('foreign_key');
		echo $this->Form->input('datum_aanmelding');
		echo $this->Form->input('binnengekomen_via');
		echo $this->Form->input('organisatie');
		echo $this->Form->input('naam_aanmelder');
		echo $this->Form->input('email_aanmelder');
		echo $this->Form->input('telefoon_aanmelder');
		echo $this->Form->input('notitie');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Iz Deelnemers', true), array('action' => 'index'));?></li>
	</ul>
</div>
