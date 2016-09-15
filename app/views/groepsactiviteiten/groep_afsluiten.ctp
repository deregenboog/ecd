<?php

if (isset($validation_error) && ! $validation_error) {
	
	echo "<script>location.reload();</script>";
	return;
	
}

$url=$this->Html->url(
		array(
				'controller' => 'groepsactiviteiten',
				'action' => 'afsluiten',
				$persoon_model,
				$persoon_id,
				$groepsactiviteit['id'],
		)
);

$showdt='none';

if (isset($validation_error) && !empty($validation_error)) {
	$showdt = 'true';
}

$wrench = $html->image('delete.png');

echo '<a href="#" id="delete_communcatie_settings'.$groepsactiviteit['id'].'" class="communicatie_option">'.$wrench.'</a>';

$de = $groepsactiviteit['einddatum'];

if (is_array($groepsactiviteit['einddatum'])) {
	$de = "{$groepsactiviteit['einddatum']['year']}-{$groepsactiviteit['einddatum']['month']}-{$groepsactiviteit['einddatum']['day']}";
}

if (!empty($de)) {
	echo $date->show($de);
}

echo $this->Form->create('Groepsactiviteit', array(
		'url' => array('action' => 'afsluiten', $persoon_model, $persoon_id, $groepsactiviteit['id']),
				'id' => "groep_afsluiten_form".$groepsactiviteit['id'],
				'style' => 'display: '.$showdt.';',
));

echo $this->Form->hidden("{$persoon_groepsactiviteiten_groepen}.id", array('value' => $groepsactiviteit['id']));
echo $this->Form->hidden("{$persoon_groepsactiviteiten_groepen}.startdatum", array('value' => $groepsactiviteit['startdatum']));

echo $date->input("{$persoon_groepsactiviteiten_groepen}.einddatum", date('Y-m-d'), array(
		'id' => 'date'.$groepsactiviteit['id'].date('U'),
		'label' => 'Einddatum',
		'rangeHigh' => (date('Y') + 10).date('-m-d'),
		'rangeLow' => (date('Y') - 19).date('-m-d'),
));

echo $this->Form->input("{$persoon_groepsactiviteiten_groepen}.groepsactiviteiten_reden_id", array(
	'label' => 'Reden',
	'value' => $groepsactiviteit['groepsactiviteiten_reden_id'],
	'options' => array(''=>'') + $groepsactiviteiten_redenen,
));

echo $this->Form->button('Opslaan', array(
	'id' => 'submit_afsluiten'.$groepsactiviteit['id'],
	'type' => 'button',
	'href' => '#',
));

echo $this->Form->end();

$this->Js->buffer("Ecd.groep_afsluiten(".$groepsactiviteit['id'].",'".$url."');");

