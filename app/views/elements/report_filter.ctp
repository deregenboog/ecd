<fieldset>
	<legend>Rapportageopties</legend>
	
	<?php
	
		$options = 0;
		
		echo $this->Form->create('options', array(
			'type' => 'post',
			'url' => array(
				'controller' => empty($rapportageController) ? 'Rapportages' : $rapportageController,
				'action' => empty($overrideAction) ? $this->action : $overrideAction,
			),
		));

		if (empty($disableLocation)) {
			
			echo '<h4>Locatie</h4>';
			echo $this->Form->input('location', array(
				'type' => 'select',
				'options' => array(0 => 'Alle locaties') + $locations,
				'label' => false,
			));
			
			$options++;
		}

	if (empty($disableDates)) {
		
		$options++; 
	?>
	
	<h4>Periode</h4>
	<p>Vanaf:</p>
	
	<?php

	echo $date->input('date_from', null, array(
		'class' => 'date',
		'rangeLow' => '2009-12-31',
		'rangeHigh' => date('Y-m-d'), )); 
	
	?>
	
	<p>tot en met:</p>
	
	<?php

	echo $date->input('date_to', null, array(
		'class' => 'date',
		'rangeLow' => '2009-12-31',
		'rangeHigh' => date('Y-m-d'),
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
	}

	if (!empty($yearOptions)) {
		
		if (empty($this->data)) {
			$radio_options['default'] = 0;
		}
		
		$yv = date('Y');

		echo $this->Form->input('year', array(
			'label' => 'Jaar',
			'type' => 'select',
			 'options' => $yearOptions,
			'value' => $yv,
		));
		
		$options++;
	}
	
	if (empty($disableGender)) {
		
		if (empty($this->data)) {
			$radio_options['default'] = 0;
		}

		echo $this->Form->input('geslacht_id', $radio_options);
		$options++;
		
	}

	if (!empty($enableExcel)) {
		
		echo '<div>';
		
		echo $this->Form->checkbox('excel');
		echo $this->Form->label(__('Excel export', true));
		
		echo '</div>';
		
		$options++;
		
	}

	if (!empty($references)) {
		
		echo $this->Form->input('reference_id', array('label' => 'Reference'));
		$options++;
		
	}

	if (!empty($countries)) {
		
		$options++;
		echo $this->Form->input('land_id', array('multiple' => true, 'label' => 'Landen'));
		echo "Ctrl+klik voor meerdere selecties";
		
	}
	?>

	<?php echo $form->end(array('label' => 'Ga')); ?>
	
</fieldset>
