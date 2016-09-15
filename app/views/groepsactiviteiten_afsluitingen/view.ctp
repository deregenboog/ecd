<div class="groepsactiviteitenAfsluitingen view">
<h2><?php  __('Groepsactiviteiten Afsluiting');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $groepsactiviteitenAfsluiting['GroepsactiviteitenAfsluiting']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Naam'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $groepsactiviteitenAfsluiting['GroepsactiviteitenAfsluiting']['naam']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $groepsactiviteitenAfsluiting['GroepsactiviteitenAfsluiting']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $groepsactiviteitenAfsluiting['GroepsactiviteitenAfsluiting']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Groepsactiviteiten Afsluiting', true), array('action' => 'edit', $groepsactiviteitenAfsluiting['GroepsactiviteitenAfsluiting']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Groepsactiviteiten Afsluiting', true), array('action' => 'delete', $groepsactiviteitenAfsluiting['GroepsactiviteitenAfsluiting']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $groepsactiviteitenAfsluiting['GroepsactiviteitenAfsluiting']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Groepsactiviteiten Afsluitingen', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Groepsactiviteiten Afsluiting', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
