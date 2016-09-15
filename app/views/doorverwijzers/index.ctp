<div class="doorverwijzers index">
	<h2><?php __('Doorverwijzers');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('naam');?></th>
			<th><?php echo $this->Paginator->sort('startdatum');?></th>
			<th><?php echo $this->Paginator->sort('einddatum');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($doorverwijzers as $doorverwijzer):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $doorverwijzer['Doorverwijzer']['id']; ?>&nbsp;</td>
		<td><?php echo $doorverwijzer['Doorverwijzer']['naam']; ?>&nbsp;</td>
		<td><?php echo $doorverwijzer['Doorverwijzer']['startdatum']; ?>&nbsp;</td>
		<td><?php echo $doorverwijzer['Doorverwijzer']['einddatum']; ?>&nbsp;</td>
		<td><?php echo $doorverwijzer['Doorverwijzer']['created']; ?>&nbsp;</td>
		<td><?php echo $doorverwijzer['Doorverwijzer']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $doorverwijzer['Doorverwijzer']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $doorverwijzer['Doorverwijzer']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $doorverwijzer['Doorverwijzer']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $doorverwijzer['Doorverwijzer']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Doorverwijzer', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Admin', true), array('controller' => 'Admin', 'action' => 'edit_models')); ?> </li>
	</ul>
</div>
