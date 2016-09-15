<div class="actions">

	<?= $this->element('klantbasic', array('data' => $klant)); ?>
	
	<?= $this->element('diensten', array( 'diensten' => $diensten, )); ?>
	
	<?= $this->element('hi5_traject', array('data' => $klant)); ?>
	
	<?= $this->element('hi5_intake', array('viewElementOptions' => $viewElementOptions, 'data' => $klant)); ?>
	
	<?= $this->element('hi5_evaluatie', array('viewElementOptions' => $viewElementOptions, 'data' => $klant)); ?>
	
<?php
	echo $this->element('hi5_contactjournal', array(
		'viewElementOptions' => $viewElementOptions,
		'countContactjournalTB' => $countContactjournalTB,
		'klant_id' => $klant['Klant']['id'],
		'countContactjournalWB' => $countContactjournalWB,
	));
?>

</div>
<div class="intakes view">
<?php
	$today = date('Y-m-d');
	
	echo $this->Form->create('Hi5Intake',array(
		'url' => array('controller'=>$this->name, 'action' => 'edit_intake') 		
	));
?>

<fieldset>
		<legend><?php __('HI5 Intake wijzigen'); ?></legend>
		<?php echo $form->hidden('klant_id', array('value' => $klant['Klant']['id'])); ?>
		<fieldset>
			<legend>Algemeen</legend>
			<?php
				echo $this->Form->input('medewerker_id', array(
					'label' => 'Naam intaker',
					'default' => $intaker_id,
				));
				echo $date->input('Hi5Intake.datum_intake', $today, array(
					'label' => 'Datum van intake',
					'rangeLow' => (date('Y') - 1).date('-m-d'),
					'required' => true,
					'rangeHigh' => $today, )
				);
			?>
		</fieldset>
		<fieldset>
			<legend>Adresgegevens</legend>
			<?php 
				echo $this->Form->input('postadres', array('label' => 'Adres'));
				
				echo $this->Form->input('postcode');
				
				echo $this->Form->input('woonplaats');
				
				echo $date->input('Hi5Intake.verblijf_in_NL_sinds', null, array(
					'label' => 'Verblijft in Nederland sinds',
					'rangeLow' => (date('Y') - 100).date('-m-d'),
					'rangeHigh' => $today,
				));
				
				echo $date->input('Hi5Intake.verblijf_in_amsterdam_sinds', null, array(
					'label' => 'Verblijft in Amsterdam sinds',
					'rangeLow' => (date('Y') - 100).date('-m-d'),
					'required' => true,
					'rangeHigh' => $today,
				));
				
				echo $this->Form->input('verblijfstatus_id', array('empty' => ''));

			?>
		</fieldset>
		<fieldset>
			<legend>Locatiekeuze</legend>
			<?php
				echo $this->Form->input('locatie1_id', array(
					'label' => 'Eerste locatiekeuze',
					'empty' => '', ));
				echo $this->Form->input('locatie2_id', array(
					'label' => 'Tweede locatiekeuze',
					'empty' => '', ));
				echo $this->Form->input('locatie3_id', array(
					'label' => 'Derde locatiekeuze',
					'empty' => '', ));
				echo $this->Form->input('werklocatie_id', array(
					'label' => 'Werklocatie',
					'empty' => '', ));
				echo $this->Form->input('mag_gebruiken', array(
					'label' => __('mag_gebruiken', true), ));
			?>
		</fieldset>
		<fieldset>
			<legend>Legitimatie</legend>
			<?php
				echo $this->Form->input('legitimatie_id', array('empty' => ''));
				echo $this->Form->input('legitimatie_nummer', array('label' => 'Legitimatienummer'));
				echo $date->input('Hi5Intake.legitimatie_geldig_tot', null, array(
					'label' => 'Legitimatie geldig tot',
					'rangeLow' => (date('Y') - 10).'-01-01',
					'rangeHigh' => (date('Y') + 10).'-01-01',
					'selected' => '--', ));
			?>
		</fieldset>
		
		<fieldset>
			<legend>Verslaving</legend>
			<h3>Primaire problematiek</h3>
			<?php
				echo $this->Form->input('primaireproblematiek_id', array(
					'options'=> $primary_problems, 'empty' => '', 'label' => 'Primaire problematiek', ));
				echo $this->Form->input('primaireproblematieksfrequentie_id', array(
					'options' => $verslavingsfrequenties,
					'empty' => '',
					'label' => 'Verslavingsfrequentie',
				));
				echo $this->Form->input('primaireproblematieksperiode_id', array(
					'options' => $verslavingsperiodes,
					'empty' => '',
					'label' => 'Verslavingsperiode',
				));
				echo $this->Form->input('Primaireproblematieksgebruikswijze', array(
					'type'=>'select',
					'multiple'=>'checkbox',
					'label' => 'Hoe gebruikt client?', ));
			?>
			<h3>Secundaire problematiek</h3>
			<?php
				echo $this->Form->input('Verslaving', array(
					'type'=>'select',
					'multiple'=>'checkbox',
					'options'=> $verslavingen,
					'label'=>'Verslavingen', ));
				echo $this->Form->input('verslaving_overig', array('label' => __('verslaving_overig', true)));
				echo $this->Form->input('verslavingsfrequentie_id', array('empty' => ''));
				echo $this->Form->input('verslavingsperiode_id', array('empty' => ''));
				echo $this->Form->input('Verslavingsgebruikswijze', array(
					'type'=>'select',
					'multiple'=>'checkbox',
					'options'=> $verslavingsgebruikswijzen,
					'label'=>__('verslavingsgebruikwijze', true), ));
			?>
			<h3>Algemene problematiek</h3>
			<?php 
			echo $date->input('Hi5Intake.eerste_gebruik', null, array(
				'label' => 'Wat is de datum van het eerste gebruik?',
				'rangeLow' => (date('Y') - 50).date('-m-d'),
				'rangeHigh' => $today,
			));
		?>
		</fieldset>
		
		<fieldset>
			<legend>Inkomen en woonsituatie</legend>
			<?php
				echo $this->Form->input('Inkomen', array(
					'type'=>'select',
					'multiple'=>'checkbox',
					'required' => true,
					'options'=> $inkomens,
					'label'=>'Inkomen', ));
				echo $this->Form->input('inkomen_overig', array('label' => __('inkomen_overig', true)));
				echo $this->Form->input('woonsituatie_id', array('label' => 'Wat is de woonsituatie?', 'empty' => ''));
			?>
		</fieldset>
		<fieldset>
			<legend>Wensen en verwachtingen</legend>
			<div style="display:table-cell">
				Waar liggen uw interesses?
<?php

	echo '<div>';
	
	echo $this->Form->input('Bedrijfsector1', array(
		'type'=>'select',
		'required' => false,
		'label' =>'Bedrijfssector',
		'options' => $bedrijfsectors,
		'empty' => '',
	));
	
	echo '</div>';
	
	echo '<div>';
	
	echo $this->Form->input('Bedrijfsector2', array(
		'type'=>'select',
		'required' => false,
		'label' =>'Bedrijfssector2',
		'options' => $bedrijfsectors,
		'empty' => '', 'default' => '',
	));
	
	echo '</div>';
?>
			</div>
			<div style="display:table-cell">
				Wat zou u graag willen doen?

				<div style="display:none" id="BedrijfItems1">
<?php

	echo $this->Form->hidden('bedrijfitem_1_id');

	echo $this->Form->input('bedrijfitem_1_id', array(
		'type'=>'select',
		'required' => false,
		'label' =>'Project',
		'empty' => '',
		'default' => '',
	));
?>
				</div>
				<div style="display:none" id="BedrijfItems2">
<?php
	echo $this->Form->hidden('bedrijfitem_2_id');

	echo $this->Form->input('bedrijfitem_2_id', array(
		'type'=>'select',
		'required' => false,
		'label' =>'Project',
		'empty' => '',
		'default' => '',
	));
?>
				</div>
				<div style="display:none;" id="bedrijfItemLists">
<?php 
	foreach ($bedrijfItems as $key => $bedrijfItemList) {
		echo $this->Form->input('BedrijfItems'.$key, array(
			'type'=>'select',
			'label' => $key,
			'options' => $bedrijfItemList,
			'empty' => '',
			'default' => '',
		));
	}
?>
				</div>
			</div>
		</fieldset>
		<fieldset id="survey">
<?php 
	$question_cnt = 0;

	$values = $this->Form->value('Hi5Answer.Hi5Answer');
	
	$selected = array();
	if (!empty($values)) {
		foreach ($values as $v) {
			$key = $v['hi5_answer_id'];
			$selected [ $key ] = true;
			if (isset($v['hi5_answer_text'])) {
				$selected [ $key ] = $v['hi5_answer_text'];
			}
		}
	}

	$lastCategory = false;
	foreach ($hi5Questions as $questionDetails) {

		if ($questionDetails['category'] != $lastCategory) {
			printf('<h2>%s</h2>', $questionDetails['category']);
			$lastCategory = $questionDetails['category'];
		}
		
		echo $questionDetails['question']."<br/>";
		
		if (isset($questionDetails['answers'])) {
			
			foreach ($questionDetails['answers'] as $questionType => $questionTypeDetails) {

				switch ($questionType) {
					case 'checkbox':
						foreach ($questionTypeDetails as $ans_id => $answer) {
							echo $this->Form->input('Hi5Answer.'.$ans_id, array(
							   'type' => 'checkbox',
							   'label' => $answer,
							   'hiddenField' => false,
							   'checked'=> (isset($selected[$ans_id])?'checked':false),
						   ));
						}
						break;
					case 'dropdown':
						$ans_keys = array_keys($questionTypeDetails);

						$ans_id = $ans_keys[0];
						$selected_dd = 0;
						$defaultSelected = array_values(array_intersect(array_keys($selected), $ans_keys));
						echo $this->Form->input('Hi5Answer_aux.'.$ans_id, array(
							'type' => 'select',
							'multiple' => false,
							'options' => $questionTypeDetails,
							'label' => false,
							'selected' =>  (isset($defaultSelected)?$defaultSelected:$ans_id),
						));
?>
		<input type="hidden" id="<?='Hi5Answer_hidden_'.$ans_id?>" />
<?php 
							break;
						case 'open':
							$dummy = array_keys($questionTypeDetails);
							$ans_id = $dummy[0];
							echo $this->Form->input('Hi5Answer.'.$ans_id.'.hi5_answer_text', array(
								'label' => false,
								'value' => isset($selected[$ans_id]) ? $selected[$ans_id] : '',
								'type' => 'textarea',
							));
							break;

					}
					$question_cnt ++;
				}
			}
		}
		?>
		</fieldset>
		
		<fieldset id="zrm">
			<legend>Zelfredzaamheid matrix</legend>
			<p>
				Vul onderstaande matrix in
			</p>
			
			<?php 
				echo $this->element('zrm', array(
					'model' => 'Hi5Intake',
					'zrm_data' => $zrm_data,
				));
			?>
	
		</fieldset>
</fieldset>
<script>
instantiateHi5Intake();
</script>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
