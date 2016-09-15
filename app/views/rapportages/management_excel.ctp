ECD management rapportages
<?php
	foreach ($reports as $report) {
		if (empty($report['result'])) {
			continue;
		} ?>
<?= $report['head'] ?>

<?php
		if ($report['isArray']) {
			foreach ($report['fields'] as $field) {
				list(, $title) = $field;
				echo "$title\t";
			}
			echo PHP_EOL;

			foreach ($report['result'] as $row) {
				foreach ($report['fields'] as $field) {
					list($path) = $field;
					echo Set::classicExtract($row, $path);
					echo "\t";
				}
				echo PHP_EOL;
			}
			echo PHP_EOL;
		} else {
			foreach ($report['fields'] as $field) {
				list($path, $title) = $field;
				$value = Set::classicExtract($report['result'], $path);
				echo $title."\t".$value.PHP_EOL;
			}
			echo PHP_EOL;
		}
	}
?>
