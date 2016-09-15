<fieldset>

<h2>Klant Aanmelding</h2>
<br/>

<?php
	echo $html->link('bewerken', array('action' => 'aanmelding', 'Klant', $this->data['Klant']['id']));
?>

<br/>
<br/>

<?php
	$datum_aanmelding = date('Y-m-d');
	
	if (! empty($this->data['IzDeelnemer']['datum_aanmelding'])) {
		
		if (is_array($this->data['IzDeelnemer']['datum_aanmelding'])) {
			$datum_aanmelding =$this->data['IzDeelnemer']['datum_aanmelding']['year']."-".$this->data['IzDeelnemer']['datum_aanmelding']['month']."-".$this->data['IzDeelnemer']['datum_aanmelding']['day'];
		} else {
			$datum_aanmelding =$this->data['IzDeelnemer']['datum_aanmelding'];
		}
	}
	
	echo"Datum aanmelding: ".$datum_aanmelding."<br/><br/>";

	echo "<h4>Interesse in projecten</h4><br/>";
	
	foreach ($this->data['IzDeelnemersIzProject'] as $project) {
		echo $projectlists[$project['iz_project_id']]."<br/>";
	}

	echo "<br/><h4>Aanmelder</h4><br/>";
	
	echo "Contact ontstaan: ".$ontstaanContactList[$this->data['IzDeelnemer']['contact_ontstaan']]."<br/><br/>";
	
	echo "Organisatie: ".$this->data['IzDeelnemer']['organisatie']."<br/><br/>";
	
	echo "Naam aanmelder: ".$this->data['IzDeelnemer']['naam_aanmelder']."<br/><br/>";
	
	echo "Email aanmelder: ".$this->data['IzDeelnemer']['email_aanmelder']."<br/><br/>";
	
	echo "Telefoon aanmelder: ".$this->data['IzDeelnemer']['telefoon_aanmelder']."<br/>";

	echo "<br/><h4>Notitie</h4><br/>";
	
	echo preg_replace("/\n/", "<br/>\n", $this->data['IzDeelnemer']['notitie'])."<br/><br/>";
?>

</fieldset>


<?php
?>

