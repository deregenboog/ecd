<div class="zrmReports form">
<?php echo $this->Form->create('ZrmReport');

	echo $this->element('zrm', array(
			'model' => 'Awbz',
			'zrm_data' => $zrm_data,
	));
?>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('ZrmReport.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('ZrmReport.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Zrm Reports', true), array('action' => 'index'));?></li>
	</ul>
</div>
