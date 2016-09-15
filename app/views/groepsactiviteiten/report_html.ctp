<?php 

foreach ($reports as $report) {

?>

<h3>
<?= $report['head'] ?></h3>
<?php
	if ($report['hasSummary']) {
		echo '<table class="withSummary">';
	} else {
		echo '<table>';
	}
	
	echo '<tr>';
	foreach ($report['fields'] as $key => $field) {
		echo "<th>{$field}</th>";
	}
	echo '</tr>';

	foreach ($report['result'] as $row) {
		
		echo '<tr>';
		
		foreach ($report['fields'] as $key => $field) {
			
			if (! is_numeric($row[$key])) {
				echo '<td>';
			} else {
				echo '<td class="numeric">';
			}
			echo $row[$key];
			echo '</td>';
			
		}
		
		echo '</tr>';
	}
	
	echo '</table>';
	echo "<br/>";  
} 

?>
