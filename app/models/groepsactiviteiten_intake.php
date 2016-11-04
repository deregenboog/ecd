<?php

class GroepsactiviteitenIntake extends AppModel
{
    const DECISION_VALUE_NO = 0;
    const DECISION_VALUE_YES = 1;

    public $name = 'GroepsactiviteitenIntake';
    public $validate = array(
        'model' => array(
            'alphanumeric' => array(
                'rule' => array('alphanumeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'foreign_key' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'intakedatum' => array(
            'date' => array(
                'rule' => array('date'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );

    public function Add2Intake($model, $foreign_key, $data)
    {
        $conditions = array(
            'foreign_key' => $foreign_key,
            'model' => $model,
        );
        $it = $this->find('first', array(
            'conditions' => $conditions,
            'fields' => array('id'),
        ));
        if (empty($it)) {
            $d = array(
                'model' => $model,
                'foreign_key' => $foreign_key,
                'medewerker_id' => $data['medewerker_id'],
                'gespreksverslag' => 'Automatisch aangemaakt door IZ-inschrijving',
                'ondernemen' => $data['ondernemen'],
                'overdag' => $data['overdag'],
                'ontmoeten' => $data['ontmoeten'],
                'regelzaken' => $data['regelzaken'],
                'intakedatum' => $data['intake_datum'],
            );
            $this->create();
            $retval = $this->save($d);

            return $retval;
        }

        return true;
    }
}
