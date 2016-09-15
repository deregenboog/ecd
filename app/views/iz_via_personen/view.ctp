<div class="izViaPersonen view">
<h2><?php  __('Iz Via Persoon');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izViaPersoon['IzViaPersoon']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Naam'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izViaPersoon['IzViaPersoon']['naam']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izViaPersoon['IzViaPersoon']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $izViaPersoon['IzViaPersoon']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Iz Via Persoon', true), array('action' => 'edit', $izViaPersoon['IzViaPersoon']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Iz Via Persoon', true), array('action' => 'delete', $izViaPersoon['IzViaPersoon']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $izViaPersoon['IzViaPersoon']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Iz Via Personen', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Iz Via Persoon', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
