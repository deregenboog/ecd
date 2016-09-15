<fieldset>
	<legend>Rapportageopties</legend>
	
	<?php
		echo $this->Form->create('options', array(
			'type' => 'post',
			'url' => array(
				'controller' => empty($rapportageController) ? 'Rapportages' : $rapportageController,
				'action' => empty($overrideAction) ? $this->action : $overrideAction,
			),
		));
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

	echo $this->Form->input('report', array(
		'type' => 'select',
		'options' => $report_select,
	));

	if (!empty($enableExcel)) {
		echo '<div>';
		echo $this->Form->checkbox('excel');
		echo $this->Form->label(__('Excel export', true));
		echo '</div>';
	}

	?>

	<?php echo $form->end(array('label' => 'Ga')); ?>
</fieldset>
