<?php if (!empty($count)) {
	?>
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
	} ?>
		</p>
		<fieldset>
			<legend>Basisstatistieken</legend>
			<table class="fixedwidth">
				<tr>
					<td>Totaal aantal registraties (bezoeken)</td>
					<td><?php echo $count['totalVisits']; ?></td>
				</tr>
				<tr>
					<td>Aantal schorsingen</td>
					<td><?php echo $count['suspensions'] ?></td>
				</tr>
				<tr>
					<td>Nieuwe klanten met een registratie
			<?php
				echo $html->link('download', array(
					'action' => 'locatie_nieuwe_klanten',
					'date_from' => $date_from,
					'date_until' => $date_until,
					'geslacht_id' => $geslacht_id,
					'locatie_id' => $locatie_id,
				)); ?>
					</td>
					<td><?php echo $count['new_clients'] ?>
					</td>
				</tr>
				<tr>
					<td><?= __('New intakes', true); ?></td>
					<td><?php echo $count['intakes'] ?></td>
				</tr>
				<tr>
					<td><?= __('Unique visitors', true); ?>
					</td>
					<td><?php echo $count['unique_visitors'] ?>
					</td>
				</tr>
				<tr>
					<td><?= __('Unieke bezoekers met 4+ bezoeken in deze periode', true); ?></td>
					<td><?php echo $count['unique_visitors_4_or_more_visits'] ?></td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend>Faciliteiten</legend>
			<table class="fixedwidth">
				<tr>
					<th>Faciliteit</th>
					<th>Keren gebruikt of aan deelgenomen</th>
				</tr>
				<tr>
					<td>Maaltijden</td>
					<td><?php echo $count['meals']; ?></td>
				</tr>
				<tr>
					<td>Kleding</td>
					<td><?php echo $count['clothes']; ?></td>
				</tr>
				<tr>
					<td>Douches</td>
					<td><?php echo $count['shower']; ?></td>
				</tr>
				<tr>
					<td>Activering</td>
					<td><?php echo $count['activation']; ?></td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend>Unieke geregistreerde bezoekers per locatie</legend>
			<br/>
			<table class="fixedwidth">
				<?php 
					$total = 0;
	foreach ($unique_per_location as $locatie) {
		$total += $locatie[0]['cnt']; ?>
				<tr>
					<td><?= $locatie['l']['naam']; ?></td>
					<td><?= $locatie[0]['cnt']; ?></td>
				</tr>
				<?php 
	} ?>
				<tr>
					<td>Totaal</td>
					<td><?= $total; ?></td>
				</tr>
			</table>
		</fieldset>

	</fieldset>
</div>
<?php 
} ?>

<div class="actions">
	<?=$this->element('report_filter');?>
</div>

