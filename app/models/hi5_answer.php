<?php

class Hi5Answer extends AppModel
{
    public $name = 'Hi5Answer';
    
    public $displayField = 'answer';
    
    public $belongsTo = array(
            'Hi5Question' => array(
                    'className' => 'Hi5Question',
                    'foreignKey' => 'hi5_question_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
            'Hi5AnswerType' => array(
                    'className' => 'Hi5AnswerType',
                    'foreignKey' => 'hi5_answer_type_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
    );

    public function processPostedData($posted_data)
    {
        $result = array();
        
        foreach ($posted_data as $ans_id => $block) {
            $item = array();
            $item['hi5_answer_id'] = $ans_id;
            
            if (is_array($block)) {
                if (!empty($block['hi5_answer_text'])) {
                    $item['hi5_answer_text'] = $block['hi5_answer_text'];
                } else {
                    continue;
                }
            }
            
            $result[] = $item;
        }
        return array( 'Hi5Answer' => $result);
    }

    public function processRetrievedData($retrievedData)
    {
        if (!$retrievedData) {
            return $retrievedData;
        }
        
        foreach ($retrievedData as $ans_id => $block) {
            $retrievedData[$ans_id] = $block['Hi5IntakesAnswer'];
        }
        
        return array( 'Hi5Answer' => $retrievedData);
    }
}
