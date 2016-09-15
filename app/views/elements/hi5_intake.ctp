<?php
	$logged_in_user_id = $this->Session->read('Auth.Medewerker.id');
?>
<fieldset>
	<legend>Intakes voor Hi5</legend>
	<?php
		if ($viewElementOptions & HI5_CREATE_INTAKES) {
			echo $html->link('ZRM overzicht', array('controller' => 'Hi5', 'action' => 'zrm', $data['Klant']['id']));
			echo '<br/>';
			echo $html->link('Nieuwe intake toevoegen', array('controller' => 'Hi5', 'action' => 'add_intake', $data['Klant']['id']));
			echo '<br/>';
			echo $html->link(__('Leeg drukken', true), array('controller' => 'Hi5', 'action' => 'print_empty_intake'));
			echo '<br/><br/>';
		}
	?>

	<ul>
	<?php
		if (! empty($data['Hi5Intake'])) {
			foreach ($data['Hi5Intake'] as $intake) {
				?>
		<li>
			<?= $format->printData($date->show($intake['datum_intake'])); ?> door
			<?= $format->printData($intake['Medewerker']['name']) ?>

			<?php

				echo '<div class="right-aligned-tools">';

				$zoom = $html->image('zoom.png');

				$url = array(
					'controller' => 'hi5',
					'action' => 'view_intake',
					$intake['id'],
				);
				$opts = array('escape' => false, 'title' => __('view', true));
				echo $this->Html->link($zoom, $url, $opts);

				if (
					$viewElementOptions & HI5_CREATE_INTAKES &&
					date('Y-m-d', strtotime($intake['created'])) == date('Y-m-d') &&
					$logged_in_user_id == $intake['medewerker_id']
				) {
					$wrench = $html->image('wrench.png');
					$url = array(
						'controller' => 'hi5',
						'action' => 'edit_intake',
						$intake['id'],
					);
					$opts = array('escape' => false, 'title' => __('edit', true));
					echo $html->link($wrench, $url, $opts);
				}
				echo '</div>'; ?>
		</li>
	<?php

			}
		}
	?>
	</ul>
</fieldset>
