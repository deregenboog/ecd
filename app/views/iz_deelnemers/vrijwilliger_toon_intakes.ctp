<fieldset>
<h2>Vrijwilliger Intake</h2>
<br/>
<?php
	$id = !empty($persoon['IzIntake']['id']) ? $persoon['IzIntake']['id'] : '';
	echo $html->link('bewerken', array('action' => 'intakes', $this->data['IzIntake']['iz_deelnemer_id']));
?>

<br/>
<br/>


<?php

	$intake_datum = date('Y-m-d');
	
	if (! empty($this->data['IzIntake']['intake_datum'])) {
		
		if (is_array($this->data['IzIntake']['intake_datum'])) {
			$intake_datum =$this->data['IzIntake']['intake_datum']['year']."-".$this->data['IzIntake']['intake_datum']['month']."-".$this->data['IzIntake']['intake_datum']['day'];
		} else {
			$intake_datum =$this->data['IzIntake']['intake_datum'];
		}
		
	}
	
	echo "Intake datum: ".$intake_datum."<br/><br/>";
	
	echo "Coodinator : ".$viewmedewerkers[$this->data['IzIntake']['medewerker_id']]."<br/><br/>";
	
	echo "Stagiair : ";
	
	echo ! empty($this->data['IzIntake']['stagiair']) ? "Ja" : "Nee";
	
	echo "<br/><br/>";

?>
</fieldset>
