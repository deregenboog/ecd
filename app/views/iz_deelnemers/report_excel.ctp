<?php
echo "{$title}\t{$startDate}\ttot\t{$endDate}";

	echo PHP_EOL;
	echo PHP_EOL;

	echo "{$mainlabel} \t";
	
	foreach ($labels as $label) {
		echo $label."\t";
	}
	
	echo PHP_EOL;

	foreach ($report as $r) {
		
		echo $r['title']."\t";
		
		foreach ($r['data'] as $k => $value) {
			
			if (in_array($k, $date_fields)) {
				
				$m=array();
				
				if (preg_match('/([0-9][0-9][0-9][0-9])-([0-9][0-9])-([0-9][0-9])/', $value, $m)) {
					$value=$m[3]."-".$m[2]."-".$m[1];
				}
			}

			echo $value."\t";
		}
		
		echo PHP_EOL;
	}
	echo PHP_EOL;
	
	echo PHP_EOL;
	
	echo PHP_EOL;
	
	echo PHP_EOL;
	
	echo PHP_EOL;
	

?>
	
