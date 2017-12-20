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
 * @since         CakePHP(tm) v 1.2.0.5669
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 */
class TranslateTableFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'TranslateTable'
     */
    public $name = 'TranslateTable';

    /**
     * table property.
     *
     * @var string 'another_i18n'
     */
    public $table = 'another_i18n';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
            'id' => ['type' => 'integer', 'key' => 'primary'],
            'locale' => ['type' => 'string', 'length' => 6, 'null' => false],
            'model' => ['type' => 'string', 'null' => false],
            'foreign_key' => ['type' => 'integer', 'null' => false],
            'field' => ['type' => 'string', 'null' => false],
            'content' => ['type' => 'text'], ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['locale' => 'eng', 'model' => 'TranslatedItemWithTable', 'foreign_key' => 1, 'field' => 'title', 'content' => 'Another Title #1'],
        ['locale' => 'eng', 'model' => 'TranslatedItemWithTable', 'foreign_key' => 1, 'field' => 'content', 'content' => 'Another Content #1'],
    ];
}
