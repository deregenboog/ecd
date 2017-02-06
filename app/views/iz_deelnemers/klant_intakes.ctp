<fieldset>

<h2>Intake deelnemer</h2>

<?php
    $url = $this->Html->url(array('controller' => 'iz_deelnemers', 'action' => 'intakes', $id), true);

    echo $this->Form->create(
        'IzIntake',
        array(
            'url' => $url,
        )
    );

    $id = !empty($persoon['IzIntake']['id']) ? $persoon['IzIntake']['id'] : '';

    echo $this->Form->hidden('IzIntake.id', array('value' => $id));

    $intake_datum = date('Y-m-d');

    if (! empty($this->data['IzIntake']['intake_datum'])) {

        if (is_array($this->data['IzIntake']['intake_datum'])) {
            $intake_datum =$this->data['IzIntake']['intake_datum']['year']."-".$this->data['IzIntake']['intake_datum']['month']."-".$this->data['IzIntake']['intake_datum']['day'];
        } else {
            $intake_datum =$this->data['IzIntake']['intake_datum'];
        }
    }

    echo $date->input("IzIntake.intake_datum",	$intake_datum,
        array(
            'label' => 'Intakedatum',
            'rangeHigh' => (date('Y') + 10).date('-m-d'),
            'rangeLow' => (date('Y') - 19).date('-m-d'),
    ));
?>

<?php
echo $this->Form->input('medewerker_id', array('options' => $viewmedewerkers, 'label' => 'Coördinator' ));
echo $this->Form->input('gezin_met_kinderen', array('type' => 'checkbox', 'label' => 'Gezin met kinderen' ));
?>
<i>IZ-deelnemers worden automatisch toegevoegd aan de ErOpUit-kalender </i>
    <div class="zrmReports form">
<?php
    echo $this->element('zrm', array(
        'model' => 'IzIntake',
        'zrm_data' => $zrm_data,
    ));
?>
</div>

<?php
/*
    echo $this->Form->textarea('IzIntake.gesprek_verslag', array(
        'label' => 'Gesprek verslag',
        'class' => 'verslag-textarea',
        'style' => 'height: 400px;',
    ));

 */

?>

<fieldset id="ondersteuning">
    <legend>Ondersteuning</legend>
    <p>
        Als je bij de vier vragen hieronder 'ja' invult,
        wordt er een e-mail verzonden naar de desbetreffende afdeling.
        Vul 'nee' in als de deelnemer geen gebruik wenst te maken van deze mogelijkheden, of deze al gebruikt.
    </p>

    <?php

        $radioOptions = array(
            IzIntake::DECISION_VALUE_NO => 'Nee',
            IzIntake::DECISION_VALUE_YES => 'Ja',
        );

        $radioAttributes = array(
            'legend' => false,
            //'value' => IzIntake::DECISION_VALUE_NO,
            'style' => 'display: inline-block;',
        );

        if (!empty($this->data['IzIntake']['ondernemen'])) {
            $radioAttributes['value'] = $this->data['IzIntake']['ondernemen'];
        }
    $v=null;
    if (!empty($this->data['IzIntake']['ondernemen'])) {
        $v=$this->data['IzIntake']['ondernemen'];
    }
        echo '<div style="margin: 0;">';
            echo $this->Form->label('ondernemen', 'Zou je het leuk vinden om iedere week met iemand samen iets te ondernemen?');
            echo '<div>';
            echo $this->Form->radio('IzIntake.ondernemen', $radioOptions, array(
                'legend' => false,
                'value' => $v,
            ));
            echo '</div>';
        echo '</div>';

        if (!empty($this->data['IzIntake']['overdag'])) {
            $radioAttributes['value'] = $this->data['IzIntake']['overdag'];
        }
    $v=null;
    if (!empty($this->data['IzIntake']['overdag'])) {
        $v = $this->data['IzIntake']['overdag'];
    }
        echo '<div style="margin: 0;">';
            echo $this->Form->label('overdag', 'Zou je het leuk vinden om overdag iets te doen te hebben?');
            echo '<div>';
            echo $this->Form->radio('IzIntake.overdag', $radioOptions, array(
                'legend' => false,
                'value' => $v,
            ));
            echo '</div>';
        echo '</div>';

        if (!empty($this->data['IzIntake']['ontmoeten'])) {
            $radioAttributes['value'] = $this->data['IzIntake']['ontmoeten'];
        }
    $v=null;
    if (!empty($this->data['IzIntake']['ontmoeten'])) {
        $v=$this->data['IzIntake']['ontmoeten'];
    }
        echo '<div style="margin: 0;">';
            echo $this->Form->label('ontmoeten', 'Zou je een plek in de buurt willen hebben waar je iedere dag koffie kan drinken en mensen kan ontmoeten?');
            echo '<div>';
            echo $this->Form->radio('IzIntake.ontmoeten', $radioOptions, array(
                'legend' => false,
                'value' => $v,
            ));
            echo '</div>';
        echo '</div>';

        if (!empty($this->data['IzIntake']['regelzaken'])) {
            $radioAttributes['value'] = $this->data['IzIntake']['regelzaken'];
        }
        echo '<div style="margin: 0;">';
    $v=null;
    if (!empty($this->data['IzIntake']['regelzaken'])) {
        $v=$this->data['IzIntake']['regelzaken'];
    }
            echo $this->Form->label('regelzaken', 'Heeft u hulp nodig met regelzaken?');
            echo '<div>';
            echo $this->Form->radio('IzIntake.regelzaken', $radioOptions, array(
                'legend' => false,
                'value' => $v,
            ));
            echo '</div>';
        echo '</div>';

    ?>
</fieldset>



<?php
    echo $this->Form->submit('Opslaan', array('id' => 'verslag-submit-0', 'div' => false));
    echo $this->Form->end();
?>

</fieldset>



