<?php

if (empty($klant['AwbzHoofdaannemer'])) {

	echo '<p>'.__('No contractors', true).'</p>';

} else {

	$contractors_count = count($klant['AwbzHoofdaannemer']);

	foreach ($klant['AwbzHoofdaannemer'] as $i => &$hoofdaannemer) {
		$vanaf = $hoofdaannemer['begindatum'];
		$tot = $hoofdaannemer['einddatum'];
		$tot = (empty($tot) || $tot == '0000-00-00') ? 'nu' : $tot; ?>

		<div class="awbz-hoofdaannemer">
			<h4> Hoofdaannemer vanaf <?= $vanaf ?> tot en met <?= $tot; ?>:</h4>
			<ul>
				<li>
			<?= $activering ?> uur activering per week
		</li>
		<li>
			<?= $begeleiding ?> uur begeleiding per week
		</li>
	</ul>
</div>
<?php if (++$i != $contratcors_count) {
			?><hr/><?php 
		} ?>



<?php

	}
}
?>
	<fieldset class="inline-labels">
		<legend>Hoofdaannemer toevoegen</legend>
	<?php
		$klant_id = $klant['Klant']['id'];

		$post_url = array(
			'controller' => 'awbz_hoofdaannemers',
			'action' => 'add',
			$klant_id,
		);

		echo $this->Form->create('AwbzHoofdaannemer', array('url' => $post_url));

		$hours = array();

		for ($i = 1; $i <= 40; $i++) {
			$hours[$i] = $i.' uur';
		}

		echo $this->Form->hidden('klant_id', array('value' => $klant_id));
		
		echo $this->Date->datepicker('begindatum', array(
			'required' => true,
			'label' => 'Begindatum hoofdaannemer',
		));
		
		echo $this->Date->datepicker('einddatum', array(
			'label' => 'Einddatum hoofdaannemer',
		));
		
		echo $this->Form->input('begeleiding_per_week', array(
			'options' => $hours,
			'label' => 'Aantal uur begeleiding per week',
		));
		
		echo $this->Form->input('activering_per_week', array(
			'options' => $hours,
			'label' => 'Aantal uur activering per week',
		));
		
		echo $this->Js->submit('Hoofdaannemer opslaan', array(
			'update' => '#awbz_hoofdaannemers',
			'url' => $post_url,
		));
		
		echo $this->Form->end();
	?>
</fieldset>
