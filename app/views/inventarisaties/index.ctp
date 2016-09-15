<div class="inventarisaties index">
	<h2><?php __('Inventarisaties');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('order');?></th>
			<th><?php echo $this->Paginator->sort('parent_id');?></th>
			<th><?php echo $this->Paginator->sort('actief');?></th>
			<th><?php echo $this->Paginator->sort('type');?></th>
			<th><?php echo $this->Paginator->sort('titel');?></th>
			<th><?php echo $this->Paginator->sort('actie');?></th>
			<th><?php echo $this->Paginator->sort('lft');?></th>
			<th><?php echo $this->Paginator->sort('rght');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($inventarisaties as $inventarisatie):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $inventarisatie['Inventarisatie']['id']; ?>&nbsp;</td>
		<td><?php echo $inventarisatie['Inventarisatie']['order']; ?>&nbsp;</td>
		<td><?php echo $inventarisatie['Inventarisatie']['parent_id']; ?>&nbsp;</td>
		<td><?php echo $inventarisatie['Inventarisatie']['actief']; ?>&nbsp;</td>
		<td><?php echo $inventarisatie['Inventarisatie']['type']; ?>&nbsp;</td>
		<td><?php echo $inventarisatie['Inventarisatie']['titel']; ?>&nbsp;</td>
		<td><?php echo $inventarisatie['Inventarisatie']['actie']; ?>&nbsp;</td>
		<td><?php echo $inventarisatie['Inventarisatie']['lft']; ?>&nbsp;</td>
		<td><?php echo $inventarisatie['Inventarisatie']['rght']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $inventarisatie['Inventarisatie']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $inventarisatie['Inventarisatie']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $inventarisatie['Inventarisatie']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $inventarisatie['Inventarisatie']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Inventarisatie', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Admin', true), array('controller' => 'Admin', 'action' => 'edit_models')); ?> </li>
	</ul>
</div>
