<div class="landen index">
	<h2><?php __('Landen');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('land');?></th>
			<th><?php echo $this->Paginator->sort('AFK2');?></th>
			<th><?php echo $this->Paginator->sort('AFK3');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($landen as $land):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $land['Land']['id']; ?>&nbsp;</td>
		<td><?php echo $land['Land']['land']; ?>&nbsp;</td>
		<td><?php echo $land['Land']['AFK2']; ?>&nbsp;</td>
		<td><?php echo $land['Land']['AFK3']; ?>&nbsp;</td>
		<td><?php echo $land['Land']['created']; ?>&nbsp;</td>
		<td><?php echo $land['Land']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $land['Land']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $land['Land']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $land['Land']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $land['Land']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Land', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Admin', true), array('controller' => 'Admin', 'action' => 'edit_models')); ?> </li>
	</ul>
</div>
