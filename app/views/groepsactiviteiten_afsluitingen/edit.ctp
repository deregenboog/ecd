<?php echo $this->element('groepsactiviteiten_subnavigation'); ?>
<?php echo $this->element('groepsactiviteiten_beheer_subnavigation'); ?>
<div class="groepsactiviteitenAfsluitingen ">
<?php echo $this->Form->create('GroepsactiviteitenAfsluiting');?>
	<fieldset>
		<legend><?php __('Edit Groepsactiviteiten Afsluiting'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('naam');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
