<div class="actions">
    <?= $this->element('klantbasic', ['data' => $klant]); ?>
    <?= $this->element('diensten', ['diensten' => $diensten]); ?>
    <?= $this->Html->link('Terug naar klantoverzicht', ['controller' => 'klanten', 'action' => 'view', $klant['Klant']['id']]); ?>
</div>
<div class="intakes view">
    <?= $this->element('intake', ['data' => $intake]); ?>
</div>
