<div class="inventarisaties form">
<?php echo $this->Form->create('Inventarisatie');?>
	<fieldset>
		<legend><?php __('Edit Inventarisatie'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('item');
		echo $this->Form->input('order');
		echo $this->Form->input('parent');
		echo $this->Form->input('actief');
		echo $this->Form->input('type');
		echo $this->Form->input('titel');
		echo $this->Form->input('actie');
		echo $this->Form->input('startdatum');
		echo $this->Form->input('einddatum');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Inventarisaties', true), array('action' => 'index'));?></li>
	</ul>
</div>
