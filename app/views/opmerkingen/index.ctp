<div class="borderedContent form">
    <h2 class="commentedHeader">Opmerkingen</h2>
    <p class="headerComment">
        <?php $klant_id = $klant['Klant']['id']; ?>
        <?php if (!empty($klant['Klant']['tussenvoegsel'])) {
                    $klant['Klant']['tussenvoegsel'] = ' '.$klant['Klant']['tussenvoegsel'];
                } ?>
        <?= ' van '.$klant['Klant']['voornaam'].' '.
                $klant['Klant']['roepnaam']. $klant['Klant']['tussenvoegsel'].
                ' '.$klant['Klant']['achternaam'];
        ?>
    </p>
    <p>
        <?= $html->link('Opmerking toevoegen', ['action' => 'add', $klant_id]) ?>
    </p>
    <?php if (count($opmerkingen) > 0): ?>
        <p>
            <?php foreach ($opmerkingen as $opmerking): ?>
                <?php $opmId = $opmerking['Opmerking']['id']; ?>
                <div id="ajax<?= $opmId ?>" class="editWrenchFloat">
                    <?php $icon = $this->Html->image('trash.png', ['title' => __('delete', true)]); ?>
                    <?= $this->Html->link(
                        $icon,
                        ['action' => 'delete', $opmId, $klant_id],
                        ['escape' => false],
                        __('Are you sure you want to delete the note?', true)
                    ); ?>
                    <?= $this->Form->create('opm'.$opmId) ?>
                    <?= $this->Form->input('opgelost', [
                        'type' => 'checkbox',
                        'checked' => $opmerking['Opmerking']['gezien'],
                        'name' => 'data[opmerking][gezien]',
                    ]) ?>
                    <?= $this->Form->end() ?>
                    <?= $this->Js->get('#opm'.$opmId.'Opgelost')->event('change', $this->Js->request(
                        ['action' => 'gezien', $opmId],
                        ['method' => 'get', 'async' => false]
                    )) ?>
                </div>
                <h3 class="commentedHeader">
                    <?= $date->show($opmerking['Opmerking']['modified'], ['short' => true]) ?>
                </h3>
                <p class="headerComment">
                    (Categorie: <?= $opmerking['Categorie']['naam'] ?>)
                </p>
                <p>
                    <?= $opmerking['Opmerking']['beschrijving'] ?>
                    <br><br>
                </p>
            <?php endforeach; ?>
        </p>
        <?= $this->Js->writeBuffer() ?>
    <?php else: ?>
        <p>Geen opmerkingen.</p>
    <?php endif; ?>
    <p>
        <?php if (isset($locatie_id)) {
            $target = ['controller' => 'registraties', 'action' => 'index', $locatie_id];
            } else {
            $target = ['controller' => 'klanten','action' => 'view', $klant['Klant']['id']];
        } ?>
        <?= $html->link('Terug', $target, ['class' => 'back']) ?>
    </p>
</div> <!-- opmerkingen div end -->
<div class="actions">
    <?= $this->element('klantbasic', ['data' => $klant]) ?>
    <?= $this->element('diensten', ['diensten' => $diensten]) ?>
</div>
