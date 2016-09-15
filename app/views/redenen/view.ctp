<div class="redenen view">
<h2><?php  __('Reden');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $reden['Reden']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Naam'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $reden['Reden']['naam']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $reden['Reden']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $reden['Reden']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Reden', true), array('action' => 'edit', $reden['Reden']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Reden', true), array('action' => 'delete', $reden['Reden']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $reden['Reden']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Redenen', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Reden', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Schorsingen', true), array('controller' => 'schorsingen', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Schorsing', true), array('controller' => 'schorsingen', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Schorsingen');?></h3>
	<?php if (!empty($reden['Schorsing'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Datum Van'); ?></th>
		<th><?php __('Datum Tot'); ?></th>
		<th><?php __('Locatie Id'); ?></th>
		<th><?php __('Klant Id'); ?></th>
		<th><?php __('Remark'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($reden['Schorsing'] as $schorsing):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $schorsing['id'];?></td>
			<td><?php echo $schorsing['datum_van'];?></td>
			<td><?php echo $schorsing['datum_tot'];?></td>
			<td><?php echo $schorsing['locatie_id'];?></td>
			<td><?php echo $schorsing['klant_id'];?></td>
			<td><?php echo $schorsing['remark'];?></td>
			<td><?php echo $schorsing['created'];?></td>
			<td><?php echo $schorsing['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'schorsingen', 'action' => 'view', $schorsing['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'schorsingen', 'action' => 'edit', $schorsing['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'schorsingen', 'action' => 'delete', $schorsing['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $schorsing['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Schorsing', true), array('controller' => 'schorsingen', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
