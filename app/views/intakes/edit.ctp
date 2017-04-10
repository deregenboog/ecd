<div class="intakes form">
    <?php
        $today = date('Y-m-d');
        echo $this->Form->create('Intake');
    ?>
    <fieldset>
        <legend><?php __('Intake aanpassen'); ?></legend>
    <?php
        echo $this->Form->hidden('klant_id');
        echo $this->Form->hidden('id');
    ?>
        <fieldset>
            <legend>Algemeen</legend>
            <?php
                echo $this->Form->hidden('medewerker_id');

                echo 'Medewerker: '.$medewerkers[$this->data['Intake']['medewerker_id']];
                echo $date->input('Intake.datum_intake', null, array(
                    'label' => 'Datum van intake',
                    'required' => true,
                    'rangeLow' => (date('Y') - 20).date('-m-d'),
                    'rangeHigh' => $today,
                ));
            ?>
        </fieldset>

        <fieldset>
            <legend>Adresgegevens</legend>
            <?php
                echo $this->Form->input('postadres', array('label' => 'Adres'));
                echo $this->Form->input('postcode');
                echo $this->Form->input('woonplaats');
                echo $date->input('Intake.verblijf_in_NL_sinds', null, array(
                    'label' => 'Verblijft in Nederland sinds',
                    'rangeLow' => (date('Y') - 100).date('-m-d'),
                    'rangeHigh' => $today,
                    'selected' => '--', ));
                echo $date->input('Intake.verblijf_in_amsterdam_sinds', null, array(
                    'label' => 'Verblijft in Amsterdam sinds',
                    'rangeLow' => (date('Y') - 100).date('-m-d'),
                    'required' => true,
                    'rangeHigh' => $today,
                    'selected' => '--', ));
                echo $this->Form->input('verblijfstatus_id', array('empty' => ''));
                echo $this->Js->buffer('verblijfstatus_toggle("#IntakeVerblijfstatusId")');
                echo $this->Js->get('#IntakeVerblijfstatusId')->event('change',
                    'verblijfstatus_toggle(this);
                ');
                echo $this->Form->input('telefoonnummer');
            ?>
        </fieldset>

        <fieldset>
            <legend>Toegang</legend>
            <?php
                echo $this->Form->input('locatie2_id', array(
                    'label' => 'Intake locatie',
                    'empty' => '', ));
                echo $this->Form->input('toegang_inloophuis', array(
                    'label' => 'Toegang tot inloophuizen',
                    'type' => 'checkbox',
                ));
                echo $this->Form->input('locatie1_id', array(
                    'label' => 'Toegang gebruikersruimte',
                    'empty' => '', ));
            ?>
        </fieldset>

        <fieldset>
            <legend>Legitimatie</legend>
            <?php
                echo $this->Form->input('legitimatie_id', array('empty' => ''));
                echo $this->Form->input('legitimatie_nummer', array('label' => __('legitimatie_nummer', true)));
                echo $date->input('Intake.legitimatie_geldig_tot', null, array(
                    'label' => 'Legitimatie geldig tot',
                    'rangeLow' => (date('Y') - 10).'-01-01',
                    'rangeHigh' => (date('Y') + 30).'-01-01',
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
            ?>
        </fieldset>

        <fieldset>
            <legend>Inkomen en woonsituatie</legend>
            <?php
                echo $this->Form->input('Inkomen', array(
                    'type'=>'select',
                    'required' => true,
                    'multiple'=>'checkbox',
                    'options'=> $inkomens,
                    'label' => '<b>Inkomen (kies minimaal een optie)</b>',
                ));
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
                echo $this->Form->input('opmerking_andere_instanties', array('label' => __('opmerking_andere_instanties', true)));
                echo $this->Form->input('medische_achtergrond', array('label' => __('medische_achtergrond', true)));
            ?>
        </fieldset>

        <fieldset>
            <legend>Verwachtingen en plannen</legend>
            <?php
                echo $this->Form->input('verwachting_dienstaanbod', array('label' => __('verwachting_dienstaanbod', true)));
                echo $this->Form->input('toekomstplannen', array('label' => __('toekomstplannen', true)));
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
                );

                $ja_label_f = 'Ja <small style="display: none">(e-mail naar ';
                $ja_label_b = ')</small>';

            //informele_zorg
                echo $this->Form->label('informele_zorg',
                    'Zou je het leuk vinden om iedere week
                    met iemand samen iets te ondernemen?'
                );
                echo $form->hidden('informele_zorg_ignore', array(
                    'value' => $this->data['Intake']['informele_zorg'], ));
                $optionsArray['options'][1] =
                    $ja_label_f.$informele_zorg_mail.$ja_label_b;
                echo $this->Form->input('informele_zorg', $optionsArray);

            //dagbesteding
                echo $this->Form->label('dagbesteding',
                    'Zou je het leuk vinden om overdag iets te doen te hebben?'
                );
                echo $form->hidden('dagbesteding_ignore', array(
                    'value' => $this->data['Intake']['dagbesteding'], ));
                $optionsArray['options'][1] =
                    $ja_label_f.$dagbesteding_mail.$ja_label_b;
                echo $this->Form->input('dagbesteding', $optionsArray);

            //inloophuis
                echo $this->Form->label('inloophuis',
                    'Zou je een plek in de buurt willen hebben waar je iedere
                    dag koffie kan drinken en mensen kan ontmoeten?'
                );
                echo $form->hidden('inloophuis_ignore', array(
                    'value' => $this->data['Intake']['inloophuis'], ));
                $optionsArray['options'][1] =
                    $ja_label_f.$inloophuis_mail.$ja_label_b;
                echo $this->Form->input('inloophuis', $optionsArray);

            //hulpverlening
                echo $this->Form->label('hulpverlening',
                    'Heeft u hulp nodig met regelzaken?'
                );
                echo $form->hidden('hulpverlening_ignore', array(
                    'value' => $this->data['Intake']['hulpverlening'], ));
                $optionsArray['options'][1] =
                    $ja_label_f.$hulpverlening_mail.$ja_label_b;
                echo $this->Form->input('hulpverlening', $optionsArray);
            ?>
        </fieldset>
        <fieldset id="zrm" style="display : block;">
            <legend>Zelfredzaamheid matrix</legend>
            <p>
                Vul onderstaande matrix in
            </p>
            <?php
            echo $this->element('zrm', array(
                'model' => 'Intake',
                'zrm_data' => $zrm_data,
            ));
            ?>
        </fieldset>
    </fieldset>
    <?php echo $this->Form->end(__('Submit', true));?>
</div>

<div class="actions">
    <?= $this->element('klantbasic', array('data' => $klant)) ?>
    <?= $this->element('diensten', array( 'diensten' => $diensten, )) ?>
    <?= $this->element('intakes_summary', array('data' => $klant)) ?>
    <fieldset>
        <legend>Schorsingen</legend>
        <?php if (!empty($klant['Schorsing'])): ?>
            <ul>
                <?php foreach ($klant['Schorsing'] as $schorsing): ?>
                    <li>
                        <?php echo 'schorsinglink'; //TODO: schorsinglink invoegen ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Geen schorsingen bekend</p>
        <?php endif; ?>
    </fieldset>
</div>

<?php
    $this->Js->buffer('ondersteuning_toggle_addresses();');
    $this->Js->buffer('Ecd.intake();');
?>
