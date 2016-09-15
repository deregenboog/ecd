<?php

	$today = date('Y-m-d');

?>

<div class="actions">
	<?php 
		echo $this->element('klantbasic', array('data' => $klant));
		echo $this->element('diensten', array( 'diensten' => $diensten, ));

		echo $this->Html->link('Terug naar klantoverzicht', array('controller' => 'hi5', 'action' => 'view', $klant['Klant']['id']));
	?>

</div>

<div class="intakes view">
<?php
	echo '<div class="editWrench">';
	$printer_img = $this->Html->image('printer.png');
	echo '<a href="#" onclick="window.print()">'.$printer_img.'</a>';
	echo '</div>';
?>

<div class="fieldset">
		<h1><?php __('HI5 Intake'); ?></h1>
		<fieldset>
		<?php
		foreach ($zrmReports as $zrmReport) {
			if (isset($zrmReport)) {
				echo $this->element('zrm_view', array('data' => $zrmReport));
			}
		}
		?>
	</fieldset>
</div>
