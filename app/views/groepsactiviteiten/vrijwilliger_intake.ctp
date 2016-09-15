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
	
	echo $this->Form->hidden('GroepsactiviteitenIntake.foreign_key', array('value' => $persoon[$persoon_model]['id']));
	echo $this->Form->hidden('GroepsactiviteitenIntake.model', array('value' => 'Vrijwilliger'));
	echo $this->Form->hidden('GroepsactiviteitenIntake.gespreksverslag', array('value' => ''));

	echo $this->Form->submit('Opslaan', array('id' => 'verslag-submit-0', 'div' => false));
	echo $this->Form->end();
?>

</fieldset>
