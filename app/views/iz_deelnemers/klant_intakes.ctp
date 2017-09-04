<fieldset>
    <h2>Intake deelnemer</h2>
    <?php $url = $this->Html->url(array('controller' => 'iz_deelnemers', 'action' => 'intakes', $id), true) ?>
    <?= $this->Form->create('IzIntake', ['url' => $url]) ?>
    <?php $id = !empty($persoon['IzIntake']['id']) ? $persoon['IzIntake']['id'] : ''; ?>
    <?= $this->Form->hidden('IzIntake.id', array('value' => $id)) ?>
    <?php
        $intake_datum = date('Y-m-d');
        if (! empty($this->data['IzIntake']['intake_datum'])) {
            if (is_array($this->data['IzIntake']['intake_datum'])) {
                $intake_datum =$this->data['IzIntake']['intake_datum']['year']."-".$this->data['IzIntake']['intake_datum']['month']."-".$this->data['IzIntake']['intake_datum']['day'];
            } else {
                $intake_datum =$this->data['IzIntake']['intake_datum'];
            }
        }
    ?>
    <?= $date->input("IzIntake.intake_datum", $intake_datum, [
        'label' => 'Intakedatum',
        'rangeHigh' => (date('Y') + 10).date('-m-d'),
        'rangeLow' => (date('Y') - 19).date('-m-d'),
    ]) ?>
    <?= $this->Form->input('medewerker_id', array('options' => $viewmedewerkers, 'label' => 'Coördinator' )) ?>
    <?= $this->Form->input('gezin_met_kinderen', array('type' => 'checkbox', 'label' => 'Gezin met kinderen' )) ?>
    <i>IZ-deelnemers worden automatisch toegevoegd aan de ErOpUit-kalender </i>
    <div class="zrmReports form">
        <?= $this->element('zrm', ['model' => 'IzIntake', 'zrmData' => $zrmData]) ?>
    </div>
    <div>
        <?php
            /*
            echo $this->Form->textarea('IzIntake.gesprek_verslag', array(
                'label' => 'Gesprek verslag',
                'class' => 'verslag-textarea',
                'style' => 'height: 400px;',
            ));
             */
        ?>
        <?= $this->Form->submit('Opslaan', array('id' => 'verslag-submit-0', 'div' => false)) ?>
        <?= $this->Form->end() ?>
    </div>
</fieldset>
