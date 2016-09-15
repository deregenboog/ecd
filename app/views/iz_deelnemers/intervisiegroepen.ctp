<?php echo $this->element('iz_subnavigation'); ?>

<?php
foreach ($personen as $persoon) {
	echo $persoon['id']." ".$persoon['model'].' '.$persoon['name']."<br/>";
}
?>

<?php 
$communicatie_typen = Configure::read('Communicatietypen');
?>

<h2>Selecties</h2>

<?= $this->Form->create(); ?>

	<div >
	<fieldset>
	<legend>Intervisiegroepen</legend>
	 <?= $this->Form->input('alle_intervisiegroepen', array(
			'type' => 'checkbox',
			'label' => 'Alles (de)selecteren',
		))
?>	  
	<div id='intervisiegroepencontainer'>
		<?= $this->Form->input('intervisiegroep_id', array(
			'type' => 'select',
			'label' => '',
			'multiple'=>'checkbox',
			'options' => $intervisiegroepenlists,
		))
		?>
	</div>
	</fieldset>
   </div>

   
	<div id="clear"></div>

	<fieldset class="action data">
		<legend>Actie</legend>
		<?= $this->Form->input('export', array(
			'legend' => "",
			'type' => 'radio',
			'multiple'=>'checkbox',
			'options' => array('csv' => 'Excel-lijst', 'email' => 'E-mail', 'etiket' => 'Etiketten'),
			'value' => 'csv',
		))
		?>
		<?= $this->Form->submit('Maak selectie') ?>
	 </fieldset>
 
		
   
<?= $this->Form->end() ?>

<?php

$this->Js->buffer(<<<EOS

$('#IzDeelnemerAlleIntervisiegroepen').change(function(event){ 
	a=$('#IzDeelnemerAlleIntervisiegroepen').attr('checked');
	$('#intervisiegroepencontainer').find('input[type=checkbox]').each(function( index ) {
	  $(this).attr('checked',a);
	});
});
 
EOS
);

?>
