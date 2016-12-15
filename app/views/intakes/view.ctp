<div class="actions">
    <?= $this->element('klantbasic', array('data' => $klant)) ?>
    <?= $this->element('diensten', array( 'diensten' => $diensten, )) ?>
    <?= $this->Html->link('Terug naar klantoverzicht', array('controller' => 'klanten', 'action' => 'view', $klant['Klant']['id'])) ?>
</div>
<div class="intakes view">
    <?= $this->element('intake', array('data' => $intake)) ?>
</div>
