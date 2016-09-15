<div class="groepsactiviteitenAfsluitingen form">
<?php echo $this->Form->create('GroepsactiviteitenAfsluiting');?>
	<fieldset>
		<legend><?php __('Add Groepsactiviteiten Afsluiting'); ?></legend>
	<?php
		echo $this->Form->input('naam');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Groepsactiviteiten Afsluitingen', true), array('action' => 'index'));?></li>
	</ul>
</div>
