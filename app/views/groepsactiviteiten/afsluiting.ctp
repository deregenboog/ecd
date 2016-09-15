<?php

$url = $this->Html->url(array('controller' => 'groepsactiviteiten', 'action' => 'afsluiting', $id), true);
$afsluitdatum = date('Y-m-d');

if (! empty($this->data['Groepsactiviteit']['afsluitdatum'])) {
	if (is_array($this->data['Groepsactiviteit']['afsluitdatum'])) {
		if (!empty($this->data['Groepsactiviteit']['afsluitdatum']['year']) && !empty($this->data['Groepsactiviteit']['afsluitdatum']['month']) && !empty($this->data['Groepsactiviteit']['afsluitdatum']['day'])) {
			$afsluitdatum =$this->data['Groepsactiviteit']['afsluitdatum']['year']."-".$this->data['Groepsactiviteit']['afsluitdatum']['month']."-".$this->data['Groepsactiviteit']['afsluitdatum']['day'];
		}
	} else {
		$afsluitdatum =$this->data['Groepsactiviteit']['afsluitdatum'];
	}
}
?>

<br/>
<fieldset class="data" id="afsluiting" style="display: block;">

		<h2 style="display: inline-block;">Afsluiting</h2>
		
		<table>
		<tr>
		<td></td>
		<td>
	<p style="color: red;">

	<?= !empty($has_active_groepen) ? 'Er is nog een lopende groep. Sluit die eerst, voor je het dossier sluit.' : ''; ?>

	</p>

	<?= $this->Form->create('GroepsactiviteitenIntake', array('url' => array('controller' => 'groepsactiviteiten', 'action' => 'afsluiting', $persoon_model, $id))); ?>

	<label>Datum afsluiting</label>
	<?= $date->input("GroepsactiviteitenIntake.afsluitdatum", $afsluitdatum,
		array(
			'label' => 'Afsluit datum',
			'rangeHigh' => (date('Y') + 10).date('-m-d'),
			'rangeLow' => (date('Y') - 19).date('-m-d'),
		));
	?>

	<br>

	<label>Reden afsluiting</label>

	<?= $this->Form->input('GroepsactiviteitenIntake.groepsactiviteiten_afsluiting_id', array(
		'options' => $groepsactiviteiten_afsluiting_list,
	))?>

	<br>


	<?= $this->Form->submit(); ?>
	</td>
	</tr>
	</table>
		
</fieldset>

<?php

if (!empty($is_afgesloten)) {
	echo $html->link('Opnieuw aanmelden', array(
			'controller' => 'groepsactiviteiten',
			'action' => 'opnieuw_aanmelden',
			$groepsactiviteiten_intake_id,
	));
}

$this->Js->buffer(<<<EOS

Ecd.afsluit_disable = function(active) {
	if(active) {
		$('#afsluiting').find('*:input').each(function () {
			$(this).attr('disabled', true);
		});
	}
}

Ecd.afsluit_disable('{$has_active_groepen}');

EOS
);
?>
<br/>
