<?php 

$hoofdclient = true;

if (isset($this->data['PfoClientenSupportgroup'])) {
	if (count($this->data['PfoClientenSupportgroup']) == 0) {
		$hoofdclient = false;
	}
}

if (empty($this->data)) {
	$hoofdclient = true;
}

$title = "Nieuwe PFO client invoeren";

if (isset($this->data['PfoClient']['id'])) {
	$title = "Edit PFO client";
}

?>

<div >

		
<?php
	echo $this->Form->create('PfoClient');
	
	echo $this->Form->hidden('id');
	
	echo '<fieldset class="twoDivs"><legend>'.__('Persoonsgegevens', true).'</legend>';
	
	echo '<div class="leftDiv">';
	
	echo $this->Form->input('roepnaam', array(
		'label' => __('Roepnaam', true),
	));

	echo $this->Form->input('tussenvoegsel', array(
		'label' => __('Tussenvoegsel', true),
	));

	echo $this->Form->input('achternaam', array(
		'label' => __('Achternaam', true),
	));

	echo '</div><div class="rightDiv">';
	
	echo $this->Form->input('geslacht_id', array(
		'label' => __('Geslacht', true),
	));
	
	echo $date->input('PfoClient.geboortedatum', null, array(
		'label' => 'Geboortedatum',
		'rangeLow' => (date('Y') - 100).date('-m-d'),
		'rangeHigh' => date('Y-m-d'),
	));

	echo '</div></fieldset>';

	echo '<fieldset class="twoDivs"><legend>'.__('Contactgegevens', true).'</legend>';
	
	echo '<div class="leftDiv">';
	
	echo $this->Form->input('adres', array(
		'label' => __('Adres', true),
	));

	echo $this->Form->input('postcode', array(
		'label' => __('Postcode', true),
	));

	echo $this->Form->input('woonplaats', array(
		'label' => __('Woonplaats', true),
	));

	echo $this->Form->input('telefoon', array(
		'label' => __('Telefoon vast', true),
	));

	echo $this->Form->input('telefoon_mobiel', array(
		'label' => __('Telefoon mobiel', true),
	));

	echo '</div><div class="rightDiv">';
	
	echo $this->Form->input('email', array(
		'label' => __('Email', true),
	));

	echo $this->Form->input('notitie', array(
		'label' => __('Notitie', true),
	));

	echo '</div></fieldset>';

	echo '<fieldset class="twoDivs"><legend>'.__('Aanmeldgegevens', true).'</legend>';
	
	echo '<div class="leftDiv">';
	
	echo $this->Form->input('medewerker_id', array(
		'type' => 'select',
		'options' => $medewerkers,
	));
	
	echo $this->Form->input('groep', array(
		'type' => 'select',
		'options' => $groepen,
	));
	
	echo $this->Form->input('aard_relatie', array(
		'type' => 'select',
		'options' => $aard_relatie,
	));
	
	echo $this->Form->input('dubbele_diagnose',
		array(
			'type' => 'radio',
			'default' => '0',
			'options' => array('0' => 'Nee', '1' => 'Ja', '2' => 'Vermoedelijk'),
			'legend' => 'Dubbele diagnose?',
	));

	echo $this->Form->input('eerdere_hulpverlening',
		array(
			'type' => 'radio',
			'default' => '0',
			'options' => array('0' => 'Nee', '1' => 'Ja'),
			'legend' => 'Eerder hulpverlening ontvangen?',
	));
	
	echo $this->Form->input('via', array('label' => 'Via'));
	
	echo '</div><div class="rightDiv">';
	
	echo $this->Form->input('hulpverleners', array('label' => 'Andere betrokken hulpverleners'));
	
	echo $this->Form->input('contacten', array('label' => 'Andere belangrijke contacten'));

	echo $date->input('PfoClient.begeleidings_formulier', null, array(
		'label' => 'Begeleidingsformulier overhandigd',
		'rangeLow' => (date('Y') - 20).date('-m-d'),
		'rangeHigh' => date('Y-m-d'),
	));

	echo $date->input('PfoClient.brief_huisarts', null, array(
		'label' => 'Brief huisarts verstuurd',
		'rangeLow' => (date('Y') - 20).date('-m-d'),
		'rangeHigh' => date('Y-m-d'),
	));

	echo $date->input('PfoClient.evaluatie_formulier', null, array(
		'label' => 'Evaluatieformulier overhandigd',
		'rangeLow' => (date('Y') - 20).date('-m-d'),
		'rangeHigh' => date('Y-m-d'),
	));

	echo $date->input('PfoClient.datum_afgesloten', null, array(
		'label' => 'Datum afgesloten',
		'rangeLow' => (date('Y') - 60).date('-m-d'),
		'rangeHigh' => date('Y-m-d'),
	));

	echo '</div></fieldset>';
	
	echo '<fieldset><legend>'.__('Koppeling', true).'</legend>';

	echo $this->Form->hidden('controll.hoofdclient', array(
		'label' => 'Hoofdclient_hidden',
		'id' => 'hoofdclient_toggle_hidden',
		'value' => $hoofdclient,
	));
	
	echo $this->element('pfo_supportgroup', array(
		'pfoClient' => $this->data,
		'data' => $this->data,
		'clienten' => $clienten,
		'hoofdclient' => $hoofdclient,
	));

	$this->Js->buffer('Ecd.supportgroup();');

	echo '</fieldset>';
	
	echo $this->Form->end(__('Submit', true));

?>
</div>
