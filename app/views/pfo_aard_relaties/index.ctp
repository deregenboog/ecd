<?php echo $this->element('pfo_subnavigation'); ?>
<div class="pfoAardRelaties index">
	<h2><?php __('Aard Relaties');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('naam');?></th>
			<th><?php echo $this->Paginator->sort('startdatum');?></th>
			<th><?php echo $this->Paginator->sort('einddatum');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($pfoAardRelaties as $pfoAardRelatie):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $pfoAardRelatie['PfoAardRelatie']['id']; ?>&nbsp;</td>
		<td><?php echo $pfoAardRelatie['PfoAardRelatie']['naam']; ?>&nbsp;</td>
		<td><?php echo $pfoAardRelatie['PfoAardRelatie']['startdatum']; ?>&nbsp;</td>
		<td><?php echo $pfoAardRelatie['PfoAardRelatie']['einddatum']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $pfoAardRelatie['PfoAardRelatie']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('Nieuwe Aard Relatie', true), array('action' => 'add')); ?></li>
	</ul>
</div>
