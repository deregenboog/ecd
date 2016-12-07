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
		array('report' => $report)
	); ?>
<?php endforeach; ?>
