<div class="centeredContentWrap">
    <?php
        $url_params = $klant_id;
        if (isset($locatie_id)) {
            $url_params .= "/$locatie_id";
        }
    ?>
    <?= $this->Form->create('Schorsing', [
        'url' =>"/schorsingen/add/$url_params",
        'class' => 'centered',
    ]) ?>
    <fieldset>
        <legend>Nieuwe schorsing</legend>
        <?= $this->Form->input('Locatie', [
            'type' => 'select',
            'multiple' => 'checkbox',
            'label' => 'Locatie(s)',
            'size' => 20,
        ]) ?>

        <?= $this->Form->hidden('klant_id', ['value' => $klant['Klant']['id']]) ?>
        <?= $this->Form->hidden('datum_van', ['value' => date('Y-m-d')]) ?>

        <?php
            $name = preg_replace('/\s+/', ' ', implode(' ', [
                $klant['Klant']['voornaam'],
                $klant['Klant']['roepnaam'],
                $klant['Klant']['tussenvoegsel'],
                $klant['Klant']['achternaam'],
            ]));

            $dateInput = $date->input('Schorsing.datum_tot', date('Y-m-d'), [
                'class' => 'date',
                'rangeLow' => date('Y-m-d'),
                'rangeHigh' => (date('Y') + 1).date('-m-d'),
            ]);

            if (isset($locatie)) {
                $location_name_for_label = ' van '.$locatie['Locatie']['naam'];
            } else {
                $location_name_for_label = '';
            }
        ?>

        <div class="input select">
            <?= $this->Form->label('nothing_at_all', $name.' schorsen'.$location_name_for_label.' voor:') ?>
            <div class="checkbox">
                <input id="SchorsingDays0" type="radio" name="data[Schorsing][days]" value="0"> </input>
                <?= $this->Form->label('Days0', 'de rest van de dag (1 dag)') ?>
            </div>
            <div class="checkbox">
                <input id="SchorsingDays1" type="radio" name="data[Schorsing][days]" value="1"> </input>
                <?= $this->Form->label('Days1', 'vandaag en morgen (2 dagen)') ?>
            </div>
            <div class="checkbox">
                <input id="SchorsingDays2" type="radio" name="data[Schorsing][days]" value="2"> </input>
                <?= $this->Form->label('Days2', '3 dagen') ?>
            </div>
            <div class="checkbox">
                <input id="SchorsingDays3" type="radio" name="data[Schorsing][days]" value="4"> </input>
                <?= $this->Form->label('Days3', '5 dagen') ?>
            </div>
            <div class="checkbox">
                <input id="SchorsingDays4" type="radio" name="data[Schorsing][days]" value="-1"> </input>
                <?= $this->Form->label('Days4', 'schorsing tot en met ') ?>
                <br/>
                <?= $dateInput ?>
            </div>
            <?= $js->get('.date')->event('change', "document.getElementById('SchorsingDays4').checked = true") ?>
            <?= $js->writeBuffer() ?>
            <?= $this->Form->input('Reden', [
                'type' => 'select',
                'multiple' => 'checkbox',
                'options' => $redenen,
                'label' => 'Met de volgende reden(en):',
            ]) ?>
            <?= $this->Form->input('overig_reden', [
                'label' => '',
                'div' => ['class' => 'overig_reden'],
            ]) ?>

            <div id="agressie" style="display: none;">
                <?php $options = ['0' => 'nee', '1' => 'ja']; ?>
                <?php $options_medewerker = Configure::read('options_medewerker'); ?>
                <?= $this->Form->label('nothing_at_all', __('Is de agressie gericht op een medewerker, stagair of vrijwilliger?', true)) ?>
                <?= $this->Form->input('agressie', array('type' => 'radio', 'options' => $options, 'legend' => false, 'fieldset' => false)) ?>

                <div id="betrokkenen" style="display: none">
                    <b><?= __('Indien ja, op wie is de agressie gericht?', true) ?><font color="red">*</font></b>
                    <fieldset>
                    <table>
                        <tr>
                            <td>
                                <?= $this->Form->input('aggressie_doelwit', array('type' => 'text', 'legend' => false, 'fieldset' => false, 'label' => __('Betrokkene', true))) ?>
                            </td>
                            <td>
                                <?= $this->Form->input('aggressie_tegen_medewerker', array('type' => 'radio', 'options' => $options_medewerker, 'legend' => false, 'fieldset' => false)) ?>
                            </td>
                            </tr>
                        </table>
                    </fieldset>

                    <fieldset>
                        <table>
                            <tr>
                                <td>
                                    <?= $this->Form->input('aggressie_doelwit2', array('type' => 'text', 'legend' => false, 'fieldset' => false, 'label' => __('Betrokkene', true))) ?>
                                </td>
                                <td>
                                    <?= $this->Form->input('aggressie_tegen_medewerker2', array('type' => 'radio', 'options' => $options_medewerker, 'legend' => false, 'fieldset' => false)) ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <fieldset>
                        <table>
                            <tr>
                                <td>
                                    <?= $this->Form->input('aggressie_doelwit3', array('type' => 'text', 'legend' => false, 'fieldset' => false, 'label' => __('Betrokkene', true))) ?>
                                </td>
                                <td>
                                    <?= $this->Form->input('aggressie_tegen_medewerker3', array('type' => 'radio', 'options' => $options_medewerker, 'legend' => false, 'fieldset' => false)) ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <fieldset>
                        <table>
                            <tr>
                                <td>
                                    <?= $this->Form->input('aggressie_doelwit4', array('type' => 'text', 'legend' => false, 'fieldset' => false, 'label' => __('Betrokkene', true))) ?>
                                </td>
                                <td>
                                    <?= $this->Form->input('aggressie_tegen_medewerker4', array('type' => 'radio', 'options' => $options_medewerker, 'legend' => false, 'fieldset' => false)) ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <?= $this->Form->label('nothing_at_all', __('Is er aangifte gedaan?', true)) ?>
                    <?= $this->Form->input('aangifte', array('type' => 'radio', 'options' => $options, 'legend' => false, 'fieldset' => false)) ?>
                    <?= $this->Form->label('nothing_at_all', __('Is er nazorg nodig?', true)) ?>
                    <?= $this->Form->input('nazorg', array('type' => 'radio', 'options' => $options, 'legend' => false, 'fieldset' => false)) ?>
                </div>
            </div>
            <?= $this->Form->input('remark', array('label' => 'En als opmerking:', 'type' => 'textarea')) ?>
            <?= $this->Form->input('locatiehoofd', array('label' => 'Locatiehoofd:', 'type' => 'text')) ?>
            <?= $this->Form->input('bijzonderheden', array('label' => 'Bijzonderheden:', 'type' => 'textarea')) ?>
        </div>
    </fieldset>
    <?= $this->Form->end(__('Submit', true)) ?>
    <p>
        <?= $html->link('Annuleren', "/schorsingen/index/$url_params") ?>
    </p>
</div>

<?php
    $options = ['violent_options' => $violent_options];
    $this->Js->buffer('Ecd.schorsing.options = '.json_encode($options));
    $this->Js->buffer('Ecd.schorsing()');
?>
