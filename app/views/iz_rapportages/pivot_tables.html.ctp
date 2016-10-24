<h2><?= $title ?></h2>
<strong>Periode:
	<?= $this->Date->show($startDate->format('Y-m-d')) ?>
	-
	<?= $this->Date->show($endDate->format('Y-m-d')) ?>
</strong>
<br><br>
<?php foreach ($reports as $report): ?>
	<?php echo $this->element(
		'../iz_rapportages/pivot_table.html',
		array(
			'title' => $report['title'],
			'xDescription' => $report['xDescription'],
			'yDescription' => $report['yDescription'],
			'startDate' => isset($report['startDate']) ? $report['startDate'] : null,
			'endDate' => isset($report['endDate']) ? $report['endDate'] : null,
			'data' => $report['data'],
		)
	); ?>
<?php endforeach; ?>
