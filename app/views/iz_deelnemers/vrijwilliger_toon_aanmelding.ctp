<fieldset>

<h2>Vrijwilliger Aanmelding</h2>

<br/>
<?php
	echo $html->link('bewerken', array('action' => 'aanmelding', 'Vrijwilliger', $this->data['Vrijwilliger']['id']));
?>
<br/>
<br/>


<?php
	$datum_aanmelding = "";
	
	if (! empty($this->data['IzDeelnemer']['datum_aanmelding'])) {
		if (is_array($this->data['IzDeelnemer']['datum_aanmelding'])) {
			$datum_aanmelding =$this->data['IzDeelnemer']['datum_aanmelding']['year']."-".$this->data['IzDeelnemer']['datum_aanmelding']['month']."-".$this->data['IzDeelnemer']['datum_aanmelding']['day'];
		} else {
			$datum_aanmelding =$this->data['IzDeelnemer']['datum_aanmelding'];
		}
	}
	
	echo"Datum aanmelding: ".$datum_aanmelding."<br/><br/>";

	echo"Binnengekomen Via: ".$viaPersoon[$this->data['IzDeelnemer']['binnengekomen_via']]."<br/><br/>";

	echo "<h4>Interesse in projecten</h4><br/>";
	
	foreach ($this->data['IzDeelnemersIzProject'] as $project) {
		echo $projectlists[$project['iz_project_id']]."<br/>";
	}

	echo "<br/><h4>Notitie</h4><br/>";
	
	echo preg_replace("/\n/", "<br/>\n", $this->data['IzDeelnemer']['notitie'])."<br/><br/>";

?>



