<?php

class Hi5EvaluatieQuestion extends AppModel
{
    public $name = 'Hi5EvaluatieQuestion';

    public $belongsTo = array(
            'Hi5EvaluatieParagraph' => array(
                    'className' => 'Hi5EvaluatieParagraph',
                    'foreignKey' => 'hi5_evaluatie_paragraph_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
    );

    public function processPostedData($questionData)
    {
        $return = array();
        
        foreach ($questionData as $questionId => $questionAnswers) {
            $item = $questionAnswers;
            $item['hi5_evaluatie_question_id'] = $questionId;
            $return[] = $item;
        }
        
        return array('Hi5EvaluatieQuestion' => $return);
    }
    
    public function processRetrievedData($retrievedData)
    {
        foreach ($retrievedData as $q_id => $block) {
            $retrievedData[$q_id] = $block['Hi5EvaluatiesHi5EvaluatieQuestion'];
        }
        
        return array( 'Hi5EvaluatieQuestion' => $retrievedData);
    }
}
