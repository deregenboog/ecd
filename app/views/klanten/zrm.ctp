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
		echo $this->element('diensten', array( 'diensten' => $diensten, ));

		$url = array('controller' => 'klanten', 'action' => 'view', $klant['Klant']['id']);
		if (isset($referer) && ! empty($referer)) {
			$url = $referer;
		}
		echo $this->Html->link('Terug naar klantoverzicht', $url);

	?>
</div>
