<?php
if (empty($klant['AwbzHoofdaannemer'])) {
	echo '<p>'.__('No contractors', true).'</p>';
} else {
	$klant_id = $klant['Klant']['id'];


	$confirm_msg = __('Are you sure you want to delete the contractor?', true);
	$pencil = $this->Html->image('wrench.png', array(
		'title' => __('edit', true),
	));

	foreach ($klant['AwbzHoofdaannemer'] as $i => &$hoofdaannemer) {
		$vanaf = $hoofdaannemer['begindatum'];
		$tot = $hoofdaannemer['einddatum'];
		$tot = (empty($tot) || $tot == '0000-00-00') ? 'nu' : $tot; ?>

		<div class="awbz-hoofdaannemer">
			<p>
				Hoofdaannemer vanaf <?= $vanaf ?> tot en met <?= $tot; ?>:
				<?=$hoofdaannemer['Hoofdaannemer']['naam']?>
				<span class="editWrenchFloat">
<?php

		echo $this->Html->link(
			$pencil,
			array(
				'controller' => 'awbz_hoofdaannemers',
				'action' => 'edit',
				$hoofdaannemer['id'],
			),
			array('escape' => false)
		);

		$del_id = 'delete-hoofdaannemer-'.$hoofdaannemer['id'];
		
		echo $this->Html->image('trash.png', array(
			'title' => __('delete', true),
			'style' => 'cursor: pointer',
			'id' => $del_id,
		));
		
		$this->Js->get('#'.$del_id)->event('click',
			'if(confirm("'.$confirm_msg.'")){'.
			$this->Js->request(
			array(
				'controller' => 'awbz_hoofdaannemers',
				'action' => 'ajax_delete',
				$hoofdaannemer['id'],
				$klant_id,
			),
			array(
				'async' => false,
				'before' => '$("#loading").css("display","block")',
				'complete' => '$("#loading").css("display","none")',
				'update' => '#awbz_hoofdaannemers',
			)
		).'}'
	); ?>
			</span>
		</p>
</div>
<?php
	}
}
?>

	<fieldset class="inline-labels">
		<legend>Hoofdaannemer aanpassen</legend>
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
		'label' => 'Begindatum nieuwe hoofdaannemer',
	));
	
	echo $this->Form->input('hoofdaannemer_id');
	
	echo $this->Js->submit('Hoofdaannemer opslaan', array(
		'update' => '#awbz_hoofdaannemers',
		'url' => $post_url,
	));

	echo $this->Form->end();
?>
</fieldset>
