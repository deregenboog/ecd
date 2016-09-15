<div class="izIntervisiegroepen view">
<h2><?php  __('Iz Intervisiegroep');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izIntervisiegroep['IzIntervisiegroep']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Naam'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izIntervisiegroep['IzIntervisiegroep']['naam']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Startdatum'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izIntervisiegroep['IzIntervisiegroep']['startdatum']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Einddatum'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izIntervisiegroep['IzIntervisiegroep']['einddatum']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Medewerker Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izIntervisiegroep['IzIntervisiegroep']['medewerker_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izIntervisiegroep['IzIntervisiegroep']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izIntervisiegroep['IzIntervisiegroep']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Iz Intervisiegroep', true), array('action' => 'edit', $izIntervisiegroep['IzIntervisiegroep']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Iz Intervisiegroep', true), array('action' => 'delete', $izIntervisiegroep['IzIntervisiegroep']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $izIntervisiegroep['IzIntervisiegroep']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Iz Intervisiegroepen', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Iz Intervisiegroep', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
