<div class="centeredContentWrap">
<div class="centered">

<?php echo $this->Form->create('Schorsing');?>

    <fieldset>
        <legend><?php __('Edit Schorsing'); ?></legend>

<?php
    echo $this->Form->input('id');
    echo $this->Date->input('Schorsing.datum_van', null, array('required' => true,
        'label' => 'Datum van', ));
    echo $this->Date->input('Schorsing.datum_tot', null, array('required' => true,
        'label' => 'Datum tot en met', ));
    echo $this->Form->hidden('locatie_id');
    echo $this->Form->hidden('klant_id');

    echo $form->input('Reden', array(
        'type'=>'select',
        'multiple'=>'checkbox',
        'options'=> $redenen,
        'label'=>'Met de volgende reden(en):',
    ));

    echo $form->input('overig_reden', array(
        'label' => '',
        'div' => array(
            'class' => 'overig_reden',
        ),
    ));

    echo '<div id="agressie" style="display: none;" >';

    $options=array(
        '0' => 'nee',
        '1' => 'ja',
    );
    $options_medewerker=Configure::read('options_medewerker');

    echo $form->label('nothing_at_all', __('Is de agressie gericht op een medewerker, stagair of vrijwilliger?', true));
    echo $this->Form->input('agressie', array('type' => 'radio', 'options' => $options, 'legend' => false, 'fieldset' => false));

    echo '<div id="betrokkenen" style="display: none">';
    echo '<b>'. __('Indien ja op wie is de agressie gericht?', true).'<font color="red">*</font></b>';
    echo "<fieldset>";
    echo "<table>";
    echo "<tr><td>".$this->Form->input('aggressie_doelwit', array('type' => 'text', 'legend' => false, 'fieldset' => false, 'label' => __('Betrokkene', true)))."</td>";
    echo "<td>".$this->Form->input('aggressie_tegen_medewerker', array('type' => 'radio', 'options' => $options_medewerker, 'legend' => false, 'fieldset' => false))."</td></tr>";
    echo "</table>";
    echo "</fieldset>";

    echo "<fieldset>";
    echo "<table>";
    echo "<tr><td>".$this->Form->input('aggressie_doelwit2', array('type' => 'text', 'legend' => false, 'fieldset' => false, 'label' => __('Betrokkene', true)))."</td>";
    echo "<td>".$this->Form->input('aggressie_tegen_medewerker2', array('type' => 'radio', 'options' => $options_medewerker, 'legend' => false, 'fieldset' => false))."</td></tr>";
    echo "</table>";
    echo "</fieldset>";

    echo "<fieldset>";
    echo "<table>";
    echo "<tr><td>".$this->Form->input('aggressie_doelwit3', array('type' => 'text', 'legend' => false, 'fieldset' => false, 'label' => __('Betrokkene', true)))."</td>";
    echo "<td>".$this->Form->input('aggressie_tegen_medewerker3', array('type' => 'radio', 'options' => $options_medewerker, 'legend' => false, 'fieldset' => false))."</td></tr>";
    echo "</table>";
    echo "</fieldset>";

    echo "<fieldset>";
    echo "<table>";
    echo "<tr><td>".$this->Form->input('aggressie_doelwit4', array('type' => 'text', 'legend' => false, 'fieldset' => false, 'label' => __('Betrokkene', true)))."</td>";
    echo "<td>".$this->Form->input('aggressie_tegen_medewerker4', array('type' => 'radio', 'options' => $options_medewerker, 'legend' => false, 'fieldset' => false))."</td></tr>";
    echo "</table>";
    echo "</fieldset>";

    echo $form->label('nothing_at_all', __('Is er aangifte gedaan?', true));
    echo $this->Form->input('aangifte', array('type' => 'radio', 'options' => $options, 'legend' => false, 'fieldset' => false));
    echo $form->label('nothing_at_all', __('Is er nazorg nodig?', true));
    echo $this->Form->input('nazorg', array('type' => 'radio', 'options' => $options, 'legend' => false, 'fieldset' => false));
    echo '</div>';
    echo $form->input('remark', array('label' => 'En als opmerking:', 'type' => 'textarea'));
    echo '</div>';

    echo $form->input('bijzonderheden', array('label' => 'Bijzonderheden:', 'type' => 'textarea'));
    echo $form->input('locatiehoofd', array('label' => 'Locatiehoofd:', 'type' => 'text'));

    echo '</div>';

?>
    </fieldset>
<?php
    echo $this->Form->end(__('Submit', true));
    $klant_id = $this->data['Schorsing']['klant_id'];
    $locatie_id = $this->data['Schorsing']['locatie_id'];
    echo '<p>'.$html->link('Annuleren',
          "/schorsingen/index/$klant_id/$locatie_id").'</p>';
?>

</div></div>
<?php

    $options = array(
            'violent_options' => $violent_options,
    );
    $this->Js->buffer('Ecd.schorsing.options = '.json_encode($options));

    $this->Js->buffer('Ecd.schorsing()');

?>
