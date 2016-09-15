<div class="izAfsluitingen view">
<h2><?php  __('Iz Afsluiting');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izAfsluiting['IzAfsluiting']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Naam'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izAfsluiting['IzAfsluiting']['naam']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izAfsluiting['IzAfsluiting']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izAfsluiting['IzAfsluiting']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Iz Afsluiting', true), array('action' => 'edit', $izAfsluiting['IzAfsluiting']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Iz Afsluiting', true), array('action' => 'delete', $izAfsluiting['IzAfsluiting']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $izAfsluiting['IzAfsluiting']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Iz Afsluitingen', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Iz Afsluiting', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
