<div class="redenen index">
	<h2><?php __('Redenen');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('naam');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($redenen as $reden):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $reden['Reden']['id']; ?>&nbsp;</td>
		<td><?php echo $reden['Reden']['naam']; ?>&nbsp;</td>
		<td><?php echo $reden['Reden']['created']; ?>&nbsp;</td>
		<td><?php echo $reden['Reden']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $reden['Reden']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $reden['Reden']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $reden['Reden']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $reden['Reden']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true),
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
	 |	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Reden', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Schorsingen', true), array('controller' => 'schorsingen', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Schorsing', true), array('controller' => 'schorsingen', 'action' => 'add')); ?> </li>
	</ul>
</div>
