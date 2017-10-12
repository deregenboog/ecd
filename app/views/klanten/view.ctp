<div class="actions">
    <?= $this->element('klantbasic', ['data' => $klant]) ?>
    <?= $this->element('diensten', ['diensten' => $diensten]) ?>
    <div class="print-invisible">
        <?= $this->element('intakes_summary', ['data' => $klant]) ?>
        <?= $this->element('opmerkingen_summary', [
            'klant_id' => $klant['Klant']['id'],
            'opmerkingen' => $opmerkingen,
        ]) ?>
        <?= $this->element('schorsingen_summary', ['data' => $klant]) ?>
        <?= $this->element('registratie_summary', ['data' => $registraties]) ?>
        <fieldset>
            <legend>Rapportage</legend>
            <p>
                <?= $html->link('Bekijk de rapportage van deze client', [
                    'controller' => 'rapportages',
                    'action' => 'klant',
                    $klant['Klant']['id']
                ]) ?>
            </p>
        </fieldset>
    </div>
</div>
<div class="klanten view">
    <?php if ($status instanceof \InloopBundle\Entity\Afsluiting): ?>
        <h2>Afgesloten inloop-dossier</h2>
        <?= $this->Html->link(
            'Inloop-dossier heropenen.',
            ['controller' => 'intakes', 'action' => 'add', $klant['Klant']['id']]
        ) ?>
        <p>&nbsp;</p>
        <dl>
            <dt>Datum afsluiting</dt>
            <dd><?= $status->getDatum()->format('d-m-Y') ?></dd>
            <dt>Reden afsluiting</dt>
            <dd><?= $status->getReden() ?></dd>
            <?php if ($status->getReden()->isLand()): ?>
                <dt>Bestemming</dt>
                <dd><?= $status->getLand() ?></dd>
            <?php endif; ?>
            <dt>Toelichting</dt>
            <dd><?= $status->getToelichting() ?></dd>
        </dl>
    <?php else: ?>
        <h2>Laatste intake</h2>
        <?= $this->Html->link(
            'Inloop-dossier afsluiten.',
            ['controller' => 'klanten', 'action' => 'close', $klant['Klant']['id']]
        ) ?>
        <?php if (!empty($newestintake)): ?>
            <?php echo $this->element('intake', ['data' => $newestintake]); ?>
        <?php else: ?>
            <p>
                Geen intakes gevonden.
                <?= $this->Html->link('Voeg een intake toe.', ['controller' => 'intakes', 'action' => 'add', $klant['Klant']['id']]) ?>
            </p>
        <?php endif; ?>
    <?php endif; ?>
</div>
