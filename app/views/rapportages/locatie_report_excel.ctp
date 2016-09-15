<?php
echo "{$title}\t{$date_from}\ttot\t{$date_until}";

	echo PHP_EOL;
	echo PHP_EOL;

	echo PHP_EOL;

	foreach ($select as $s) {
		echo 'K'.$s['k']['klant_id']."\t";
		echo $s['k']['voornaam']."\t";
		echo $s['k']['achternaam']."\t";
		echo PHP_EOL;
	}
	echo PHP_EOL;
	echo PHP_EOL;
	echo PHP_EOL;
	echo PHP_EOL;
	echo PHP_EOL;

?>
	
