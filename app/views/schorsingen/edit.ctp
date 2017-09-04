<div class="centeredContentWrap">
    <div class="centered">
        <?= $this->Form->create('Schorsing') ?>
        <fieldset>
            <legend><?= __('Edit Schorsing', true) ?></legend>
            <?= $this->Form->input('id') ?>
            <?= $this->Form->input('Locatie', [
                'type' => 'select',
                'multiple' => 'checkbox',
                'label' => 'Locatie(s)',
                'size' => 20,
            ]) ?>

            <?= $this->Form->hidden('klant_id') ?>
            <?= $date->input('Schorsing.datum_van', null, ['required' => true, 'label' => 'Datum van']) ?>
            <?= $date->input('Schorsing.datum_tot', null, ['required' => true, 'label' => 'Datum tot en met']) ?>
            <?= $this->Form->input('Reden', [
                    'type'=>'select',
                    'multiple'=>'checkbox',
                    'options'=> $redenen,
                    'label'=>'Met de volgende reden(en):',
            ]) ?>
            <?= $this->Form->input('overig_reden', [
                    'label' => '',
                    'div' => ['class' => 'overig_reden'],
            ]) ?>

            <div id="agressie" style="display: none;" >
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
                    <br>
                    <?= $this->Form->label('nothing_at_all', __('Is er nazorg nodig?', true)); ?>
                    <?= $this->Form->input('nazorg', array('type' => 'radio', 'options' => $options, 'legend' => false, 'fieldset' => false)) ?>
                </div>
            </div>
            <?= $this->Form->input('remark', array('label' => 'En als opmerking:', 'type' => 'textarea')) ?>
            <?= $this->Form->input('locatiehoofd', array('label' => 'Locatiehoofd:', 'type' => 'text')) ?>
            <?= $this->Form->input('bijzonderheden', array('label' => 'Bijzonderheden:', 'type' => 'textarea')) ?>
        </fieldset>
    <?= $this->Form->end(__('Submit', true)) ?>
    <p>
        <?php $klant_id = $this->data['Schorsing']['klant_id']; ?>
        <?= $html->link('Annuleren', "/schorsingen/index/{$klant_id}") ?>
    </p>
    </div>
</div>

<?php
    $options = ['violent_options' => $violent_options];
    $this->Js->buffer('Ecd.schorsing.options = '.json_encode($options));
    $this->Js->buffer('Ecd.schorsing()');
?>
