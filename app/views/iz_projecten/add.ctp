<?php echo $this->element('iz_subnavigation'); ?>
<?php echo $this->element('iz_beheer_subnavigation'); ?>
<div class="izProjecten ">
    <?php echo $this->Form->create('IzProject');?>
    <fieldset>
        <legend><?php __('Add Iz Project'); ?></legend>
        <?= $this->Form->input('naam') ?>
        <?= $date->input('IzProject.startdatum', null, array(
            'label' => 'Startdatum',
            'rangeHigh' => (date('Y') + 10).date('-m-d'),
            'rangeLow' => (date('Y') - 3).date('-m-d'),
        )) ?>
        <?= $date->input('IzProject.einddatum', null, array(
            'label' => 'Einddatum',
            'rangeHigh' => (date('Y') + 10).date('-m-d'),
            'rangeLow' => (date('Y') - 3).date('-m-d'),
        )) ?>
        <?= $this->Form->input('heeft_koppelingen') ?>
        <?= $this->Form->input('prestatie_strategy', [
            'label' => 'Prestatieberekening',
            'options' => [
                IzBundle\Entity\IzProject::STRATEGY_PRESTATIE_TOTAL => 'Totaal',
                IzBundle\Entity\IzProject::STRATEGY_PRESTATIE_STARTED => 'Gestart',
            ],
        ]) ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit', true));?>
</div>
