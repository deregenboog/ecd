<br />
<fieldset>

<h2>Intakes</h2>

<?php

	echo $this->Form->create(
		'Groepsactiviteit',
		array(
			'url' => array( $persoon_model, $persoon[$persoon_model]['id']),
		)
	);

	$id = !empty($persoon['GroepsactiviteitenIntake']['id']) ? $persoon['GroepsactiviteitenIntake']['id'] : '';
	echo $this->Form->hidden('GroepsactiviteitenIntake.id', array('value' => $id));

	echo $date->input("GroepsactiviteitenIntake.intakedatum", ! empty($persoon['GroepsactiviteitenIntake']['intakedatum']) ? $persoon['GroepsactiviteitenIntake']['intakedatum'] : date('Y-m-d'), array(
			'label' => 'Intakedatum',
			'rangeHigh' => (date('Y') + 10).date('-m-d'),
			'rangeLow' => (date('Y') - 19).date('-m-d'),
			));
	
	$checked="";
	if (! empty($persoon['GroepsactiviteitenIntake']['gezin_met_kinderen'])) {
		$checked='checked';
	}
	
	echo $this->Form->input('GroepsactiviteitenIntake.gezin_met_kinderen', array('type' => 'checkbox', 'label' => 'Gezin met kinderen', 'checked' => $checked));
?>

	<p>Coordinator: <?php echo !empty($persoon['GroepsactiviteitenIntake']['medewerker_id']) ?	$viewmedewerkers[ $persoon['GroepsactiviteitenIntake']['medewerker_id'] ] : ''; ?></p>
	
	<div class="zrmReports ">
	
<?php 
	echo $this->element('zrm', array(
			'model' => 'GroepsactiviteitenIntake',
			'zrm_data' => $zrm_data,
	));

?>
</div>

<?php

	$gespreksverslag = !empty($persoon['GroepsactiviteitenIntake']['gespreksverslag']) ? $persoon['GroepsactiviteitenIntake']['gespreksverslag'] : '';
	
	echo $this->Form->label('gespreksverslag');
	echo $this->Form->textarea('GroepsactiviteitenIntake.gespreksverslag', array('value' => $gespreksverslag, 'class' => 'verslag-textarea'));

?>

<fieldset id="ondersteuning">
	<legend>Ondersteuning</legend>
	<p>
		Als je bij de vier vragen hieronder 'ja' invult,
		wordt er een e-mail verzonden naar de desbetreffende afdeling.
		Vul 'nee' in als de klant geen gebruik wenst te maken van deze
		mogelijkheden, of deze al gebruikt.
	</p>

	<?php

		$radioOptions = array(
			GroepsactiviteitenIntake::DECISION_VALUE_NO => 'Nee',
			GroepsactiviteitenIntake::DECISION_VALUE_YES => 'Ja',
		);

		$radioAttributes = array(
			'legend' => false,
			'value' => GroepsactiviteitenIntake::DECISION_VALUE_NO,
			'style' => 'display: inline-block;',
		);

		$radioAttributes['value'] = null;
		if(isset($persoon['GroepsactiviteitenIntake']['ondernemen'])) {
			$radioAttributes['value'] = $persoon['GroepsactiviteitenIntake']['ondernemen'];
		}
		
		echo '<div style="margin: 0;">';
		echo $this->Form->label('ondernemen', 'Zou je het leuk vinden om iedere week met iemand samen iets te ondernemen?');
		echo '<div>';
		echo $this->Form->radio('GroepsactiviteitenIntake.ondernemen', $radioOptions, $radioAttributes);
		echo '</div>';
		echo '</div>';

		$radioAttributes['value'] = null;
		if(isset($persoon['GroepsactiviteitenIntake']['overdag'])) {
			$radioAttributes['value'] = $persoon['GroepsactiviteitenIntake']['overdag'];
		}
		echo '<div style="margin: 0;">';
		echo $this->Form->label('overdag', 'Zou je het leuk vinden om overdag iets te doen te hebben?');
		echo '<div>';
		echo $this->Form->radio('GroepsactiviteitenIntake.overdag', $radioOptions, $radioAttributes);
		echo '</div>';
		echo '</div>';

		$radioAttributes['value'] = null;
		if(isset($persoon['GroepsactiviteitenIntake']['ontmoeten'])) {
			$radioAttributes['value'] = $persoon['GroepsactiviteitenIntake']['ontmoeten'];
		}
		echo '<div style="margin: 0;">';
		echo $this->Form->label('ontmoeten', 'Zou je een plek in de buurt willen hebben waar je iedere dag koffie kan drinken en mensen kan ontmoeten?');
		echo '<div>';
		echo $this->Form->radio('GroepsactiviteitenIntake.ontmoeten', $radioOptions, $radioAttributes);
		echo '</div>';
		echo '</div>';

		$radioAttributes['value'] = null;
		if(isset($persoon['GroepsactiviteitenIntake']['regelzaken'])) {
			$radioAttributes['value'] = $persoon['GroepsactiviteitenIntake']['regelzaken'];
		}
		echo '<div style="margin: 0;">';
		echo $this->Form->label('regelzaken', 'Heeft u hulp nodig met regelzaken?');
		echo '<div>';
		echo $this->Form->radio('GroepsactiviteitenIntake.regelzaken', $radioOptions, $radioAttributes);
		echo '</div>';
		echo '</div>';

	?>
</fieldset>

<?php
	echo $this->Form->submit('Opslaan', array('id' => 'verslag-submit-0', 'div' => false));
	echo $this->Form->end();
?>

</fieldset>



