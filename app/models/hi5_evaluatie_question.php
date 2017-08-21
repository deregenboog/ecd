<?php

class Hi5EvaluatieQuestion extends AppModel
{
    public $name = 'Hi5EvaluatieQuestion';

    public $belongsTo = [
            'Hi5EvaluatieParagraph' => [
                    'className' => 'Hi5EvaluatieParagraph',
                    'foreignKey' => 'hi5_evaluatie_paragraph_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ],
    ];

    public function processPostedData($questionData)
    {
        $return = [];

        foreach ($questionData as $questionId => $questionAnswers) {
            $item = $questionAnswers;
            $item['hi5_evaluatie_question_id'] = $questionId;
            $return[] = $item;
        }

        return ['Hi5EvaluatieQuestion' => $return];
    }

    public function processRetrievedData($retrievedData)
    {
        foreach ($retrievedData as $q_id => $block) {
            $retrievedData[$q_id] = $block['Hi5EvaluatiesHi5EvaluatieQuestion'];
        }

        return ['Hi5EvaluatieQuestion' => $retrievedData];
    }
}
