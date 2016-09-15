<div class="intakes view">
<?php
$today = date('Y-m-d');
?>

<div class="fieldset">
	<h1><?php __('Hi5 Evaluatie'); ?></h1>

	<table class="fixedwidth">
		<?php
			echo $format->printEmptyTableLine('Naam intaker');
			
			echo $format->printEmptyTableLine('Datum van evaluatie');
			
			echo $format->printEmptyTableLine('Werkproject');

			echo '<br/><br/>';

			echo $format->printEmptyTableLine('Aantal dagdelen in het rooster (1-9)');
			
			echo $format->printEmptyTableLine('Startdatum traject');

			echo '<br/>';

			echo $format->printEmptyTableLine('Verslag over de periode van');
			
			echo $format->printEmptyTableLine('t/m');

			echo '<br/>';

			echo $format->printEmptyTableLine('Extra aanwezigen bij het gesprek');
		?>
	</table>

	<?php
		$radioOptions = '<nobr>'.__('slecht', true).' ( ) ( ) ( ) ( ) ( ) '.__('goed', true).'</nobr>';

		foreach ($paragraphs as $paragraphDetails) {
			?>
		<fieldset><legend><?=$paragraphDetails['paragraph']?></legend>
		<table id="Hi5EvaluatieParagraph">
			<thead>
				<tr>
					<th></th>
					<th>Volgens Hi5'er</th>
					<th>Volgens werkbegeleider</th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ($paragraphDetails['questions'] as $questionId => $question) {
				//$question;
			?>
				<tr>
					<td><?=$question?></td>
					<td class="hi5_evaluatie_paragraph">
						<span>
							<?php
							echo $radioOptions;
				echo '<div height="3em"></div>'; ?>
						</span>
					</td>
					<td>
							<?php
							echo $radioOptions;
				echo '<div height="3em"></div>'; ?>
					</td>
				</tr>
			<?php

			} ?>
			</tbody>
		</table>
		</fieldset>
		<?php 
		}
	?>
	<div class="fieldset">
		<h2>Opmerkingen</h2>
	<table class="fixedwidth extra_td_height">
		<?php
			echo $format->printEmptyTableLine('Overige opmerkingen (optioneel)');
			echo $format->printEmptyTableLine('Afspraken afgelopen periode');
		?>
		</table>
	</div>
	<div class="fieldset">
		<h2>Afspraken komende periode</h2>
		<table class="fixedwidth extra_td_height">
			<?php
				echo $format->printEmptyTableLine('Wat doe jij?');
				echo $format->printEmptyTableLine('Wat doet de TB\'er ?');
				echo $format->printEmptyTableLine('Wat doet de werkbegeleiding ?');
			?>
		</table>
	</div>

</div>
</div>
