<div class="actions">
    <?= $this->element('klantbasic', array('data' => $klant)) ?>
    <?= $this->element('diensten', array( 'diensten' => $diensten, )) ?>
    <?= $this->Html->link('Terug naar klantoverzicht', array('controller' => 'hi5', 'action' => 'view', $klant['Klant']['id'])) ?>
</div>

<div class="intakes view">
    <div class="editWrench">
        <a href="#" onclick="window.print()">
            <?= $this->Html->image('printer.png') ?>
        </a>
    </div>
    <div class="fieldset">
        <h1><?php __('HI5 Intake'); ?></h1>
        <fieldset>
            <?php foreach ($zrmReports as $zrmReportModel => $reports): ?>
                <?php foreach ($reports as $zrmReport): ?>
                    <?php if (isset($zrmReport)): ?>
                        <?= $this->element('zrm_view', array('data' => $zrmReport, 'zrmData' => $zrmData[$zrmReportModel])) ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
    </fieldset>
</div>
