<?php

	 if (isset($intake_type)) {
		 $intake_controller = $intake_type.'_intakes';
		 $intake_model = Inflector::classify($intake_type).'Intake';
	 } else {
		 $intake_controller = 'intakes';
		 $intake_model = 'Intake';
	 }
?>

<fieldset><legend>Intakes</legend>
<p>
	<?php

		echo $this->Html->link('ZRM overzicht', array(
			'controller' => 'klanten',
			'action' => 'zrm',
			$klant['Klant']['id'],
		));
		echo '<br/>';
		echo $this->Html->link('ZRM toevoegen', array(
			'controller' => 'klanten',
			'action' => 'zrm_add',
			$klant['Klant']['id'],
		));
		echo '<br/>';

		echo $this->Html->link('Nieuwe intake toevoegen', array(
			'controller' => $intake_controller,
			'action' => 'add',
			$klant['Klant']['id'],
		));
		echo '<br/>';
		echo $this->Html->link(__('Leeg drukken', true), array(
			'controller' => $intake_controller,
			'action' => 'print_empty',
		));
	?>
</p>
<br/>
<?php if (!empty($klant[$intake_model])):?>
<ul>
<?php foreach ($klant[$intake_model] as $intake): ?>
	<li><?php
	echo '<div class="liText">';
	echo $date->show($intake['datum_intake'], array('separator' => ' '));
	echo '</div>';

	$zoom = $html->image('zoom.png');

	$url = array(
		'controller' => $intake_controller,
		'action' => 'view',
		$intake['id'],
	);
	$opts = array('escape' => false, 'title' => __('view', true));
	echo $this->Html->link($zoom, $url, $opts);

	$logged_in_user_id = $this->Session->read('Auth.Medewerker.id');
	if (substr($intake['created'], 0, 10) == date('Y-m-d')
		&& $logged_in_user_id == $intake['medewerker_id']
	) {
		$wrench = $html->image('wrench.png');
		$url['action'] = 'edit';
		$opts['title'] = __('edit', true);
		echo $this->Html->link($wrench, $url, $opts);
	}
	?></li>
	<?php endforeach; ?>
</ul>
	<?php else: ?>
<p>Nog geen intakes opgeslagen</p>
	<?php endif; ?></fieldset>
