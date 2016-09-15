<div class="verslagen view">
	<?=$this->element('verslagen_index', array(compact('verslagen')));?>
</div>
<div class="actions">

<?= $this->element('persoon_view_basic',
	array(
		'name' => 'Klant',
		'data' => $klant,
		'show_documents' => false,
		'view' => $this,
		'logged_in_user_id' => $this->Session->read('Auth.Medewerker.id'),
)); ?>

<?= $this->element('diensten', array(
		'diensten' => $diensten,
))?>

<?= $this->element('klantdocuments', array('data' => $klant, 'group' => Attachment::GROUP_MW));?>
<?= $this->element('verslaginfo', array(
		'verslaginfo' => $verslaginfo,
		'klantId' => $klant['Klant']['id'],
));
?>

	<div class="links">
<?php
	echo $this->Html->link('Nieuw verslag invoeren', array(
		'controller' => 'maatschappelijk_werk',
		'action' => 'add',
		$klant['Klant']['id'],
	));
	
	echo '<br/>';
	
	echo $this->Html->link('Rapportage', array(
		'controller' => 'maatschappelijk_werk',
		'action' => 'rapportage',
	));
	
?>
	</div>
</div>