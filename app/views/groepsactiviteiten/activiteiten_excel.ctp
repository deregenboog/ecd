Activiteiten<?php echo PHP_EOL; ?>
<?php

	echo "Datum\t";
	echo "Groep\t";
	echo "Activiteit\t";
	echo "Status\t";
	echo PHP_EOL;

	foreach ($activiteiten as $activiteit) {

		echo $activiteit['Groepsactiviteit']['datum'];
		echo "\t";
		
		echo $groepsactiviteitengroepen_list[$activiteit['Groepsactiviteit']['groepsactiviteiten_groep_id']];
		echo "\t";
		
		echo $activiteit['Groepsactiviteit']['naam'];
		echo "\t";
		
		echo $activiteit[$persoon_groepsactiviteiten]['afmeld_status'];
		echo "\t";
		
		echo PHP_EOL;
	}

?>
