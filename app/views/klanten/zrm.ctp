<div class="klanten view">
    <fieldset>
        <legend>ZRM rapporten</legend>
        <?php foreach ($zrmReports as $zrmReportModel => $reports): ?>
            <?php foreach ($reports as $zrmReport): ?>
                <?php if (isset($zrmReport)): ?>
                    <?= $this->element('zrm_view', array('data' => $zrmReport, 'zrmData' => $zrmData[$zrmReportModel])) ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </fieldset>
</div>

<div class="actions">
    <?= $this->element('klantbasic', array('data' => $klant)) ?>
    <?= $this->element('diensten', array( 'diensten' => $diensten, )) ?>
    <?php
        $url = array('controller' => 'klanten', 'action' => 'view', $klant['Klant']['id']);
        if (isset($referer) && !empty($referer)) {
            $url = $referer;
        }
    ?>
    <?= $this->Html->link('Terug naar klantoverzicht', $url) ?>
</div>
