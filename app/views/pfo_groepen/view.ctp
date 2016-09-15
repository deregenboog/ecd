<div class="pfoGroepen view">
<h2><?php  __('Pfo Groep');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $pfoGroep['PfoGroep']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Naam'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $pfoGroep['PfoGroep']['naam']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $pfoGroep['PfoGroep']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $pfoGroep['PfoGroep']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Groep', true), array('action' => 'edit', $pfoGroep['PfoGroep']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Wis Groep', true), array('action' => 'delete', $pfoGroep['PfoGroep']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $pfoGroep['PfoGroep']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Groepen lijst', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nieuwe Groep', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
