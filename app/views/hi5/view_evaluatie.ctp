<?php
	/* @var $format FormatHelper */
?>
<div class="actions">
<?php

	echo $this->element('klantbasic', array(
			'data' => $klant,
	));
	
	echo $this->element('diensten', array( 'diensten' => $diensten, ));

	echo $this->element('hi5_traject', array(
			'data' => $klant,
	));
	
	echo $this->element('hi5_intake', array(
			'viewElementOptions' => $viewElementOptions,
			'data' => $klant,
	));
	
	echo $this->element('hi5_evaluatie', array(
			'viewElementOptions' => $viewElementOptions,
			'data' => $klant,
	));
	
	echo $this->element('hi5_contactjournal', array(
		'viewElementOptions' => $viewElementOptions,
		'klant_id' => $klant['Klant']['id'],
		'countContactjournalTB' => $countContactjournalTB,
		'countContactjournalWB' => $countContactjournalWB,
	));
?>
</div>
<div class="intakes view">

<?php
	$today = date('Y-m-d');
	echo $this->Form->create('Hi5Evaluatie', array(
		'url' => array(
				'controller' => $this->name,
				'action' => 'edit_evaluatie',
		),
	));
?>

<?php
	echo '<div class="editWrench">';
	$printer_img = $this->Html->image('printer.png');
	echo '<a href="#" onclick="window.print()">'.$printer_img.'</a>';
	echo '</div>';
?>

<div class="fieldset">
	<h1><?php __('Nieuwe tussentijdse evaluatie'); ?></h1>
	<table class="fixedwidth">
<?php
	echo $format->printTableLine(
		'Naam intaker',
		$evaluatie['Medewerker']['name']);

	echo $format->printTableLine(
		'Datum van evaluatie',
		$evaluatie['Hi5Evaluatie']['datumevaluatie'],
		FormatHelper::DATE);

	echo $format->printTableLine(
		'Werkproject',
		$evaluatie['Hi5Evaluatie']['werkproject']);

?>

		<br /><br/>

<?php

	echo $format->printTableLine(
		'Aantal dagdelen in het rooster',
		$evaluatie['Hi5Evaluatie']['aantal_dagdelen'].' dagdelen');

	echo $format->printTableLine(
		'Startdatum traject',
		$evaluatie['Hi5Evaluatie']['startdatumtraject'],
		FormatHelper::DATE);

?>
		<br/>

<?php
	echo $format->printTableLine(
		'Verslag over de periode van',
		$evaluatie['Hi5Evaluatie']['verslagvan'],
		FormatHelper::DATE);
	echo $format->printTableLine(
		't/m',
		$evaluatie['Hi5Evaluatie']['verslagtm'],
		FormatHelper::DATE);
?>
		<br />

<?php
	echo $format->printTableLine(
		'Extra aanwezigen bij het gesprek',
		$evaluatie['Hi5Evaluatie']['extraanwezigen']);
?>
	</table>

<?php

	$values = array();

	foreach ($evaluatie['Hi5EvaluatieQuestion'] as $question) {
		$values[$question['id']] = $question['Hi5EvaluatiesHi5EvaluatieQuestion'];
	}

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
	$radioOptions = array(
		1 => '',
		2 => '',
		3 => '',
		4 => '',
		5 => '',
	);
	foreach ($paragraphDetails['questions'] as $questionId => $question) {
		$answer = $values[$questionId]; 
?>

				<tr>
					<td><?=$question?></td>
					<td>
						<span>
							<div class="category stars-<?= $answer['hi5er_radio'] ?>"><span>
								<?= str_repeat('*', $answer['hi5er_radio']); ?>
							</span></div>
							<?= $answer['hi5er_details'] ?>
						</span>
					</td>
					<td>
						<div class="category stars-<?= $answer['wb_radio'] ?>"><span>
								<?= str_repeat('*', $answer['wb_radio']); ?>
							</span></div>
						<?= $answer['wb_details'] ?>
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
	<fieldset>
		<legend>Opmerkingen</legend>
	<table class="fixedwidth">
<?php
	echo $format->printTableLine(
		'Overige opmerkingen (optioneel)',
		$evaluatie['Hi5Evaluatie']['opmerkingen_overige']);
	echo $format->printTableLine(
		'Afspraken afgelopen periode',
		$evaluatie['Hi5Evaluatie']['afspraken_afgelopen']);
?>
		</table>
	</fieldset>
	<fieldset>
		<legend>Afspraken komende periode</legend>
	<table class="fixedwidth">
<?php
	echo $format->printTableLine(
		'Wat doe jij?',
		$evaluatie['Hi5Evaluatie']['watdoejij']);
	echo $format->printTableLine(
		'Wat doet de TB\'er ?',
		$evaluatie['Hi5Evaluatie']['watdoetb']);
	echo $format->printTableLine(
		'Wat doet de werkbegeleiding ?',
		$evaluatie['Hi5Evaluatie']['watdoewb']);

?>
		</table>
	</fieldset>
</div>
</div>
