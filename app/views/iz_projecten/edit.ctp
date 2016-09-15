<?php echo $this->element('iz_subnavigation'); ?>
<?php echo $this->element('iz_beheer_subnavigation'); ?>
<div class="izProjecten ">
<?php echo $this->Form->create('IzProject');?>
	<fieldset>
		<legend><?php __('Edit Iz Project'); ?></legend>
	<?php
		echo $this->Form->input('id');
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
		echo $this->Form->input('heeft_koppelingen');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
