<h2><?php __('AWBZ Klantenlijst');?></h2>

<?php

	echo $form->create('Klant', array('controller' => 'awbz', 'action'=>'index', 'id'=>'filters'));
	
	$dd = array('type' => 'text', 'label' => false);
	
	echo '<table class="filter"><tr>';
	
	echo '<td class="klantnrCol">'.$form->input('id', $dd).'</td>';
	
	echo '<td class="voornaamCol">'.$form->input('voornaam', $dd).'</td>';
	
	echo '<td class="achternaamCol">'.$form->input('achternaam', $dd).'</td>';
	
	echo '<td class="gebortedatumCol">'.$form->input('geboortedatum', $dd).'</td>';
	
	echo '<td colspan="3"></td>';
	
	echo '</tr></table>';

	echo $form->end();
	
	$ajax_url = $this->Html->url('/awbz/index', true);
	$this->Js->get('#filters');
	$this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');
	
	echo $js->writeBuffer();
?>

<div id="contentForIndex">
	<?php echo $this->element('awbz_klantenlijst'); ?>
</div>
