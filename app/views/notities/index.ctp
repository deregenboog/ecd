<div class="notities index">
	<h2><?php __('Notities');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('klant_id');?></th>
			<th><?php echo $this->Paginator->sort('medewerker_id');?></th>
			<th><?php echo $this->Paginator->sort('datum');?></th>
			<th><?php echo $this->Paginator->sort('opmerking');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($notities as $notitie):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $notitie['Notitie']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($notitie['Klant']['id'], array('controller' => 'klanten', 'action' => 'view', $notitie['Klant']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($notitie['Medewerker']['id'], array('controller' => 'medewerkers', 'action' => 'view', $notitie['Medewerker']['id'])); ?>
		</td>
		<td><?php echo $notitie['Notitie']['datum']; ?>&nbsp;</td>
		<td><?php echo $notitie['Notitie']['opmerking']; ?>&nbsp;</td>
		<td><?php echo $notitie['Notitie']['created']; ?>&nbsp;</td>
		<td><?php echo $notitie['Notitie']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $notitie['Notitie']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $notitie['Notitie']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $notitie['Notitie']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $notitie['Notitie']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Notitie', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Klanten', true), array('controller' => 'klanten', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Medewerkers', true), array('controller' => 'medewerkers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Medewerker', true), array('controller' => 'medewerkers', 'action' => 'add')); ?> </li>
	</ul>
</div>
