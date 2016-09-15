<div class="izVraagaanboden view">
<h2><?php  __('Iz Vraagaanbod');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izVraagaanbod['IzVraagaanbod']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Naam'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izVraagaanbod['IzVraagaanbod']['naam']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izVraagaanbod['IzVraagaanbod']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izVraagaanbod['IzVraagaanbod']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Iz Vraagaanbod', true), array('action' => 'edit', $izVraagaanbod['IzVraagaanbod']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Iz Vraagaanbod', true), array('action' => 'delete', $izVraagaanbod['IzVraagaanbod']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $izVraagaanbod['IzVraagaanbod']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Iz Vraagaanboden', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Iz Vraagaanbod', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Iz Koppelingen', true), array('controller' => 'iz_koppelingen', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Iz Koppeling', true), array('controller' => 'iz_koppelingen', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Iz Koppelingen');?></h3>
	<?php if (!empty($izVraagaanbod['IzKoppeling'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Project Id'); ?></th>
		<th><?php __('Iz Deelnemer Id'); ?></th>
		<th><?php __('Medewerker Id'); ?></th>
		<th><?php __('Startdatum'); ?></th>
		<th><?php __('Einddatum'); ?></th>
		<th><?php __('Iz Vraagaanbod Id'); ?></th>
		<th><?php __('Iz Koppeling Id'); ?></th>
		<th><?php __('Koppeling Startdatum'); ?></th>
		<th><?php __('Koppeling Einddatum'); ?></th>
		<th><?php __('Koppeling Iz Eindekoppeling Id'); ?></th>
		<th><?php __('Koppeling Succesvol'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($izVraagaanbod['IzKoppeling'] as $izKoppeling):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $izKoppeling['id'];?></td>
			<td><?php echo $izKoppeling['project_id'];?></td>
			<td><?php echo $izKoppeling['iz_deelnemer_id'];?></td>
			<td><?php echo $izKoppeling['medewerker_id'];?></td>
			<td><?php echo $izKoppeling['startdatum'];?></td>
			<td><?php echo $izKoppeling['einddatum'];?></td>
			<td><?php echo $izKoppeling['iz_vraagaanbod_id'];?></td>
			<td><?php echo $izKoppeling['iz_koppeling_id'];?></td>
			<td><?php echo $izKoppeling['koppeling_startdatum'];?></td>
			<td><?php echo $izKoppeling['koppeling_einddatum'];?></td>
			<td><?php echo $izKoppeling['koppeling_iz_eindekoppeling_id'];?></td>
			<td><?php echo $izKoppeling['koppeling_succesvol'];?></td>
			<td><?php echo $izKoppeling['created'];?></td>
			<td><?php echo $izKoppeling['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'iz_koppelingen', 'action' => 'view', $izKoppeling['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'iz_koppelingen', 'action' => 'edit', $izKoppeling['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'iz_koppelingen', 'action' => 'delete', $izKoppeling['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $izKoppeling['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Iz Koppeling', true), array('controller' => 'iz_koppelingen', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
