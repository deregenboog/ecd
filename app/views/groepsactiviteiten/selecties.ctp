<?= $this->element('groepsactiviteiten_subnavigation'); ?>

<?php
foreach ($personen as $persoon) {
	echo $persoon['id']." ".$persoon['model'].' '.$persoon['name']."<br/>";
}
?>

<?php 

$werkgebieden = Configure::read('Werkgebieden');
unset($werkgebieden['']);
$persoon_typen = Configure::read('Persoontypen');
$communicatie_typen = Configure::read('Communicatietypen');

?>

<?= $this->Form->create(); ?>

	<div class="left3cDiv">
		<fieldset>
		<legend>Groep</legend>
		
		<?= $this->Form->input('alle_groepen', array(
			'type' => 'checkbox',
			'label' => 'Alles (de)selecteren',
		))
		?>		 
		
		<div id='activiteitengroepcontainer'>
		
		<?= $this->Form->input('activiteitengroepen', array(
			'type' => 'select',
			'multiple'=>'checkbox',
			'options' => $activiteitengroepen,
		))
		?>
		
		</div>
		
	</fieldset>
	</div>
	
	<div class="center3cDiv">
		<fieldset>
		<legend>Werkgebied</legend>
	
	<?= $this->Form->input('alle_werkgebieden', array(
			'type' => 'checkbox',
			'label' => 'Alles (de)selecteren',
		))
	?>
	<div id='werkgebiedcontainer'>
	
		<?= $this->Form->input('werkgebieden', array(
			'type' => 'select',
			'multiple'=>'checkbox',
			'options' => $werkgebieden,
		))
		?>
	 </div>
	 
	</fieldset>
	</div>
	
	<div class="right3cDiv">
			<fieldset>
		<legend>Personen</legend>
		<?php

		echo $this->Form->input('persoon_model', array(
			'type' => 'select',
			'label' => 'Selecteer klanten / vrijwilligers',
			'div'=>array('class'=>'required'),
			'multiple'=>'checkbox',
			'options' => $persoon_typen,
		))
		?>
		
	</fieldset>
			<fieldset>
		<legend>Contact</legend>
		<?= $this->Form->input('communicatie_type', array(
			'type' => 'select',
			'label' => 'Selecteer communicatievorm',
			'multiple'=>'checkbox',
			'div'=>array('class'=>'required'),
			'options' => $communicatie_typen,
		))
		?>
		
	</fieldset>
	</div>
	


	<div id="clear"></div>

	<fieldset class="action data">
		<legend>Actie</legend>
		
		<?= $this->Form->input('export', array(
			'legend' => "",
			'type' => 'radio',
			'multiple'=>'checkbox',
			'options' => array('csv' => 'Excel-lijst', 'email' => 'E-mail'),
			'value' => 'csv',
		))
		?>
		
		<?= $this->Form->submit('Maak selectie') ?>
		
	 </fieldset>
   
<?= $this->Form->end() ?>

<?php

$this->Js->buffer(<<<EOS

$('#GroepsactiviteitAlleGroepen').change(function(event){ 
	a=$('#GroepsactiviteitAlleGroepen').attr('checked');
	$('#activiteitengroepcontainer').find('input[type=checkbox]').each(function( index ) {
	  $(this).attr('checked',a);
	});
});
$('#GroepsactiviteitAlleWerkgebieden').change(function(event){ 
	a=$('#GroepsactiviteitAlleWerkgebieden').attr('checked');
	$('#werkgebiedcontainer').find('input[type=checkbox]').each(function( index ) {
	  $(this).attr('checked',a);
	});
});    
 
EOS
);

?>
