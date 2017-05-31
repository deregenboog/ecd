<div class="verslagen view">
    <?php if (!$lastZrmReport): ?>
        <div class="warning">
            LET OP: geen ZRM aanwezig. Klik
            <?= $this->Html->link('hier', ['controller' => 'klanten', 'action' => 'zrm_add', $klant['Klant']['id']]) ?>
            om een ZRM toe te voegen.
        </div>
    <?php endif; ?>
    <?php $zrmDagen = 183; ?>
    <?php if ($lastZrmReport && new \DateTime("-{$zrmDagen} days") > new \DateTime($lastZrmReport['ZrmReport']['created'])): ?>
        <div class="warning">
            LET OP: laatste ZRM is meer dan <?= $zrmDagen ?> dagen oud. Klik
            <?= $this->Html->link('hier', ['controller' => 'klanten', 'action' => 'zrm_add', $klant['Klant']['id']]) ?>
            om een nieuwe ZRM toe te voegen.
        </div>
    <?php endif; ?>
    <?= $this->element('verslagen_index', array(compact('verslagen'))) ?>
</div>

<div class="actions">
    <?= $this->element('persoon_view_basic', [
        'name' => 'Klant',
        'data' => $klant,
        'show_documents' => false,
        'view' => $this,
        'logged_in_user_id' => $this->Session->read('Auth.Medewerker.id'),
    ]) ?>

    <?= $this->element('diensten', ['diensten' => $diensten]) ?>

    <?= $this->element('klantdocuments', array('data' => $klant, 'group' => Attachment::GROUP_MW));?>

    <?= $this->element('verslaginfo', array(
            'verslaginfo' => $verslaginfo,
            'klantId' => $klant['Klant']['id'],
    )) ?>
    <div class="links">
        <?= $this->Html->link('Nieuw verslag invoeren', array(
            'controller' => 'maatschappelijk_werk',
            'action' => 'add',
            $klant['Klant']['id'],
        )) ?>
        <br/>
        <?= $this->Html->link('Rapportage', array(
            'controller' => 'maatschappelijk_werk',
            'action' => 'rapportage',
        )) ?>
    </div>
</div>
