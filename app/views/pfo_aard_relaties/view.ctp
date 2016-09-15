<?php echo $this->element('pfo_subnavigation'); ?>
<div class="pfoAardRelaties view">
<h2><?php  __('Aard Relatie');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $pfoAardRelatie['PfoAardRelatie']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $pfoAardRelatie['PfoAardRelatie']['naam']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $pfoAardRelatie['PfoAardRelatie']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $pfoAardRelatie['PfoAardRelatie']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Aard Relatie', true), array('action' => 'edit', $pfoAardRelatie['PfoAardRelatie']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Wis Aard Relatie', true), array('action' => 'delete', $pfoAardRelatie['PfoAardRelatie']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $pfoAardRelatie['PfoAardRelatie']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Aard Relaties lijst', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nieuwe Aard Relatie', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
