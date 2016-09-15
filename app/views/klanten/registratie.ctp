<div class="klanten view">

	<fieldset>
		<legend>Alle registraties</legend>
		<?php if (!empty($registraties)): ?>
			<table>
				<tr>
					<th>Datum</th>
					<th>Locatie</th>
				</tr>
				<?php foreach ($registraties as $registratie): ?>
					<tr>
						<td><?php echo $date->show($registratie['Registratie']['binnen']); ?></td>
						<td><?php echo $registratie['Locatie']['naam']; ?></td>
					</th>
				<?php endforeach; ?>
			</table>
		<?php else: ?>
			<p>Geen registraties gevonden.</p>
		<?php endif; ?>
	</fieldset>
	
</div>
	
<div class="actions">
	<?php
		echo $this->element('klantbasic', array('data' => $klant));
		echo $this->element('diensten', array( 'diensten' => $diensten, ));
		echo $this->element('intakes_summary', array('data' => $klant));
		echo $this->element('schorsingen_summary', array('data' => $klant));
		echo $this->element('registratie_summary', array('data' => array_reverse(array_slice($registraties, -3))));
	?>
</div>
