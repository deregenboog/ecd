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

        $result = $this->find('first', [
            'conditions' => [
                'van <=' => $postcode,
                'tot >=' => $postcode,
            ],
        ]);

        if (!empty($result['Postcodegebied']['postcodegebied'])) {
            return $result['Postcodegebied']['postcodegebied'];
        }

        return self::DEFAULT_POSTCODEGEBIED;
    }
}
