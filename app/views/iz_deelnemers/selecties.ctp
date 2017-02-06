<?php echo $this->element('iz_subnavigation'); ?>

<?php
    foreach ($personen as $persoon) {
        echo $persoon['id']." ".$persoon['model'].' '.$persoon['name']."<br/>";
    }
?>

<?php
    $postcodegebieden = array('geen_postcodegebied' => 'Geen postcodegebied') + Configure::read('Postcodegebieden');
    $werkgebieden = array('geen_werkgebied' => 'Geen werkgebied') + Configure::read('Werkgebieden');

    unset($werkgebieden['']);
    unset($postcodegebieden['']);

    $persoon_typen = Configure::read('Persoontypen');
    $communicatie_typen = Configure::read('Communicatietypen');
    $iz_fasen = Configure::read('IzFase');
?>

<h2>Selecties</h2>

<?= $this->Form->create(); ?>


    <div class="left3cDiv">
    <fieldset>
    <legend>Projecten</legend>
     <?= $this->Form->input('alle_projecten', array(
            'type' => 'checkbox',
            'label' => 'Alles (de)selecteren',
    ))
    ?>
    <div id='projectencontainer'>
    <?= $this->Form->input('project_id', array(
        'type' => 'select',
        'label' => '',
        'multiple'=>'checkbox',
        'options' => $projectlists,
        'div'=>array('class'=>'required'),
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
        'div'=>array('class'=>'required'),
    ))
    ?>

    </div>

    </fieldset>

    <fieldset style='display: none;'>
        <legend>Postcodegebied</legend>

    <?= $this->Form->input('alle_postcodegebieden', array(
            'type' => 'checkbox',
            'label' => 'Alles (de)selecteren',
        ))
    ?>

    <div id='postcodegebiedcontainer'>

    <?= $this->Form->input('postcodegebieden', array(
        'type' => 'select',
        'multiple'=>'checkbox',
        'options' => $postcodegebieden,
        'div'=>array('class'=>'required'),
    ))
    ?>

    </div>
    </fieldset>



    </div>
    <div class="right3cDiv">
    <fieldset>
    <legend>Personen</legend>
    <?= $this->Form->input('persoon_model', array(
        'type' => 'select',
        'label' => 'Selecteer deelnemers/vrijwilligers',
        'div'=>array('class'=>'required'),
        'multiple'=>'checkbox',
        'options' => $persoon_typen,
    ))
    ?>
    </fieldset>

    <fieldset>
    <legend>Fase</legend>
    <?= $this->Form->input('alle_fasen', array(
            'type' => 'checkbox',
            'label' => 'Alles (de)selecteren',
    ))
    ?>

<style>
label {
   margin-bottom: 10px;
}
</style>
    <div id='fasecontainer'>

    <?= $this->Form->input('iz_fase', array(
        'type' => 'select',
        'label' => 'Selecteer fase',
        'multiple'=>'checkbox',
        'options' => $iz_fasen,
        'div'=>array('class'=>'required'),
    ))
    ?>
    </div>

    </fieldset>
        <fieldset>
        <legend>Contact</legend>

    <?= $this->Form->input('communicatie_type', array(
        'type' => 'select',
        'label' => 'Selecteer communicatievorm',
        'multiple'=>'checkbox',
        'options' => $communicatie_typen,
        'div'=>array('class'=>'required'),
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
        'options' => array('csv' => 'Excel-lijst', 'email' => 'E-mail', 'etiket' => 'Etiketten'),
        'value' => 'csv',
    ))
    ?>

    <?= $this->Form->submit('Maak selectie') ?>

     </fieldset>



<?= $this->Form->end() ?>

<?php

$this->Js->buffer(<<<EOS

$('#IzDeelnemerAlleProjecten').change(function(event){
    a=$('#IzDeelnemerAlleProjecten').attr('checked');
    $('#projectencontainer').find('input[type=checkbox]').each(function( index ) {
      $(this).attr('checked',a);
    });
});
$('#IzDeelnemerAlleWerkgebieden').change(function(event){
    a=$('#IzDeelnemerAlleWerkgebieden').attr('checked');
    $('#werkgebiedcontainer').find('input[type=checkbox]').each(function( index ) {
      $(this).attr('checked',a);
    });
});
$('#IzDeelnemerAllePostcodegebieden').change(function(event){
    a=$('#IzDeelnemerAllePostcodegebieden').attr('checked');
    $('#postcodegebiedcontainer').find('input[type=checkbox]').each(function( index ) {
      $(this).attr('checked',a);
    });
});
$('#IzDeelnemerAllePostcodegebieden').attr('checked',true);
$('#IzDeelnemerAllePostcodegebieden').trigger("change");
$('#IzDeelnemerAlleFasen').change(function(event){
    a=$('#IzDeelnemerAlleFasen').attr('checked');
    $('#fasecontainer').find('input[type=checkbox]').each(function( index ) {
      $(this).attr('checked',a);
    });
});

EOS
);

?>
