<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('ZRM configuratie', true), array('controller' => 'ZrmSettings', 'action' => 'matrix')); ?></li>
		<li><?php echo $this->Html->link(__('Medewerkers uit dienst', true), array( 'action' => 'uit_dienst')); ?></li>
		<li><?php echo $this->Html->link(__('Cache', true), array('controller' => 'medewerkers', 'action' => 'clear_cache')); ?></li>
		<li><?php echo $this->Html->link(__('ZRM intitalisatie', true), array('controller' => 'ZrmSettings', 'action' => 'update_table')); ?></li>
		<li><?php echo $this->Html->link(__('Models', true), array( 'action' => 'edit_models')); ?></li>
		</ul>
</div>
