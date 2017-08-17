<?php

/*i18n schema generated on: 2007-11-25 07:11:25 : 1196004805*/
/**
 * This is i18n Schema file.
 *
 * Use it to configure database for i18n
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 0.2.9
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/*
 *
 * Using the Schema command line utility
 * cake schema run create i18n
 *
 */
class i18nSchema extends CakeSchema
{
    public $name = 'i18n';

    public function before($event = [])
    {
        return true;
    }

    public function after($event = [])
    {
    }

    public $i18n = [
            'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
            'locale' => ['type' => 'string', 'null' => false, 'length' => 6, 'key' => 'index'],
            'model' => ['type' => 'string', 'null' => false, 'key' => 'index'],
            'foreign_key' => ['type' => 'integer', 'null' => false, 'length' => 10, 'key' => 'index'],
            'field' => ['type' => 'string', 'null' => false, 'key' => 'index'],
            'content' => ['type' => 'text', 'null' => true, 'default' => null],
            'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1], 'locale' => ['column' => 'locale', 'unique' => 0], 'model' => ['column' => 'model', 'unique' => 0], 'row_id' => ['column' => 'foreign_key', 'unique' => 0], 'field' => ['column' => 'field', 'unique' => 0]],
        ];
}
