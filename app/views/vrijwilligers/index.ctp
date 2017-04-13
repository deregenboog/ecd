<?php 

		$wrench = $html->image('user_add.png');
		$url = array('action' => 'add');
		$opts = array('escape' => false, 'title' => __('add', true));
		
		echo $html->link($wrench, $url, $opts);
		echo $this->Html->link(__('Nieuwe vrijwilliger', true), array('action' => 'add'));
		
?>

<div>&nbsp;</div>

<h2><?= $persoon_model; ?></h2>

<?php

	echo $form->create($persoon_model, array('controller' => $persoon_model, 'action'=>'index', 'id'=>'filters', 'selection' => $persoon_model));
	echo $form->hidden('selectie', array('value' => $persoon_model));
	
	$dd = array('type' => 'text', 'label' => false);
	$dm = array('type' => 'select', 'options' => $medewerkers, 'label' => false);

	echo '<table class="filter"><tr>';
	echo '<td class="klantnrCol">'.$form->input('id', $dd).'</td>';
	echo '<td class="voornaamCol">'.$form->input('voornaam', $dd).'</td>';
	echo '<td class="achternaamCol">'.$form->input('achternaam', $dd).'</td>';
	echo '<td class="gebortedatumCol">'.$form->input('geboortedatum', $dd).'</td>';
	echo '<td class="medewerkerCol">'.$form->input('medewerker_id', $dm).'</td>';
	echo '<td class="overeenkomstCol">'.$form->input('overeenkomst_aanwezig', array('type' => 'select', 'options' => array('' => '', 0 => 'Nee', 1 => 'Ja'), 'label' => false)).'</td>';
	echo '<td colspan="5"></td>';
	echo '</tr></table>';

	echo $form->end();
	
	$onclick_action = $rowOnclickUrl['controller'].'.'.$rowOnclickUrl['action'];
	$ajax_url = $this->Html->url('/vrijwilligers/index/rowUrl:'.$onclick_action, true);
	
	$this->Js->get('#filters');
	$this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');
	$this->Js->get('#VrijwilligerMedewerkerId');
	$this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
	$this->Js->get('#VrijwilligerOvereenkomstAanwezig');
	$this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');

	echo $this->Js->writeBuffer();
?>


<div id="contentForIndex">
	<?php
		echo $this->element('personen_lijst', array('bot' => false));
	?>
</div>
