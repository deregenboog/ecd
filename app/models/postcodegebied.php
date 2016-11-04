<?php

class postcodegebied extends AppModel
{
    const DEFAULT_POSTCODEGEBIED = 'Overig';

    public $name = 'Postcodegebied';
    public $primaryKey = 'postcode';
    public $validate = array(
        'postcode' => array(
            'alphanumeric' => array(
                'rule' => array('alphanumeric'),
            ),
        ),
    );

    public function getPostcodegebiedByPostcode($postcode)
    {
        $postcode = substr($postcode, 0, 4);

        $conditions = array(
            'van <=' => $postcode,
            'tot >=' => $postcode,
        );

        $result = $this->find('first', array(
            'conditions' => $conditions,
        ));

        if (!empty($result['Postcodegebied']['postcodegebied'])) {
            return $result['Postcodegebied']['postcodegebied'];
        } else {
            return self::DEFAULT_POSTCODEGEBIED;
        }
    }
}
