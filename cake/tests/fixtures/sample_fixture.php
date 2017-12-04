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
class SampleFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Sample'
     */
    public $name = 'Sample';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'apple_id' => ['type' => 'integer', 'null' => false],
        'name' => ['type' => 'string', 'length' => 40, 'null' => false],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['apple_id' => 3, 'name' => 'sample1'],
        ['apple_id' => 2, 'name' => 'sample2'],
        ['apple_id' => 4, 'name' => 'sample3'],
        ['apple_id' => 5, 'name' => 'sample4'],
    ];
}
