<div class="awbzHoofdaannemers view">
<h2><?php  __('Awbz Hoofdaannemer');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $awbzHoofdaannemer['AwbzHoofdaannemer']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Klant'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $this->Html->link($awbzHoofdaannemer['Klant']['name'], array('controller' => 'klanten', 'action' => 'view', $awbzHoofdaannemer['Klant']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Begindatum'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $awbzHoofdaannemer['AwbzHoofdaannemer']['begindatum']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Einddatum'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $awbzHoofdaannemer['AwbzHoofdaannemer']['einddatum']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Hoofdaannemer'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $this->Html->link($awbzHoofdaannemer['Hoofdaannemer']['naam'], array('controller' => 'hoofdaannemers', 'action' => 'view', $awbzHoofdaannemer['Hoofdaannemer']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $awbzHoofdaannemer['AwbzHoofdaannemer']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $awbzHoofdaannemer['AwbzHoofdaannemer']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Awbz Hoofdaannemer', true), array('action' => 'edit', $awbzHoofdaannemer['AwbzHoofdaannemer']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Awbz Hoofdaannemer', true), array('action' => 'delete', $awbzHoofdaannemer['AwbzHoofdaannemer']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $awbzHoofdaannemer['AwbzHoofdaannemer']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Awbz Hoofdaannemers', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Awbz Hoofdaannemer', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Klanten', true), array('controller' => 'klanten', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Hoofdaannemers', true), array('controller' => 'hoofdaannemers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Hoofdaannemer', true), array('controller' => 'hoofdaannemers', 'action' => 'add')); ?> </li>
	</ul>
</div>
