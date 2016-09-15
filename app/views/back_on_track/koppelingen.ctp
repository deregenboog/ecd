<div class="actions">
	<?= $this->element('klantbasic', array('data' => $klant)); ?>
</div>
<div class="backOnTrack form">

<?php 
	echo $this->Form->create('BotKoppeling', array(
		'url' => array('controller' => 'BotKoppeling', 'action' => 'add', 'back_on_trac_id' => $this->data['BackOnTrack']['id']),
	));
?>
	
	<fieldset>
		<legend><?php __('Koppelingen'); ?></legend>
<?php

	echo $this->Form->hidden('klant_id', array('value' => $klant['Klant']['id']));
	
	echo $date->input('BackOnTrack.startdatum', null, array(
		'label' => 'Startdatum',
	));
	
	echo $date->input('BackOnTrack.einddatum', null, array(
		'label' => 'Einddatum',
	));

?>
	</fieldset>
<?php 
	echo $this->Form->end(__('Submit', true));
?>
</div>
