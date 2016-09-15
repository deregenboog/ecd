<div class="verslavingsperiodes index">
	<h2><?php __('Verslavingsperiodes');?></h2>
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
	foreach ($verslavingsperiodes as $verslavingsperiode):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $verslavingsperiode['Verslavingsperiode']['id']; ?>&nbsp;</td>
		<td><?php echo $verslavingsperiode['Verslavingsperiode']['naam']; ?>&nbsp;</td>
		<td><?php echo $verslavingsperiode['Verslavingsperiode']['datum_van']; ?>&nbsp;</td>
		<td><?php echo $verslavingsperiode['Verslavingsperiode']['datum_tot']; ?>&nbsp;</td>
		<td><?php echo $verslavingsperiode['Verslavingsperiode']['created']; ?>&nbsp;</td>
		<td><?php echo $verslavingsperiode['Verslavingsperiode']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $verslavingsperiode['Verslavingsperiode']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $verslavingsperiode['Verslavingsperiode']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $verslavingsperiode['Verslavingsperiode']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $verslavingsperiode['Verslavingsperiode']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Verslavingsperiode', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Admin', true), array('controller' => 'Admin', 'action' => 'edit_models')); ?> </li>
	</ul>
</div>
