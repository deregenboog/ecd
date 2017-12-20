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
class ContentAccountFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Aco'
     */
    public $name = 'ContentAccount';
    public $table = 'ContentAccounts';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'iContentAccountsId' => ['type' => 'integer', 'key' => 'primary'],
        'iContentId' => ['type' => 'integer'],
        'iAccountId' => ['type' => 'integer'],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['iContentId' => 1, 'iAccountId' => 1],
        ['iContentId' => 2, 'iAccountId' => 2],
        ['iContentId' => 3, 'iAccountId' => 3],
        ['iContentId' => 4, 'iAccountId' => 4],
        ['iContentId' => 1, 'iAccountId' => 2],
        ['iContentId' => 2, 'iAccountId' => 3],
    ];
}
