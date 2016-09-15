<div class="doorverwijzers form">
<?php echo $this->Form->create('Doorverwijzer');?>
	<fieldset>
		<legend><?php __('Add Doorverwijzer'); ?></legend>
	<?php
		echo $this->Form->input('naam');
		echo $this->Form->input('startdatum');
		echo $this->Form->input('einddatum');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Doorverwijzers', true), array('action' => 'index'));?></li>
	</ul>
</div>
