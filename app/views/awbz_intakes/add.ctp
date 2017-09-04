<?php $today = date('Y-m-d'); ?>

<div class="intakes view">
    <?= $this->Form->create('AwbzIntake', ['url' => ['controller' => $this->name, 'action' => 'add', $klant['Klant']['id']]]) ?>
    <fieldset>
        <legend><?php __('Intake toevoegen'); ?></legend>
        <?= $form->hidden('klant_id', array('value' => $klant['Klant']['id'])) ?>

        <fieldset>
            <legend>Algemeen</legend>
            <?php
                echo $this->Form->input('medewerker_id', array('label' => 'Naam intaker', 'default' => $intaker_id ));
                echo $date->input('AwbzIntake.datum_intake', $today, array(
                    'label' => 'Datum van intake',
                    'rangeLow' => (date('Y') - 1).date('-m-d'),
                    'required' => true,
                    'rangeHigh' => $today, )
                );
            ?>
        </fieldset>

        <fieldset>
            <legend>Adresgegevens</legend>
            <?php
                echo $this->Form->input('postadres', array('label' => 'Adres'));
                echo $this->Form->input('postcode');
                echo $this->Form->input('woonplaats');
                echo $date->input('AwbzIntake.verblijf_in_NL_sinds', null, array(
                    'label' => 'Verblijft in Nederland sinds',
                    'rangeLow' => (date('Y') - 100).date('-m-d'),
                    'rangeHigh' => $today,
                    'selected' => '--', ));
                echo $date->input('AwbzIntake.verblijf_in_amsterdam_sinds', null, array(
                    'label' => 'Verblijft in Amsterdam sinds',
                    'rangeLow' => (date('Y') - 100).date('-m-d'),
                    'required' => true,
                    'rangeHigh' => $today,
                    'selected' => '--', ));
                echo $this->Form->input('verblijfstatus_id', array('empty' => ''));
                echo $this->Js->buffer('verblijfstatus_toggle("#AwbzIntakeVerblijfstatusId")');
                echo $this->Js->get('#AwbzIntakeVerblijfstatusId')->event('change',
                    'verblijfstatus_toggle(this);
                ');
            ?>
        </fieldset>

        <fieldset>
            <legend>Locatiekeuze</legend>
            <?php
                echo $this->Form->input('locatie1_id', array(
                    'label' => 'Eerste locatiekeuze',
                    'empty' => '', ));
                echo $this->Form->input('locatie2_id', array(
                    'label' => 'Tweede locatiekeuze',
                    'empty' => '', ));
                echo $this->Form->input('mag_gebruiken', array(
                    'label' => __('mag_gebruiken', true), ));
            ?>
        </fieldset>

        <fieldset>
            <legend>Legitimatie</legend>
            <?php
                echo $this->Form->input('legitimatie_id', array('empty' => ''));
                echo $this->Form->input('legitimatie_nummer', array('label' => 'Legitimatienummer'));
                echo $date->input('AwbzIntake.legitimatie_geldig_tot', null, array(
                    'label' => 'Legitimatie geldig tot',
                    'rangeLow' => (date('Y') - 10).'-01-01',
                    'rangeHigh' => (date('Y') + 10).'-01-01',
                    'selected' => '--', ));
            ?>
        </fieldset>

        <fieldset>
            <legend>Verslaving</legend>
            <h3>Problematiek</h3>
            <?php
                echo $this->Form->input('Verslaving', array(
                    'type'=>'select',
                    'multiple'=>'checkbox',
                    'options'=> $verslavingen,
                    'label'=>'Verslavingen', ));
                echo $this->Form->input('verslaving_overig', array('label' => __('verslaving_overig', true)));
                echo $this->Form->input('verslavingsfrequentie_id', array('empty' => ''));
                echo $this->Form->input('verslavingsperiode_id', array('empty' => ''));
                echo $this->Form->input('Verslavingsgebruikswijze', array(
                    'type'=>'select',
                    'multiple'=>'checkbox',
                    'options'=> $verslavingsgebruikswijzen,
                    'label'=>__('verslavingsgebruikwijze', true), ));
            ?>
            <h3>Algemene problematiek</h3>
            <?php
            echo $date->input('AwbzIntake.eerste_gebruik', null, array(
                'label' => 'Wat is de datum van het eerste gebruik?',
                'rangeLow' => (date('Y') - 50).date('-m-d'),
                'rangeHigh' => $today,
            ));
        ?>
        </fieldset>

        <fieldset>
            <legend>Inkomen en woonsituatie</legend>
            <?php
                echo $this->Form->input('Inkomen', array(
                    'type'=>'select',
                    'multiple'=>'checkbox',
                    'required' => true,
                    'options'=> $inkomens,
                    'label'=>'Inkomen', ));
                echo $this->Form->input('inkomen_overig', array('label' => __('inkomen_overig', true)));
                echo $this->Form->input('woonsituatie_id', array('label' => 'Wat is de woonsituatie?', 'empty' => ''));
            ?>
        </fieldset>

        <fieldset>
            <legend>Overige hulpverlening</legend>
            <?php
                echo $this->Form->input('Instantie', array(
                    'type'=>'select',
                    'multiple'=>'checkbox',
                    'options'=> $instanties,
                    'label'=>'Heeft de client contact met andere instanties?', ));
                echo $this->Form->input('opmerking_andere_instanties', array('label' => 'Opmerkingen van andere instanties'));
                echo $this->Form->input('medische_achtergrond', array('label' => 'Relevante medische achtergrond'));
            ?>
        </fieldset>

        <fieldset>
            <legend>Verwachtingen en plannen</legend>
            <?php
                echo $this->Form->input('verwachting_dienstaanbod', array('label' => 'Wat verwacht de client van het dienstaanbod?'));
                echo $this->Form->input('toekomstplannen', array('label' => 'Wat zijn de toekomstplannen van de client?'));
            ?>
        </fieldset>

        <fieldset>
            <legend>Indruk</legend>
            <?= $this->Form->input('indruk', ['label' => __('label_indruk', true)]) ?>
            <?= $this->Form->label('doelgroep', __('label_doelgroep', true)) ?>
            <?= $this->Form->input('doelgroep', [
                'type' => 'radio',
                'options' => [1 => 'Ja', 0 => 'Nee'],
                'legend' => false,
            ]) ?>
        </fieldset>

        <fieldset id="ondersteuning">
            <legend>Ondersteuning</legend>
            <p>
                Als je bij de vier vragen hieronder 'ja' invult,
                wordt er een e-mail verzonden naar de desbetreffende afdeling.
                Vul 'nee' in als de klant geen gebruik wenst te maken van deze
                mogelijkheden, of deze al gebruikt.
            </p>
            <?php
                $optionsArray = array(
                    'options'	 => array(0 =>'Nee', 1 => 'Ja'),
                    'type'		  => 'radio',
                    'label'    => ' ',
                    'legend'	=> false,
                    'value'		=> 0,
                );

                $ja_label_f = 'Ja <small style="display: none">(e-mail naar ';
                $ja_label_b = ')</small>';

                // informele_zorg
                echo $this->Form->label('informele_zorg',
                    'Zou je het leuk vinden om iedere week
                    met iemand samen iets te ondernemen?'
                );
                $optionsArray['options'][1] =
                    $ja_label_f.$informele_zorg_mail.$ja_label_b;
                echo $this->Form->input('informele_zorg', $optionsArray);

                // dagbesteding
                echo $this->Form->label('dagbesteding',
                    'Zou je het leuk vinden om overdag iets te doen te hebben?'
                );
                $optionsArray['options'][1] =
                    $ja_label_f.$dagbesteding_mail.$ja_label_b;
                echo $this->Form->input('dagbesteding', $optionsArray);

                // inloophuis
                echo $this->Form->label('inloophuis',
                    'Zou je een plek in de buurt willen hebben waar je iedere
                    dag koffie kan drinken en mensen kan ontmoeten?'
                );
                $optionsArray['options'][1] =
                    $ja_label_f.$inloophuis_mail.$ja_label_b;
                echo $this->Form->input('inloophuis', $optionsArray);

                // hulpverlening
                echo $this->Form->label('hulpverlening',
                    'Heeft u hulp nodig met regelzaken?'
                );
                $optionsArray['options'][1] =
                    $ja_label_f.$hulpverlening_mail.$ja_label_b;
                echo $this->Form->input('hulpverlening', $optionsArray);
            ?>
        </fieldset>

        <fieldset id="zrm" style="display: block;">
            <legend>Zelfredzaamheidmatrix</legend>
            <p>Vul onderstaande matrix in</p>
            <?= $this->element('zrm', ['model' => 'AwbzIntake', 'zrmData' => $zrmData]) ?>
        </fieldset>
    </fieldset>
    <?php echo $this->Form->end(__('Submit', true));?>
</div>

<div class="actions">
    <?php echo $this->element('klantbasic', array('data' => $klant)); ?>
    <p>
        <?php
            echo $this->Html->link('Toevoegen annuleren', array(
                'controller' => 'awbz',
                'action' => 'view',
                $klant['Klant']['id'],
            ));
        ?>
    </p>
</div>

<?php $this->Js->buffer('ondersteuning_toggle_addresses();'); ?>
