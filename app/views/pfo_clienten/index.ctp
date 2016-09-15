<?php echo $this->element('pfo_subnavigation'); ?>
<h2><?php __('PFO Clienten lijst');?></h2>

<?php
	$afsluitdatum = "";
	if ($active) {
		$afsluitdatum = "";
	}
	$a=array('' => '');
	$groepen = $a + $groepen;

	echo $html->link('Nieuwe klant invoeren', array('action' => 'add'), array(),
		__('Weet u zeker dat dit een nieuwe klant is?', true));

	echo $form->create('PfoClient', array('controller' => 'pfo_clienten', 'action'=>'index', 'id'=>'filters'));
	$dd = array('type' => 'text', 'label' => false);
	$dr = array('type' => 'select', 'options' => $groepen, 'label' => false);
	$dm = array('type' => 'select', 'options' => $medewerkers, 'label' => false);
	
	echo '<table class="filter"><tr>';
	echo '<td class="pfovoornaamColHeader">'.$form->input('roepnaam', $dd).'</td>';
	echo '<td class="pfoachternaamColHeader">'.$form->input('achternaam', $dd).'</td>';
	echo '<td class="pfogroepColHeader">'.$form->input('groep', $dr).'</td>';
	echo '<td class="medewerkerCol">'.$form->input('medewerker_id', $dm).'</td>';
	echo '<td class="medewerkerCol">'.$afsluitdatum.'</td>';
	echo '</tr></table>';

	echo $form->end();
	
	if (empty($active)) {
		$ajax_url = $this->Html->url('/pfo_clienten/index', true);
	} else {
		$ajax_url = $this->Html->url('/pfo_clienten/index/'.$active, true);
	}

	$this->Js->get('#PfoClientRoepnaam');
	$this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');

	$this->Js->get('#PfoClientAchternaam');
	$this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');

	$this->Js->get('#PfoClientGroep');
	$this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');

	$this->Js->get('#PfoClientMedewerkerId');
	$this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');

	echo $js->writeBuffer();

?>

<div id="contentForIndex">
	<?php echo $this->element('pfoclientenlijst'); ?>
</div>
