<?php if (!empty($count)) : ?>
<div class="form">
	<fieldset>
		<legend>Infobalierapportage</legend>
		<p>
			<?php
			echo 'Rapportage van ';
			if ($this->data) {
				if ($this->data['options']['location'] == 0) {
					echo 'alle locaties';
				} else {
					echo $locations[$this->data['options']['location']];
				}

				echo ' met de gegevens van '.$this->Time->format('d-m-Y', $date_from).' tot en met '.$this->Time->format('d-m-Y', $date_to).',<br/> vergeleken met gegevens van '.$this->Time->format('d-m-Y', $ref_from).' tot en met '.$this->Time->format('d-m-Y', $ref_to).'.';
			} else {
				echo 'alle locaties met alle opgeslagen gegevens';
			}

		?>

		</p>
		<fieldset>
			<legend>Basisstatistieken</legend>
			<table class="fixedwidth">
				<tr>
					<td>Klanten uit:</td>
					<td colspan='2'><?php echo implode($count['amoc_landen'], ' '); ?></td>
				</tr>
				<!--
				<tr>
					<td>Totaal klanten</td>
					<td><?php echo $count['totalClients']; ?></td>
				</tr>
				-->
			   <tr>
					<td>Nieuwe klanten in die periode</td>
					<td><?php echo $count['totalNewClients']; ?></td>
					<td><?php echo $ref['totalNewClients']; ?></td>
				</tr>
				<tr>
					<td>Totaal unique bezoekers</td>
					<td><?php echo $count['uniqueVisits'] ?></td>
					<td><?php echo $ref['uniqueVisits'] ?></td>
				</tr>
				<tr>
					<td>Aantal bezoeken aan een inloophuis</td>
					<td><?php echo $count['totalVisits'] ?></td>
					<td><?php echo $ref['totalVisits'] ?></td>
				</tr>
				<tr>
					<td>Aantal verslagen bij Maatschappelijk Werk</td>
					<td><?php echo $count['totalVerslagen'] ?></td>
					<td><?php echo $ref['totalVerslagen'] ?></td>
				</tr>
				<tr>
					<td>Aantal doorverwijzingen</td>
					<td><?php echo $count['doorverwijzers_count'] ?></td>
					<td><?php echo $ref['doorverwijzers_count'] ?></td>
				</tr>
				<tr>
					<td>Gemiddelde leeftijd</td>
					<td><?php echo $count['averageAge'] ?></td>
					<td><?php echo $ref['averageAge'] ?></td>
				</tr>
 
			</table>
		</fieldset>

		<fieldset>
			<legend>Aantal ingeschreven personen per land</legend>
			<table class="fixedwidth">
				<tr>
					<th>Land</th>
					<th>Aantal personen</th>
					<th>Ref.</th>
				</tr>

				<?php foreach ($count['amoc_landen'] as $cid => $name) {
			?>
				<tr>
					<td><?php echo $name ?></td>
					<td><?php echo (isset($count['clientsPerCountry'][$cid])) ?
						   $count['clientsPerCountry'][$cid] : '--'; ?></td>
					<td><?php echo (isset($ref['clientsPerCountry'][$cid])) ?
						   $ref['clientsPerCountry'][$cid] : '--'; ?></td>
				</tr>
				<?php 
		} ?>
			</table>
		</fieldset>

		<fieldset>
			<legend>Leeftijd</legend>
			<table class="fixedwidth">
				<tr>
					<th>Leeftijd</th>
					<th>Aantal personen</th>
					<th>Ref.</th>
				</tr>

				<?php foreach ($count['ages'] as $age => $cnt) {
			?>
				<tr>
					<td><?php echo $age ?></td>
					<td><?php echo $cnt ?></td>
					<td><?php echo (isset($ref['ages'][$age])) ? $ref['ages'][$age] : '--' ?></td>
				</tr>
				<?php 
		} ?>
			</table>
		</fieldset>

		<fieldset>
			<legend>Primaireproblematiek</legend>
			<table class="fixedwidth">
				<tr>
					<th>Problematiek</th>
					<th>Aantal personen</th>
					<th>Ref.</th>
				</tr>

				<?php foreach ($count['primaireproblematiek'] as $pid => $name) {
			?>
				<tr>
					<td><?php echo $name ?></td>
					<td><?php echo (isset($count['primaryProblems'][$pid])) ?
						   $count['primaryProblems'][$pid] : '--'; ?></td>
					<td><?php echo (isset($ref['primaryProblems'][$pid])) ?
						   $ref['primaryProblems'][$pid] : '--'; ?></td>
				</tr>
				<?php 
		} ?>
			</table>
		</fieldset>

		<fieldset>
			<legend>Doorverwijsrichtingen</legend>
			<table class="fixedwidth">
				<tr>
					<th>Doorverwijsrichting</th>
					<th>Aantal personen</th>
					<th>Ref.</th>
				</tr>

				<?php foreach ($count['doorverwijzers'] as $pid => $name) {
			?>
				<tr>
					<td><?php echo $name ?></td>
					<td><?php echo (isset($count['count_per_doorverwijzers'][$pid])) ?
						   $count['count_per_doorverwijzers'][$pid] : '--'; ?></td>
					<td><?php echo (isset($ref['count_per_doorverwijzers'][$pid])) ?
						   $ref['count_per_doorverwijzers'][$pid] : '--'; ?></td>
				</tr>
				<?php 
		} ?>
			</table>
		</fieldset>



	</fieldset>
<?php
?>
</div>
<?php endif; ?>

<div class="actions">
	<?=$this->element('report_filter', array('countries' => $landen,
	'references' => $references, ));?>
</div>

