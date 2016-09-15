<div class="pfoVerslagen form">
<?php echo $this->Form->create('PfoVerslag');?>
	<fieldset>
		<legend><?php __('Add Pfo Verslag'); ?></legend>
	<?php
		echo $this->Form->input('contact_type');
		echo $this->Form->input('verslag');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Pfo Verslagen', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Pfo Clienten Pfo Verslagen', true), array('controller' => 'pfo_clienten_pfo_verslagen', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Pfo Clienten Pfo Verslag', true), array('controller' => 'pfo_clienten_pfo_verslagen', 'action' => 'add')); ?> </li>
	</ul>
</div>
