<div class="actions">
	<?= $this->element('klantbasic', array('data' => $klant)); ?>
	<?= $this->element('diensten', array( 'diensten' => $diensten, ))?>

	<div class="print-invisible">
		<?php
			echo $this->element('intakes_summary', array('data' => $klant));
			echo $this->element('opmerkingen_summary', array(
				'klant_id' => $klant['Klant']['id'],
				'opmerkingen' => $opmerkingen,
			));
			echo $this->element('schorsingen_summary', array('data' => $klant));
			echo $this->element('registratie_summary', array('data' => $registraties));
		?>
		<fieldset>
			<legend>Rapportage</legend>
			<p><?php echo $html->link('Bekijk de rapportage van deze client', array('controller' => 'rapportages', 'action' => 'klant', $klant['Klant']['id']))?></p>
		</fieldset>
	</div>
</div>
<div class="klanten view">

		<h2>Laatste intake</h2>
		<?php if (!empty($newestintake)): ?>
			<?php echo $this->element('intake', array('data' => $newestintake)); ?>
		<?php else: ?>
			<p>Geen intakes gevonden. <?php echo $this->Html->link('Voeg een intake toe.', array('controller' => 'intakes', 'action' => 'add', $klant['Klant']['id'])); ?></p>
		<?php endif;

			?>
		
</div>

<?php 
?>
