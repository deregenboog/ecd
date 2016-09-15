
<fieldset>

<h2>Intervisiegroepen</h2>

<?php
	$url = $this->Html->url(array('controller' => 'iz_deelnemers', 'action' => 'vrijwilliger_intervisiegroepen', $id), true);
	echo $this->Form->create(
		'IzDeelnemer',
		array(
		   'url' => $url,
		)
	);

	echo $this->Form->input('iz_intervisiegroep_id', array(
			'type'=>'select',
			'multiple'=>'checkbox',
			'options'=> $intervisiegroepenlists,
			'label'=>'', ));

	echo $this->Form->submit('Opslaan', array('id' => 'verslag-submit-0', 'div' => false));
	echo $this->Form->end();
?>

</fieldset>




