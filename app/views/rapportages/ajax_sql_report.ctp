<?php
	$displayedReport = 0;
	foreach ($reports as $report) {
		if (empty($report['result'])) {
			continue;
		}
		$displayedReport++; ?>
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
				$label = $field['label'];
				echo "<th>$label</th>";
			}
			echo '</tr>';

			foreach ($report['result'] as $row) {
				echo '<tr>';
				foreach ($report['fields'] as $field) {
					$path = $field['name'];
					$value = Set::classicExtract($row, $path);
					if (!empty($field['helper']) && !empty($field['function'])) {
						$helper = $field['helper'];
						$func = $field['function'];
						$value = $this->$helper->$func($value);
					}
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
		//var_dump($report);
	}

	if ($displayedReport == 0) {
		echo '<p>'.__('Rapport heeft geen gegevens', true).'</p>';
	}
?>
