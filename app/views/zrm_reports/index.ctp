<div class="zrmReports index">
	<h2><?php __('Zrm Reports');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('model');?></th>
			<th><?php echo $this->Paginator->sort('foreign_key');?></th>
			<th><?php echo $this->Paginator->sort('request_module');?></th>
			<th><?php echo $this->Paginator->sort('inkomen');?></th>
			<th><?php echo $this->Paginator->sort('dagbesteding');?></th>
			<th><?php echo $this->Paginator->sort('huisvesting');?></th>
			<th><?php echo $this->Paginator->sort('gezinsrelaties');?></th>
			<th><?php echo $this->Paginator->sort('geestelijke_gezondheid');?></th>
			<th><?php echo $this->Paginator->sort('fysieke_gezondheid');?></th>
			<th><?php echo $this->Paginator->sort('verslaving');?></th>
			<th><?php echo $this->Paginator->sort('adl_vaardigheden');?></th>
			<th><?php echo $this->Paginator->sort('sociaal_netwerk');?></th>
			<th><?php echo $this->Paginator->sort('maatschappelijke_participatie');?></th>
			<th><?php echo $this->Paginator->sort('justitie');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($zrmReports as $zrmReport):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $zrmReport['ZrmReport']['id']; ?>&nbsp;</td>
		<td><?php echo $zrmReport['ZrmReport']['model']; ?>&nbsp;</td>
		<td><?php echo $zrmReport['ZrmReport']['foreign_key']; ?>&nbsp;</td>
		<td><?php echo $zrmReport['ZrmReport']['request_module']; ?>&nbsp;</td>
		<td><?php echo $zrmReport['ZrmReport']['inkomen']; ?>&nbsp;</td>
		<td><?php echo $zrmReport['ZrmReport']['dagbesteding']; ?>&nbsp;</td>
		<td><?php echo $zrmReport['ZrmReport']['huisvesting']; ?>&nbsp;</td>
		<td><?php echo $zrmReport['ZrmReport']['gezinsrelaties']; ?>&nbsp;</td>
		<td><?php echo $zrmReport['ZrmReport']['geestelijke_gezondheid']; ?>&nbsp;</td>
		<td><?php echo $zrmReport['ZrmReport']['fysieke_gezondheid']; ?>&nbsp;</td>
		<td><?php echo $zrmReport['ZrmReport']['verslaving']; ?>&nbsp;</td>
		<td><?php echo $zrmReport['ZrmReport']['adl_vaardigheden']; ?>&nbsp;</td>
		<td><?php echo $zrmReport['ZrmReport']['sociaal_netwerk']; ?>&nbsp;</td>
		<td><?php echo $zrmReport['ZrmReport']['maatschappelijke_participatie']; ?>&nbsp;</td>
		<td><?php echo $zrmReport['ZrmReport']['justitie']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $zrmReport['ZrmReport']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $zrmReport['ZrmReport']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $zrmReport['ZrmReport']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $zrmReport['ZrmReport']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Zrm Report', true), array('action' => 'add')); ?></li>
	</ul>
</div>
