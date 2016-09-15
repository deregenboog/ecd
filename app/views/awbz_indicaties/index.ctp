<div class="awbzIndicaties index">
	<h2><?php __('Awbz Indicaties');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('klant_id');?></th>
			<th><?php echo $this->Paginator->sort('begindatum');?></th>
			<th><?php echo $this->Paginator->sort('einddatum');?></th>
			<th><?php echo $this->Paginator->sort('begeleiding_per_week');?></th>
			<th><?php echo $this->Paginator->sort('activering_per_week');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($awbzIndicaties as $awbzIndicatie):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $awbzIndicatie['AwbzIndicatie']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($awbzIndicatie['Klant']['name'], array('controller' => 'klanten', 'action' => 'view', $awbzIndicatie['Klant']['id'])); ?>
		</td>
		<td><?php echo $awbzIndicatie['AwbzIndicatie']['begindatum']; ?>&nbsp;</td>
		<td><?php echo $awbzIndicatie['AwbzIndicatie']['einddatum']; ?>&nbsp;</td>
		<td><?php echo $awbzIndicatie['AwbzIndicatie']['begeleiding_per_week']; ?>&nbsp;</td>
		<td><?php echo $awbzIndicatie['AwbzIndicatie']['activering_per_week']; ?>&nbsp;</td>
		<td><?php echo $awbzIndicatie['AwbzIndicatie']['created']; ?>&nbsp;</td>
		<td><?php echo $awbzIndicatie['AwbzIndicatie']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $awbzIndicatie['AwbzIndicatie']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $awbzIndicatie['AwbzIndicatie']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $awbzIndicatie['AwbzIndicatie']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $awbzIndicatie['AwbzIndicatie']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Awbz Indicaty', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Klanten', true), array('controller' => 'klanten', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add')); ?> </li>
	</ul>
</div>
