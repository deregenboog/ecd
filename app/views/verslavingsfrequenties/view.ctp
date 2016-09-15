<div class="verslavingsfrequenties view">
<h2><?php  __('Verslavingsfrequentie');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $verslavingsfrequentie['Verslavingsfrequentie']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Naam'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $verslavingsfrequentie['Verslavingsfrequentie']['naam']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Datum Van'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $verslavingsfrequentie['Verslavingsfrequentie']['datum_van']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Datum Tot'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $verslavingsfrequentie['Verslavingsfrequentie']['datum_tot']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $verslavingsfrequentie['Verslavingsfrequentie']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $verslavingsfrequentie['Verslavingsfrequentie']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Verslavingsfrequentie', true), array('action' => 'edit', $verslavingsfrequentie['Verslavingsfrequentie']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Verslavingsfrequentie', true), array('action' => 'delete', $verslavingsfrequentie['Verslavingsfrequentie']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $verslavingsfrequentie['Verslavingsfrequentie']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Verslavingsfrequenties', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Verslavingsfrequentie', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Intakes', true), array('controller' => 'intakes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Intake', true), array('controller' => 'intakes', 'action' => 'add')); ?> </li>
	</ul>
</div>
