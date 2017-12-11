<div class="zrmReports form">
    <?= $this->Form->create($zrmReportModel); ?>
    <?= $this->element('zrm', ['model' => 'Awbz', 'zrmData' => $zrmData]) ?>
    <?= $this->Form->end(__('Submit', true)) ?>
</div>

<div class="actions">
    <h3><?php __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(
            __('Delete', true),
            array('action' => 'delete', $this->Form->value($zrmReportModel.'.id')),
            null,
            sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value($zrmReportModel.'.id'))
        ); ?></li>
        <li><?php echo $this->Html->link(__('List Zrm Reports', true), array('action' => 'index'));?></li>
    </ul>
</div>
