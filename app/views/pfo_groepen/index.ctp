<?php echo $this->element('pfo_subnavigation'); ?>
<div class="pfoGroepen index">
	<h2><?php __('Groepen');?></h2>
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
	foreach ($pfoGroepen as $pfoGroep):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $pfoGroep['PfoGroep']['id']; ?>&nbsp;</td>
		<td><?php echo $pfoGroep['PfoGroep']['naam']; ?>&nbsp;</td>
		<td><?php echo $pfoGroep['PfoGroep']['startdatum']; ?>&nbsp;</td>
		<td><?php echo $pfoGroep['PfoGroep']['einddatum']; ?>&nbsp;</td>
		<td class="actions">
			<?php //echo $this->Html->link(__('View', true), array('action' => 'view', $pfoGroep['PfoGroep']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $pfoGroep['PfoGroep']['id'])); ?>
			<?php //echo $this->Html->link(__('Delete', true), array('action' => 'delete', $pfoGroep['PfoGroep']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $pfoGroep['PfoGroep']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('Nieuwe Groep', true), array('action' => 'add')); ?></li>
	</ul>
</div>
