<fieldset><legend>ZRM</legend>
<p>
	<?php

		echo $this->Html->link('ZRM overzicht', array(
			'controller' => 'klanten',
			'action' => 'zrm',
			$persoon[$persoon_model]['id'],
		));
		echo '<br/>';
		echo $this->Html->link('ZRM toevoegen', array(
			'controller' => 'klanten',
			'action' => 'zrm_add',
			$persoon[$persoon_model]['id'],
		));
		echo '<br/>';

	?>
</p>
</fieldset>

