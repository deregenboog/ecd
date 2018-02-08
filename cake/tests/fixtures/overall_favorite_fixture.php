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
 * @since         CakePHP(tm) v 1.2.0.7198
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 */
class OverallFavoriteFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'OverallFavorite'
     */
    public $name = 'OverallFavorite';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'model_type' => ['type' => 'string', 'length' => 255],
        'model_id' => ['type' => 'integer'],
        'priority' => ['type' => 'integer'],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['id' => 1, 'model_type' => 'Cd', 'model_id' => '1', 'priority' => '1'],
        ['id' => 2, 'model_type' => 'Book', 'model_id' => '1', 'priority' => '2'],
    ];
}
