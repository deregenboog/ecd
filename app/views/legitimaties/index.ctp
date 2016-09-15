<div class="legitimaties index">
	<h2><?php __('Legitimaties');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('naam');?></th>
			<th><?php echo $this->Paginator->sort('datum_van');?></th>
			<th><?php echo $this->Paginator->sort('datum_tot');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($legitimaties as $legitimatie):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $legitimatie['Legitimatie']['id']; ?>&nbsp;</td>
		<td><?php echo $legitimatie['Legitimatie']['naam']; ?>&nbsp;</td>
		<td><?php echo $legitimatie['Legitimatie']['datum_van']; ?>&nbsp;</td>
		<td><?php echo $legitimatie['Legitimatie']['datum_tot']; ?>&nbsp;</td>
		<td><?php echo $legitimatie['Legitimatie']['created']; ?>&nbsp;</td>
		<td><?php echo $legitimatie['Legitimatie']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $legitimatie['Legitimatie']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $legitimatie['Legitimatie']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $legitimatie['Legitimatie']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $legitimatie['Legitimatie']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Legitimatie', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Admin', true), array('controller' => 'Admin', 'action' => 'edit_models')); ?> </li>
	</ul>
</div>
