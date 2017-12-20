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
class ArosAcoFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'ArosAco'
     */
    public $name = 'ArosAco';

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
    public $records = [];
}
