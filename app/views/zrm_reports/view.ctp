<div class="zrmReports view">
<?php 
	echo $this->element('zrm_view', array(
			'zrm_data' => $zrm_data,
			'data' => $zrmReport,
	));
?>

</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Zrm Report', true), array('action' => 'edit', $zrmReport['ZrmReport']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Zrm Report', true), array('action' => 'delete', $zrmReport['ZrmReport']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $zrmReport['ZrmReport']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Zrm Reports', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Zrm Report', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
