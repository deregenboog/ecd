<?php
/**
 * TestPluginController used by Dispatcher test to test plugin shortcut urls.
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
 * @since         CakePHP(tm) v 1.3
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
class TestPluginController extends TestPluginAppController
{
    public $uses = [];

    public function index()
    {
        $this->autoRender = false;
    }

    public function add()
    {
        $this->autoRender = false;
    }
}
