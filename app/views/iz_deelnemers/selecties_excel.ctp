<?php

function clean($s)
{
	$s = preg_replace("/'/", "\\'", $s);
	return $s;
}

	echo "Klantnr\t";
	echo "Fase\t";
	echo "Intake datum\t";
	echo "Afsluit datum\t";
	echo "Geslacht\t";
	echo "Voornaam\t";
	echo "Tussenvoegsel\t";
	echo "Achternaam\t";
	echo "Geboortedatum\t";
	echo "E-mail\t";
	echo "Adres\t";
	echo "Postcode\t";
	echo "Woonplaats\t";
	echo "Mobiel\t";
	echo "Telefoon\t";
	echo "Coordinator\t";
	echo "Werkgebied\t";
	echo "Postcodegebied\t";
	echo "Stagiair\t";
	echo "Gezin met kinderen\t";
	echo "Geen post\t";
	echo "Geen email\t";
	echo "Projecten\t";
	echo PHP_EOL;

	foreach ($personen as $person) {
		$datumafsluiting = '';
		if (!empty($person['datumafsluiting'])) {
			$datumafsluiting = date('d-m-Y', strtotime($person['datumafsluiting']));
		}
		
		$intake_datum = '';
		if (!empty($person['intake_datum'])) {
			$intake_datum = date('d-m-Y', strtotime($person['intake_datum']));
		}
		
		echo $person['klant_nummer'];
		echo "\t";
		
		echo $person['fase'];
		echo "\t";
		
		echo $intake_datum;
		echo "\t";
		
		echo $datumafsluiting;
		echo "\t";
		
		echo $person['geslacht'];
		echo "\t";
		
		echo clean($person['voornaam']);
		echo "\t";
		
		echo clean($person['tussenvoegsel']);
		echo "\t";
		
		echo clean($person['achternaam']);
		echo "\t";
		
		echo clean($person['geboortedatum']);
		echo "\t";
		
		echo clean($person['email']);
		echo "\t";
		
		echo clean($person['adres']);
		echo "\t";
		
		echo clean($person['postcode']);
		echo "\t";
		
		echo clean($person['plaats']);
		echo "\t";
		
		echo clean($person['mobiel']);
		echo "\t";
		
		echo clean($person['telefoon']);
		echo "\t";
		
		echo clean($person['coordinator']);
		echo "\t";
		
		echo clean($person['werkgebied']);
		echo "\t";
		
		echo clean($person['postcodegebied']);
		echo "\t";
		
		echo clean($person['stagiair']);
		echo "\t";
		
		echo clean($person['gezin_met_kinderen']);
		echo "\t";
		
		echo $person['geen_post'];
		echo "\t";
		
		echo $person['geen_email'];
		echo "\t";
		
		echo $person['projecten'];
		echo "\t";

		echo PHP_EOL;
	}
