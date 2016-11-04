<div id="subheader">
	<?php
	if ($this->action == 'index'
		&& $this->name == 'IzDeelnemers'
		&& (empty($this->params['named'])
		|| (isset($this->params['named']['Klant.selectie'])
			&& !isset($this->params['named']['Klant.wachtlijst'])
		)
		)
	) {
		echo $html->link('Deelnemerslijst', array(
				'controller' => 'iz_deelnemers',
				'action' => 'index',
				'Klant.selectie' => '1',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Deelnemerslijst', array(
			'controller' => 'iz_deelnemers',
			'action' => 'index',
			'Klant.selectie' => '1',
		));
	}
	echo '&nbsp;&nbsp;';
	if ($this->action == 'index' &&
			$this->name == 'IzDeelnemers' &&
			isset($this->params['named']['Vrijwilliger.selectie']) &&
			!isset($this->params['named']['Vrijwilliger.wachtlijst'])) {
		echo $html->link('Vrijwilligerslijst', array(
				'controller' => 'iz_deelnemers',
				'action' => 'index',
				'Vrijwilliger.selectie' => '1',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Vrijwilligerslijst', array(
			'controller' => 'iz_deelnemers',
			'action' => 'index',
			'Vrijwilliger.selectie' => '1',
		));
	}
	echo '&nbsp;&nbsp;';
	if ($this->action == 'index' &&
			$this->name == 'IzDeelnemers' &&
			isset($this->params['named']['Klant.selectie']) &&
			isset($this->params['named']['Klant.wachtlijst'])) {
		echo $html->link('Wachtlijst Deelnemers', array(
				'controller' => 'iz_deelnemers',
				'action' => 'index',
				'Klant.selectie' => '1',
				'Klant.wachtlijst' => 1,
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Wachtlijst Deelnemers', array(
			'controller' => 'iz_deelnemers',
			'action' => 'index',
			'Klant.selectie' => '1',
			'Klant.wachtlijst' => 1,
		));
	}
	echo '&nbsp;&nbsp;';
	if ($this->action == 'index' &&
			$this->name == 'IzDeelnemers' &&
			isset($this->params['named']['Vrijwilliger.selectie']) &&
			isset($this->params['named']['Vrijwilliger.wachtlijst'])) {
		echo $html->link('Wachtlijst Vrijwilligers', array(
				'controller' => 'iz_deelnemers',
				'action' => 'index',
				'Vrijwilliger.selectie' => '1',
				'Vrijwilliger.wachtlijst' => '1',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Wachtlijst Vrijwilligers', array(
				'controller' => 'iz_deelnemers',
				'action' => 'index',
				'Vrijwilliger.selectie' => '1',
				'Vrijwilliger.wachtlijst' => '1',
		));
	}
	echo '&nbsp;&nbsp;';
	if ($this->params['controller'] == 'iz_rapportages') {
		echo $html->link('Rapportages', array(
				'controller' => 'iz_rapportages',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Rapportages', array(
			'controller' => 'iz_rapportages',
		));
	}
	echo '&nbsp;&nbsp;';
	if ($this->action == 'selecties') {
		echo $html->link('Selecties', array(
				'controller' => 'iz_deelnemers',
				'action' => 'selecties',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Selecties', array(
		'controller' => 'iz_deelnemers',
		'action' => 'selecties',
	));
	}
	echo '&nbsp;&nbsp;';
	if ($this->action == 'intervisiegroepen') {
		echo $html->link('Intervisiegroepen', array(
				'controller' => 'iz_deelnemers',
				'action' => 'intervisiegroepen',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Intervisiegroepen', array(
		'controller' => 'iz_deelnemers',
		'action' => 'intervisiegroepen',
	));
	}
	echo '&nbsp;&nbsp;';
	if ($this->params['controller'] == 'iz_koppelingen'
		&& $this->params['action'] == 'index'
	) {
		echo $html->link('Koppellijst', array(
				'controller' => 'iz_koppelingen',
				'action' => 'index',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Koppellijst', array(
			'controller' => 'iz_koppelingen',
			'action' => 'index',
	));
	}
	echo '&nbsp;&nbsp;';
	if ($this->action == 'beheer'
		|| !in_array($this->name, ['IzDeelnemers', 'IzVrijwilligers', 'IzRapportages'])
	) {
		echo $html->link('Beheer', array(
				'controller' => 'iz_deelnemers',
				'action' => 'beheer',
			),
			array('class' => 'selected')
		);
	} else {
		echo $html->link('Beheer', array(
			'controller' => 'iz_deelnemers',
			'action' => 'beheer',
		));
	}
	echo '&nbsp;&nbsp;';
	echo '<div>&nbsp;</div>'
	?>
</div>
