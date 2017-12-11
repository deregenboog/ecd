<div class="zrmReports form">
    <?= $this->Form->create($zrmReportModel) ?>
    <?= $this->element('zrm', ['model' => 'Klant', 'zrmData' => $zrmData]) ?>
    <?= $this->Form->end(__('Submit', true)) ?>
</div>

<div class="actions">
</div>
