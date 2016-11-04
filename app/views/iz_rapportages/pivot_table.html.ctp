<?php if (!empty($report['data'])): ?>
	<table>
		<thead>
			<tr>
				<th><h3><?= $report['title'] ?></h3></th>
				<th colspan="<?= count(current($report['data'])) ?>">
					<small><?= $report['xDescription'] ?></small>
				</th>
			</tr>
			<tr>
				<th><small><?= $report['yDescription'] ?></small></th>
				<?php foreach (array_keys(current($report['data'])) as $y): ?>
					<th><div><?= $y ?></div></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($report['data'] as $y => $series): ?>
				<tr>
					<td>
						<?php if (isset($report['yLookupCollection']) && isset($report['yLookupCollection'][$y])): ?>
							<?= $report['yLookupCollection'][$y] ?>
						<?php else: ?>
							<?= $y ?>
						<?php endif; ?>
					</td>
					<?php foreach ($series as $x): ?>
						<td><?= $x ?></td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<table>
		<thead>
			<tr><th><h3><?= $report['title'] ?></h3></th></tr>
			<tr><th>Geen data</th></tr>
		</thead>
	</table>
<?php endif; ?>