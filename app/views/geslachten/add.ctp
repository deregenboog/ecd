<div class="geslachten form">
<?php echo $this->Form->create('Geslacht');?>
	<fieldset>
		<legend><?php __('Add Geslacht'); ?></legend>
	<?php
		echo $this->Form->input('afkorting');
		echo $this->Form->input('volledig');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Geslachten', true), array('action' => 'index'));?></li>
	</ul>
</div>
