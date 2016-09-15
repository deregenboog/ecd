<?php echo $this->element('iz_subnavigation'); ?>
<?php echo $this->element('iz_beheer_subnavigation'); ?>

<div class="izOntstaanContacten ">
<?php echo $this->Form->create('IzOntstaanContact');?>
	<fieldset>
		<legend><?php __('Ontstaan via contact toevoegen'); ?></legend>
	<?php
		echo $this->Form->input('naam');
		echo $this->Form->input('active');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
