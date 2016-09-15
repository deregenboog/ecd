<div class="izEindekoppelingen view">
<h2><?php  __('Iz Eindekoppeling');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izEindekoppeling['IzEindekoppeling']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Naam'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izEindekoppeling['IzEindekoppeling']['naam']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izEindekoppeling['IzEindekoppeling']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izEindekoppeling['IzEindekoppeling']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Iz Eindekoppeling', true), array('action' => 'edit', $izEindekoppeling['IzEindekoppeling']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Iz Eindekoppeling', true), array('action' => 'delete', $izEindekoppeling['IzEindekoppeling']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $izEindekoppeling['IzEindekoppeling']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Iz Eindekoppelingen', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Iz Eindekoppeling', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
