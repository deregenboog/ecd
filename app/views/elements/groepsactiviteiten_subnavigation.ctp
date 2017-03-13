<div id="subheader">
    <?= $html->link(
        'Deelnemers',
        array(
            'controller' => 'groepsactiviteiten_klanten',
            'action' => 'index',
        )
    ) ?>
    &nbsp;&nbsp;
    <?= $html->link(
        'Vrijwilligers',
        array(
            'controller' => 'groepsactiviteiten_vrijwilligers',
            'action' => 'index',
        )
    ) ?>
    &nbsp;&nbsp;
    <?php
        if ($this->action == 'export') {
            echo $html->link(
                'Groepen',
                array(
                    'controller' => 'Groepsactiviteiten',
                    'action' => 'export',
                ),
                array('class' => 'selected')
            );
        } else {
            echo $html->link(
                'Groepen',
                array(
                    'controller' => 'Groepsactiviteiten',
                    'action' => 'export',
                )
            );
        }
    ?>
    &nbsp;&nbsp;
    <?php
        if ($this->action == 'planning') {
            echo $html->link(
                'Activiteitenregistratie',
                array(
                    'controller' => 'Groepsactiviteiten',
                    'action' => 'planning',
                ),
                array('class' => 'selected')
                );
            } else {
                echo $html->link('Activiteitenregistratie', array(
                    'controller' => 'Groepsactiviteiten',
                    'action' => 'planning',
                )
            );
        }
    ?>
    &nbsp;&nbsp;
    <?php
        echo $html->link('Rapportages', array(
            'controller' => 'groepsactiviteiten_rapportages',
            'action' => 'index',
        ));
    ?>
    &nbsp;&nbsp;
    <?php
        if ($this->action == 'selecties') {
            echo $html->link(
                'Selecties',
                array(
                    'controller' => 'Groepsactiviteiten',
                    'action' => 'selecties',
                ),
                array('class' => 'selected')
            );
        } else {
            echo $html->link(
                'Selecties',
                array(
                    'controller' => 'Groepsactiviteiten',
                    'action' => 'selecties',
                )
            );
        }
    ?>
    &nbsp;&nbsp;
    <?php
        if ($this->action == 'beheer' || $this->name != 'Groepsactiviteiten') {
            echo $html->link(
                'Beheer',
                array(
                    'controller' => 'Groepsactiviteiten',
                    'action' => 'beheer',
                ),
                array('class' => 'selected')
            );
        } else {
            echo $html->link(
                'Beheer',
                array(
                    'controller' => 'Groepsactiviteiten',
                    'action' => 'beheer',
                )
            );
        }
    ?>
    &nbsp;&nbsp;
    <div>&nbsp;</div>
</div>
