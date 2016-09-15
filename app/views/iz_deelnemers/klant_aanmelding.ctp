<fieldset>

<h2>Klant Aanmelding</h2>

<?php
	$url = $this->Html->url(array('controller' => 'iz_deelnemers', 'action' => 'aanmelding', $persoon_model, $persoon[$persoon_model]['id'], $id), true);
	
	echo $this->Form->create(
		'IzDeelnemer',
		array(
		   'url' => $url,
		)
	);

	$datum_aanmelding = date('Y-m-d');
	
	if (! empty($this->data['IzDeelnemer']['datum_aanmelding'])) {
		if (is_array($this->data['IzDeelnemer']['datum_aanmelding'])) {
			$datum_aanmelding =$this->data['IzDeelnemer']['datum_aanmelding']['year']."-".$this->data['IzDeelnemer']['datum_aanmelding']['month']."-".$this->data['IzDeelnemer']['datum_aanmelding']['day'];
		} else {
			$datum_aanmelding =$this->data['IzDeelnemer']['datum_aanmelding'];
		}
	}

	echo $date->input("IzDeelnemer.datum_aanmelding", $datum_aanmelding,
			array(
			'label' => 'Datum aanmelding',
			'rangeHigh' => (date('Y') + 10).date('-m-d'),
			'rangeLow' => (date('Y') - 19).date('-m-d'),
			));

	echo $this->Form->input('project_id', array(
			'type'=>'select',
			'multiple'=>'checkbox',
			'options'=> $projectlists,
			'label'=>'Interesse in project', ));

	echo $this->Form->input('IzDeelnemer.contact_ontstaan', array(
		'type' => 'select',
		'options' => $ontstaanContactList,
		'label' => 'Hoe is contact ontstaan?',
	));

	echo $this->Form->input('IzDeelnemer.organisatie', array(
			'label' => 'Aanmeldende organisatie',
	));

	echo $this->Form->input('IzDeelnemer.naam_aanmelder', array(
			'label' => 'Naam aanmelder',
	));

	echo $this->Form->input('IzDeelnemer.email_aanmelder', array(
			'label' => 'Mailadres aanmelder',
	));

	echo $this->Form->input('IzDeelnemer.telefoon_aanmelder', array(
			'label' => 'Telefoonnummer aanmelder',
	));

	echo "<label>Notitie</label>";
	
	echo $this->Form->textarea('IzDeelnemer.notitie', array('class' => 'verslag-textarea'));

	echo $this->Form->submit('Opslaan', array('id' => 'verslag-submit-0', 'div' => false));
	
	echo $this->Form->end();
?>

</fieldset>


<?php
?>

