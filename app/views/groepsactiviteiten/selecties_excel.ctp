<?php

	echo "Klantnr\t";
	echo "Voornaam\t";
	echo "Tussenvoegsel\t";
	echo "Achternaam\t";
	echo "Geboortedatum\t";
	echo "E-mail\t";
	echo "Adres\t";
	echo "Postcode\t";
	echo "Woonplaats\t";
	echo "Telefoon\t";
	echo "Mobiel\t";
	echo "Geen post\t";
	echo "Geen email\t";
	echo "Geslacht\t";
	echo "Werkgebied\t";
	echo "Startdatum\t";
	echo "Datum intake\t";
	echo "Datum afsluiting\t";
	echo "Gezin met kinderen";
	echo PHP_EOL;

	foreach ($personen as $person) {
		
		$startdatum = "" ;
		if (! empty($person['startdatum'])) {
			$startdatum = date('d-m-Y', strtotime($person['startdatum']));
		}
		
		$intakedatum = "" ;
		if (! empty($person['intakedatum'])) {
			$intakedatum = date('d-m-Y', strtotime($person['intakedatum']));
		}
		
		$afsluitdatum = "" ;
		if (! empty($person['afsluitdatum'])) {
			$afsluitdatum = date('d-m-Y', strtotime($person['afsluitdatum']));
		}
		
		echo $person['klant_nummer'];
		echo "\t";
		
		echo $person['voornaam'];
		echo "\t";
		
		echo $person['tussenvoegsel'];
		echo "\t";
		
		echo $person['achternaam'];
		echo "\t";
		
		echo $person['geboortedatum'];
		echo "\t";
		
		echo $person['email'];
		echo "\t";
		
		echo preg_replace('/\t/', ' ', $person['adres']);
		echo "\t";
		
		echo $person['postcode'];
		echo "\t";
		
		echo $person['plaats'];
		echo "\t";
		
		echo $person['telefoon'];
		echo "\t";

		echo $person['mobiel'];
		echo "\t";
		
		if (!empty($person['geen_post'])) {
			echo "ja";
		} else {
			echo "nee";
		}
		echo "\t";
		
		if (!empty($person['geen_email'])) {
			echo "ja";
		} else {
			echo "nee";
		}
		echo "\t";
		
		echo $person['geslacht'];
		echo "\t";
		
		echo $person['werkgebied'];
		echo "\t";
		
		echo $startdatum;
		echo "\t";
		
		echo $intakedatum;
		echo "\t";
		
		echo $afsluitdatum;
		echo "\t";
		
		echo $person['gezin_met_kinderen'];
		echo "\t";

		echo PHP_EOL;
	}
