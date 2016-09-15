<?php echo $this->element('iz_subnavigation'); ?>
<?php echo $this->element('iz_beheer_subnavigation'); ?>
<div class="izAfsluitingen ">
<?php echo $this->Form->create('IzAfsluiting');?>
	<fieldset>
		<legend><?php __('Add Iz Afsluiting'); ?></legend>
	<?php
		echo $this->Form->input('naam');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
