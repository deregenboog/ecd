<?php echo $this->element('iz_subnavigation'); ?>
<?php echo $this->element('iz_beheer_subnavigation'); ?>
<div class="izProjecten ">
<?php echo $this->Form->create('IzProject');?>
	<fieldset>
		<legend><?php __('Add Iz Project'); ?></legend>
	<?php
		echo $this->Form->input('naam');
		echo $date->input('IzProject.startdatum', null, array(
				'label' => 'Startdatum',
				'rangeHigh' => (date('Y') + 10).date('-m-d'),
				'rangeLow' => (date('Y') - 3).date('-m-d'),
		));
		echo $date->input('IzProject.einddatum', null, array(
			'label' => 'Einddatum',
			'rangeHigh' => (date('Y') + 10).date('-m-d'),
			'rangeLow' => (date('Y') - 3).date('-m-d'),
		));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
