<?php

class Stadsdeel extends AppModel
{
    const DEFAULT_STADSDEEL = 'Overig';

    public $name = 'Stadsdeel';
    public $primaryKey = 'postcode';
    public $validate = array(
        'postcode' => array(
            'alphanumeric' => array(
                'rule' => array('alphanumeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );

    public function getStadsdeelByPostcode($postcode)
    {
        $result = $this->findByPostcode($postcode);

        if (!empty($result['Stadsdeel']['stadsdeel'])) {
            return $result['Stadsdeel']['stadsdeel'];
        } else {
            return self::DEFAULT_STADSDEEL;
        }
    }
}
