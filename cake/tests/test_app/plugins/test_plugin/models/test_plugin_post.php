<?php
/**
 * Test Plugin Post Model.
 *
 *
 *
 * PHP versions 4 and 5
 *
 * CakePHP : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc.
 *
 * @see          http://cakephp.org CakePHP Project
 * @since         CakePHP v 1.2.0.4487
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class TestPluginPost extends TestPluginAppModel
{
    /**
     * Name property.
     *
     * @var string
     */
    public $name = 'Post';

    /**
     * useTable property.
     *
     * @var string
     */
    public $useTable = 'posts';
}
