<div class="awbzIndicaties view">
<h2><?php  __('Awbz Indicaty');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $awbzIndicatie['AwbzIndicatie']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Klant'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $this->Html->link($awbzIndicatie['Klant']['name'], array('controller' => 'klanten', 'action' => 'view', $awbzIndicatie['Klant']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Begindatum'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $awbzIndicatie['AwbzIndicatie']['begindatum']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Einddatum'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $awbzIndicatie['AwbzIndicatie']['einddatum']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Begeleiding Per Week'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $awbzIndicatie['AwbzIndicatie']['begeleiding_per_week']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Activering Per Week'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $awbzIndicatie['AwbzIndicatie']['activering_per_week']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $awbzIndicatie['AwbzIndicatie']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $awbzIndicatie['AwbzIndicatie']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Awbz Indicaty', true), array('action' => 'edit', $awbzIndicatie['AwbzIndicatie']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Awbz Indicaty', true), array('action' => 'delete', $awbzIndicatie['AwbzIndicatie']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $awbzIndicatie['AwbzIndicatie']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Awbz Indicaties', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Awbz Indicaty', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Klanten', true), array('controller' => 'klanten', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add')); ?> </li>
	</ul>
</div>
