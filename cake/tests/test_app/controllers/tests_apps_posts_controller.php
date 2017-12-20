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
 * @since         CakePHP(tm) v 1.2.0.4206
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
class TestsAppsPostsController extends AppController
{
    public $name = 'TestsAppsPosts';
    public $uses = ['Post'];
    public $viewPath = 'tests_apps';

    public function add()
    {
        $data = [
            'Post' => [
                'title' => 'Test article',
                'body' => 'Body of article.',
                'author_id' => 1,
            ],
        ];
        $this->Post->save($data);

        $this->set('posts', $this->Post->find('all'));
        $this->render('index');
    }

    /**
     * check url params.
     */
    public function url_var()
    {
        $this->set('params', $this->params);
        $this->render('index');
    }

    /**
     * post var testing.
     */
    public function post_var()
    {
        $this->set('data', $this->data);
        $this->render('index');
    }

    /**
     * Fixturized action for testAction().
     */
    public function fixtured()
    {
        $this->set('posts', $this->Post->find('all'));
        $this->render('index');
    }
}
