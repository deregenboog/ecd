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
class ItemsPortfolioFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'ItemsPortfolio'
     */
    public $name = 'ItemsPortfolio';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'item_id' => ['type' => 'integer', 'null' => false],
        'portfolio_id' => ['type' => 'integer', 'null' => false],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['item_id' => 1, 'portfolio_id' => 1],
        ['item_id' => 2, 'portfolio_id' => 2],
        ['item_id' => 3, 'portfolio_id' => 1],
        ['item_id' => 4, 'portfolio_id' => 1],
        ['item_id' => 5, 'portfolio_id' => 1],
        ['item_id' => 6, 'portfolio_id' => 2],
    ];
}
