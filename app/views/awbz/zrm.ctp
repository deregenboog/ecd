<div class="klanten view">

	<fieldset>
		<legend>ZRM rapporten</legend>
		<?php
		foreach ($zrmReports as $zrmReport) {
			if (isset($zrmReport)) {
				echo $this->element('zrm_view', array('data' => $zrmReport));
			}
		}
		?>
	</fieldset>
	
</div>
	
<div class="actions">
	<?php
		echo $this->element('klantbasic', array('data' => $klant));
		echo $this->Html->link('Terug naar klantoverzicht', array('controller' => 'awbz', 'action' => 'view', $klant['Klant']['id']));

	?>
</div>
