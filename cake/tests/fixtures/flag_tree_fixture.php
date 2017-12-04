<?php
/**
 * Tree behavior class test fixture.
 *
 * Enables a model object to act as a node-based tree.
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
 * @since         CakePHP(tm) v 1.2.0.5331
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Flag Tree Test Fixture.
 *
 * Like Number Tree, but uses a flag for testing scope parameters
 */
class FlagTreeFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'FlagTree'
     */
    public $name = 'FlagTree';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'name' => ['type' => 'string', 'null' => false],
        'parent_id' => 'integer',
        'lft' => ['type' => 'integer', 'null' => false],
        'rght' => ['type' => 'integer', 'null' => false],
        'flag' => ['type' => 'integer', 'null' => false, 'length' => 1, 'default' => 0],
    ];
}
