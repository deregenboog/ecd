<div class="izOntstaanContacten view">
<h2><?php  __('Iz Ontstaan Contact');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izOntstaanContact['IzOntstaanContact']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Naam'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izOntstaanContact['IzOntstaanContact']['naam']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izOntstaanContact['IzOntstaanContact']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izOntstaanContact['IzOntstaanContact']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Iz Ontstaan Contact', true), array('action' => 'edit', $izOntstaanContact['IzOntstaanContact']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Iz Ontstaan Contact', true), array('action' => 'delete', $izOntstaanContact['IzOntstaanContact']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $izOntstaanContact['IzOntstaanContact']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Iz Ontstaan Contacten', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Iz Ontstaan Contact', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
