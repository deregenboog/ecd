<div class="nationaliteiten form">
<?php echo $this->Form->create('Nationaliteit');?>
	<fieldset>
		<legend><?php __('Edit Nationaliteit'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('naam');
		echo $this->Form->input('afkorting');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Nationaliteiten', true), array('action' => 'index'));?></li>
	</ul>
</div>
