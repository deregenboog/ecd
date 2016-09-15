<h2>Back On Track</h2>

<?php
	if ($back_on_track_coordinator) {
		
		echo $html->link('Nieuwe klant invoeren', array( 'controller' => 'Klanten', 'action' => 'add'), array(),
			__('Hebt u de algemene klantenlijst al gechecked? Weet u zeker dat dit een nieuwe klant is?', true));
	
	}
	
	echo $form->create('Klant', array('controller' => 'BackOnTrack', 'action'=>'index', 'id'=>'filters'));
	
	$dd = array('type' => 'text', 'label' => false);
	$dm = array('type' => 'select', 'options' => $medewerkers, 'label' => false);

	echo '<table class="filter"><tr>';
	
	echo '<td class="klantnrCol">'.$form->input('id', $dd).'</td>';
	
	echo '<td class="voornaamCol">'.$form->input('voornaam', $dd).'</td>';
	
	echo '<td class="achternaamCol">'.$form->input('achternaam', $dd).'</td>';
	
	echo '<td class="gebortedatumCol">'.$form->input('geboortedatum', $dd).'</td>';
	
	echo '<td class="medewerkerCol">'.$form->input('medewerker_id', $dm).'</td>';
	
	if ($back_on_track_coordinator) {
		echo '<td class="show_allCol">'.$form->input('show_all', array(
			'type' => 'checkbox',
			'label' => 'Toon alle klanten',
			'checked' => false,
	)).'</td>';
	} else {
		echo '<td/>';
	}
	
	echo '<td colspan="4"></td>';
	
	echo '</tr></table>';

	echo $form->end();
	
	$onclick_action = $rowOnclickUrl['controller'].'.'.$rowOnclickUrl['action'];
	
	$ajax_url = $this->Html->url('/BackOnTrack/index/rowUrl:'.$onclick_action, true);
	
	$this->Js->get('#filters');
	
	$this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');
	
	$this->Js->get('#KlantMedewerkerId');
	
	$this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
	
	$this->Js->get('#KlantShowAll');
	
	$this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
	

	echo $this->Js->writeBuffer();
?>


<div id="contentForIndex">
<?php
	echo $this->element('klantenlijst', array('bot' => false));
?>
</div>
