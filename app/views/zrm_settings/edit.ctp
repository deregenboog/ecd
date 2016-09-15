<div class="zrmSettings form">
<?php echo $this->Form->create('ZrmSetting');?>
	<fieldset>
		<legend><?php __('Edit Zrm Setting'); ?></legend>
	<?php
		echo $this->Form->input('id');
		
		echo $this->Form->input('request_module');
		
		echo $this->Form->input('inkomen');
		
		echo $this->Form->input('dagbesteding');
		
		echo $this->Form->input('huisvesting');
		
		echo $this->Form->input('gezinsrelaties');
		
		echo $this->Form->input('geestelijke_gezondheid');
		
		echo $this->Form->input('fysieke_gezondheid');
		
		echo $this->Form->input('verslaving');
		
		echo $this->Form->input('adl_vaardigheden');
		
		echo $this->Form->input('sociaal_netwerk');
		
		echo $this->Form->input('maatschappelijke_participatie');
		
		echo $this->Form->input('justitie');
	?>
	</fieldset>
<?php 
	echo $this->Form->end(__('Submit', true));
?>

</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('ZrmSetting.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('ZrmSetting.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Zrm Settings', true), array('action' => 'index'));?></li>
	</ul>
</div>
