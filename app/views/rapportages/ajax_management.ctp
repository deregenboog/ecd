<?php
	foreach ($reports as $report) {
		if (empty($report['result'])) {
			continue;
		} ?>
<h3><?= $report['head'] ?></h3>
<?php
		if ($report['isArray']) {
			if ($report['hasSummary']) {
				echo '<table class="withSummary">';
			} else {
				echo '<table>';
			}
			echo '<tr>';
			foreach ($report['fields'] as $field) {
				list(, $title) = $field;
				echo "<th>$title</th>";
			}
			echo '</tr>';

			foreach ($report['result'] as $row) {
				echo '<tr>';
				foreach ($report['fields'] as $field) {
					list($path) = $field;
					$value = Set::classicExtract($row, $path);
					if (! is_numeric($value)) {
						echo '<td>';
					} else {
						echo '<td class="numeric">';
					}
					echo $value;
					echo '</td>';
				}
				echo '</tr>';
			}
			echo '</table>';
		} else {
			echo '<table>';
			foreach ($report['fields'] as $field) {
				list($path, $title) = $field;
				$value = Set::classicExtract($report['result'], $path);
				echo "<tr><th>$title</th></tr><tr><td>$value</td></tr>";
			}
			echo '</table>';
		}
		// var_dump($report);
		// echo $this->element('sql_dump'); 
	}
?>
