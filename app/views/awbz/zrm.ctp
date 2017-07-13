<div class="klanten view">
    <fieldset>
        <legend>ZRM rapporten</legend>
        <?php foreach ($zrmReports as $zrmReportModel => $reports): ?>
            <?php foreach ($reports as $zrmReport): ?>
                <?= $this->element('zrm_view', array('data' => $zrmReport, 'zrmData' => $zrmData[$zrmReportModel])) ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </fieldset>
</div>

<div class="actions">
    <?= $this->element('klantbasic', array('data' => $klant)) ?>
    <?= $this->Html->link('Terug naar klantoverzicht', array('controller' => 'awbz', 'action' => 'view', $klant['Klant']['id'])) ?>
</div>
