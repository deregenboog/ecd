<?= $this->element('groepsactiviteiten_subnavigation') ?>
<?= $this->element('groepsactiviteiten_beheer_subnavigation') ?>

<div class="groepsactiviteitenGroepen ">
    <?php echo $this->Form->create('GroepsactiviteitenGroep');?>
    <fieldset>
        <legend><?php __('Groep'); ?></legend>
        <?php
            echo $this->Form->input('naam');
            echo $date->input('GroepsactiviteitenGroep.startdatum', null, array(
                'label' => 'Startdatum',
                'rangeHigh' => (date('Y') + 10).date('-m-d'),
                'rangeLow' => (date('Y') - 3).date('-m-d'),
            ));
            echo $date->input('GroepsactiviteitenGroep.einddatum', null, array(
                'label' => 'Einddatum',
                'rangeHigh' => (date('Y') + 10).date('-m-d'),
                'rangeLow' => (date('Y') - 3).date('-m-d'),
            ));
            echo $this->Form->input('werkgebied', array(
                'label' => 'Werkgebied',
                'options' => $werkgebieden,
            ));
            echo $this->Form->input('type', array(
                'label' => 'Type',
                'options' => [
                    '' => '',
                    'Buurtmaatjes' => 'Buurtmaatjes',
                    'ErOpUit' => 'ErOpUit',
                    'Kwartiermaken' => 'Kwartiermaken',
                    'OpenHuis' => 'OpenHuis',
                    'Organisatie' => 'Organisatie',
                ],
            ));
            echo $this->Form->input('activiteiten_registreren', array(
                'type' => 'checkbox',
                'label' => 'Activiteiten registreren',
            ));
        ?>
    </fieldset>
    <?= $this->Form->end(__('Submit', true)) ?>
    <?= $this->Html->link(__('terug', true), array('action' => 'index')) ?>
</div>
