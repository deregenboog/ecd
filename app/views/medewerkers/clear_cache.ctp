<div class="">
<?php echo $this->Form->create('Medewerker', array('clear_cache'));?>
	<fieldset>
		<legend><?php echo 'Clear caches'; ?></legend>
	<?php
echo $this->Form->checkbox('type.7', array('value' => 'default')).' Default <br /><br/>';
echo $this->Form->checkbox('type.0', array('value' => 'ldap')).' LDAP <br /><br/>';
echo $this->Form->checkbox('type.1', array('value' => 'models')).' Models <br /><br/>';
echo $this->Form->checkbox('type.2', array('value' => 'persistent')).' Persistent <br /><br/>';
echo $this->Form->checkbox('type.3', array('value' => 'apc')).' APC Cache <br /><br/>';
echo $this->Form->checkbox('type.4', array('value' => 'opcode')).' APC OPCODE Cache <br /><br/>';
echo $this->Form->checkbox('type.5', array('value' => 'views')).' Views<br /><br/>';
	?>
	</fieldset>
<?php echo $this->Form->end('Clear these caches');?>
</div>
