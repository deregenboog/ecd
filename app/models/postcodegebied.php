<?php

class Postcodegebied extends AppModel
{
    const DEFAULT_POSTCODEGEBIED = 'Overig';

    public $name = 'Postcodegebied';
    public $primaryKey = 'postcode';
    public $validate = [
        'postcode' => [
            'alphanumeric' => [
                'rule' => ['alphanumeric'],
            ],
        ],
    ];

    public function getPostcodegebiedByPostcode($postcode)
    {
        $postcode = substr($postcode, 0, 4);

        $conditions = [
            'van <=' => $postcode,
            'tot >=' => $postcode,
        ];

        $result = $this->find('first', [
            'conditions' => $conditions,
        ]);

        if (!empty($result['Postcodegebied']['postcodegebied'])) {
            return $result['Postcodegebied']['postcodegebied'];
        } else {
            return self::DEFAULT_POSTCODEGEBIED;
        }
    }
}
