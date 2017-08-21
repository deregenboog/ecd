<?php

class Hi5EvaluatieParagraph extends AppModel
{
    public $name = 'Hi5EvaluatieParagraph';

    public $hasMany = [
            'Hi5EvaluatieQuestion' => [
                    'className' => 'Hi5EvaluatieQuestion',
                    'foreignKey' => 'hi5_evaluatie_paragraph_id',
                    'dependent' => false,
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
                    'limit' => '',
                    'offset' => '',
                    'exclusive' => '',
                    'finderQuery' => '',
                    'counterQuery' => '',
            ],
    ];

    public function getParagraphs()
    {
        $paragraphList = $this->find('all', [
                'recursive' => 2,
        ]);

        $paragraphResult = [];

        foreach ($paragraphList as $paragraphDetails) {
            $paragraphId = $paragraphDetails['Hi5EvaluatieParagraph']['id'];
            $paragraphResult[$paragraphId]['paragraph'] = $paragraphDetails['Hi5EvaluatieParagraph']['text'];

            foreach ($paragraphDetails['Hi5EvaluatieQuestion'] as $questionDetails) {
                $questionId = $questionDetails['id'];
                $question = $questionDetails['text'];
                $paragraphResult[$paragraphId]['questions'][$questionId] = $question;
            }
        }

        return $paragraphResult;
    }
}
