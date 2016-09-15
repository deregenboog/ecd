<?php
	foreach ($reports as $report) {
		if (empty($report['result'])) {
			continue;
		} ?>
<?= $report['head']."\t".date("Y-m-d H:i:s") ?>

<?php
		if ($report['isArray']) {
			foreach ($report['fields'] as $field) {
				$title = $field['label'];
				echo "$title\t";
			}
			echo PHP_EOL;

			foreach ($report['result'] as $row) {
				foreach ($report['fields'] as $field) {
					$path = $field['name'];
					echo Set::classicExtract($row, $path);
					echo "\t";
				}
				echo PHP_EOL;
			}
			echo PHP_EOL;
		} else {
			foreach ($report['fields'] as $field) {
				$path = $field['name'];
				$title = $field['label'];
				$value = Set::classicExtract($report['result'], $path);
				echo $title."\t".$value.PHP_EOL;
			}
			echo PHP_EOL;
		}
	}
?>
