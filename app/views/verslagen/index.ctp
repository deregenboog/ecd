<h2>Maatschappelijk werk</h2>

<?php

	echo $this->Html->link('Rapportage', array(
		'controller' => 'maatschappelijk_werk',
		'action' => 'rapportage',
	));

	echo $form->create('Klant', array('controller' => 'MaatschappelijkWerk', 'action'=>'index', 'id'=>'filters'));
	
	$dd = array('type' => 'text', 'label' => false);
	$dm = array('type' => 'select', 'options' => $medewerkers, 'label' => false);
	
	$dt = $date->input("verslagen.laatste_rapportage", null,
		array(
		'label' => false,
		'rangeHigh' => (date('Y') + 10).date('-m-d'),
		'rangeLow' => (date('Y') - 19).date('-m-d'),
	));

	echo '<table class="filter"><tr>';
	
	echo '<td class="klantnrCol">'.$form->input('id', $dd).'</td>';
	
	echo '<td class="voornaamCol">'.$form->input('voornaam', $dd).'</td>';
	
	echo '<td class="achternaamCol">'.$form->input('achternaam', $dd).'</td>';
	
	echo '<td class="gebortedatumCol">'.$form->input('geboortedatum', $dd).'</td>';
	
	echo '<td class="medewerkerCol">'.$form->input('medewerker_id', $dm).'</td>';
	
	echo '<td class="datumCol">'.$dt.'</td>';
	
	echo '<td colspan="1"></td>';
	
	echo '</tr></table>';

	echo $form->end();
	
	$onclick_action =
		$rowOnclickUrl['controller'].'.'.$rowOnclickUrl['action'];
	
	$ajax_url =
		$this->Html->url('/MaatschappelijkWerk/index/rowUrl:'.$onclick_action, true);
	
	$this->Js->get('#filters');
	
	$this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');
	
	$this->Js->get('#KlantMedewerkerId');
	
	$this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');

	$this->Js->get('#VerslagenLaatsteRapportage');
	
	$this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
	
	$this->Js->get('#VerslagenLaatsteRapportage-dd');
	
	$this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
	
	$this->Js->get('#VerslagenLaatsteRapportage-mm');
	
	$this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
	
	echo $this->Js->writeBuffer();
?>


<div id="contentForIndex">
	<?php
		echo $this->element('klantenlijst', array('maatschappelijkwerk' => true));
	?>
</div>
