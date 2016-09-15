<div class="form">
	<fieldset id="fldManagementRapportages">
		<legend>ECD management rapportages</legend>
		<div id="divAjaxLoading" class="centeredContentWrap" style="display:none;">
			<?= $this->Html->image('ajax-loader.gif') ?>
			<br/>
			<?php __('Uw rapportage wordt aangemaakt. Dit kan 1 minuut duren.') ?>

		</div>
		<div id="divManagementReport">
			<?php __('Selecteer filteropties.') ?>
		</div>
	</fieldset>
</div>

<iframe name="iframeExcel" style="display: none;"></iframe>

<div class="actions">
	<?=$this->element('report_filter', array('disableGender' => true, 'disableLocation' => true, 'enableExcel' => true, 'overrideAction' => 'ajaxManagement'));?>
</div>
<script type="text/javascript">
	$(startManagementReports);
</script>
