<?php

class verslaginfo extends AppModel
{
    public $name = 'Verslaginfo';
    
    public $displayField = 'klant_id';
    
    public $validate = array(
        'klant_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'casemanager_email' => array(
            'email' => array(
                'rule' => array('email'),
                'message' => 'Niet een geldig e-mailadres.',
                'allowEmpty' => true,
                'required' => false,
            ),
        ),
        'trajectbegeleider_email' => array(
            'email' => array(
                'rule' => array('email'),
                'message' => 'Niet een geldig e-mailadres.',
                'allowEmpty' => true,
                'required' => false,
            ),
        ),
        'trajecthouder_extern_email' => array(
            'email' => array(
                'rule' => array('email'),
                'message' => 'Niet een geldig e-mailadres.',
                'allowEmpty' => true,
                'required' => false,
            ),
        ),
        'klantmanager_email' => array(
            'email' => array(
                'rule' => array('email'),
                'message' => 'Niet een geldig e-mailadres.',
                'allowEmpty' => true,
                'required' => false,
            ),
        ),

    );

    public $belongsTo = array(
        'Klant' => array(
            'className' => 'Klant',
            'foreignKey' => 'klant_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );
}
