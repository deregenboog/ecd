<div class="centeredContentWrap">
    <?php
        $url_params = $klant_id;
        if (isset($locatie_id)) {
            $url_params .= "/$locatie_id";
        }
    ?>
    <?= $form->create('Schorsing', array(
            'url' =>"/schorsingen/add/$url_params",
            'class' => 'centered',
    )) ?>
    <fieldset>
        <legend>Nieuwe schorsing</legend>
        <?php if (isset($locatie_id)): ?>
            <?= $form->hidden('locatie_id', array('value' => $locatie['Locatie']['id'])) ?>
        <?php elseif (isset($locaties)): ?>
            <?= $this->Form->input('locatie_id', ['empty' => '']) ?>
        <?php endif; ?>
        <?= $form->hidden('klant_id', array('value' => $klant['Klant']['id'])) ?>
        <?= $form->hidden('datum_van', array('value' => date('Y-m-d'))) ?>

        <?php
            if (!empty($klant['Klant']['tussenvoegsel'])) {
                $klant['Klant']['tussenvoegsel'] = ' '.$klant['Klant']['tussenvoegsel'];
            }
            $name = $klant['Klant']['voornaam'].' '.$klant['Klant']['roepnaam'].
            $klant['Klant']['tussenvoegsel'].' '.$klant['Klant']['achternaam'];
        ?>
        <?php
            $dateInput = $date->input('Schorsing.datum_tot', date('Y-m-d'), array(
                'class' => 'date',
                'rangeLow' => date('Y-m-d'),
                'rangeHigh' => (date('Y') + 1).date('-m-d'), ));
            if (isset($locatie)) {
                $location_name_for_label = ' van '.$locatie['Locatie']['naam'];
            } else {
                $location_name_for_label = '';
            }
        ?>
        <div class="input select">
            <?= $form->label('nothing_at_all', $name.' schorsen'.$location_name_for_label.' voor:') ?>
            <div class="checkbox">
                <input id="SchorsingDays0" type="radio" name="data[Schorsing][days]" value="0"> </input>
                <?= $form->label('Days0', 'de rest van de dag (1 dag)') ?>
            </div>
            <div class="checkbox">
                <input id="SchorsingDays1" type="radio" name="data[Schorsing][days]" value="1"> </input>
                <?= $form->label('Days1', 'vandaag en morgen (2 dagen)') ?>
            </div>
            <div class="checkbox">
                <input id="SchorsingDays2" type="radio" name="data[Schorsing][days]" value="2"> </input>
                <?= $form->label('Days2', '3 dagen') ?>
            </div>
            <div class="checkbox">
                <input id="SchorsingDays3" type="radio" name="data[Schorsing][days]" value="4"> </input>
                <?= $form->label('Days3', '5 dagen') ?>
            </div>
            <div class="checkbox">
                <input id="SchorsingDays4" type="radio" name="data[Schorsing][days]" value="-1"> </input>
                <?= $form->label('Days4', 'schorsing tot en met ') ?>
                <br/>
                <?= $dateInput ?>
            </div>
            <?= $js->get('.date')->event('change', 'document.getElementById(\'SchorsingDays4\').checked = true') ?>
            <?= $js->writeBuffer() ?>
            <?= $form->input('Reden', array(
                'type'=>'select',
                'multiple'=>'checkbox',
                'options'=> $redenen,
                'label'=>'Met de volgende reden(en):', ))
            ?>
            <?= $form->input('overig_reden', array(
                    'label' => '',
                    'div' => array(
                        'class' => 'overig_reden',
                    ),
                ))
            ?>
            <div id="agressie" style="display: none;" >
                <?php
                    $options = array('0' => 'nee', '1' => 'ja');
                    $options_medewerker = Configure::read('options_medewerker');
                ?>
                <?= $form->label('nothing_at_all', __('Is de agressie gericht op een medewerker, stagair of vrijwilliger?')) ?>
                <?= $this->Form->input('agressie', array('type' => 'radio', 'options' => $options, 'legend' => false, 'fieldset' => false)) ?>
                <div id="betrokkenen" style="display: none">
                    <b>
                        <?= __('Indien ja op wie is de agressie gericht?') ?>
                        <font color="red">*</font>
                    </b>
                    <fieldset>
                        <table>
                            <tr>
                                <td>
                                    <?= $this->Form->input('aggressie_doelwit', array('type' => 'text', 'legend' => false, 'fieldset' => false, 'label' => __('Betrokkene'))) ?>
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
                                    <?= $this->Form->input('aggressie_doelwit2', array('type' => 'text', 'legend' => false, 'fieldset' => false, 'label' => __('Betrokkene'))) ?>
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
                                    <?= $this->Form->input('aggressie_doelwit3', array('type' => 'text', 'legend' => false, 'fieldset' => false, 'label' => __('Betrokkene'))) ?>
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
                                    <?= $this->Form->input('aggressie_doelwit4', array('type' => 'text', 'legend' => false, 'fieldset' => false, 'label' => __('Betrokkene'))) ?>
                                </td>
                                <td>
                                    <?= $this->Form->input('aggressie_tegen_medewerker4', array('type' => 'radio', 'options' => $options_medewerker, 'legend' => false, 'fieldset' => false)) ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <?= $form->label('nothing_at_all', __('Is er aangifte gedaan?')) ?>
                    <?= $this->Form->input('aangifte', array('type' => 'radio', 'options' => $options, 'legend' => false, 'fieldset' => false)) ?>
                    <?= $form->label('nothing_at_all', __('Is er nazorg nodig?')) ?>
                    <?= $this->Form->input('nazorg', array('type' => 'radio', 'options' => $options, 'legend' => false, 'fieldset' => false)) ?>
                </div>
            </div>
        </div>
        <?= $form->input('remark', array('label' => 'En als opmerking:', 'type' => 'textarea')) ?>
        <?= $form->input('locatiehoofd', array('label' => 'Locatiehoofd:', 'type' => 'text')) ?>
        <?= $form->input('bijzonderheden', array('label' => 'Bijzonderheden:', 'type' => 'textarea')) ?>
    </fieldset>
    <?= $form->end(__('Submit', true)) ?>
    <p>
        <?= $html->link('Annuleren', "/schorsingen/index/$url_params") ?>
    </p>
</div>

<?php
    $options = array('violent_options' => $violent_options);
     $this->Js->buffer('Ecd.schorsing.options = '.json_encode($options));
     $this->Js->buffer('Ecd.schorsing()');
?>
