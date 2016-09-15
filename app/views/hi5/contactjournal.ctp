<div class="actions">

<?php
	echo $this->element('klantbasic', array(
			'data' => $klant,
	));
	
	echo $this->element('diensten', array( 'diensten' => $diensten, ));

	echo $this->element('hi5_traject', array(
			'data' => $klant,
	));
	
	echo $this->element('hi5_intake', array(
			'viewElementOptions' => $viewElementOptions,
			'data' => $klant,
	));
	
	echo $this->element('hi5_evaluatie', array(
			'viewElementOptions' => $viewElementOptions,
			'data' => $klant,
	));
	
	echo $this->element('hi5_contactjournal', array(
		'viewElementOptions' => $viewElementOptions,
		'klant_id' => $klant['Klant']['id'],
		'countContactjournalTB' => $countContactjournalTB,
		'countContactjournalWB' => $countContactjournalWB,
	));
	
?>
</div>
<div class="intakes view">

<?php
	$confirmDeleteMessage = __("Weet u zeker dat u deze notitie wilt verwijderen?", true);

	foreach ($contactJournals as $contactJournalDetails) {
?>

	<div class="hi5contactjournal">
	<div class="editWrench" style="">
		<h4 style="float: left; font-weight: bold;">
		Notitie door <?= $viewmedewerkers[$contactJournalDetails['Contactjournal']['medewerker_id']]?> op <?= $this->date->show($contactJournalDetails['Contactjournal']['datum']) ?>
		</h4>
<?php 
	$wrench = $html->image('wrench.png');
	$url = array('controller' => 'hi5',
		'action' => 'cj_edit',
		$contactJournalDetails['Contactjournal']['id'],
	);
	
	$opts = array(
		'escape' => false,
		'title' => __('edit', true),
	);
	
	echo $html->link($wrench, $url, $opts); 
?>

<?php 
	$wrench = $html->image('trash.png');

	$url = array('controller' => 'hi5',
		'action' => 'cj_delete',
		$contactJournalDetails['Contactjournal']['id'],
	);
	
	$opts = array(
		'escape' => false,
		'onclick' => "return confirm('$confirmDeleteMessage');",
		'title' => __('delete', true),
	);
	
	echo $html->link($wrench, $url, $opts); 
?>

	</div>
<?php
	echo nl2br($contactJournalDetails['Contactjournal']['text']); 
?>

	<hr />
	</div>
<?php } ?>

</div>
<?php echo $this->element('cj_form'); ?>
