<?php

$logged_in_user_id = $this->Session->read('Auth.Medewerker.id');
?>
<fieldset>
	<legend>Tussentijdse evaluaties</legend>
	<?php 
		if ($viewElementOptions & HI5_CREATE_EVALUATIONS) {
			echo $html->link('Nieuwe evaluatie toevoegen', array('controller'=> 'Hi5', 'action' => 'add_evaluatie', $data['Klant']['id']));
			echo '<br/>';
			echo $html->link(__('Leeg drukken', true), array('controller'=> 'Hi5', 'action' => 'print_empty_evaluatie'));
		}
	?>
	<ul>
	<?php 
		if (! empty($data['Hi5Evaluatie'])) {
			foreach ($data['Hi5Evaluatie'] as $evaluatie) {
				?>
		<li>
			<?= $format->printData($date->show($evaluatie['datumevaluatie'])); ?> door
			<?= $format->printData($evaluatie['Medewerker']['name']) ?>

			<?php

				echo '<div class="right-aligned-tools">';

				$zoom = $html->image('zoom.png');

				$url = array(
					'controller' => 'hi5',
					'action' => 'view_evaluatie',
					$evaluatie['id'],
				);
				
				$opts = array('escape' => false, 'title' => __('view', true));
				echo $this->Html->link($zoom, $url, $opts);

				if (
					($viewElementOptions & HI5_CREATE_EVALUATIONS) &&
					date('Y-m-d', strtotime($evaluatie['created'])) == date('Y-m-d') &&
					$logged_in_user_id == $evaluatie['medewerker_id']
				) {
					$wrench = $html->image('wrench.png');
					$url = array(
						'controller' => 'hi5',
						'action' => 'edit_evaluatie',
						$evaluatie['id'],
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
