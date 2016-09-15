<div id="subheader">
<?php
	
	if ($this->name == 'Groepsactiviteiten' &&
			$this->action == 'index' && empty($this->params['named'])) {
		echo $html->link('Deelnemerslijst', array(
				'controller' => 'Groepsactiviteiten',
				'action' => 'index',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Deelnemerslijst', array(
			'controller' => 'Groepsactiviteiten',
			'action' => 'index',
		));
	}
	
	echo "&nbsp;&nbsp;";
	
	if ($this->name == 'Groepsactiviteiten' &&
			$this->action == 'index' && !empty($this->params['named'])) {
		echo $html->link('Vrijwilligerslijst', array(
				'controller' => 'Groepsactiviteiten',
				'action' => 'index',
				'selectie' => 'Vrijwilliger',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Vrijwilligerslijst', array(
			'controller' => 'Groepsactiviteiten',
			'action' => 'index',
			'selectie' => 'Vrijwilliger',
		));
	}
	
	echo "&nbsp;&nbsp;";
	
	if ($this->action == 'export') {
		echo $html->link('Groepen', array(
				'controller' => 'Groepsactiviteiten',
				'action' => 'export',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Groepen', array(
			'controller' => 'Groepsactiviteiten',
			'action' => 'export',
		));
	}
	
	echo "&nbsp;&nbsp;";
	
	if ($this->action == 'planning') {
		echo $html->link('Activiteitenregistratie', array(
				'controller' => 'Groepsactiviteiten',
				'action' => 'planning',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Activiteitenregistratie', array(
			'controller' => 'Groepsactiviteiten',
			'action' => 'planning',
		));
	}
	
	echo "&nbsp;&nbsp;";
	
	if ($this->action == 'rapportages') {
		echo $html->link('Rapportages', array(
				'controller' => 'Groepsactiviteiten',
				'action' => 'rapportages',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Rapportages', array(
				'controller' => 'Groepsactiviteiten',
				'action' => 'rapportages',
		));
	}
	
	echo "&nbsp;&nbsp;";
	
	if ($this->action == 'selecties') {
		echo $html->link('Selecties', array(
				'controller' => 'Groepsactiviteiten',
				'action' => 'selecties',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Selecties', array(
			'controller' => 'Groepsactiviteiten',
			'action' => 'selecties',
		));
	}
	
	echo "&nbsp;&nbsp;";
	
	if ($this->action == 'beheer' || $this->name != 'Groepsactiviteiten') {
		echo $html->link('Beheer', array(
				'controller' => 'Groepsactiviteiten',
				'action' => 'beheer',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Beheer', array(
		'controller' => 'Groepsactiviteiten',
		'action' => 'beheer',
	));
	}
	
	echo "&nbsp;&nbsp;";
	echo "<div>&nbsp;</div>"
			
	?>
</div>
