<div id="subheader">
    <?php
        if ($this->name == 'Groepsactiviteiten'
            && $this->action == 'index'
            && empty($this->params['named'])
        ) {
            echo $html->link(
                'Deelnemerslijst',
                array(
                    'controller' => 'Groepsactiviteiten',
                    'action' => 'index',
                ),
                array('class' => 'selected')
            );
        } else {
            echo $html->link(
                'Deelnemerslijst',
                array(
                    'controller' => 'Groepsactiviteiten',
                    'action' => 'index',
                )
            );
        }
    ?>
    &nbsp;&nbsp;
    <?php
        if ($this->name == 'Groepsactiviteiten'
            && $this->action == 'index'
            && !empty($this->params['named'])
        ) {
            echo $html->link(
                'Vrijwilligerslijst',
                array(
                    'controller' => 'Groepsactiviteiten',
                    'action' => 'index',
                    'selectie' => 'Vrijwilliger',
                ),
                array('class' => 'selected')
            );
        } else {
            echo $html->link(
                'Vrijwilligerslijst',
                array(
                    'controller' => 'Groepsactiviteiten',
                    'action' => 'index',
                    'selectie' => 'Vrijwilliger',
                )
            );
        }
    ?>
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
        if ($this->action == 'rapportages') {
            echo $html->link(
                'Rapportages OUD',
                array(
                    'controller' => 'Groepsactiviteiten',
                    'action' => 'rapportages',
                ),
                array('class' => 'selected')
            );
        } else {
            echo $html->link(
                'Rapportages OUD',
                array(
                    'controller' => 'Groepsactiviteiten',
                    'action' => 'rapportages',
                )
            );
        }
    ?>
    &nbsp;&nbsp;
    <?php
        echo $html->link('Rapportages NIEUW', array(
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
