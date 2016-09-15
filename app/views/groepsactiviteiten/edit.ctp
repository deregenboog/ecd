<?php echo $this->element('groepsactiviteiten_subnavigation'); ?>

<div class="groepsactiviteiten ">

<?php echo $this->Form->create('Groepsactiviteit', array('url' => array($id)));?>

	<fieldset>
		<legend><?php __('Bewerk Groepsactiviteit'); ?></legend>
		
	<?php
	
		echo $this->Form->input('groepsactiviteiten_groep_id', array('options' => array('' => '' ) + $groepsactiviteitengroepen));
		
		echo $this->Form->input('naam');
		
		echo $date->input("Groepsactiviteit.datum", null, array(
			'label' => 'Datum',
			'rangeHigh' => (date('Y') + 10).date('-m-d'),
			'rangeLow' => (date('Y') - 19).date('-m-d'),
		));
		
		echo $this->Form->input('time');
		
	?>
	</fieldset>
	
<?php echo $this->Form->end(__('Submit', true));?>

<?= $this->Html->link(__('terug', true), array('action' => 'planning')); ?>

</div>
