<?php echo $this->element('iz_subnavigation'); ?>
<?php echo $this->element('iz_beheer_subnavigation'); ?>
<div class="izIntervisiegroepen ">
<?php echo $this->Form->create('IzIntervisiegroep');?>
	<fieldset>
		<legend><?php __('Edit Iz Intervisiegroep'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('naam');
		echo $date->input('IzIntervisiegroep.startdatum', null, array(
				'label' => 'Startdatum',
				'rangeHigh' => (date('Y') + 10).date('-m-d'),
				'rangeLow' => (date('Y') - 3).date('-m-d'),
		));
		echo $date->input('IzIntervisiegroep.einddatum', null, array(
			'label' => 'Einddatum',
			'rangeHigh' => (date('Y') + 10).date('-m-d'),
			'rangeLow' => (date('Y') - 3).date('-m-d'),
		));
		echo $this->Form->input('medewerker_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('IzIntervisiegroep.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('IzIntervisiegroep.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Iz Intervisiegroepen', true), array('action' => 'index'));?></li>
	</ul>
</div>
