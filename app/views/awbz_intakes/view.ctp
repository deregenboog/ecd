<div class="actions">
	<?php
		echo $this->element('klantbasic', array('data' => $klant));

		echo '<p>';
		
		echo $this->Html->link('AWBZ-indicaties en hoofdaannemers beheren',
			array(
				'controller' => 'awbz',
				'action' => 'view',
				$klant['Klant']['id'],
			)
		);
		
		echo '<br/>'.$indicaties_counter;
		
		echo '</p>';

	?>
</div>
<div class="intakes view">
	<?php
		$intake['Intake'] = & $intake['AwbzIntake'];
		echo $this->element('intake', array('data' => $intake));
	?>
</div>

