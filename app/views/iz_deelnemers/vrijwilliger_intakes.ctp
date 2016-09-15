<fieldset>

<h2>Vrijwilliger Intake</h2>

<?php
	$url = $this->Html->url(array('controller' => 'iz_deelnemers', 'action' => 'intakes', $id), true);

	echo $this->Form->create(
		'IzIntake',
		array(
			'url' => $url,
		)
	);

	$id = !empty($persoon['IzIntake']['id']) ? $persoon['IzIntake']['id'] : '';
	echo $this->Form->hidden('IzIntake.id', array('value' => $id));

	$intake_datum = date('Y-m-d');
	if (! empty($this->data['IzIntake']['intake_datum'])) {
		if (is_array($this->data['IzIntake']['intake_datum'])) {
			$intake_datum =$this->data['IzIntake']['intake_datum']['year']."-".$this->data['IzIntake']['intake_datum']['month']."-".$this->data['IzIntake']['intake_datum']['day'];
		} else {
			$intake_datum =$this->data['IzIntake']['intake_datum'];
		}
	}

		echo $date->input("IzIntake.intake_datum",	$intake_datum,
			array(
			'label' => 'Intakedatum',
			'rangeHigh' => (date('Y') + 10).date('-m-d'),
			'rangeLow' => (date('Y') - 19).date('-m-d'),
			))
?>

<?php

	echo $this->Form->input('medewerker_id', array('options' => $viewmedewerkers, 'label' => 'Coordinator'));

	echo $this->Form->input('stagiair', array('type' => 'checkbox', 'label' => 'Stagiair' ));

	echo $this->Form->submit('Opslaan', array('id' => 'verslag-submit-0', 'div' => false));

	echo $this->Form->end();
?>

</fieldset>


