<?php
/**
 * Short description for file.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html>
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html CakePHP(tm) Tests
 * @since         CakePHP(tm) v 1.2.0.4667
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 */
class ArosAcoTwoFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'ArosAcoTwo'
     */
    public $name = 'ArosAcoTwo';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'aro_id' => ['type' => 'integer', 'length' => 10, 'null' => false],
        'aco_id' => ['type' => 'integer', 'length' => 10, 'null' => false],
        '_create' => ['type' => 'string', 'length' => 2, 'default' => 0],
        '_read' => ['type' => 'string', 'length' => 2, 'default' => 0],
        '_update' => ['type' => 'string', 'length' => 2, 'default' => 0],
        '_delete' => ['type' => 'string', 'length' => 2, 'default' => 0],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['id' => 1, 'aro_id' => '1', 'aco_id' => '1', '_create' => '-1',  '_read' => '-1', '_update' => '-1', '_delete' => '-1'],
        ['id' => 2, 'aro_id' => '2', 'aco_id' => '1', '_create' => '0',  '_read' => '1', '_update' => '1', '_delete' => '1'],
        ['id' => 3, 'aro_id' => '3', 'aco_id' => '2', '_create' => '0',  '_read' => '1', '_update' => '0', '_delete' => '0'],
        ['id' => 4, 'aro_id' => '4', 'aco_id' => '2', '_create' => '1',  '_read' => '1', '_update' => '0', '_delete' => '-1'],
        ['id' => 5, 'aro_id' => '4', 'aco_id' => '6', '_create' => '1',  '_read' => '1', '_update' => '0', '_delete' => '0'],
        ['id' => 6, 'aro_id' => '5', 'aco_id' => '1', '_create' => '1',  '_read' => '1', '_update' => '1', '_delete' => '1'],
        ['id' => 7, 'aro_id' => '6', 'aco_id' => '3', '_create' => '-1',  '_read' => '1', '_update' => '-1', '_delete' => '-1'],
        ['id' => 8, 'aro_id' => '6', 'aco_id' => '4', '_create' => '-1',  '_read' => '1', '_update' => '-1', '_delete' => '1'],
        ['id' => 9, 'aro_id' => '6', 'aco_id' => '6', '_create' => '-1',  '_read' => '1', '_update' => '1', '_delete' => '-1'],
        ['id' => 10, 'aro_id' => '7', 'aco_id' => '2', '_create' => '-1',  '_read' => '-1', '_update' => '-1', '_delete' => '-1'],
        ['id' => 11, 'aro_id' => '7', 'aco_id' => '7', '_create' => '1',  '_read' => '1', '_update' => '1', '_delete' => '0'],
        ['id' => 12, 'aro_id' => '7', 'aco_id' => '8', '_create' => '1',  '_read' => '1', '_update' => '1', '_delete' => '0'],
        ['id' => 13, 'aro_id' => '7', 'aco_id' => '9', '_create' => '1',  '_read' => '1', '_update' => '1', '_delete' => '1'],
        ['id' => 14, 'aro_id' => '7', 'aco_id' => '10', '_create' => '0',  '_read' => '0', '_update' => '0', '_delete' => '1'],
        ['id' => 15, 'aro_id' => '8', 'aco_id' => '10', '_create' => '1',  '_read' => '1', '_update' => '1', '_delete' => '1'],
        ['id' => 16, 'aro_id' => '8', 'aco_id' => '2', '_create' => '-1',  '_read' => '-1', '_update' => '-1', '_delete' => '-1'],
        ['id' => 17, 'aro_id' => '9', 'aco_id' => '4', '_create' => '1',  '_read' => '1', '_update' => '1', '_delete' => '-1'],
        ['id' => 18, 'aro_id' => '9', 'aco_id' => '9', '_create' => '0',  '_read' => '0', '_update' => '1', '_delete' => '1'],
        ['id' => 19, 'aro_id' => '10', 'aco_id' => '9', '_create' => '1',  '_read' => '1', '_update' => '1', '_delete' => '1'],
        ['id' => 20, 'aro_id' => '10', 'aco_id' => '10', '_create' => '-1',  '_read' => '-1', '_update' => '-1', '_delete' => '-1'],
    ];
}
