<?php

	foreach ($reports as $report) {
		
		echo $report['head'];
		
		echo PHP_EOL;
		echo PHP_EOL;
		
		foreach ($report['fields'] as $key => $field) {
			echo "$field\t";
		}
		
		echo PHP_EOL;

		foreach ($report['result'] as $key => $row) {
			
			foreach ($report['fields'] as $key => $field) {
				echo "{$row[$key]}\t";
			}
			echo PHP_EOL;
			
		}
		
		echo PHP_EOL;
		echo PHP_EOL;
		echo PHP_EOL;
	}
