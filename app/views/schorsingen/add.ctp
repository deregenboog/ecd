<div class="centeredContentWrap">
<?php
    $url_params = $klant_id;
    if (isset($locatie_id)) {
        $url_params .= "/$locatie_id";
    }
    echo $form->create('Schorsing', array(
        'url' =>"/schorsingen/add/$url_params",
        'class' => 'centered',
    ));
?>
    <fieldset>
        <legend>Nieuwe schorsing</legend>
<?php
    if (isset($locatie_id)) {
        echo $form->hidden('locatie_id',
            array('value' => $locatie['Locatie']['id']));
    } elseif (isset($locaties)) {
        echo $this->Form->input('locatie_id');
    }
    echo $form->hidden('klant_id', array('value' => $klant['Klant']['id']));
    echo $form->hidden('datum_van', array('value' => date('Y-m-d')));

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

    echo '<div class="input select">';
    echo $form->label('nothing_at_all', $name.' schorsen'.$location_name_for_label.' voor:');
    echo '<div class="checkbox">';
    echo '<input id="SchorsingDays0" type="radio" name="data[Schorsing][days]" value="0"> </input>';
    echo $form->label('Days0', 'de rest van de dag (1 dag)').'</div>';
    echo '<div class="checkbox">';
    echo '<input id="SchorsingDays1" type="radio" name="data[Schorsing][days]" value="1"> </input>';
    echo $form->label('Days1', 'vandaag en morgen (2 dagen)').'</div>';
    echo '<div class="checkbox">';
    echo '<input id="SchorsingDays2" type="radio" name="data[Schorsing][days]" value="2"> </input>';
    echo $form->label('Days2', '3 dagen').'</div>';
    echo '<div class="checkbox">';
    echo '<input id="SchorsingDays3" type="radio" name="data[Schorsing][days]" value="4"> </input>';
    echo $form->label('Days3', '5 dagen').'</div>';
    echo '<div class="checkbox">';
    echo '<input id="SchorsingDays4" type="radio" name="data[Schorsing][days]" value="-1"> </input>';
    echo $form->label('Days4', 'schorsing tot en met ');
    echo '<br/>'.$dateInput;
    echo '</div>';
    echo $js->get('.date')->event('change',
        'document.getElementById(\'SchorsingDays4\').checked = true'
    );
    echo $js->writeBuffer();
    echo $form->input('Reden', array(
        'type'=>'select',
        'multiple'=>'checkbox',
        'options'=> $redenen,
        'label'=>'Met de volgende reden(en):', ));
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
    echo '</div>';

    echo "</div>";
    echo $form->input('remark', array('label' => 'En als opmerking:', 'type' => 'textarea'));
    echo $form->input('locatiehoofd', array('label' => 'Locatiehoofd:', 'type' => 'text'));
    echo $form->input('bijzonderheden', array('label' => 'Bijzonderheden:', 'type' => 'textarea'));
?>
    </fieldset>
<?php
    echo $form->end(__('Submit', true));
    echo '<p>'.$html->link('Annuleren', "/schorsingen/index/$url_params").'</p>';
?>

</div>

<?php
    $options = array(
        'violent_options' => $violent_options,
    );
    $this->Js->buffer('Ecd.schorsing.options = '.json_encode($options));

    $this->Js->buffer('Ecd.schorsing()');

?>
