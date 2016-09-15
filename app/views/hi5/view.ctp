<div class="actions">

	<?= $this->element('klantbasic', array('data' => $klant)); ?>
	
	<?= $this->element('diensten', array( 'diensten' => $diensten, ))?>
	
	<?= $this->element('hi5_traject', array('data' => $klant));?>
	
	<?= $this->element('hi5_intake', array('viewElementOptions' => $viewElementOptions, 'data' => $klant)); ?>
	
	<?= $this->element('klantdocuments', array('data' => $klant, 'group' => Attachment::GROUP_HI5));?>
	
	<?= $this->element('hi5_evaluatie', array('viewElementOptions' => $viewElementOptions, 'data' => $klant)); ?>

<?php
	echo $this->element('hi5_contactjournal', array(
		'viewElementOptions' => $viewElementOptions,
		'klant_id' => $klant['Klant']['id'],
		'countContactjournalTB' => $countContactjournalTB,
		'countContactjournalWB' => $countContactjournalWB,
	));
?>
</div>

