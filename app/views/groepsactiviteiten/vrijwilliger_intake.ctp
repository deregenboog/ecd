<fieldset>
    <h2>Intakes</h2>
    <?= $this->Form->create(
        'Groepsactiviteit',
        array(
            'url' => array( $persoon_model, $persoon[$persoon_model]['id']),
        )
    ) ?>
    <?= $this->Form->hidden('GroepsactiviteitenIntake.foreign_key', array('value' => $persoon[$persoon_model]['id'])) ?>
    <?= $this->Form->hidden('GroepsactiviteitenIntake.model', array('value' => 'Vrijwilliger')) ?>
    <?= $this->Form->hidden('GroepsactiviteitenIntake.gespreksverslag', array('value' => '')) ?>
    <?=$this->Form->submit('Opslaan', array('id' => 'verslag-submit-0', 'div' => false)) ?>
    <?= $this->Form->end() ?>
</fieldset>
