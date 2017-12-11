<?php $today = date('Y-m-d'); ?>

<div class="actions">
    <?= $this->element('klantbasic', ['data' => $klant]); ?>
    <?= $this->element('diensten', ['diensten' => $diensten]); ?>
    <?= $this->element('hi5_traject', ['data' => $klant]); ?>
    <?= $this->element('hi5_intake', ['viewElementOptions' => $viewElementOptions, 'data' => $klant]); ?>
    <?= $this->element('hi5_evaluatie', ['viewElementOptions' => $viewElementOptions, 'data' => $klant]); ?>
    <?php
        echo $this->element('hi5_contactjournal', [
            'viewElementOptions' => $viewElementOptions,
            'klant_id' => $klant['Klant']['id'],
            'countContactjournalTB' => $countContactjournalTB,
            'countContactjournalWB' => $countContactjournalWB,
        ]);
    ?>
</div>

<div class="intakes view">
    <?= $this->Form->create('Hi5Intake', [
        'url' => [
            'controller' => $this->name,
            'action' => 'add_intake',
            $klant['Klant']['id'],
        ],
    ]) ?>
    <fieldset>
        <legend><?php __('HI5 Intake toevoegen'); ?></legend>
        <?php echo $form->hidden('klant_id', ['value' => $klant['Klant']['id']]); ?>

        <fieldset>
            <legend>Algemeen</legend>
            <?php
                echo $this->Form->input('medewerker_id', [
                    'label' => 'Naam intaker',
                    'default' => $intaker_id,
                ]);
                echo $date->input('Hi5Intake.datum_intake', $today, [
                    'label' => 'Datum van intake',
                    'rangeLow' => (date('Y') - 1).date('-m-d'),
                    'required' => true,
                    'rangeHigh' => $today, ]
                );
            ?>
        </fieldset>

        <fieldset>
            <legend>Adresgegevens</legend>
            <?php
                echo $this->Form->input('postadres', ['label' => 'Adres']);
                echo $this->Form->input('postcode');
                echo $this->Form->input('woonplaats');
                echo $date->input('Hi5Intake.verblijf_in_NL_sinds', null, [
                    'label' => 'Verblijft in Nederland sinds',
                    'rangeLow' => (date('Y') - 100).date('-m-d'),
                    'rangeHigh' => $today,
                ]);
                echo $date->input('Hi5Intake.verblijf_in_amsterdam_sinds', null, [
                    'label' => 'Verblijft in Amsterdam sinds',
                    'rangeLow' => (date('Y') - 100).date('-m-d'),
                    'required' => true,
                    'rangeHigh' => $today,
                ]);
                echo $this->Form->input('verblijfstatus_id', ['empty' => '']);
            ?>
        </fieldset>

        <fieldset>
            <legend>Locatiekeuze</legend>
            <?php
                echo $this->Form->input('locatie1_id', [
                    'label' => 'Eerste locatiekeuze',
                    'empty' => '',
                ]);
                echo $this->Form->input('locatie2_id', [
                    'label' => 'Tweede locatiekeuze',
                    'empty' => '',
                ]);
                echo $this->Form->input('locatie3_id', [
                    'label' => 'Derde locatiekeuze',
                    'empty' => '',
                ]);
                echo $this->Form->input('werklocatie_id', [
                    'label' => 'Werklocatie',
                    'empty' => '',
                ]);
                echo $this->Form->input('mag_gebruiken', [
                    'label' => __('mag_gebruiken', true),
                ]);
            ?>
        </fieldset>

        <fieldset>
            <legend>Legitimatie</legend>
            <?php
                echo $this->Form->input('legitimatie_id', ['empty' => '']);
                echo $this->Form->input('legitimatie_nummer', ['label' => 'Legitimatienummer']);
                echo $date->input('Hi5Intake.legitimatie_geldig_tot', null, [
                    'label' => 'Legitimatie geldig tot',
                    'rangeLow' => (date('Y') - 10).'-01-01',
                    'rangeHigh' => (date('Y') + 10).'-01-01',
                    'selected' => '--',
                ]);
            ?>
        </fieldset>

        <fieldset>
            <legend>Verslaving</legend>
            <h3>Problematiek</h3>
            <?php
                echo $this->Form->input('Verslaving', [
                    'type' => 'select',
                    'multiple' => 'checkbox',
                    'options' => $verslavingen,
                    'label' => 'Verslavingen', ]);
                echo $this->Form->input('verslaving_overig', ['label' => __('verslaving_overig', true)]);
            ?>
        </fieldset>

        <fieldset>
            <legend>Inkomen en woonsituatie</legend>
            <?php
                echo $this->Form->input('Inkomen', [
                    'type' => 'select',
                    'multiple' => 'checkbox',
                    'required' => true,
                    'options' => $inkomens,
                    'label' => 'Inkomen', ]);
                echo $this->Form->input('inkomen_overig', ['label' => __('inkomen_overig', true)]);
                echo $this->Form->input('woonsituatie_id', ['label' => 'Wat is de woonsituatie?', 'empty' => '']);
            ?>
        </fieldset>

        <fieldset>
            <legend>Wensen en verwachtingen</legend>
            <div style="display:table-cell">
                Waar liggen uw interesses?
                <?php
                    // This is automatically selected anyway
                    // because it's what we post
                    // BUT! We must populate this in the edit form !
                    echo '<div>';
                    echo $this->Form->input('Bedrijfsector1', [
                        'type' => 'select',
                        'required' => false,
                        'label' => 'Bedrijfssector',
                        'options' => $bedrijfsectors,
                        'empty' => '',
                    ]);
                    echo '</div>';
                    echo '<div>';
                    echo $this->Form->input('Bedrijfsector2', [
                        'type' => 'select',
                        'required' => false,
                        'label' => 'Bedrijfssector2',
                        'options' => $bedrijfsectors,
                        'empty' => '', 'default' => '',
                    ]);
                    echo '</div>';
                ?>
            </div>
            <div style="display:table-cell">
                Wat zou u graag willen doen?
                <?php // we need to find out what did he select and show that up right now. ?>
                <div style="display:none" id="BedrijfItems1">
                    <?php
                        // This hidden field is used to store
                        // initial value of the element.
                        // There's not value when creating an intake, but there's
                        // one when the form is rendered again after some
                        // validation error.
                        echo $this->Form->hidden('bedrijfitem_1_id');

                        // We render an empty input, and we will populate the
                        // dropdown later with javascript instantiateHi5Intake() and
                        // bedrijfSectorChange(). That's why we will use
                        // the default value in the hidden field.

                        echo $this->Form->input('bedrijfitem_1_id', [
                            'type' => 'select',
                            'required' => false,
                            'label' => 'Project',
                            'empty' => '',
                            'default' => '',
                        ]);
                    ?>
                </div>
                <div style="display:none" id="BedrijfItems2">
                    <?php
                        echo $this->Form->hidden('bedrijfitem_2_id');
                        echo $this->Form->input('bedrijfitem_2_id', [
                            'type' => 'select',
                            'required' => false,
                            'label' => 'Project',
                            'empty' => '',
                            'default' => '',
                        ]);
                    ?>
                </div>
                <div style="display:none;" id="bedrijfItemLists">
                    <?php
                        foreach ($bedrijfItems as $key => $bedrijfItemList) {
                            echo $this->Form->input('BedrijfItems'.$key, [
                                'type' => 'select',
                                'label' => $key,
                                'options' => $bedrijfItemList,
                                'empty' => '',
                                'default' => '',
                            ]);
                        }
                    ?>
                </div>
            </div>
        </fieldset>

        <fieldset id="survey">
            <?php
                $question_cnt = 0;
                $values = $this->Form->value('Hi5Answer.Hi5Answer');
                $selected = [];
                if (!empty($values)) {
                    foreach ($values as $v) {
                        $key = $v['hi5_answer_id'];
                        $selected[$key] = true;
                        if (isset($v['hi5_answer_text'])) {
                            $selected[$key] = $v['hi5_answer_text'];
                        }
                    }
                }

                $lastCategory = false;
                foreach ($hi5Questions as $questionDetails) {
                    //category changed -> add the category as header
                    if ($questionDetails['category'] != $lastCategory) {
                        printf('<h2>%s</h2>', $questionDetails['category']);
                        $lastCategory = $questionDetails['category'];
                    }
                    echo $questionDetails['question'].'<br/>';
                    if (isset($questionDetails['answers'])) {
                        foreach ($questionDetails['answers'] as $questionType => $questionTypeDetails) {
                            switch ($questionType) {
                                case 'checkbox':
                                    foreach ($questionTypeDetails as $ans_id => $answer) {
                                        echo $this->Form->input('Hi5Answer.'.$ans_id, [
                                           'type' => 'checkbox',
                                           'label' => $answer,
                                           'hiddenField' => false,
                                           'checked' => (isset($selected[$ans_id]) ? 'checked' : false),
                                       ]);
                                    }
                                    break;
                                case 'dropdown':
                                    $ans_keys = array_keys($questionTypeDetails);
                                    // Take the first key as answer_id:
                                    $ans_id = $ans_keys[0];
                                    $selected_dd = 0;
                                    $defaultSelected = array_values(array_intersect(array_keys($selected), $ans_keys));
                                    echo $this->Form->input('Hi5Answer_aux.'.$ans_id, [
                                        'type' => 'select',
                                        'multiple' => false,
                                        'options' => $questionTypeDetails,
                                        'label' => false,
                                        'selected' => (isset($defaultSelected) ? $defaultSelected : $ans_id),
                                    ]);
                            ?>
                            <input type="hidden" id="<?= 'Hi5Answer_hidden_'.$ans_id ?>" />
                            <?php
                                    break;
                                case 'open':
                                    $dummy = array_keys($questionTypeDetails);
                                    $ans_id = $dummy[0];
                                    // echo   $this->Form->hidden('Hi5Answer.'.$ans_id.'.hi5_answer_id', array('value' => $ans_id)	);
                                    echo $this->Form->input('Hi5Answer.'.$ans_id.'.hi5_answer_text', [
                                        'label' => false,
                                        'value' => isset($selected[$ans_id]) ? $selected[$ans_id] : '',
                                        'type' => 'textarea',
                                    ]);
                                    break;
                            }
                            ++$question_cnt;
                        }
                    }
                }
            ?>
        </fieldset>

        <fieldset id="zrm">
            <legend>Zelfredzaamheidmatrix</legend>
            <p>Vul onderstaande matrix in</p>
            <?= $this->element('zrm', ['model' => 'Hi5Intake', 'zrmData' => $zrmData]) ?>
        </fieldset>
    </fieldset>

    <script>instantiateHi5Intake();</script>
    <?= $this->Form->end(__('Submit', true)) ?>
</div>
