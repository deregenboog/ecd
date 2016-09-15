<div class="landen form">
<?php echo $this->Form->create('Land');?>
	<fieldset>
		<legend><?php __('Add Land'); ?></legend>
	<?php
		echo $this->Form->input('land');
		echo $this->Form->input('AFK2');
		echo $this->Form->input('AFK3');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Landen', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Klanten', true), array('controller' => 'klanten', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add')); ?> </li>
	</ul>
</div>
