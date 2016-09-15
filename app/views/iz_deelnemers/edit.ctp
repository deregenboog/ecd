<div class="izDeelnemers form">
<?php echo $this->Form->create('IzDeelnemer');?>
	<fieldset>
		<legend><?php __('Edit Iz Deelnemer'); ?></legend>
	<?php
		echo $this->Form->input('id');
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

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('IzDeelnemer.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('IzDeelnemer.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Iz Deelnemers', true), array('action' => 'index'));?></li>
	</ul>
</div>
