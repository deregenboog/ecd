<h3><?= $title ?></h3>
<?php if (!empty($data)): ?>
	<table style='width: 300px;'>
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th colspan="<?= count(current($data)) ?>">
					<small><?= $xDescription ?></small>
				</th>
			</tr>
			<tr>
				<th><small><?= $yDescription ?></small></th>
				<?php foreach (array_keys(current($data)) as $werkgebied): ?>
					<th><div><?php echo $werkgebied ?: '---'; ?></div></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($data as $werkgebied => $series): ?>
				<tr>
					<td><?php echo $werkgebied; ?></td>
					<?php foreach ($series as $project): ?>
						<td class="numeric"><?php echo $project; ?></td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<p><strong>Geen data</strong></p>
<?php endif; ?>