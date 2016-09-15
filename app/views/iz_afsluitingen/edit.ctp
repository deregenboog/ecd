<?php echo $this->element('iz_subnavigation'); ?>
<?php echo $this->element('iz_beheer_subnavigation'); ?>
<div class="izAfsluitingen ">
<?php echo $this->Form->create('IzAfsluiting');?>
	<fieldset>
		<legend><?php __('Edit Iz Afsluiting'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('naam');
		echo $this->Form->input('active', array('label' => 'Actief'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
