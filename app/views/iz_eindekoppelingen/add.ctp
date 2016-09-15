<?php echo $this->element('iz_subnavigation'); ?>
<?php echo $this->element('iz_beheer_subnavigation'); ?>
<div class="izEindekoppelingen ">
<?php echo $this->Form->create('IzEindekoppeling');?>
	<fieldset>
		<legend><?php __('Add Iz Eindekoppeling'); ?></legend>
	<?php
		echo $this->Form->input('naam');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
