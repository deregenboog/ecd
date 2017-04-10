<fieldset>
	<legend>Rapportageopties</legend>
		<?= $this->Form->create('options', array(
				'type' => 'post',
				'url' => array(
					'controller' => empty($rapportageController) ? 'Rapportages' : $rapportageController,
					'action' => empty($overrideAction) ? $this->action : $overrideAction,
				),
			)) ?>
		<h4>Periode</h4>
	<?= $date->input('date_from', null, array(
		'label' => 'Van',
		'class' => 'date',
		'rangeLow' => '2010-01-01',
		'rangeHigh' => date('Y-m-d'),
	)) ?>
	<?= $date->input('date_to', null, array(
		'label' => 'Tot en met',
		'class' => 'date',
		'rangeLow' => '2010-01-01',
		'rangeHigh' => date('Y-m-d'),
	)) ?>
	<?= $this->Form->input('report', array(
		'type' => 'select',
		'options' => $report_select,
	)) ?>
	<?php if (!empty($enableExcel)):
	?>
		<div>
			<?= $this->Form->checkbox('excel') ?>
			<?= $this->Form->label(__('Excel Export', true)) ?>
		</div>
	<?php endif; ?>
	<?= $form->end(array('label' => 'Start')) ?>
</fieldset>
