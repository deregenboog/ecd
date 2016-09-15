<div class="borderedContent form">
<h2 class="commentedHeader">Opmerkingen</h2>
<p class="headerComment">

<?php
		$klant_id = $klant['Klant']['id'];

		if (!empty($klant['Klant']['tussenvoegsel'])) {
			$klant['Klant']['tussenvoegsel'] = ' '.
			$klant['Klant']['tussenvoegsel'];
		}

		echo ' van '.$klant['Klant']['voornaam'].' '.
			$klant['Klant']['roepnaam'].$klant['Klant']['tussenvoegsel'].
			' '.$klant['Klant']['achternaam'];
?>

</p><br/>

<?php 
	echo $html->link('Opmerking toevoegen', array(
		'action' => 'add', $klant_id, 
	));
?>

<br/><br/>

<?php 
	if (count($opmerkingen) > 0) {
?>
<p>
<?php 
	foreach ($opmerkingen as $opmerking) {
?>

<?php
		$opmId = $opmerking['Opmerking']['id'];
		
		echo '<div id="ajax'.$opmId.'" class="editWrenchFloat">';

		$icon = & $this->Html->image('trash.png', array(
			'title' => __('delete', true),
		));
		
		echo $this->Html->link(
			$icon,
			array('action' => 'delete', $opmId, $klant_id),
			array('escape' => false),
			__('Are you sure you want to delete the note?', true)
		);

		echo $this->Form->create('opm'.$opmId);
		
		echo $this->Form->input('opgelost', array(
			'type' => 'checkbox',
			'checked' => $opmerking['Opmerking']['gezien'],
			'name' => 'data[opmerking][gezien]',
		));
		
		echo $this->Form->end();
		
		echo $this->Js->get('#opm'.$opmId.'Opgelost')->event('change', $this->Js->request(
			array('action' => 'gezien', $opmId),
			array(
				'method' => 'get',
				'async' => false,
			)
		));
		
		echo '</div>';
		
		echo '<h3 class="commentedHeader">';
		
		echo $date->show($opmerking['Opmerking']['modified'],
				array('short' => true));
		
		echo '</h3>';
		
		echo ' <p class="headerComment">(Categorie: ';
		
		echo $opmerking['Categorie']['naam'].')</p>';
		
		echo '<p>';
		
		echo $opmerking['Opmerking']['beschrijving'].'<br/><br/>'."\n";
		
		echo '</p>';
	}
?>
</p>
<?php 
		echo $this->Js->writeBuffer();
	} else {  
?> 
<p>Geen opmerkingen.</p>
<?php 
} 
?>
<p>
<?php 
	if (isset($locatie_id)) {
		
		$target = array('controller' => 'registraties', 'action' => 'index', $locatie_id);
		
	} else {
	
		$target = array('controller' => 'klanten','action' => 'view', $klant['Klant']['id'], );
	
	}
	
	echo $html->link('Terug', $target, array('class' => 'back')); 
?>
	
</p>
</div> <!-- opmerkingen div end -->
<div class="actions">
<?php

	echo $this->element('klantbasic', array('data' => $klant));
	
	echo $this->element('diensten', array( 'diensten' => $diensten, ));
	
?>
</div>
