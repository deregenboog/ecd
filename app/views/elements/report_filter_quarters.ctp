<?php

$years = array();
$currentYear = date('Y');

for ($i = 2009; $i <= $currentYear; $i++) {
	$years[$i] = $i;
}

$quarters = array(
	1 => '1e kwartaal',
	2 => '2e kwartaal',
	3 => '3e kwartaal',
	4 => '4e kwartaal',
)

?>
<fieldset>
	<legend>Rapportageopties</legend>
	<?php
	
		echo $this->Form->create('options', array(
			'type' => 'post',
			'id' => $id,
			'url' => array(
				'controller' => 'Rapportages',
				'action' => empty($overrideAction) ? $this->action : $overrideAction,
			),
		));

		if (empty($disableLocation)) {
			
			echo '<h4>Locatie</h4>';
			
			echo $this->Form->input('location', array(
				'empty' => false,
				'type' => 'select',
				'options' => $locations,
				'label' => false,
			));
			
		}
	?>
	
	<h4>Periode</h4>
	<p>Jaar:</p>
	<?php
	
	echo $this->Form->input('year', array(
		'label' => false,
		'options' => $years,
	));
	
	?>
	<p>Kwartaal:</p>
	
	<?php
	
	echo $this->Form->input('quarter', array(
		'label' => false,
		'options' => $quarters,
	));

	$radio_options = array(
		'legend' => __('Sex', true),
		'type' => 'radio',
		'options' => array(
			1 => __('Men', true),
			2 => __('Women', true),
			0 => __('Men and women', true),
		),

	);

	if (empty($disableGender)) {
		
		if (empty($this->data)) {
			$radio_options['default'] = 0;
		}

		echo $this->Form->input('geslacht_id', $radio_options);
	}

	if (!empty($enableExcel)) {
		
		echo $this->Form->checkbox('excel');
		echo $this->Form->label(__('Excel export', true));
		
	}
	?>

	<?php echo $form->end(array('label' => 'Ga')); ?>
	
</fieldset>
