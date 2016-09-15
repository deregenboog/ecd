<div class="actions">
	<?= $this->element('klantbasic', array('data' => $klant)); ?>
</div>
<div class="backOnTrack form">
<?php 
	echo $this->Form->create('BackOnTrack', array('url' => array('controller' => 'BackOnTrack')));
?>
	<fieldset>
		<legend><?php __('Basisgegevens Back On Track'); ?></legend>
<?php
	echo $this->Form->input('id');
	
	echo $this->Form->hidden('klant_id', array('value' => $klant['Klant']['id']));
	
	echo $date->input('BackOnTrack.startdatum', null, array(
		'label' => 'Datum aanmelding',
	));
	
	echo $date->input('BackOnTrack.intakedatum', null, array(
		'label' => 'Datum intake',
	));
	
	echo $date->input('BackOnTrack.einddatum', null, array(
		'label' => 'Einddatum',
	));

	?>
	</fieldset>
<?php 

	echo $this->Form->end(__('Submit', true));
	
	$url = array('controller' => 'BackOnTrack', 'action' => 'view', $klant['Klant']['id']);
	
	echo $this->Html->link(_('Cancel'), $url);
?>
</div>
