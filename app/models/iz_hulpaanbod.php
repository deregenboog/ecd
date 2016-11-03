<?php

class IzHulpaanbod extends IzKoppeling
{
    public $name = 'IzHulpaanbod';

    public $hasOne = array(
        'IzHulpvraag' => array(
            'className' => 'IzHulpvraag',
            'foreignKey' => 'iz_koppeling_id',
        ),
    );

    public $belongsTo = array(
        'IzVrijwilliger' => array(
            'className' => 'IzVrijwilliger',
            'foreignKey' => 'iz_deelnemer_id',
            'conditions' => array('model' => 'Vrijwilliger'),
        ),
        'IzProject' => array(
            'className' => 'IzProject',
            'foreignKey' => 'project_id',
        ),
        'Medewerker' => array(
            'className' => 'Medewerker',
            'foreignKey' => 'medewerker_id',
        ),
        'IzEindekoppeling' => array(
            'className' => 'IzEindekoppeling',
            'foreignKey' => 'iz_eindekoppeling_id',
        ),
        'IzVraagaanbod' => array(
            'className' => 'IzVraagaanbod',
            'foreignKey' => 'iz_vraagaanbod_id',
        ),
    );
}
