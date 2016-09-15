<?php echo $this->element('iz_subnavigation'); ?>
<?php echo $this->element('iz_beheer_subnavigation'); ?>

<div class="izVraagaanboden ">
<?php echo $this->Form->create('IzVraagaanbod');?>
	<fieldset>
		<legend><?php __('Edit Iz Vraagaanbod'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('naam');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
