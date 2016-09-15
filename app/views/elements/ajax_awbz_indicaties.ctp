<?php
if (empty($klant['AwbzIndicatie'])) {
	
	echo '<p>'.__('No indications', true).'</p>';
	
} else {
	
	$indications_count = count($klant['AwbzIndicatie']);
	$confirm_msg = __('Are you sure you want to delete the indication?', true);
	$pencil = $this->Html->image('wrench.png', array(
		'title' => __('edit', true),
	));
	$klant_id = $klant['Klant']['id'];

	foreach ($klant['AwbzIndicatie'] as $i => &$indicatie) {
		$vanaf = $date->show($indicatie['begindatum']);
		$tot = $date->show($indicatie['einddatum']);
		$tot = (empty($tot) || $tot == '0000-00-00') ? 'nu' : $tot;

		$activering = $indicatie['activering_per_week'];
		$begeleiding = $indicatie['begeleiding_per_week'];
		$hoofdaannemer =
			! empty($indicatie['Hoofdaannemer']['naam']) ?
			$indicatie['Hoofdaannemer']['naam'] :
			null; ?>

<div class="awbz-indicatie">
	<h4> Indicatie vanaf <?= $vanaf ?> tot en met <?= $tot; ?> (<?= $hoofdaannemer ?>):
		<span class="editWrenchFloat">
			<?php

			echo $this->Html->link(
				$pencil,
				array(
					'controller' => 'awbz_indicaties',
					'action' => 'edit',
					$indicatie['id'],
				),
				array('escape' => false)
			);

		$del_id = 'delete-indicatie-'.$indicatie['id'];
		echo $this->Html->image('trash.png', array(
				'title' => __('delete', true),
				'style' => 'cursor: pointer',
				'id' => $del_id,
			));

		$this->Js->get('#'.$del_id)->event('click',
				'if(confirm("'.$confirm_msg.'")){'.
				$this->Js->request(
					array(
						'controller' => 'awbz_indicaties',
						'action' => 'ajax_delete',
						$indicatie['id'],
						$klant_id,
					),
					array(
						'async' => false,
						'before' => '$("#loading").css("display","block")',
						'complete' => '$("#loading").css("display","none")',
						'update' => '#awbz_indicaties',
					)
				).'}'
		); 
?>
		</span>
	</h4>
	<ul>
		<li>
			<?= $activering ?> uur activering per week
		</li>
		<li>
			<?= $begeleiding ?> uur begeleiding per week
		</li>
	</ul>
</div>

<?php 
	if (++$i != $indications_count) {
?>
<hr/>
<?php } ?>

<?php
	}
}
?>

	<fieldset class="inline-labels">
		<legend>Indicatie toevoegen</legend>
<?php
	$klant_id = $klant['Klant']['id'];
	$post_url = array(
		'controller' => 'awbz_indicaties',
		'action' => 'add',
		$klant_id,
	);

	echo $this->Form->create('AwbzIndicatie', array('url' => $post_url));

	$hours = array();

	for ($i = 0; $i <= 40; $i++) {
		$hours[$i] = $i.' uur';
	}

	echo $this->Form->hidden('klant_id', array('value' => $klant_id));

	echo $this->Date->datepicker('begindatum', array(
		'required' => true,
		'label' => 'Begindatum indicatie',
	));
	
	echo $this->Date->datepicker('einddatum', array(
		'required' => true,
		'label' => 'Einddatum indicatie',
	));
	
	echo $this->Form->input('activering_per_week', array(
		'options' => $hours,
		'label' => 'Aantal uur activering per week',
	));
	
	echo $this->Form->input('begeleiding_per_week', array(
		'options' => $hours,
		'label' => 'Aantal uur begeleiding per week',
	));
	
	echo $this->Form->input('hoofdaannemer_id');
	
	echo $this->Js->submit('Indicatie opslaan', array(
		'update' => '#awbz_indicaties',
		'url' => $post_url,
		'complete' => 'if ($(\'#flashMessage\').text() == \'AWBZ indicatie opgeslagen\') { $(\':text, select\', \'#AwbzIndicatieAddForm\').val(\'\').removeAttr(\'selected\'); }',
	));
	echo $this->Form->end();
	?>
</fieldset>
