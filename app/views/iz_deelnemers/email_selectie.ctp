<?= $this->element('iz_subnavigation'); ?>

<?php

    $werkgebieden = Configure::read('Werkgebieden');
    $postcodegebieden = Configure::read('Postcodegebieden');
    $persoon_typen = Configure::read('Persoontypen');
    $communicatie_typen = Configure::read('Communicatietypen');
    $iz_fasen = Configure::read('IzFase');

    $url = $this->Html->url(array('controller' => 'iz_deelnemers', 'action' => 'email_selectie'), true);

?>

<?= $this->Form->create('IzDeelnemer', array(
        'enctype' => 'multipart/form-data',
        'url' => $url,
)) ;
?>

<div style="display: none">

<?= $this->Form->input('emailsource', array('value' => $emailsource)); ?>

<?= $this->Form->input('project_id', array(
    'type' => 'select',
    'label' => '',
    'multiple'=>'checkbox',
    'options' => $projectlists,
))
?>

<?= $this->Form->input('alle_werkgebieden', array(
    'type' => 'checkbox',
    'label' => 'Alles (de)selecteren',
))
?>

<?= $this->Form->input('werkgebieden', array(
    'type' => 'select',
    'multiple'=>'checkbox',
    'options' => $werkgebieden,
))
?>

<?= $this->Form->input('alle_postcodegebieden', array(
            'type' => 'checkbox',
            'label' => 'Alles (de)selecteren',
))
?>

<?= $this->Form->input('postcodegebieden', array(
            'type' => 'select',
            'multiple'=>'checkbox',
            'options' => $postcodegebieden,
            'div'=>array('class'=>'required'),
))
?>

<?= $this->Form->input('persoon_model', array(
    'type' => 'select',
    'label' => 'Selecteer deelnemers/vrijwilligers',
    'div'=>array('class'=>'required'),
    'multiple'=>'checkbox',
    'options' => $persoon_typen,
));
?>

<?= $this->Form->input('iz_fase', array(
    'type' => 'radio',
    'label' => 'Selecteer fase',
    'options' => $iz_fasen,
    'div'=>array('class'=>'required'),
))
?>

<?= $this->Form->input('communicatie_type', array(
    'type' => 'select',
    'label' => 'Selecteer communicatievorm',
    'multiple'=>'checkbox',
    'options' => $communicatie_typen,
));
?>

<?= $this->Form->input('intervisiegroep_id', array(
    'type' => 'select',
    'label' => '',
    'multiple'=>'checkbox',
    'options' => $intervisiegroepenlists,
));
?>

</div>

<fieldset class="action data">
        <legend>E-mail</legend>

        Ontvangers:
        <ul id="ontvangers" stype='list-style-type: none;'>
<?php
    if (count($personen) > 20) {
?>
        <?= count($personen) ?> Personen
<?php

} else {

    foreach ($personen as $persoon) {
        ?>
            <li  style='display: inline;'><?= $persoon['email'] ?>,</li>
<?php
    }
}
?>
    </ul>
    <br class="clear">
    <br class="clear">
    <p>Afzender: <?php echo $this->Session->read('Auth.Medewerker.LdapUser.mail'); ?></p>

    <?= $this->Form->input('onderwerp', array('style' => 'width: 500px')) ;?>
    <br>
    <?= $this->Form->input('text', array('label' => 'Bericht', 'type' => 'textarea', 'style' => 'width: 500px')) ;?>

    <?= $this->Form->hidden('Document.0.group', array('value' => 'email'))?>
    <?= $this->Form->input('Document.0.file', array(
            'type' => 'file',
        ));
    ?>

    <?= $this->Form->hidden('Document.1.group', array('value' => 'email'))?>
    <?= $this->Form->input('Document.1.file', array(
        'type' => 'file',
    ));
    ?>

    <?= $this->Form->hidden('Document.2.group', array('value' => 'email'))?>
    <?= $this->Form->input('Document.2.file', array(
        'type' => 'file',
    ));
    ?>

    <?php
        if (isset($this->validationErrors['Document']['2'])) {
            echo $this->data['Document'][2]['file']['name'].' '.$this->data['Document'][2]['file']['type'];
        }
    ?>

    <?= $this->Form->submit('Verzenden') ;?>
</fieldset>
<?= $this->Form->end() ;?>
