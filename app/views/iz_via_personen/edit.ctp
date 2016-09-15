<?php echo $this->element('iz_subnavigation'); ?>
<?php echo $this->element('iz_beheer_subnavigation'); ?>

<div class="izViaPersonen ">
<?php echo $this->Form->create('IzViaPersoon');?>
	<fieldset>
		<legend><?php __('Binnengekomen via edit'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('naam');
		echo $this->Form->input('active');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
