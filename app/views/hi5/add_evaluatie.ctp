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
				'action' => 'add_evaluatie',
				$klant['Klant']['id'],
		),
));
?>

<fieldset><legend><?php
	__('Nieuwe tussentijdse evaluatie');
	?></legend>
	<?php
	echo $form->hidden('klant_id', array('value' => $klant['Klant']['id']));
	?>
		<?php
		echo $form->input('medewerker_id', array(
				'label' => 'Evaluatie gedaan door',
				'default' => $intaker_id,
		));
		echo $date->input('Hi5Evaluatie.datumevaluatie', $today,
			array(
					'label' => 'Datum van evaluatie',
					'rangeLow' => (date('Y') - 1).date('-m-d'),
					'required' => true,
					'rangeHigh' => $today,
		));
		echo $form->input('werkproject');
		?><br /><br /><?php 
		echo $form->input('aantal_dagdelen', array(
				'label' => 'Aantal dagdelen in het rooster',
				'type'=>'select',
				'options' => $aantal_dagdelens,
		));
		echo $date->input('Hi5Evaluatie.startdatumtraject', $today,
			array(
					'label' => 'Startdatum traject',
					'rangeLow' => (date('Y') - 1).date('-m-d'),
					'required' => true,
					'rangeHigh' => $today,
		));
		?><br />
		<?php 
		echo $date->input('Hi5Evaluatie.verslagvan', null,
			array(
					'label' => 'Verslag over de periode van',
					'rangeLow' => (date('Y') - 1).date('-m-d'),
					'required' => true,
					'rangeHigh' => $today,
		));
		echo $date->input('Hi5Evaluatie.verslagtm', $today,
			array(
					'label' => 't/m',
					'rangeLow' => (date('Y') - 1).date('-m-d'),
					'required' => true,
					'rangeHigh' => $today,
		));
		?>
		<br />
		<?php
		echo $form->input('extraanwezigen',
			array('label' => 'Extra aanwezigen bij het gesprek'));
		?>
	<?php
	$values = $this->Form->value('Hi5EvaluatieQuestion.Hi5EvaluatieQuestion');
	$selected = array();
	if (!empty($values)) {
		foreach ($values as $v) {
			$key = $v['hi5_evaluatie_question_id'];
			$selected[$key] = $v;
		}
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
			//$question;
			?>
				<tr>
					<td><?=$question?></td>
					<td>
						<span>
							<?php
							echo $form->input("Hi5EvaluatieQuestion.$questionId.hi5er_radio",
								array(
									'type'=> 'radio',
									'before' => 'slecht',
									'after' => 'goed',
									'value' => isset($selected[$questionId]) ? $selected[$questionId]['hi5er_radio'] : 3,
									'legend' => false,
									'label' => false,
									'options' => $radioOptions,
								)
							);
			echo $form->input("Hi5EvaluatieQuestion.$questionId.hi5er_details", array(
								'type' => 'textarea',
								'label' => '',
								'value' => isset($selected[$questionId]) ? $selected[$questionId]['hi5er_details'] : '',
							)); ?>
						</span>
					</td>
					<td>
						<?php
						echo $form->input("Hi5EvaluatieQuestion.$questionId.wb_radio",
								array(
									'type'=> 'radio',
									'before' => 'slecht',
									'after' => 'goed',
									'value' => isset($selected[$questionId]) ? $selected[$questionId]['wb_radio'] : 3,
									'legend' => false,
									'label' => false,
									'options' => $radioOptions,
								)
							);
			echo $form->input("Hi5EvaluatieQuestion.$questionId.wb_details", array(
							'type' => 'textarea',
							'value' => isset($selected[$questionId]) ? $selected[$questionId]['wb_details'] : '',
							'label' => '',
						)); ?>
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
	<fieldset><legend>Opmerkingen</legend>
	<?php
	echo $form->input('opmerkingen_overige', array(
		'label' => "Overige opmerkingen (optioneel)",
	));
	echo $form->input('afspraken_afgelopen', array(
		'label' => "Afspraken afgelopen periode",
	));
	?>
	</fieldset>
	<fieldset><legend>Afspraken komende periode</legend>
	<?php
	echo $form->input('watdoejij', array(
		'label' => "Wat doe jij?",
	));
	echo $form->input('watdoetb', array(
		'label' => "Wat doet de TB'er ?",
	));
	echo $form->input('watdoewb', array(
		'label' => "Wat doet de werkbegeleiding ?",
	));
	?>
	</fieldset>
</fieldset>
<?php echo $this->Form->end(__('Evaluatie opslaan', true));?>
</div>
