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
 * UnderscoreFieldFixture class.
 */
class UnderscoreFieldFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'UnderscoreField'
     */
    public $name = 'UnderscoreField';
    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'user_id' => ['type' => 'integer', 'null' => false],
        'my_model_has_a_field' => ['type' => 'string', 'null' => false],
        'body_field' => 'text',
        'published' => ['type' => 'string', 'length' => 1, 'default' => 'N'],
        'another_field' => ['type' => 'integer', 'length' => 3],
    ];
    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['user_id' => 1, 'my_model_has_a_field' => 'First Article', 'body_field' => 'First Article Body', 'published' => 'Y', 'another_field' => 2],
        ['user_id' => 3, 'my_model_has_a_field' => 'Second Article', 'body_field' => 'Second Article Body', 'published' => 'Y', 'another_field' => 3],
        ['user_id' => 1, 'my_model_has_a_field' => 'Third Article', 'body_field' => 'Third Article Body', 'published' => 'Y', 'another_field' => 5],
    ];
}
