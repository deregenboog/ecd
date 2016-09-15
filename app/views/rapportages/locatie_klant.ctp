<div class="form">
	<fieldset>
		<legend>Locatierapportage</legend>
		<p>
			<?php
			echo 'Rapportage van ';
			if ($this->data) {
				if ($this->data['options']['location'] == 0) {
					echo 'alle locaties';
				} else {
					echo $locations[$this->data['options']['location']];
				}
				echo ' met de gegevens van '.$this->data['date_from']['day'].'-'.$this->data['date_from']['month'].'-'.$this->data['date_from']['year'].' tot en met '.$this->data['date_to']['day'].'-'.$this->data['date_to']['month'].'-'.$this->data['date_to']['year'];
			} else {
				echo 'alle locaties met alle opgeslagen gegevens';
			}?>
		</p>
		<fieldset>
			<legend>Klanten</legend>
			<table>
				<tr>
					<th>#</th>
					<th>Naam (roepnaam)</th>
					<th>Totaal registraties</th>
				</tr>
				<?php foreach ($registratie_counts as $klant_id => &$klant) {
				?>
				<tr>
					<td><?= $klant_id ?></td>
					<td>
						<?php
							echo $klant[0]['name'].' ';
				if (!empty($klant['Klant']['roepnaam'])) {
					echo '('.$klant['Klant']['roepnaam'].')';
				} ?>
					</td>
					<td><?= $klant[0]['cnt']?></td>
				</tr>
				<?php 
			} ?>
			</table>
		</fieldset>
	</fieldset>
</div>

<div class="actions">
	<?= $this->element('report_filter');?>
</div>
