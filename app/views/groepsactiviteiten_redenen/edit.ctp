<?php echo $this->element('groepsactiviteiten_subnavigation'); ?>
<?php echo $this->element('groepsactiviteiten_beheer_subnavigation'); ?>
<div class="groepsactiviteitenRedenen ">
<?php echo $this->Form->create('GroepsactiviteitenReden');?>
	<fieldset>
		<legend><?php __('Reden einde koppeling'); ?></legend>
	<?php
		echo $this->Form->input('naam');
	?>
	</fieldset>
<?= $this->Form->end(__('Submit', true));?>
<?= $this->Html->link(__('terug', true), array('action' => 'index')); ?>
</div>
