<?= $this->element('iz_subnavigation') ?>

<div class="actions">
	<?= $this->element('../iz_rapportages/rapport_form', [
		'disableGender' => true,
		'disableLocation' => true,
		'disableDates' => false,
		'rapportageController' => 'iz_rapportages',
		'enableExcel' => true,
	]);?>
</div>

<div class="form">
	<fieldset id="fldRapportages">
		<legend>Rapportages</legend>
		<?php if (isset($reports)): ?>
			<?= $this->element('../iz_rapportages/pivot_tables.html') ?>
		<?php else: ?>
			<div id="divManagementReport">
				<?php __('Selecteer filteropties.') ?>
			</div>
		<?php endif; ?>
	</fieldset>
</div>
