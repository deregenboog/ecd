<div class="actions">
	<?php echo $this->element('klantbasic', array('data' => $klant)); ?>
	<?php echo $this->element('diensten', array( 'diensten' => $diensten, )); ?>
	<?php echo $this->Html->link('Terug naar klantoverzicht', array('controller' => 'klanten', 'action' => 'view', $klant['Klant']['id']))?>
</div>
<div class="intakes view">
	<?php echo $this->element('intake', array('data' => $intake)); ?>
</div>

