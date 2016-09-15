<div class="notities view">
<h2><?php  __('Notitie');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $notitie['Notitie']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Klant'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $this->Html->link($notitie['Klant']['id'], array('controller' => 'klanten', 'action' => 'view', $notitie['Klant']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Medewerker'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $this->Html->link($notitie['Medewerker']['id'], array('controller' => 'medewerkers', 'action' => 'view', $notitie['Medewerker']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Datum'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $notitie['Notitie']['datum']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Opmerking'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $notitie['Notitie']['opmerking']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $notitie['Notitie']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $notitie['Notitie']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Notitie', true), array('action' => 'edit', $notitie['Notitie']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Notitie', true), array('action' => 'delete', $notitie['Notitie']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $notitie['Notitie']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Notities', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Notitie', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Klanten', true), array('controller' => 'klanten', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Medewerkers', true), array('controller' => 'medewerkers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Medewerker', true), array('controller' => 'medewerkers', 'action' => 'add')); ?> </li>
	</ul>
</div>
