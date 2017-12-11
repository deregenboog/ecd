<div class="intakes view">
    <?php $today = date('Y-m-d'); ?>
    <?= $this->Form->create('Intake', ['url' => ['controller' => $this->name, 'action' => 'add', $klant['Klant']['id']]]); ?>
    <fieldset>
        <legend><?php __('Intake toevoegen'); ?></legend>
        <?= $this->Form->hidden('klant_id', ['value' => $klant['Klant']['id']]); ?>
        <fieldset>
            <legend>Algemeen</legend>
            <?= $this->Form->hidden('medewerker_id', ['default' => $intaker_id]); ?>
            <?= 'Medewerker: '.$medewerkers[$intaker_id]; ?>
            <?php //echo $this->Form->input('medewerker_id', array('label' => 'Naam intaker', 'default' => $intaker_id ));?>
            <?= $this->Date->input('Intake.datum_intake', $datum_intake, [
                'label' => 'Datum van intake',
                'required' => true,
                'rangeLow' => (date('Y') - 1).date('-m-d'),
                'rangeHigh' => $today,
            ]); ?>
        </fieldset>

        <fieldset>
            <legend>Adresgegevens</legend>
            <?= $this->Form->input('postadres', ['label' => 'Adres']); ?>
            <?= $this->Form->input('postcode'); ?>
            <?= $this->Form->input('woonplaats'); ?>
            <?= $this->Date->input('Intake.verblijf_in_NL_sinds', null, [
                'label' => 'Verblijft in Nederland sinds',
                'rangeLow' => (date('Y') - 100).date('-m-d'),
                'rangeHigh' => $today,
                'selected' => '--',
            ]); ?>
            <?= $this->Date->input('Intake.verblijf_in_amsterdam_sinds', null, [
                'label' => 'Verblijft in Amsterdam sinds',
                'rangeLow' => (date('Y') - 100).date('-m-d'),
                'required' => true,
                'rangeHigh' => $today,
                'selected' => '--',
            ]); ?>
            <?= $this->Form->input('verblijfstatus_id', ['empty' => '']); ?>
            <?= $this->Form->input('telefoonnummer'); ?>
        </fieldset>

        <fieldset>
            <legend>Toegang</legend>
            <?= $this->Form->input('locatie2_id', [
                'label' => 'Intake locatie',
                'empty' => '',
            ]); ?>
            <?= $this->Form->input('toegang_inloophuis', [
                'label' => 'Toegang tot inloophuizen',
                'type' => 'checkbox',
            ]); ?>
            <br/><br/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            N.B. Als een klant niet rechthebbend is, heeft hij/zij de eerste zes maanden alleen
            <br/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            toegang tot AMOC. Na zes maanden mag de klant ook naar de andere inloophuizen.
            <br/><br/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Om voor AMOC een periode korter dan zes maanden te hanteren kan hieronder een
            <br/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            einddatum ingevuld worden.
            <br/><br/>
            <?= $this->Date->input('Intake.amoc_toegang_tot', null, [
                'label' => 'Einddatum toegang AMOC',
                'rangeLow' => (new \DateTime('today'))->format('Y-m-d'),
                'rangeHigh' => (new \DateTime('+6 months'))->format('Y-m-d'),
            ]); ?>
            <br/>
            <?= $this->Form->input('locatie1_id', [
                'label' => 'Toegang gebruikersruimte',
                'empty' => '',
            ]); ?>
        </fieldset>

        <fieldset>
            <legend>Legitimatie</legend>
            <?= $this->Form->input('legitimatie_id', ['empty' => '']); ?>
            <?= $this->Form->input('legitimatie_nummer', ['label' => 'Legitimatienummer']); ?>
            <?= $this->Date->input('Intake.legitimatie_geldig_tot', null, [
                'label' => 'Legitimatie geldig tot',
                'rangeLow' => (date('Y') - 10).'-01-01',
                'rangeHigh' => (date('Y') + 30).'-01-01',
                'selected' => '--',
            ]); ?>
        </fieldset>

        <fieldset>
            <legend>Verslaving</legend>
            <h3>Problematiek</h3>
            <?= $this->Form->input('Verslaving', [
                'type' => 'select',
                'multiple' => 'checkbox',
                'options' => $verslavingen,
                'label' => 'Verslavingen',
            ]); ?>
            <?= $this->Form->input('verslaving_overig', ['label' => __('verslaving_overig', true)]); ?>
        </fieldset>

        <fieldset>
            <legend>Inkomen en woonsituatie</legend>
            <?= $this->Form->input('Inkomen', [
                'type' => 'select',
                'required' => true,
                'multiple' => 'checkbox',
                'options' => $inkomens,
                'label' => '<b>Inkomen (kies minimaal een optie)</b>',
            ]); ?>
            <?= $this->Form->input('inkomen_overig', ['label' => __('inkomen_overig', true)]); ?>
            <?= $this->Form->input('woonsituatie_id', ['label' => 'Wat is de woonsituatie?', 'empty' => '']); ?>
        </fieldset>

        <fieldset>
            <legend>Overige hulpverlening</legend>
            <?= $this->Form->input('Instantie', [
                'type' => 'select',
                'multiple' => 'checkbox',
                'options' => $instanties,
                'label' => 'Heeft de client contact met andere instanties?',
            ]); ?>
            <?= $this->Form->input('opmerking_andere_instanties', ['label' => 'Opmerkingen van andere instanties']); ?>
            <?= $this->Form->input('medische_achtergrond', ['label' => 'Relevante medische achtergrond']); ?>
        </fieldset>

        <fieldset>
            <legend>Verwachtingen en plannen</legend>
            <?= $this->Form->input('verwachting_dienstaanbod', ['label' => 'Wat verwacht de client van het dienstaanbod?']); ?>
            <?= $this->Form->input('toekomstplannen', ['label' => 'Wat zijn de toekomstplannen van de client?']); ?>
        </fieldset>

        <fieldset>
            <legend>Indruk</legend>
            <?= $this->Form->input('indruk', ['label' => __('label_indruk', true)]); ?>
            <?= $this->Form->label('doelgroep', __('label_doelgroep', true)); ?>
            <?= $this->Form->input('doelgroep', [
                'type' => 'radio',
                'options' => [1 => 'Ja', 0 => 'Nee'],
                'legend' => false,
            ]); ?>
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
                $optionsArray = [
                    'options' => [0 => 'Nee', 1 => 'Ja'],
                    'type' => 'radio',
                    'label' => ' ',
                    'legend' => false,
                    'value' => 0,
                ];
                $ja_label_f = 'Ja <small style="display: none">(e-mail naar ';
                $ja_label_b = ')</small>';

                // informele_zorg
                echo $this->Form->label('informele_zorg', 'Zou je het leuk vinden om iedere week met iemand samen iets te ondernemen?');
                $optionsArray['options'][1] = $ja_label_f.$informele_zorg_mail.$ja_label_b;
                echo $this->Form->input('informele_zorg', $optionsArray);

                // dagbesteding
                echo $this->Form->label('dagbesteding', 'Zou je het leuk vinden om overdag iets te doen te hebben?');
                $optionsArray['options'][1] = $ja_label_f.$dagbesteding_mail.$ja_label_b;
                echo $this->Form->input('dagbesteding', $optionsArray);

                // inloophuis
                echo $this->Form->label('inloophuis', 'Zou je een plek in de buurt willen hebben waar je iedere dag koffie kan drinken en mensen kan ontmoeten?');
                $optionsArray['options'][1] = $ja_label_f.$inloophuis_mail.$ja_label_b;
                echo $this->Form->input('inloophuis', $optionsArray);

                // hulpverlening
                echo $this->Form->label('hulpverlening', 'Heeft u hulp nodig met regelzaken?');
                $optionsArray['options'][1] = $ja_label_f.$hulpverlening_mail.$ja_label_b;
                echo $this->Form->input('hulpverlening', $optionsArray);
            ?>
        </fieldset>

        <fieldset id="zrm" style="display : block;">
            <legend>Zelfredzaamheidmatrix</legend>
            <p>Vul onderstaande matrix in</p>
            <?= $this->element('zrm', ['model' => 'Intake', 'zrmData' => $zrmData]); ?>
        </fieldset>
    </fieldset>
    <?php echo $this->Form->end(__('Submit', true)); ?>
</div>

<div class="actions">
    <?= $this->element('klantbasic', ['data' => $klant]); ?>
    <?= $this->element('diensten', ['diensten' => $diensten]); ?>
    <?= $this->Html->link('Toevoegen annuleren', ['controller' => 'klanten', 'action' => 'view', $klant['Klant']['id']]); ?>
</div>

<?php
    $this->Js->buffer('ondersteuning_toggle_addresses();');
    $this->Js->buffer('Ecd.intake();');
?>
