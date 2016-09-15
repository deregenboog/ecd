<?php
$today = date('Y-m-d');

$url = array(
	'controller' => $this->name,
);

if (empty($this->data['Contactjournal']['id'])) {
	$url['action'] = 'contactjournal';
	$url[] = $this->data['Contactjournal']['klant_id'];
	$url[] = $this->data['Contactjournal']['is_tb'];
} else {
	$url['action'] = 'cj_edit';
	$url[] = $this->data['Contactjournal']['id'];
}

?>
<div class="hi5 form">
	<fieldset>
		<legend> <?= __('Aantekening toevoegen') ?> </legend>
<?php

echo $this->Form->create('Contactjournal', array('url' => $url));

echo $form->hidden('id');

echo $form->hidden('klant_id');

echo $form->hidden('is_tb');

echo $date->input('Contactjournal.datum', date('Y-m-d'),
	array(
		'label' => 'Datum',
		'rangeLow' => (date('Y') - 1).date('-m-d'),
		'required' => true,
		'rangeHigh' => $today,
));

echo $this->Form->input('medewerker_id', array(
	'default' => $this->Session->read('Auth.Medewerker.id'),
));

echo $form->input('text', array('label' => 'Notitie'));

echo $this->Form->end(__('Notitie opslaan', true));
?>
	</fieldset>
</div>
