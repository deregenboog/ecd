<?php
	echo $title . PHP_EOL;
	echo "Periode: "
		. $this->Date->show($startDate->format('Y-m-d'))
		. ' - '
		. $this->Date->show($endDate->format('Y-m-d')) . PHP_EOL;
	foreach ($reports as $report) {
		echo PHP_EOL;
		echo $report['title'] . PHP_EOL;
		if (!empty($report['data'])) {
			echo "\t" . $report['xDescription'] . PHP_EOL;
			echo $report['yDescription']. "\t";
			foreach (array_keys(current($report['data'])) as $werkgebied) {
				echo ($werkgebied ?: '---') . "\t";
			}
			echo PHP_EOL;
			foreach ($report['data'] as $werkgebied => $series) {
				echo $werkgebied . "\t";
				foreach ($series as $project) {
					echo $project . "\t";
				}
				echo PHP_EOL;
			}
		} else {
			echo 'Geen data';
		}
	}
?>
