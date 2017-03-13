<div id="subheader">
    <?php
        echo $html->link('Deelnemers', array(
            'controller' => 'iz_klanten',
            'action' => 'index',
        ));
        echo "&nbsp;&nbsp;";
        if ($this->action == 'index'
            && $this->name == 'IzDeelnemers'
            && isset($this->params['named']['Vrijwilliger.selectie'])
            && ! isset($this->params['named']['Vrijwilliger.wachtlijst'])
        ) {
            echo $html->link('Vrijwilligers', array(
                'controller' => 'iz_deelnemers',
                'action' => 'index',
                'Vrijwilliger.selectie' => '1'
            ), array(
                'class' => 'selected'
            ));
        } else {
            echo $html->link('Vrijwilligers', array(
                'controller' => 'iz_deelnemers',
                'action' => 'index',
                'Vrijwilliger.selectie' => '1'
            ));
        }
        echo "&nbsp;&nbsp;";
        echo $html->link('Hulpvragen', array(
            'controller' => 'iz_hulpvragen',
            'action' => 'index'
        ));
        echo "&nbsp;&nbsp;";
        echo $html->link('Hulpaanbiedingen', array(
            'controller' => 'iz_hulpaanbiedingen',
            'action' => 'index'
        ));
        echo "&nbsp;&nbsp;";
        if ($this->name == 'Izkoppelingen') {
            echo $html->link('Koppelingen', array(
                'controller' => 'iz_koppelingen',
                'action' => 'index'
            ), array(
                'class' => 'selected'
            ));
        } else {
            echo $html->link('Koppelingen', array(
                'controller' => 'iz_koppelingen',
                'action' => 'index'
            ));
        }
        echo "&nbsp;&nbsp;";
        echo $html->link('Rapportages', array(
            'controller' => 'iz_rapportages',
        ));
        echo "&nbsp;&nbsp;";
        echo $html->link('Selecties', array(
            'controller' => 'iz_deelnemers',
            'action' => 'selecties'
        ));
        echo "&nbsp;&nbsp;";
        if ($this->action == 'intervisiegroepen') {
            echo $html->link('Intervisiegroepen', array(
                'controller' => 'iz_deelnemers',
                'action' => 'intervisiegroepen'
            ), array(
                'class' => 'selected'
            ));
        } else {
            echo $html->link('Intervisiegroepen', array(
                'controller' => 'iz_deelnemers',
                'action' => 'intervisiegroepen'
            ));
        }
        echo "&nbsp;&nbsp;";
        if ($this->action == 'beheer' || $this->name != 'IzDeelnemers') {
            echo $html->link('Beheer', array(
                'controller' => 'iz_deelnemers',
                'action' => 'beheer'
            ), array(
                'class' => 'selected'
            ));
        } else {
            echo $html->link('Beheer', array(
                'controller' => 'iz_deelnemers',
                'action' => 'beheer'
            ));
        }
        echo "&nbsp;&nbsp;";
        echo "<div>&nbsp;</div>"
    ?>
</div>
