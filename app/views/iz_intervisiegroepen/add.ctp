<?php echo $this->element('iz_subnavigation'); ?>
<?php echo $this->element('iz_beheer_subnavigation'); ?>
<div class="izIntervisiegroepen ">
<?php echo $this->Form->create('IzIntervisiegroep');?>
	<fieldset>
		<legend><?php __('Add Iz Intervisiegroep'); ?></legend>
	<?php
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
