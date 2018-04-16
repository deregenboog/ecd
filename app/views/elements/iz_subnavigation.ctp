<div id="subheader">
    <?= $html->link('Mijn IZ', [
            'controller' => 'iz/mijn',
            'action' => '',
    ]); ?>
    &nbsp;&nbsp;
    <?= $html->link('Deelnemers', [
            'controller' => 'iz/klanten',
            'action' => '',
    ]); ?>
    &nbsp;&nbsp;
    <?= $html->link('Vrijwilligers', [
        'controller' => 'iz/vrijwilligers',
        'action' => 'index',
    ]); ?>
    &nbsp;&nbsp;
    <?= $html->link('Hulpvragen', [
        'controller' => 'iz/hulpvragen',
        'action' => '',
    ]); ?>
    &nbsp;&nbsp;
    <?= $html->link('Hulpaanbiedingen', [
        'controller' => 'iz/hulpaanbiedingen',
        'action' => '',
    ]); ?>
    &nbsp;&nbsp;
    <?= $html->link('Koppelingen', [
        'controller' => 'iz/koppelingen',
        'action' => '',
    ]); ?>
    &nbsp;&nbsp;
    <?= $html->link('Rapportages', [
        'controller' => 'iz/rapportages',
    ]); ?>
    &nbsp;&nbsp;
    <?= $html->link('Selecties', [
        'controller' => 'iz/selecties',
        'action' => '',
    ]); ?>
    &nbsp;&nbsp;
    <?php if ('intervisiegroepen' == $this->action): ?>
        <?= $html->link('Intervisiegroepen', [
            'controller' => 'iz_deelnemers',
            'action' => 'intervisiegroepen',
        ], [
            'class' => 'selected',
        ]); ?>
    <?php else: ?>
        <?= $html->link('Intervisiegroepen', [
                'controller' => 'iz_deelnemers',
                'action' => 'intervisiegroepen',
        ]); ?>
    <?php endif; ?>
    &nbsp;&nbsp;
    <div>&nbsp;</div>
</div>
