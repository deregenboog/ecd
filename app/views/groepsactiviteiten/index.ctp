<?php echo $this->element('groepsactiviteiten_subnavigation'); ?>
<h2>Groeps activiteiten - <?= $persoon_model; ?></h2>

<?php

	echo $form->create($persoon_model, array('controller' => 'Groepsactiviteiten', 'action'=>'index', 'id'=>'filters', 'selection' => $persoon_model));
	echo $form->hidden('selectie', array('value' => $persoon_model));
	
	$dd = array('type' => 'text', 'label' => false, 'style' => 'width: 60px;');
	$dm = array('type' => 'select', 'options' => $medewerkers, 'label' => false, 'style' => 'width: 100px;');

	echo '<table class="filter"><tr>';
	
	echo '<td class="klantnrCol">'.$form->input('id', $dd).'</td>';
	
	echo '<td class="voornaamCol">'.$form->input('voornaam', $dd).'</td>';
	
	echo '<td class="achternaamCol">'.$form->input('achternaam', $dd).'</td>';
	
	echo '<td class="gebortedatumCol">'.$form->input('geboortedatum', $dd).'</td>';
	
	echo '<td class="medewerkerCol">'.$form->input('medewerker_id', $dm).'</td>';
	
	echo '<td class="show_allCol">'.$form->input('show_all', array(
			'type' => 'checkbox',
			'label' => 'Toon alle Regenboog klanten',
			'checked' => false,
	)).'</td>';
	
	echo '</tr></table>';

	echo $form->end();
	
	$onclick_action = $rowOnclickUrl['controller'].'.'.$rowOnclickUrl['action'];
	$ajax_url = $this->Html->url('/groepsactiviteiten/index/selectie:'.$persoon_model.'/rowUrl:'.$onclick_action, true);
	
	$this->Js->get('#filters');
	$this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');
	$this->Js->get('#'.$persoon_model.'MedewerkerId');
	$this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
	$this->Js->get('#'.$persoon_model.'ShowAll');
	$this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');

	echo $this->Js->writeBuffer();
	
?>


<div id="contentForIndex">
	<?php
		echo $this->element('personen_lijst', array('bot' => false));
	?>
</div>


