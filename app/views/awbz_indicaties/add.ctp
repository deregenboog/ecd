<div class="awbzIndicaties form">
<?php echo $this->Form->create('AwbzIndicatie');?>
	<fieldset>
		<legend><?php __('Add Awbz Indicaty'); ?></legend>
	<?php
		echo $this->Form->input('klant_id');
		echo $this->Form->input('begindatum');
		echo $this->Form->input('einddatum');
		echo $this->Form->input('begeleiding_per_week');
		echo $this->Form->input('activering_per_week');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Awbz Indicaties', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Klanten', true), array('controller' => 'klanten', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add')); ?> </li>
	</ul>
</div>
