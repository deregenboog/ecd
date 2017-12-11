<?php

/*DbAcl schema generated on: 2007-11-24 15:11:13 : 1195945453*/
/**
 * This is Acl Schema file.
 *
 * Use it to configure database for ACL
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
 * cake schema run create DbAcl
 *
 */
class DbAclSchema extends CakeSchema
{
    public $name = 'DbAcl';

    public function before($event = [])
    {
        return true;
    }

    public function after($event = [])
    {
    }

    public $acos = [
            'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
            'parent_id' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
            'model' => ['type' => 'string', 'null' => true],
            'foreign_key' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
            'alias' => ['type' => 'string', 'null' => true],
            'lft' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
            'rght' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
            'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        ];

    public $aros = [
            'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
            'parent_id' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
            'model' => ['type' => 'string', 'null' => true],
            'foreign_key' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
            'alias' => ['type' => 'string', 'null' => true],
            'lft' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
            'rght' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
            'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        ];

    public $aros_acos = [
            'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
            'aro_id' => ['type' => 'integer', 'null' => false, 'length' => 10, 'key' => 'index'],
            'aco_id' => ['type' => 'integer', 'null' => false, 'length' => 10],
            '_create' => ['type' => 'string', 'null' => false, 'default' => '0', 'length' => 2],
            '_read' => ['type' => 'string', 'null' => false, 'default' => '0', 'length' => 2],
            '_update' => ['type' => 'string', 'null' => false, 'default' => '0', 'length' => 2],
            '_delete' => ['type' => 'string', 'null' => false, 'default' => '0', 'length' => 2],
            'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1], 'ARO_ACO_KEY' => ['column' => ['aro_id', 'aco_id'], 'unique' => 1]],
        ];
}
