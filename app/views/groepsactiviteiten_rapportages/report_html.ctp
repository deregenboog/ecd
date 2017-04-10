<?php
	$startDate=$date->show($startDate, array('short'=>true));
	$endDate=$date->show($endDate, array('short'=>true));
?>

<h3><?php echo "{$title}: {$startDate} tot {$endDate}"; ?></h3>

<table style='width: 300px;'>
	<thead>
		<tr>
			<th><?= $mainlabel; ?></th>
			<?php
				foreach ($labels as $label) {
					echo '<th><div>'.$label.'</div></th>';
				}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($report as $r) {
				
				echo '<tr>';
				echo '<th>'.$r['title'].'</th>';
				
				foreach ($r['data'] as $k => $value) {
					
					if (in_array($k, $date_fields)) {
						$value=$date->show($value, array('short'=>true));
					}
					
					echo '<td class="numeric">'.$value.'</td>';
				}
				
				echo '</tr>';
			}
		?>
	</tbody>
</table>

<style>
	thead tr {
		height: 7em;
	}

	Xthead th div {
		/* Safari */
		-webkit-transform: rotate(-90deg);

		/* Firefox */
		-moz-transform: rotate(-90deg);

		/* IE */
		-ms-transform: rotate(-90deg);

		/* Opera */
		-o-transform: rotate(-90deg);

		/* Internet Explorer */
		filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);

	}

	table {
		margin-top: 2em;
	}
</style>
