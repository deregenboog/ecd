<h2>Intake deelnemer</h2>
<br>
<?php $id = !empty($persoon['IzIntake']['id']) ? $persoon['IzIntake']['id'] : '' ; ?>
<?= $html->link('bewerken', array('action' => 'intakes', $this->data['IzIntake']['iz_deelnemer_id'])) ?>
<br>
<br>
<?php
    $intake_datum = date('Y-m-d');
    if (!empty($this->data['IzIntake']['intake_datum'])) {
        if (is_array($this->data['IzIntake']['intake_datum'])) {
            $intake_datum =$this->data['IzIntake']['intake_datum']['year']."-".$this->data['IzIntake']['intake_datum']['month']."-".$this->data['IzIntake']['intake_datum']['day'];
        } else {
            $intake_datum =$this->data['IzIntake']['intake_datum'];
        }
    }
?>

Intake datum: <?= $intake_datum ?><br/><br/>
Coodinator: <?= $viewmedewerkers[$this->data['IzIntake']['medewerker_id']] ?><br/><br/>
<?php $antwoord = !empty($this->data['IzIntake']['gezin_met_kinderen']) ? 'Ja' : 'Nee'; ?>
Gezin met kinderen: <?= $antwoord ?><br/><br/>

<div>
    <?= $this->element('zrm_view', [
        'zrmReport' => $this->data,
        'model' => 'IzIntake',
        'zrmData' => $zrmData,
    ]) ?>
</div>
