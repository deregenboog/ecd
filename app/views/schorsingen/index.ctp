<div class="borderedContent form">
    <?php
        $active_count = count($active_schorsingen);
        $expired_count = count($expired_schorsingen);
    ?>
    <h2 class="commentedHeader"><?php __('Schorsingen');?></h2>
    <p class="headerComment">
        <?php if (!empty($klant['Klant']['tussenvoegsel'])): ?>
            <?php $klant['Klant']['tussenvoegsel'] = ' '.$klant['Klant']['tussenvoegsel']; ?>
        <?php endif; ?>
        van
        <?= $klant['Klant']['voornaam'] ?>
        <?= $klant['Klant']['roepnaam'] ?>
        <?= $klant['Klant']['tussenvoegsel'] ?>
        <?= $klant['Klant']['achternaam'] ?>
    </p>
    <br>
    <?php
        $add_url = array('action' => 'add', $klant_id);
        if ($locatie_id != null) {
            $add_url[] = $locatie_id;
        }
    ?>
    <?= $html->link('Schorsing toevoegen', $add_url) ?>
    <br>
    <br>
    <h3 class="commentedHeader">Huidige schorsingen</h3>
    <p class="headerComment">
        (<?= $active_count.' stuk'.($active_count == 1 ? '' : 's') ?>)
    </p>
    <br>
    <br>
    <?php if ($active_count > 0): ?>
        <?php foreach ($active_schorsingen as $schorsing): ?>
            <p>
                <?php $schId = $schorsing['Schorsing']['id']; ?>
                <div id="ajax<?= $schId ?>" class="editWrenchFloat">
                    <?= $html->link(
                        $html->image('wrench.png'),
                        ['action' => 'edit', $schId],
                        ['escape' => false, 'title' => __('edit', true)]
                    ) ?>
                    <?php if (in_array('CN=ECD Teamleiders,CN=Users,DC=cluster,DC=deregenboog', $this->Session->read('Auth.Medewerker.Group'))): ?>
                        <?= $html->link(
                            $html->image('delete.png'),
                            ['action' => 'delete', $schId],
                            [
                                'escape' => false,
                                'title' => __('delete', true),
                                'onclick' => "return confirm('Weet u zeker dat u deze schorsing wilt verwijderen?');",
                            ]
                        ) ?>
                    <?php endif; ?>
                </div>
                Op locatie(s)
                <ul>
                    <?php foreach ($schorsing['Locatie'] as $locatie): ?>
                        <li><?= $locatie['naam'] ?></li>
                    <?php endforeach; ?>
                </ul>
                geschorst van
                <strong><?= $date->show($schorsing['Schorsing']['datum_van']) ?></strong>
                tot en met
                <strong><?= $date->show($schorsing['Schorsing']['datum_tot']) ?></strong>
                <?php if (!empty($schorsing['Reden'])): ?>
                    voor
                    <ul>
                        <?php foreach ($schorsing['Reden'] as $reden): ?>
                            <li>
                                <?= $reden['naam'] ?>
                                <?php if ($reden['SchorsingenReden']['reden_id'] == 100): ?>
                                    <?= ' - '.$schorsing['Schorsing']['overig_reden'] ?>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php if (!empty($schorsing['Schorsing']['remark'])): ?>
                    Met als opmerking: <?= $schorsing['Schorsing']['remark'] ?><br>
                <?php endif; ?>
                <?= $this->Html->link(
                    __('print', true),
                    array('action' => 'get_pdf', $schorsing['Schorsing']['id']),
                    array('target' => '_blank')
                ) ?>
                |
                <?= $this->Html->link(
                    __('print english', true),
                    array('action' => 'get_pdf', $schorsing['Schorsing']['id'], 1),
                    array('target' => '_blank')
                ) ?>
                <br><br>
            </p>
        <?php endforeach;?>
    <?php else: ?>
        <p>Deze persoon is op dit moment niet geschorst.</p>
    <?php endif; ?>

    <p>&nbsp;</p>
    <h3 class="commentedHeader">Verlopen schorsingen</h3>
    <p class="headerComment">
        (<?=$expired_count.' stuk'.($active_count == 1 ? '' : 's') ?>)
    </p>
    <br>
    <br>
    <?php if ($expired_count > 0): ?>
        <?php foreach ($expired_schorsingen as $schorsing): ?>
            <p>
                <div class="editWrenchFloat">
                    <?php $schId = $schorsing['Schorsing']['id']; ?>
                    <?= $this->Form->create('sch'.$schId) ?>
                    <?= $this->Form->input('Gezien', array(
                        'type' => 'checkbox',
                        'checked' => $schorsing['Schorsing']['gezien'],
                        'name' => 'data[Schorsing][gezien]',
                        'id' => 'sch'.$schId.'Gezien',
                    )) ?>
                    <?= $this->Form->end() ?>
                    <?= $this->Js->get('#sch'.$schId.'Gezien')->event('change',
                        $this->Js->request(
                            ['action' => 'gezien', $schId],
                            ['method' => 'post', 'async' => false]
                        )
                    ) ?>
                    <?php if (in_array('CN=ECD Teamleiders,CN=Users,DC=cluster,DC=deregenboog', $this->Session->read('Auth.Medewerker.Group'))): ?>
                        <?= $html->link(
                            $html->image('delete.png'),
                            ['action' => 'delete', $schId],
                            [
                                'escape' => false,
                                'title' => __('delete', true),
                                'onclick' => "return confirm('Weet u zeker dat u deze schorsing wilt verwijderen?');",
                            ]
                        ) ?>
                    <?php endif; ?>
                </div>
                Op locatie(s)
                <ul>
                    <?php foreach ($schorsing['Locatie'] as $locatie): ?>
                        <li><?= $locatie['naam'] ?></li>
                    <?php endforeach; ?>
                </ul>
                geschorst van
                <?= $date->show($schorsing['Schorsing']['datum_van']) ?>
                tot en met
                <?= $date->show($schorsing['Schorsing']['datum_tot']) ?>
                <?php if (!empty($schorsing['Reden'])): ?>
                    voor
                    <ul>
                        <?php foreach ($schorsing['Reden'] as $reden): ?>
                            <li>
                                <?= $reden['naam'] ?>
                                <?php if ($reden['SchorsingenReden']['reden_id'] == 100): ?>
                                    <?= ' - '.$schorsing['Schorsing']['overig_reden'] ?>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php if (!empty($schorsing['Schorsing']['remark'])): ?>
                    Met als opmerking: <?= $schorsing['Schorsing']['remark'] ?><br>
                <?php endif; ?>
                <br><br>
            </p>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Deze persoon is in het verleden niet geschorst.</p>
    <?php endif; ?>

    <?= $this->Js->writeBuffer() ?>
    <p>&nbsp;</p>
    <p>
        <?php if (isset($locatie_id)): ?>
            <?php $target = array('controller' => 'registraties', 'action' => 'index', $locatie_id); ?>
        <?php else: ?>
            <?php $target = array('controller' => 'klanten', 'action' => 'view', $klant_id); ?>
        <?php endif; ?>
        <?= $html->link('Terug', $target, ['class' => 'back']) ?>
    </p>
</div>
<div class="actions">
    <?= $this->element('klantbasic', array('data' => $klant)) ?>
</div>
