<div id="subheader">

<?php

	if ($this->name == 'PfoClienten' && $this->action == 'index' &&
			empty($this->params['pass'])) {
		echo $html->link('Clientenlijst', array(
				'controller' => 'PfoClienten',
				'action' => 'index',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Clientenlijst', array(
			'controller' => 'PfoClienten',
			'action' => 'index',
		));
	}
	
	echo "&nbsp;&nbsp;";
	
	if ($this->name == 'PfoClienten' && $this->action == 'index' &&
			!empty($this->params['pass'])) {
		echo $html->link('Afgesloten clientenlijst', array(
				'controller' => 'PfoClienten',
				'action' => 'index',
				'afgesloten',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Afgesloten clientenlijst', array(
			'controller' => 'PfoClienten',
			'action' => 'index',
			'afgesloten',
		));
	}
	
	echo "&nbsp;&nbsp;";
	
	if ($this->action == 'rapportage') {
		echo $html->link('Rapportage', array(
				'controller' => 'PfoClienten',
				'action' => 'rapportage',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Rapportage', array(
			'controller' => 'PfoClienten',
			'action' => 'rapportage',
		));
	}
	
	echo "&nbsp;&nbsp;";
	
	if ($this->action == 'beheer' || $this->name != 'PfoClienten') {
		echo $html->link('Beheer', array(
				'controller' => 'PfoClienten',
				'action' => 'beheer',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Beheer', array(
			'controller' => 'PfoClienten',
			'action' => 'beheer',
		));
	}
	
	echo "&nbsp;&nbsp;";
	
	echo "<div>&nbsp;</div>"
?>
</div>
