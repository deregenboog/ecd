<?php

class Hi5Question extends AppModel
{
    public $name = 'Hi5Question';

    public $displayField = 'question';

    public $hasMany = array(
            'Hi5Answer' => array(
                    'className' => 'Hi5Answer',
                    'foreignKey' => 'hi5_question_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
            ),
    );

    public function getQuestions()
    {
        $questionList = $this->find('all', array(
                'recursive' => 2,
                'order' => 'order ASC',
        ));

        $questionResult = [];

        foreach ($questionList as $questionDetails) {
            $questionId = $questionDetails['Hi5Question']['id'];
            $questionResult[$questionId]['question'] = $questionDetails['Hi5Question']['question'];
            $questionResult[$questionId]['category'] = $questionDetails['Hi5Question']['category'];

            foreach ($questionDetails['Hi5Answer'] as $answerDetails) {
                $answerType = $answerDetails['Hi5AnswerType']['answer_type'];
                $answerId = $answerDetails['id'];
                $answer = $answerDetails['answer'];
                $questionResult[$questionId]['answers'][$answerType][$answerId] = $answer;
            }
        }

        return $questionResult;
    }
}
