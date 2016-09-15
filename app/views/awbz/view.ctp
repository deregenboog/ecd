<div class="actions">
	<?= $this->element('klantbasic', array('data' => $klant)); ?>
	<div class="print-invisible">
		<?php
			echo $this->element('intakes_summary', array(
				'data' => $klant, )
			);
			$klant_id = $klant['Klant']['id'];
		?>
	</div>
</div>

<div class="view awbz">
	<fieldset>
		<legend>AWBZ-indicaties</legend>
		<div id="awbz_indicaties">
			<?= $this->element('ajax_awbz_indicaties');?>
		</div>
	</fieldset>
</div>
