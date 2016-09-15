<div class="actions">
	<?= $this->element('klantbasic', array('data' => $klant)); ?>
	<div class="print-invisible">
	<?php
		echo  $this->element('bot_documents', array('data' => $klant, 'group' => Attachment::GROUP_BTO));
		echo $this->element('back_on_track', array('data' => $klant));
	?>
	</div>
</div>

<div class="backOnTracks view">
<h2><?php  __('Back On Track');?></h2>

<?php
		echo $this->element('bot_verslag', array(
			'klant' =>$klant,
		));
?>
<div>&nbsp;</div>
<?php 
	foreach ($klant['BotVerslag'] as $verslag) {
		
		$data['BotVerslag'] = $verslag;
		
		echo $this->element('bot_verslag', array(
			'data' => $data,
			'klant' =>$klant,
		));
		
	}
?>

</div>
