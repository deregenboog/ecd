<div class="awbzHoofdaannemers form">
<?php echo $this->Form->create('AwbzHoofdaannemer');?>
	<fieldset>
		<legend><?php __('Add Awbz Hoofdaannemer'); ?></legend>
	<?php
		echo $this->Form->input('klant_id');
		echo $this->Form->input('begindatum');
		echo $this->Form->input('einddatum');
		echo $this->Form->input('hoofdaannemer_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>

</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Awbz Hoofdaannemers', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Klanten', true), array('controller' => 'klanten', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Hoofdaannemers', true), array('controller' => 'hoofdaannemers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Hoofdaannemer', true), array('controller' => 'hoofdaannemers', 'action' => 'add')); ?> </li>
	</ul>
</div>
