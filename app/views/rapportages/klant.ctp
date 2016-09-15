<?php if (!empty($count)) : ?>

<div class="form">
	<fieldset>
		<legend><?php __('Klantrapportage')?></legend>
		<?php if ($daterange): ?>
			<p><?php echo 'Rapportage van '.$daterange['date_from']['day'].'-'.$daterange['date_from']['month'].'-'.$daterange['date_from']['year'].' tot en met '.$daterange['date_to']['day'].'-'.$daterange['date_to']['month'].'-'.$daterange['date_to']['year']?></p>
		<?php else: ?>
			<p>Rapportage van alle opgeslagen gegevens.</p>
		<?php endif;?>
		<fieldset>
			<legend>Basisstatistieken</legend>
			<table class="fixedwidth">
				<tr>
					<td>Bezoeken aan de inloophuizen</td>
					<td><?php echo $count['visits']; ?></td>
				</tr>
				<tr>
					<td>Aantal schorsingen</td>
					<td><?php echo $count['suspension']?></td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend>Faciliteiten van inloophuizen</legend>
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
			<legend>Bezoeken aan inloophuizen</legend>
			<table class="fixedwidth">
				<tr>
					<th>Locatie</th>
					<th>Aantal bezoeken</th>
				</tr>
				<?php foreach ($countLocation as $item): ?>
					<tr>
						<td><?php echo $item['Locatie']['naam']?></td>
						<td><?php echo $item[0]['count']?></td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php if (!empty($lastRegistration[0]['max'])) {
	?>
				<?php __('Laatste bezoek aan inloophuis') ?>:
				<?= $this->Date->show($lastRegistration[0]['max']) ?>
			<?php 
} else {
	?>
				<?php __('Geen bezoek aan inloophuis') ?>:
			<?php 
} ?>

		</fieldset>
	</fieldset>
</div>
<?php endif; ?>

<div class="actions">
	<?php echo $this->element('klantbasic', array('data' => $klant)); ?>
	
	<fieldset>
		<legend>Rapportageperiode</legend>
		<?php echo $form->create('period', array('type' => 'post', 'url' => '/rapportages/klant/'.$klant['Klant']['id'])); ?>
		<p>Vanaf:</p>
		<?php 
			if ($daterange) {
				$defaultdate = $date_from;
			} else {
				$defaultdate = '2010-01-01';
			}

			echo $date->input('date_from', $defaultdate, array(
						'class' => 'date',
						'rangeLow' => '2009-12-31',
						'rangeHigh' => date('Y-m-d'), ));
		?>
		<p>tot en met:</p>
		<?php 
			if ($daterange) {
				$defaultdate = $date_to;
			} else {
				$defaultdate = date('Y-m-d');
			}

			echo $date->input('date_to', $defaultdate, array(
						'class' => 'date',
						'rangeLow' => '2009-12-31',
						'rangeHigh' => date('Y-m-d'), ));
		?>
		
		<?php echo $form->end(array('label' => 'Ga')); ?>
	</fieldset>
</div>
