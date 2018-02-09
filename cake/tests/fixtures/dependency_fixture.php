<?php
/**
 * Short description for file.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 1.2.0.6879//Correct version number as needed**
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Short description for file.
 *
 * @since         CakePHP(tm) v 1.2.0.6879//Correct version number as needed**
 */
class DependencyFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Dependency'
     */
    public $name = 'Dependency';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'child_id' => 'integer',
        'parent_id' => 'integer',
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['child_id' => 1, 'parent_id' => 2],
    ];
}
