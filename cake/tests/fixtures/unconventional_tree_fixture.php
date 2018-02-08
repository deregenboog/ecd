<?php
/**
 * Unconventional Tree behavior class test fixture.
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
 * @since         CakePHP(tm) v 1.2.0.7879
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * UnconventionalTreeFixture class.
 *
 * Like Number tree, but doesn't use the default values for lft and rght or parent_id
 *
 * @uses          \CakeTestFixture
 */
class UnconventionalTreeFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'FlagTree'
     */
    public $name = 'UnconventionalTree';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'name' => ['type' => 'string', 'null' => false],
        'join' => 'integer',
        'left' => ['type' => 'integer', 'null' => false],
        'right' => ['type' => 'integer', 'null' => false],
    ];
}
