<div class="inkomens view">
<h2><?php  __('Inkomen');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $inkomen['Inkomen']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Naam'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $inkomen['Inkomen']['naam']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Datum Van'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $inkomen['Inkomen']['datum_van']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Datum Tot'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $inkomen['Inkomen']['datum_tot']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $inkomen['Inkomen']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $inkomen['Inkomen']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Inkomens', true), array('action' => 'index')); ?> </li>
	</ul>
</div>

	<div class="actions">
		<ul>
		</ul>
	</div>
</div>
