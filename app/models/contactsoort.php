<?php

class Contactsoort extends AppModel
{
    public $name = 'Contactsoort';

    public $displayField = 'text';

    public $validate = [
        'text' => [
            'notempty' => [
                'rule' => 'notEmpty',
            ],
        ],
    ];
}
