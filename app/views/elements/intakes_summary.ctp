<?php
    if (isset($intake_type)) {
        $intake_controller = $intake_type.'_intakes';
        $intake_model = Inflector::classify($intake_type).'Intake';
    } else {
        $intake_controller = 'intakes';
        $intake_model = 'Intake';
    }
?>

<fieldset>
    <legend>Intakes</legend>
    <p>
        <?= $this->Html->link('ZRM overzicht', array(
            'controller' => 'klanten',
            'action' => 'zrm',
            $klant['Klant']['id'],
        )) ?>
        <br/>
        <?= $this->Html->link('ZRM toevoegen', array(
            'controller' => 'klanten',
            'action' => 'zrm_add',
            $klant['Klant']['id'],
        )) ?>
        <br/>
        <?= $this->Html->link('Intake toevoegen', array(
            'controller' => $intake_controller,
            'action' => 'add',
            $klant['Klant']['id'],
        ))
        ?>
        <br/>
        <?= $this->Html->link(__('Leeg drukken', true), array(
            'controller' => $intake_controller,
            'action' => 'print_empty',
        )) ?>
    </p>
    <br/>
    <?php if (empty($klant[$intake_model])): ?>
        <p>Nog geen intakes opgeslagen</p>
    <?php else: ?>
        Laatste intake:
        <ul>
            <?php $intake = current($klant[$intake_model]); ?>
            <li>
                <?= $date->show($intake['datum_intake'], array('separator' => ' ')) ?>
                <?= $this->Html->link(
                        $html->image('zoom.png'),
                        [
                            'controller' => $intake_controller,
                            'action' => 'view',
                            $intake['id'],
                        ],
                        ['escape' => false, 'title' => __('view', true)]
                ); ?>
                <?php if (substr($intake['created'], 0, 10) == date('Y-m-d')
                    && $this->Session->read('Auth.Medewerker.id') == $intake['medewerker_id']
                ): ?>
                    <?= $this->Html->link(
                        $html->image('wrench.png'),
                        [
                            'controller' => $intake_controller,
                            'action' => 'edit',
                            $intake['id'],
                        ],
                        ['escape' => false, 'title' => __('edit', true)]
                    ) ?>
                <?php endif; ?>
            </li>
        </ul>
        <?php if (count($klant[$intake_model]) > 1): ?>
            Naar intake-overzicht
            <?= $this->Html->link(
                $html->image('zoom.png'),
                [
                    'controller' => $intake_controller,
                    'action' => 'index',
                    $klant['Klant']['id'],
                ],
                [
                    'escape' => false,
                    'title' => __('view', true)
                ]
            ) ?>
        <?php endif; ?>
    <?php endif; ?>
</fieldset>
