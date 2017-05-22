<div class="actions">
    <?= $this->element('klantbasic', array('data' => $klant)) ?>
    <?= $this->Html->link('Terug naar klantoverzicht', array('controller' => 'klanten', 'action' => 'view', $klant['Klant']['id'])) ?>
</div>
<div class="intakes index">
    <h2><?php __('Intakes');?></h2>
    <p>
        <fieldset>
            <ul>
                <?php foreach ($intakes as $intake): ?>
                    <li>
                        <?= $date->show($intake['Intake']['datum_intake'], array('separator' => ' ')) ?>
                        <?= $this->Html->link(
                                $html->image('zoom.png'),
                                [
                                    'controller' => 'intakes',
                                    'action' => 'view',
                                    $intake['Intake']['id'],
                                ],
                                ['escape' => false, 'title' => __('view', true)]
                        ); ?>
                        <?php if (substr($intake['Intake']['created'], 0, 10) == date('Y-m-d')
                            && $this->Session->read('Auth.Medewerker.id') == $intake['medewerker_id']
                        ): ?>
                            <?= $this->Html->link(
                                $html->image('wrench.png'),
                                [
                                    'controller' => 'intakes',
                                    'action' => 'edit',
                                    $intake['Intake']['id'],
                                ],
                                ['escape' => false, 'title' => __('edit', true)]
                            ) ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
    </p>
</div>
