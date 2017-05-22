<fieldset>
	<legend>Opties</legend>
	<?= $this->Form->create() ?>
	<h4>Periode</h4>
	<?= $date->input('Query.from', null, array(
		'label' => 'Van',
		'class' => 'date',
		'rangeLow' => '2010-01-01',
		'rangeHigh' => date('Y-m-d'),
	)) ?>
	<?= $date->input('Query.until', null, array(
		'label' => 'Tot en met',
		'class' => 'date',
		'rangeLow' => '2010-01-01',
		'rangeHigh' => date('Y-m-d'),
	)) ?>
	<?= $this->Form->input('Query.name', array(
		'type' => 'select',
		'options' => $report_select,
	)) ?>
<?= $form->end(array('label' => 'Start')) ?>
</fieldset>
