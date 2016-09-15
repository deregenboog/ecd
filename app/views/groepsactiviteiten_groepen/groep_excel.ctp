<?php echo $persoon_model ?> list in group: <?php echo $groep; echo PHP_EOL; ?>
<?php

function convert_date($dt)
{
	if (!empty($dt)) {
		$da = explode("-", $dt);
		if (count($da) == 3) {
			$dt=$da[2]."-".$da[1]."-".$da[0];
		}
	}
	return $dt;
}

	echo "Klantnr\t";
	echo "Voornaam\t";
	echo "Tussenvoegsel\t";
	echo "Achternaam\t";
	echo "Geboortedatum\t";
	echo "E-Mail\t";
	echo "Adres\t";
	echo "Postcode\t";
	echo "Woonplaats\t";
	echo "Telefoon\t";
	echo "Mobiel\t";
	echo "Geen post\t";
	echo "Geen email\t";
	echo "Geslacht\t";
	echo "Werkgebied\t";
	echo "Stardatum (Lid sinds)\t";
	echo "Datum Intake\t";
	echo "Datum Afsluiting\t";
	echo PHP_EOL;

	foreach ($members as $member) {
		
		$intakedatum = "";
		$afsluitdatum = "";
		
		$geboortedatum = $member[$persoon_model]['geboortedatum'] ;
		$startdatum = $member[$model]['startdatum'];
		
		if (!empty($member[$persoon_model]['GroepsactiviteitenIntake']['intakedatum'])) {
			$intakedatum = $member[$persoon_model]['GroepsactiviteitenIntake']['intakedatum'];
		}
		
		if (!empty($member[$persoon_model]['GroepsactiviteitenIntake']['afsluitdatum'])) {
			$intakedatum = $member[$persoon_model]['GroepsactiviteitenIntake']['afsluitdatum'];
		}

		$intakedatum = convert_date($intakedatum);
		$afsluitdatum = convert_date($afsluitdatum);
		$geboortedatum = convert_date($geboortedatum);
		$startdatum = convert_date($startdatum);

		echo $member[$persoon_model]['klant_nummer'];
		echo "\t";
		
		echo $member[$persoon_model]['voornaam'];
		echo "\t";
		
		echo $member[$persoon_model]['tussenvoegsel'];
		echo "\t";
		
		echo $member[$persoon_model]['achternaam'];
		echo "\t";
		
		echo $geboortedatum;
		echo "\t";
		
		echo $member[$persoon_model]['email'];
		echo "\t";
		
		echo $member[$persoon_model]['adres'];
		echo "\t";
		
		echo $member[$persoon_model]['postcode'];
		echo "\t";
		
		echo $member[$persoon_model]['plaats'];
		echo "\t";
		
		echo $member[$persoon_model]['telefoon'];
		echo "\t";
		
		echo $member[$persoon_model]['mobiel'];
		echo "\t";
		echo $member[$persoon_model]['geen_post'];
		echo "\t";
		
		echo $member[$persoon_model]['geen_email'];
		echo "\t";
		
		echo $member[$persoon_model]['geslacht_id'];
		echo "\t";
		
		echo $member[$persoon_model]['werkgebied'];
		echo "\t";
		
		echo $startdatum;
		echo "\t";
		
		echo $intakedatum;
		echo "\t";
		
		echo $afsluitdatum;
		
		echo "\t";
		echo PHP_EOL;
	}
?>
