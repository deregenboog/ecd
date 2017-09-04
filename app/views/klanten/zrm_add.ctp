<div class="zrmReports form">
    <?= $this->Form->create('Klant', array('url' => array($id))) ?>
    <?= $this->Form->hidden('referer') ?>
    <?= $this->element('zrm', [
        'model' => 'Klant',
        'zrmData' => $zrmData,
    ]) ?>
    <?= $this->Form->end(__('Submit', true)) ?>
</div>

<div class="actions">
</div>
