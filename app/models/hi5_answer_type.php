<?php

class Hi5AnswerType extends AppModel
{
    public $name = 'Hi5AnswerType';

    public $displayField = 'answer_type';

    public $hasMany = [
            'Hi5Answer' => [
                    'className' => 'Hi5Answer',
                    'foreignKey' => 'hi5_question_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
    ];
}
