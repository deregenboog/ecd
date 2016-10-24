<?= $this->element('iz_subnavigation') ?>
<?php
	$year = date('Y');
	$yearOptions = array();
	for ($c = $year - 5 ; $c <= $year +1 ; $c++) {
		$yearOptions[$c] = $c;
	}
?>
<div class="form">
	<fieldset id="fldRapportages">
		<legend>Groepsactiviteiten rapportages: <?= $title; ?></legend>
		<div id="divAjaxLoading" class="centeredContentWrap" style="display:none;">
			<?= $this->Html->image('ajax-loader.gif') ?>
			<br/>
			<?php __('Uw rapportage wordt aangemaakt.') ?>

		</div>
		<div id="divManagementReport">
			<?php __('Selecteer filteropties.') ?>
		</div>
	</fieldset>
</div>

<iframe name="iframeExcel" style="display: none;"></iframe>

<div class="actions">
	<?= $this->element('../iz_deelnemers/rapport_form', array(
		'disableGender' => true,
		'disableLocation' => true,
		'disableDates' => false,
		'rapportageController' => 'iz_rapportages',
		'overrideAction' => $report_generator,
		'enableExcel' => true,
	));?>
</div>
<script type="text/javascript">
	$(startManagementReports);
</script>
