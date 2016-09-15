<div class="medewerkers index">
	<h2><?php __('Medewerkers');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('username');?></th>
			<th><?php echo $this->Paginator->sort('ldapid');?></th>
			<th><?php echo $this->Paginator->sort('voornaam');?></th>
			<th><?php echo $this->Paginator->sort('tussenvoegsel');?></th>
			<th><?php echo $this->Paginator->sort('achternaam');?></th>
			<th><?php echo $this->Paginator->sort('eerste_bezoek');?></th>
			<th><?php echo $this->Paginator->sort('laatste_bezoek');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($medewerkers as $medewerker):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $medewerker['Medewerker']['id']; ?>&nbsp;</td>
		<td><?php echo $medewerker['Medewerker']['username']; ?>&nbsp;</td>
		<td><?php echo $medewerker['Medewerker']['ldapid']; ?>&nbsp;</td>
		<td><?php echo $medewerker['Medewerker']['voornaam']; ?>&nbsp;</td>
		<td><?php echo $medewerker['Medewerker']['tussenvoegsel']; ?>&nbsp;</td>
		<td><?php echo $medewerker['Medewerker']['achternaam']; ?>&nbsp;</td>
		<td><?php echo $medewerker['Medewerker']['eerste_bezoek']; ?>&nbsp;</td>
		<td><?php echo $medewerker['Medewerker']['laatste_bezoek']; ?>&nbsp;</td>
		<td><?php echo $medewerker['Medewerker']['created']; ?>&nbsp;</td>
		<td><?php echo $medewerker['Medewerker']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $medewerker['Medewerker']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $medewerker['Medewerker']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $medewerker['Medewerker']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $medewerker['Medewerker']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Medewerker', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Intakes', true), array('controller' => 'intakes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Intake', true), array('controller' => 'intakes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Klanten', true), array('controller' => 'klanten', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Notities', true), array('controller' => 'notities', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Notitie', true), array('controller' => 'notities', 'action' => 'add')); ?> </li>
	</ul>
</div>
