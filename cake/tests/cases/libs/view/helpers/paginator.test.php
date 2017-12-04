<?php
/**
 * PaginatorHelperTest file.
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
App::import('Helper', ['Html', 'Paginator', 'Form', 'Ajax', 'Javascript', 'Js']);

Mock::generate('JsHelper', 'PaginatorMockJsHelper');

if (!defined('FULL_BASE_URL')) {
    define('FULL_BASE_URL', 'http://cakephp.org');
}

/**
 * PaginatorHelperTest class.
 */
class PaginatorHelperTest extends CakeTestCase
{
    /**
     * setUp method.
     */
    public function setUp()
    {
        $this->Paginator = new PaginatorHelper(['ajax' => 'Ajax']);
        $this->Paginator->params['paging'] = [
            'Article' => [
                'current' => 9,
                'count' => 62,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 7,
                'defaults' => [
                    'order' => ['Article.date' => 'asc'],
                    'limit' => 9,
                    'conditions' => [],
                ],
                'options' => [
                    'order' => ['Article.date' => 'asc'],
                    'limit' => 9,
                    'page' => 1,
                    'conditions' => [],
                ],
            ],
        ];
        $this->Paginator->Html = new HtmlHelper();
        $this->Paginator->Ajax = new AjaxHelper();
        $this->Paginator->Ajax->Html = new HtmlHelper();
        $this->Paginator->Ajax->Javascript = new JavascriptHelper();
        $this->Paginator->Ajax->Form = new FormHelper();

        Configure::write('Routing.prefixes', []);
        Router::reload();
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        unset($this->Paginator);
    }

    /**
     * testHasPrevious method.
     */
    public function testHasPrevious()
    {
        $this->assertIdentical($this->Paginator->hasPrev(), false);
        $this->Paginator->params['paging']['Article']['prevPage'] = true;
        $this->assertIdentical($this->Paginator->hasPrev(), true);
        $this->Paginator->params['paging']['Article']['prevPage'] = false;
    }

    /**
     * testHasNext method.
     */
    public function testHasNext()
    {
        $this->assertIdentical($this->Paginator->hasNext(), true);
        $this->Paginator->params['paging']['Article']['nextPage'] = false;
        $this->assertIdentical($this->Paginator->hasNext(), false);
        $this->Paginator->params['paging']['Article']['nextPage'] = true;
    }

    /**
     * testDisabledLink method.
     */
    public function testDisabledLink()
    {
        $this->Paginator->params['paging']['Article']['nextPage'] = false;
        $this->Paginator->params['paging']['Article']['page'] = 1;
        $result = $this->Paginator->next('Next', [], true);
        $expected = '<span class="next">Next</span>';
        $this->assertEqual($result, $expected);

        $this->Paginator->params['paging']['Article']['prevPage'] = false;
        $result = $this->Paginator->prev('prev', ['update' => 'theList', 'indicator' => 'loading', 'url' => ['controller' => 'posts']], null, ['class' => 'disabled', 'tag' => 'span']);
        $expected = [
            'span' => ['class' => 'disabled'], 'prev', '/span',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testSortLinks method.
     */
    public function testSortLinks()
    {
        Router::reload();
        Router::parse('/');
        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'accounts', 'action' => 'index', 'pass' => [], 'form' => [], 'url' => ['url' => 'accounts/'], 'bare' => 0],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '/officespace', 'here' => '/officespace/accounts/', 'webroot' => '/officespace/', 'passedArgs' => []],
        ]);
        $this->Paginator->options(['url' => ['param']]);
        $result = $this->Paginator->sort('title');
        $expected = [
            'a' => ['href' => '/officespace/accounts/index/param/page:1/sort:title/direction:asc'],
            'Title',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->sort('date');
        $expected = [
            'a' => ['href' => '/officespace/accounts/index/param/page:1/sort:date/direction:desc', 'class' => 'asc'],
            'Date',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->numbers(['modulus' => '2', 'url' => ['controller' => 'projects', 'action' => 'sort'], 'update' => 'list']);
        $this->assertPattern('/\/projects\/sort\/page:2/', $result);
        $this->assertPattern('/<script type="text\/javascript">\s*'.str_replace('/', '\\/', preg_quote('//<![CDATA[')).'\s*Event.observe/', $result);

        $result = $this->Paginator->sort('TestTitle', 'title');
        $expected = [
            'a' => ['href' => '/officespace/accounts/index/param/page:1/sort:title/direction:asc'],
            'TestTitle',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->sort(['asc' => 'ascending', 'desc' => 'descending'], 'title');
        $expected = [
            'a' => ['href' => '/officespace/accounts/index/param/page:1/sort:title/direction:asc'],
            'ascending',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging']['Article']['options']['sort'] = 'title';
        $result = $this->Paginator->sort(['asc' => 'ascending', 'desc' => 'descending'], 'title');
        $expected = [
            'a' => ['href' => '/officespace/accounts/index/param/page:1/sort:title/direction:desc', 'class' => 'asc'],
            'descending',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging']['Article']['options']['order'] = ['Article.title' => 'desc'];
        $this->Paginator->params['paging']['Article']['options']['sort'] = null;
        $result = $this->Paginator->sort('title');
        $this->assertPattern('/\/accounts\/index\/param\/page:1\/sort:title\/direction:asc" class="desc">Title<\/a>$/', $result);

        $this->Paginator->params['paging']['Article']['options']['order'] = ['Article.title' => 'asc'];
        $this->Paginator->params['paging']['Article']['options']['sort'] = null;
        $result = $this->Paginator->sort('title');
        $this->assertPattern('/\/accounts\/index\/param\/page:1\/sort:title\/direction:desc" class="asc">Title<\/a>$/', $result);

        $this->Paginator->params['paging']['Article']['options']['order'] = ['Article.title' => 'desc'];
        $this->Paginator->params['paging']['Article']['options']['sort'] = null;
        $result = $this->Paginator->sort('Title', 'title', ['direction' => 'desc']);
        $this->assertPattern('/\/accounts\/index\/param\/page:1\/sort:title\/direction:asc" class="desc">Title<\/a>$/', $result);

        $this->Paginator->params['paging']['Article']['options']['order'] = ['Article.title' => 'desc'];
        $this->Paginator->params['paging']['Article']['options']['sort'] = null;
        $result = $this->Paginator->sort('Title', 'title', ['direction' => 'asc']);
        $this->assertPattern('/\/accounts\/index\/param\/page:1\/sort:title\/direction:asc" class="desc">Title<\/a>$/', $result);

        $this->Paginator->params['paging']['Article']['options']['order'] = ['Article.title' => 'asc'];
        $this->Paginator->params['paging']['Article']['options']['sort'] = null;
        $result = $this->Paginator->sort('Title', 'title', ['direction' => 'asc']);
        $this->assertPattern('/\/accounts\/index\/param\/page:1\/sort:title\/direction:desc" class="asc">Title<\/a>$/', $result);

        $this->Paginator->params['paging']['Article']['options']['order'] = ['Article.title' => 'asc'];
        $this->Paginator->params['paging']['Article']['options']['sort'] = null;
        $result = $this->Paginator->sort('Title', 'title', ['direction' => 'desc']);
        $this->assertPattern('/\/accounts\/index\/param\/page:1\/sort:title\/direction:desc" class="asc">Title<\/a>$/', $result);

        $this->Paginator->params['paging']['Article']['options']['order'] = ['Article.title' => 'asc'];
        $this->Paginator->params['paging']['Article']['options']['sort'] = null;
        $result = $this->Paginator->sort('Title', 'title', ['direction' => 'desc', 'class' => 'foo']);
        $this->assertPattern('/\/accounts\/index\/param\/page:1\/sort:title\/direction:desc" class="foo asc">Title<\/a>$/', $result);
    }

    /**
     * test that sort() works with virtual field order options.
     */
    public function testSortLinkWithVirtualField()
    {
        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'accounts', 'action' => 'index', 'pass' => [], 'form' => [], 'url' => ['url' => 'accounts/']],
            ['base' => '', 'here' => '/accounts/', 'webroot' => '/'],
        ]);
        $this->Paginator->params['paging']['Article']['options']['order'] = ['full_name' => 'asc'];

        $result = $this->Paginator->sort('Article.full_name');
        $expected = [
            'a' => ['href' => '/accounts/index/page:1/sort:Article.full_name/direction:desc', 'class' => 'asc'],
            'Article.full Name',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->sort('full_name');
        $expected = [
            'a' => ['href' => '/accounts/index/page:1/sort:full_name/direction:desc', 'class' => 'asc'],
            'Full Name',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging']['Article']['options']['order'] = ['full_name' => 'desc'];
        $result = $this->Paginator->sort('Article.full_name');
        $expected = [
            'a' => ['href' => '/accounts/index/page:1/sort:Article.full_name/direction:asc', 'class' => 'desc'],
            'Article.full Name',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->sort('full_name');
        $expected = [
            'a' => ['href' => '/accounts/index/page:1/sort:full_name/direction:asc', 'class' => 'desc'],
            'Full Name',
            '/a',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testSortLinksUsingDirectionOption method.
     */
    public function testSortLinksUsingDirectionOption()
    {
        Router::reload();
        Router::parse('/');
        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'accounts', 'action' => 'index', 'pass' => [],
                'form' => [], 'url' => ['url' => 'accounts/', 'mod_rewrite' => 'true'], 'bare' => 0, ],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '/', 'here' => '/accounts/',
                'webroot' => '/', 'passedArgs' => [], ],
        ]);
        $this->Paginator->options(['url' => ['param']]);

        $result = $this->Paginator->sort('TestTitle', 'title', ['direction' => 'desc']);
        $expected = [
            'a' => ['href' => '/accounts/index/param/page:1/sort:title/direction:desc'],
            'TestTitle',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->sort(['asc' => 'ascending', 'desc' => 'descending'], 'title', ['direction' => 'desc']);
        $expected = [
            'a' => ['href' => '/accounts/index/param/page:1/sort:title/direction:desc'],
            'descending',
            '/a',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testSortLinksUsingDotNotation method.
     */
    public function testSortLinksUsingDotNotation()
    {
        Router::reload();
        Router::parse('/');
        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'accounts', 'action' => 'index', 'pass' => [],  'form' => [], 'url' => ['url' => 'accounts/', 'mod_rewrite' => 'true'], 'bare' => 0],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '/officespace', 'here' => '/officespace/accounts/', 'webroot' => '/officespace/', 'passedArgs' => []],
        ]);

        $this->Paginator->params['paging']['Article']['options']['order'] = ['Article.title' => 'desc'];
        $result = $this->Paginator->sort('Title', 'Article.title');
        $expected = [
            'a' => ['href' => '/officespace/accounts/index/page:1/sort:Article.title/direction:asc', 'class' => 'desc'],
            'Title',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging']['Article']['options']['order'] = ['Article.title' => 'asc'];
        $result = $this->Paginator->sort('Title', 'Article.title');
        $expected = [
            'a' => ['href' => '/officespace/accounts/index/page:1/sort:Article.title/direction:desc', 'class' => 'asc'],
            'Title',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging']['Article']['options']['order'] = ['Account.title' => 'asc'];
        $result = $this->Paginator->sort('title');
        $expected = [
            'a' => ['href' => '/officespace/accounts/index/page:1/sort:title/direction:asc'],
            'Title',
            '/a',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testSortKey method.
     */
    public function testSortKey()
    {
        $result = $this->Paginator->sortKey(null, [
            'order' => ['Article.title' => 'desc',
        ], ]);
        $this->assertEqual('Article.title', $result);

        $result = $this->Paginator->sortKey('Article', ['sort' => 'Article.title']);
        $this->assertEqual($result, 'Article.title');

        $result = $this->Paginator->sortKey('Article', ['sort' => 'Article']);
        $this->assertEqual($result, 'Article');
    }

    /**
     * testSortDir method.
     */
    public function testSortDir()
    {
        $result = $this->Paginator->sortDir();
        $expected = 'asc';

        $this->assertEqual($result, $expected);

        $this->Paginator->params['paging']['Article']['options']['order'] = ['Article.title' => 'desc'];
        $result = $this->Paginator->sortDir();
        $expected = 'desc';

        $this->assertEqual($result, $expected);

        unset($this->Paginator->params['paging']['Article']['options']);
        $this->Paginator->params['paging']['Article']['options']['order'] = ['Article.title' => 'asc'];
        $result = $this->Paginator->sortDir();
        $expected = 'asc';

        $this->assertEqual($result, $expected);

        unset($this->Paginator->params['paging']['Article']['options']);
        $this->Paginator->params['paging']['Article']['options']['order'] = ['title' => 'desc'];
        $result = $this->Paginator->sortDir();
        $expected = 'desc';

        $this->assertEqual($result, $expected);

        unset($this->Paginator->params['paging']['Article']['options']);
        $this->Paginator->params['paging']['Article']['options']['order'] = ['title' => 'asc'];
        $result = $this->Paginator->sortDir();
        $expected = 'asc';

        $this->assertEqual($result, $expected);

        unset($this->Paginator->params['paging']['Article']['options']);
        $this->Paginator->params['paging']['Article']['options']['direction'] = 'asc';
        $result = $this->Paginator->sortDir();
        $expected = 'asc';

        $this->assertEqual($result, $expected);

        unset($this->Paginator->params['paging']['Article']['options']);
        $this->Paginator->params['paging']['Article']['options']['direction'] = 'desc';
        $result = $this->Paginator->sortDir();
        $expected = 'desc';

        $this->assertEqual($result, $expected);

        unset($this->Paginator->params['paging']['Article']['options']);
        $result = $this->Paginator->sortDir('Article', ['direction' => 'asc']);
        $expected = 'asc';

        $this->assertEqual($result, $expected);

        $result = $this->Paginator->sortDir('Article', ['direction' => 'desc']);
        $expected = 'desc';

        $this->assertEqual($result, $expected);

        $result = $this->Paginator->sortDir('Article', ['direction' => 'asc']);
        $expected = 'asc';

        $this->assertEqual($result, $expected);
    }

    /**
     * testSortAdminLinks method.
     */
    public function testSortAdminLinks()
    {
        Configure::write('Routing.prefixes', ['admin']);

        Router::reload();
        Router::setRequestInfo([
            ['pass' => [], 'named' => [], 'controller' => 'users', 'plugin' => null, 'action' => 'admin_index', 'prefix' => 'admin', 'admin' => true, 'url' => ['ext' => 'html', 'url' => 'admin/users'], 'form' => []],
            ['base' => '', 'here' => '/admin/users', 'webroot' => '/'],
        ]);
        Router::parse('/admin/users');
        $this->Paginator->params['paging']['Article']['page'] = 1;
        $result = $this->Paginator->next('Next');
        $expected = [
            '<span',
            'a' => ['href' => '/admin/users/index/page:2', 'class' => 'next'],
            'Next',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        Router::reload();
        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'test', 'action' => 'admin_index', 'pass' => [], 'prefix' => 'admin', 'admin' => true, 'form' => [], 'url' => ['url' => 'admin/test']],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '', 'here' => '/admin/test', 'webroot' => '/'],
        ]);
        Router::parse('/');
        $this->Paginator->options(['url' => ['param']]);
        $result = $this->Paginator->sort('title');
        $expected = [
            'a' => ['href' => '/admin/test/index/param/page:1/sort:title/direction:asc'],
            'Title',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->options(['url' => ['param']]);
        $result = $this->Paginator->sort('Title', 'Article.title');
        $expected = [
            'a' => ['href' => '/admin/test/index/param/page:1/sort:Article.title/direction:asc'],
            'Title',
            '/a',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testUrlGeneration method.
     */
    public function testUrlGeneration()
    {
        $result = $this->Paginator->sort('controller');
        $expected = [
            'a' => ['href' => '/index/page:1/sort:controller/direction:asc'],
            'Controller',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->url();
        $this->assertEqual($result, '/index/page:1');

        $this->Paginator->params['paging']['Article']['options']['page'] = 2;
        $result = $this->Paginator->url();
        $this->assertEqual($result, '/index/page:2');

        $options = ['order' => ['Article' => 'desc']];
        $result = $this->Paginator->url($options);
        $this->assertEqual($result, '/index/page:2/sort:Article/direction:desc');

        $this->Paginator->params['paging']['Article']['options']['page'] = 3;
        $options = ['order' => ['Article.name' => 'desc']];
        $result = $this->Paginator->url($options);
        $this->assertEqual($result, '/index/page:3/sort:Article.name/direction:desc');
    }

    /**
     * test URL generation with prefix routes.
     */
    public function testUrlGenerationWithPrefixes()
    {
        $_back = Configure::read('Routing');

        Configure::write('Routing.prefixes', ['members']);
        Router::reload();

        Router::parse('/');

        Router::setRequestInfo([
            ['controller' => 'posts', 'action' => 'index', 'form' => [], 'url' => [], 'plugin' => null],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '', 'here' => 'posts/index', 'webroot' => '/'],
        ]);

        $this->Paginator->params['paging']['Article']['options']['page'] = 2;
        $this->Paginator->params['paging']['Article']['page'] = 2;
        $this->Paginator->params['paging']['Article']['prevPage'] = true;
        $options = ['members' => true];

        $result = $this->Paginator->url($options);
        $expected = '/members/posts/index/page:2';
        $this->assertEqual($result, $expected);

        $result = $this->Paginator->sort('name', null, ['url' => $options]);
        $expected = [
            'a' => ['href' => '/members/posts/index/page:2/sort:name/direction:asc'],
            'Name',
            '/a',
        ];
        $this->assertTags($result, $expected, true);

        $result = $this->Paginator->next('next', ['url' => $options]);
        $expected = [
            '<span',
            'a' => ['href' => '/members/posts/index/page:3', 'class' => 'next'],
            'next',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->prev('prev', ['url' => $options]);
        $expected = [
            '<span',
            'a' => ['href' => '/members/posts/index/page:1', 'class' => 'prev'],
            'prev',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $options = ['members' => true, 'controller' => 'posts', 'order' => ['name' => 'desc']];
        $result = $this->Paginator->url($options);
        $expected = '/members/posts/index/page:2/sort:name/direction:desc';
        $this->assertEqual($result, $expected);

        $options = ['controller' => 'posts', 'order' => ['Article.name' => 'desc']];
        $result = $this->Paginator->url($options);
        $expected = '/posts/index/page:2/sort:Article.name/direction:desc';
        $this->assertEqual($result, $expected);

        Configure::write('Routing', $_back);
    }

    /**
     * testOptions method.
     */
    public function testOptions()
    {
        $this->Paginator->options('myDiv');
        $this->assertEqual('myDiv', $this->Paginator->options['update']);

        $this->Paginator->options = [];
        $this->Paginator->params = [];

        $options = ['paging' => ['Article' => [
            'order' => 'desc',
            'sort' => 'title',
        ]]];
        $this->Paginator->options($options);

        $expected = ['Article' => [
            'order' => 'desc',
            'sort' => 'title',
        ]];
        $this->assertEqual($expected, $this->Paginator->params['paging']);

        $this->Paginator->options = [];
        $this->Paginator->params = [];

        $options = ['Article' => [
            'order' => 'desc',
            'sort' => 'title',
        ]];
        $this->Paginator->options($options);
        $this->assertEqual($expected, $this->Paginator->params['paging']);

        $options = ['paging' => ['Article' => [
            'order' => 'desc',
            'sort' => 'Article.title',
        ]]];
        $this->Paginator->options($options);

        $expected = ['Article' => [
            'order' => 'desc',
            'sort' => 'Article.title',
        ]];
        $this->assertEqual($expected, $this->Paginator->params['paging']);
    }

    /**
     * testPassedArgsMergingWithUrlOptions method.
     */
    public function testPassedArgsMergingWithUrlOptions()
    {
        Router::reload();
        Router::parse('/');
        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'articles', 'action' => 'index', 'pass' => ['2'], 'named' => ['foo' => 'bar'], 'form' => [], 'url' => ['url' => 'articles/index/2/foo:bar'], 'bare' => 0],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '/', 'here' => '/articles/', 'webroot' => '/', 'passedArgs' => [0 => '2', 'foo' => 'bar']],
        ]);
        $this->Paginator->params['paging'] = [
            'Article' => [
                'page' => 1, 'current' => 3, 'count' => 13,
                'prevPage' => false, 'nextPage' => true, 'pageCount' => 8,
                'defaults' => [
                    'limit' => 3, 'step' => 1, 'order' => [], 'conditions' => [],
                ],
                'options' => [
                    'page' => 1, 'limit' => 3, 'order' => [], 'conditions' => [],
                ],
            ],
        ];

        $this->Paginator->params['pass'] = [2];
        $this->Paginator->params['named'] = ['foo' => 'bar'];
        $this->Paginator->beforeRender();

        $result = $this->Paginator->sort('title');
        $expected = [
            'a' => ['href' => '/articles/index/2/page:1/foo:bar/sort:title/direction:asc'],
            'Title',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->numbers();
        $expected = [
            ['span' => ['class' => 'current']], '1', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/articles/index/2/page:2/foo:bar']], '2', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/articles/index/2/page:3/foo:bar']], '3', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/articles/index/2/page:4/foo:bar']], '4', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/articles/index/2/page:5/foo:bar']], '5', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/articles/index/2/page:6/foo:bar']], '6', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/articles/index/2/page:7/foo:bar']], '7', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->next('Next');
        $expected = [
            '<span',
            'a' => ['href' => '/articles/index/2/page:2/foo:bar', 'class' => 'next'],
            'Next',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testPagingLinks method.
     */
    public function testPagingLinks()
    {
        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 1, 'current' => 3, 'count' => 13, 'prevPage' => false, 'nextPage' => true, 'pageCount' => 5,
            'defaults' => ['limit' => 3, 'step' => 1, 'order' => ['Client.name' => 'DESC'], 'conditions' => []],
            'options' => ['page' => 1, 'limit' => 3, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];
        $result = $this->Paginator->prev('<< Previous', null, null, ['class' => 'disabled']);
        $expected = [
            'span' => ['class' => 'disabled'],
            '&lt;&lt; Previous',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->prev('<< Previous', null, null, ['class' => 'disabled', 'tag' => 'div']);
        $expected = [
            'div' => ['class' => 'disabled'],
            '&lt;&lt; Previous',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging']['Client']['page'] = 2;
        $this->Paginator->params['paging']['Client']['prevPage'] = true;
        $result = $this->Paginator->prev('<< Previous', null, null, ['class' => 'disabled']);
        $expected = [
            '<span',
            'a' => ['href' => '/index/page:1', 'class' => 'prev'],
            '&lt;&lt; Previous',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->next('Next');
        $expected = [
            '<span',
            'a' => ['href' => '/index/page:3', 'class' => 'next'],
            'Next',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->next('Next', ['tag' => 'li']);
        $expected = [
            '<li',
            'a' => ['href' => '/index/page:3', 'class' => 'next'],
            'Next',
            '/a',
            '/li',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->prev('<< Previous', ['escape' => true]);
        $expected = [
            '<span',
            'a' => ['href' => '/index/page:1', 'class' => 'prev'],
            '&lt;&lt; Previous',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->prev('<< Previous', ['escape' => false]);
        $expected = [
            '<span',
            'a' => ['href' => '/index/page:1', 'class' => 'prev'],
            'preg:/<< Previous/',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 1, 'current' => 1, 'count' => 13, 'prevPage' => false, 'nextPage' => true, 'pageCount' => 5,
            'defaults' => [],
            'options' => ['page' => 1, 'limit' => 3, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];

        $result = $this->Paginator->prev('<< Previous', null, '<strong>Disabled</strong>');
        $expected = [
            'span' => ['class' => 'prev'],
            '&lt;strong&gt;Disabled&lt;/strong&gt;',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->prev('<< Previous', null, '<strong>Disabled</strong>', ['escape' => true]);
        $expected = [
            'span' => ['class' => 'prev'],
            '&lt;strong&gt;Disabled&lt;/strong&gt;',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->prev('<< Previous', null, '<strong>Disabled</strong>', ['escape' => false]);
        $expected = [
            'span' => ['class' => 'prev'],
            '<strong', 'Disabled', '/strong',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 1, 'current' => 3, 'count' => 13, 'prevPage' => false, 'nextPage' => true, 'pageCount' => 5,
            'defaults' => [],
            'options' => ['page' => 1, 'limit' => 3, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];

        $this->Paginator->params['paging']['Client']['page'] = 2;
        $this->Paginator->params['paging']['Client']['prevPage'] = true;
        $result = $this->Paginator->prev('<< Previous', null, null, ['class' => 'disabled']);
        $expected = [
            '<span',
            'a' => ['href' => '/index/page:1/limit:3/sort:Client.name/direction:DESC', 'class' => 'prev'],
            '&lt;&lt; Previous',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected, true);

        $result = $this->Paginator->next('Next');
        $expected = [
            '<span',
            'a' => ['href' => '/index/page:3/limit:3/sort:Client.name/direction:DESC', 'class' => 'next'],
            'Next',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 2, 'current' => 1, 'count' => 13, 'prevPage' => true, 'nextPage' => false, 'pageCount' => 2,
            'defaults' => [],
            'options' => ['page' => 2, 'limit' => 10, 'order' => [], 'conditions' => []],
        ]];
        $result = $this->Paginator->prev('Prev');
        $expected = [
            '<span',
            'a' => ['href' => '/index/page:1/limit:10', 'class' => 'prev'],
            'Prev',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging'] = [
            'Client' => [
                'page' => 2, 'current' => 1, 'count' => 13, 'prevPage' => true,
                'nextPage' => false, 'pageCount' => 2,
                'defaults' => [],
                'options' => [
                    'page' => 2, 'limit' => 10, 'order' => [], 'conditions' => [],
                ],
            ],
        ];
        $this->Paginator->options(['url' => [12, 'page' => 3]]);
        $result = $this->Paginator->prev('Prev', ['url' => ['foo' => 'bar']]);
        $expected = [
            '<span',
            'a' => ['href' => '/index/12/page:1/limit:10/foo:bar', 'class' => 'prev'],
            'Prev',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test that __pagingLink methods use $options when $disabledOptions is an empty value.
     * allowing you to use shortcut syntax.
     */
    public function testPagingLinksOptionsReplaceEmptyDisabledOptions()
    {
        $this->Paginator->params['paging'] = [
            'Client' => [
                'page' => 1, 'current' => 3, 'count' => 13, 'prevPage' => false,
                'nextPage' => true, 'pageCount' => 5,
                'defaults' => [
                    'limit' => 3, 'step' => 1, 'order' => ['Client.name' => 'DESC'], 'conditions' => [],
                ],
                'options' => [
                    'page' => 1, 'limit' => 3, 'order' => ['Client.name' => 'DESC'], 'conditions' => [],
                ],
            ],
        ];
        $result = $this->Paginator->prev('<< Previous', ['escape' => false]);
        $expected = [
            'span' => ['class' => 'prev'],
            'preg:/<< Previous/',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->next('Next >>', ['escape' => false]);
        $expected = [
            '<span',
            'a' => ['href' => '/index/page:2', 'class' => 'next'],
            'preg:/Next >>/',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testPagingLinksNotDefaultModel.
     *
     * Test the creation of paging links when the non default model is used.
     */
    public function testPagingLinksNotDefaultModel()
    {
        // Multiple Model Paginate
        $this->Paginator->params['paging'] = [
            'Client' => [
                'page' => 1, 'current' => 3, 'count' => 13, 'prevPage' => false, 'nextPage' => true, 'pageCount' => 5,
                'defaults' => ['limit' => 3, 'order' => ['Client.name' => 'DESC']],
                'options' => ['page' => 1, 'limit' => 3, 'order' => ['Client.name' => 'DESC'], 'conditions' => []],
            ],
            'Server' => [
                'page' => 1, 'current' => 1, 'count' => 5, 'prevPage' => false, 'nextPage' => false, 'pageCount' => 5,
                'defaults' => [],
                'options' => ['page' => 1, 'limit' => 5, 'order' => ['Server.name' => 'ASC'], 'conditions' => []],
            ],
        ];
        $result = $this->Paginator->next('Next', ['model' => 'Client']);
        $expected = [
            '<span',
            'a' => ['href' => '/index/page:2', 'class' => 'next'],
            'Next',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->next('Next', ['model' => 'Server'], 'No Next', ['model' => 'Server']);
        $expected = [
            'span' => ['class' => 'next'], 'No Next', '/span',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testGenericLinks method.
     */
    public function testGenericLinks()
    {
        $result = $this->Paginator->link('Sort by title on page 5', ['sort' => 'title', 'page' => 5, 'direction' => 'desc']);
        $expected = [
            'a' => ['href' => '/index/page:5/sort:title/direction:desc'],
            'Sort by title on page 5',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging']['Article']['options']['page'] = 2;
        $result = $this->Paginator->link('Sort by title', ['sort' => 'title', 'direction' => 'desc']);
        $expected = [
            'a' => ['href' => '/index/page:2/sort:title/direction:desc'],
            'Sort by title',
            '/a',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging']['Article']['options']['page'] = 4;
        $result = $this->Paginator->link('Sort by title on page 4', ['sort' => 'Article.title', 'direction' => 'desc']);
        $expected = [
            'a' => ['href' => '/index/page:4/sort:Article.title/direction:desc'],
            'Sort by title on page 4',
            '/a',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * Tests generation of generic links with preset options.
     */
    public function testGenericLinksWithPresetOptions()
    {
        $result = $this->Paginator->link('Foo!', ['page' => 1]);
        $this->assertTags($result, ['a' => ['href' => '/index/page:1'], 'Foo!', '/a']);

        $this->Paginator->options(['sort' => 'title', 'direction' => 'desc']);
        $result = $this->Paginator->link('Foo!', ['page' => 1]);
        $this->assertTags($result, [
            'a' => [
                'href' => '/index/page:1',
                'sort' => 'title',
                'direction' => 'desc',
            ],
            'Foo!',
            '/a',
        ]);

        $this->Paginator->options(['sort' => null, 'direction' => null]);
        $result = $this->Paginator->link('Foo!', ['page' => 1]);
        $this->assertTags($result, ['a' => ['href' => '/index/page:1'], 'Foo!', '/a']);

        $this->Paginator->options(['url' => [
            'sort' => 'title',
            'direction' => 'desc',
        ]]);
        $result = $this->Paginator->link('Foo!', ['page' => 1]);
        $this->assertTags($result, [
            'a' => ['href' => '/index/page:1/sort:title/direction:desc'],
            'Foo!',
            '/a',
        ]);
    }

    /**
     * testNumbers method.
     */
    public function testNumbers()
    {
        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 8, 'current' => 3, 'count' => 30, 'prevPage' => false, 'nextPage' => 2, 'pageCount' => 15,
            'defaults' => ['limit' => 3, 'step' => 1, 'order' => ['Client.name' => 'DESC'], 'conditions' => []],
            'options' => ['page' => 1, 'limit' => 3, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];
        $result = $this->Paginator->numbers();
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:4']], '4', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:5']], '5', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:6']], '6', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:7']], '7', '/a', '/span',
            ' | ',
            ['span' => ['class' => 'current']], '8', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:9']], '9', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:10']], '10', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:11']], '11', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:12']], '12', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->numbers(['tag' => 'li']);
        $expected = [
            ['li' => []], ['a' => ['href' => '/index/page:4']], '4', '/a', '/li',
            ' | ',
            ['li' => []], ['a' => ['href' => '/index/page:5']], '5', '/a', '/li',
            ' | ',
            ['li' => []], ['a' => ['href' => '/index/page:6']], '6', '/a', '/li',
            ' | ',
            ['li' => []], ['a' => ['href' => '/index/page:7']], '7', '/a', '/li',
            ' | ',
            ['li' => ['class' => 'current']], '8', '/li',
            ' | ',
            ['li' => []], ['a' => ['href' => '/index/page:9']], '9', '/a', '/li',
            ' | ',
            ['li' => []], ['a' => ['href' => '/index/page:10']], '10', '/a', '/li',
            ' | ',
            ['li' => []], ['a' => ['href' => '/index/page:11']], '11', '/a', '/li',
            ' | ',
            ['li' => []], ['a' => ['href' => '/index/page:12']], '12', '/a', '/li',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->numbers(['tag' => 'li', 'separator' => false]);
        $expected = [
            ['li' => []], ['a' => ['href' => '/index/page:4']], '4', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/index/page:5']], '5', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/index/page:6']], '6', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/index/page:7']], '7', '/a', '/li',
            ['li' => ['class' => 'current']], '8', '/li',
            ['li' => []], ['a' => ['href' => '/index/page:9']], '9', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/index/page:10']], '10', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/index/page:11']], '11', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/index/page:12']], '12', '/a', '/li',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->numbers(true);
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:1']], 'first', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:4']], '4', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:5']], '5', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:6']], '6', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:7']], '7', '/a', '/span',
            ' | ',
            ['span' => ['class' => 'current']], '8', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:9']], '9', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:10']], '10', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:11']], '11', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:12']], '12', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:15']], 'last', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 1, 'current' => 3, 'count' => 30, 'prevPage' => false, 'nextPage' => 2, 'pageCount' => 15,
            'defaults' => ['limit' => 3, 'step' => 1, 'order' => ['Client.name' => 'DESC'], 'conditions' => []],
            'options' => ['page' => 1, 'limit' => 3, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];
        $result = $this->Paginator->numbers();
        $expected = [
            ['span' => ['class' => 'current']], '1', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:2']], '2', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:3']], '3', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:4']], '4', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:5']], '5', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:6']], '6', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:7']], '7', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:8']], '8', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:9']], '9', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 14, 'current' => 3, 'count' => 30, 'prevPage' => false, 'nextPage' => 2, 'pageCount' => 15,
            'defaults' => ['limit' => 3, 'step' => 1, 'order' => ['Client.name' => 'DESC'], 'conditions' => []],
            'options' => ['page' => 1, 'limit' => 3, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];
        $result = $this->Paginator->numbers();
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:7']], '7', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:8']], '8', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:9']], '9', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:10']], '10', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:11']], '11', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:12']], '12', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:13']], '13', '/a', '/span',
            ' | ',
            ['span' => ['class' => 'current']], '14', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:15']], '15', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 2, 'current' => 3, 'count' => 27, 'prevPage' => false, 'nextPage' => 2, 'pageCount' => 9,
            'defaults' => ['limit' => 3, 'step' => 1, 'order' => ['Client.name' => 'DESC'], 'conditions' => []],
            'options' => ['page' => 1, 'limit' => 3, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];

        $result = $this->Paginator->numbers(['first' => 1]);
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:1']], '1', '/a', '/span',
            ' | ',
            ['span' => ['class' => 'current']], '2', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:3']], '3', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:4']], '4', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:5']], '5', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:6']], '6', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:7']], '7', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:8']], '8', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:9']], '9', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->numbers(['last' => 1]);
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:1']], '1', '/a', '/span',
            ' | ',
            ['span' => ['class' => 'current']], '2', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:3']], '3', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:4']], '4', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:5']], '5', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:6']], '6', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:7']], '7', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:8']], '8', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:9']], '9', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 15, 'current' => 3, 'count' => 30, 'prevPage' => false, 'nextPage' => 2, 'pageCount' => 15,
            'defaults' => ['limit' => 3, 'step' => 1, 'order' => ['Client.name' => 'DESC'], 'conditions' => []],
            'options' => ['page' => 1, 'limit' => 3, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];

        $result = $this->Paginator->numbers(['first' => 1]);
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:1']], '1', '/a', '/span',
            '...',
            ['span' => []], ['a' => ['href' => '/index/page:7']], '7', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:8']], '8', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:9']], '9', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:10']], '10', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:11']], '11', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:12']], '12', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:13']], '13', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:14']], '14', '/a', '/span',
            ' | ',
            ['span' => ['class' => 'current']], '15', '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 10, 'current' => 3, 'count' => 30, 'prevPage' => false, 'nextPage' => 2, 'pageCount' => 15,
            'defaults' => ['limit' => 3, 'step' => 1, 'order' => ['Client.name' => 'DESC'], 'conditions' => []],
            'options' => ['page' => 1, 'limit' => 3, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];

        $result = $this->Paginator->numbers(['first' => 1, 'last' => 1]);
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:1']], '1', '/a', '/span',
            '...',
            ['span' => []], ['a' => ['href' => '/index/page:6']], '6', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:7']], '7', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:8']], '8', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:9']], '9', '/a', '/span',
            ' | ',
            ['span' => ['class' => 'current']], '10', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:11']], '11', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:12']], '12', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:13']], '13', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:14']], '14', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:15']], '15', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 6, 'current' => 15, 'count' => 623, 'prevPage' => 1, 'nextPage' => 1, 'pageCount' => 42,
            'defaults' => ['limit' => 15, 'step' => 1, 'page' => 1, 'order' => ['Client.name' => 'DESC'], 'conditions' => []],
            'options' => ['page' => 6, 'limit' => 15, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];

        $result = $this->Paginator->numbers(['first' => 1, 'last' => 1]);
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:1']], '1', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:2']], '2', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:3']], '3', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:4']], '4', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:5']], '5', '/a', '/span',
            ' | ',
            ['span' => ['class' => 'current']], '6', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:7']], '7', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:8']], '8', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:9']], '9', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:10']], '10', '/a', '/span',
            '...',
            ['span' => []], ['a' => ['href' => '/index/page:42']], '42', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 37, 'current' => 15, 'count' => 623, 'prevPage' => 1, 'nextPage' => 1, 'pageCount' => 42,
            'defaults' => ['limit' => 15, 'step' => 1, 'page' => 1, 'order' => ['Client.name' => 'DESC'], 'conditions' => []],
            'options' => ['page' => 37, 'limit' => 15, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];

        $result = $this->Paginator->numbers(['first' => 1, 'last' => 1]);
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:1']], '1', '/a', '/span',
            '...',
            ['span' => []], ['a' => ['href' => '/index/page:33']], '33', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:34']], '34', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:35']], '35', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:36']], '36', '/a', '/span',
            ' | ',
            ['span' => ['class' => 'current']], '37', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:38']], '38', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:39']], '39', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:40']], '40', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:41']], '41', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:42']], '42', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging'] = [
            'Client' => [
                'page' => 1,
                'current' => 10,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 3,
                'defaults' => [
                    'limit' => 3,
                    'step' => 1,
                    'order' => ['Client.name' => 'DESC'],
                    'conditions' => [],
                ],
                'options' => [
                    'page' => 1,
                    'limit' => 3,
                    'order' => ['Client.name' => 'DESC'],
                    'conditions' => [],
                ],
            ],
        ];
        $options = ['modulus' => 10];
        $result = $this->Paginator->numbers($options);
        $expected = [
            ['span' => ['class' => 'current']], '1', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:2']], '2', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:3']], '3', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 2, 'current' => 10, 'count' => 31, 'prevPage' => true, 'nextPage' => true, 'pageCount' => 4,
            'defaults' => ['limit' => 10],
            'options' => ['page' => 1, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];
        $result = $this->Paginator->numbers();
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:1/sort:Client.name/direction:DESC']], '1', '/a', '/span',
            ' | ',
            ['span' => ['class' => 'current']], '2', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:3/sort:Client.name/direction:DESC']], '3', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:4/sort:Client.name/direction:DESC']], '4', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 4895, 'current' => 10, 'count' => 48962, 'prevPage' => 1, 'nextPage' => 1, 'pageCount' => 4897,
            'defaults' => ['limit' => 10],
            'options' => ['page' => 4894, 'limit' => 10, 'order' => 'Client.name DESC', 'conditions' => []], ],
        ];

        $result = $this->Paginator->numbers(['first' => 2, 'modulus' => 2, 'last' => 2]);
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:1']], '1', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:2']], '2', '/a', '/span',
            '...',
            ['span' => []], ['a' => ['href' => '/index/page:4894']], '4894', '/a', '/span',
            ' | ',
            ['span' => ['class' => 'current']], '4895', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:4896']], '4896', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:4897']], '4897', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging']['Client']['page'] = 3;

        $result = $this->Paginator->numbers(['first' => 2, 'modulus' => 2, 'last' => 2]);
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:1']], '1', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:2']], '2', '/a', '/span',
            ' | ',
            ['span' => ['class' => 'current']], '3', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:4']], '4', '/a', '/span',
            '...',
            ['span' => []], ['a' => ['href' => '/index/page:4896']], '4896', '/a', '/span',
            ' | ',
            ['span' => []], ['a' => ['href' => '/index/page:4897']], '4897', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->numbers(['first' => 2, 'modulus' => 2, 'last' => 2, 'separator' => ' - ']);
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:1']], '1', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:2']], '2', '/a', '/span',
            ' - ',
            ['span' => ['class' => 'current']], '3', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4']], '4', '/a', '/span',
            '...',
            ['span' => []], ['a' => ['href' => '/index/page:4896']], '4896', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4897']], '4897', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->numbers(['first' => 5, 'modulus' => 5, 'last' => 5, 'separator' => ' - ']);
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:1']], '1', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:2']], '2', '/a', '/span',
            ' - ',
            ['span' => ['class' => 'current']], '3', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4']], '4', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:5']], '5', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:6']], '6', '/a', '/span',
            '...',
            ['span' => []], ['a' => ['href' => '/index/page:4893']], '4893', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4894']], '4894', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4895']], '4895', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4896']], '4896', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4897']], '4897', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging']['Client']['page'] = 4893;
        $result = $this->Paginator->numbers(['first' => 5, 'modulus' => 4, 'last' => 5, 'separator' => ' - ']);
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:1']], '1', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:2']], '2', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:3']], '3', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4']], '4', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:5']], '5', '/a', '/span',
            '...',
            ['span' => []], ['a' => ['href' => '/index/page:4891']], '4891', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4892']], '4892', '/a', '/span',
            ' - ',
            ['span' => ['class' => 'current']], '4893', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4894']], '4894', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4895']], '4895', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4896']], '4896', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4897']], '4897', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging']['Client']['page'] = 58;
        $result = $this->Paginator->numbers(['first' => 5, 'modulus' => 4, 'last' => 5, 'separator' => ' - ']);
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:1']], '1', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:2']], '2', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:3']], '3', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4']], '4', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:5']], '5', '/a', '/span',
            '...',
            ['span' => []], ['a' => ['href' => '/index/page:56']], '56', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:57']], '57', '/a', '/span',
            ' - ',
            ['span' => ['class' => 'current']], '58', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:59']], '59', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:60']], '60', '/a', '/span',
            '...',
            ['span' => []], ['a' => ['href' => '/index/page:4893']], '4893', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4894']], '4894', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4895']], '4895', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4896']], '4896', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4897']], '4897', '/a', '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging']['Client']['page'] = 5;
        $result = $this->Paginator->numbers(['first' => 5, 'modulus' => 4, 'last' => 5, 'separator' => ' - ']);
        $expected = [
            ['span' => []], ['a' => ['href' => '/index/page:1']], '1', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:2']], '2', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:3']], '3', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4']], '4', '/a', '/span',
            ' - ',
            ['span' => ['class' => 'current']], '5', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:6']], '6', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:7']], '7', '/a', '/span',
            '...',
            ['span' => []], ['a' => ['href' => '/index/page:4893']], '4893', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4894']], '4894', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4895']], '4895', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4896']], '4896', '/a', '/span',
            ' - ',
            ['span' => []], ['a' => ['href' => '/index/page:4897']], '4897', '/a', '/span',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testFirstAndLast method.
     */
    public function testFirstAndLast()
    {
        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 1, 'current' => 3, 'count' => 30, 'prevPage' => false, 'nextPage' => 2, 'pageCount' => 15,
            'defaults' => ['limit' => 3, 'step' => 1, 'order' => ['Client.name' => 'DESC'], 'conditions' => []],
            'options' => ['page' => 1, 'limit' => 3, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];

        $result = $this->Paginator->first();
        $expected = '';
        $this->assertEqual($result, $expected);

        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 4, 'current' => 3, 'count' => 30, 'prevPage' => false, 'nextPage' => 2, 'pageCount' => 15,
            'defaults' => ['limit' => 3, 'step' => 1, 'order' => ['Client.name' => 'DESC'], 'conditions' => []],
            'options' => ['page' => 1, 'limit' => 3, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];

        $result = $this->Paginator->first();
        $expected = [
            '<span',
            'a' => ['href' => '/index/page:1'],
            '&lt;&lt; first',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->first('<<', ['tag' => 'li']);
        $expected = [
            '<li',
            'a' => ['href' => '/index/page:1'],
            '&lt;&lt;',
            '/a',
            '/li',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->last();
        $expected = [
            '<span',
            'a' => ['href' => '/index/page:15'],
            'last &gt;&gt;',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->last(1);
        $expected = [
            '...',
            '<span',
            'a' => ['href' => '/index/page:15'],
            '15',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->last(2);
        $expected = [
            '...',
            '<span',
            ['a' => ['href' => '/index/page:14']], '14', '/a',
            '/span',
            ' | ',
            '<span',
            ['a' => ['href' => '/index/page:15']], '15', '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->last(2, ['tag' => 'li']);
        $expected = [
            '...',
            '<li',
            ['a' => ['href' => '/index/page:14']], '14', '/a',
            '/li',
            ' | ',
            '<li',
            ['a' => ['href' => '/index/page:15']], '15', '/a',
            '/li',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 15, 'current' => 3, 'count' => 30, 'prevPage' => false, 'nextPage' => 2, 'pageCount' => 15,
            'defaults' => ['limit' => 3, 'step' => 1, 'order' => ['Client.name' => 'DESC'], 'conditions' => []],
            'options' => ['page' => 1, 'limit' => 3, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];
        $result = $this->Paginator->last();
        $expected = '';
        $this->assertEqual($result, $expected);

        $this->Paginator->params['paging'] = ['Client' => [
            'page' => 4, 'current' => 3, 'count' => 30, 'prevPage' => false, 'nextPage' => 2, 'pageCount' => 15,
            'defaults' => ['limit' => 3],
            'options' => ['page' => 1, 'limit' => 3, 'order' => ['Client.name' => 'DESC'], 'conditions' => []], ],
        ];

        $result = $this->Paginator->first();
        $expected = [
            '<span',
            ['a' => ['href' => '/index/page:1/sort:Client.name/direction:DESC']], '&lt;&lt; first', '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->last();
        $expected = [
            '<span',
            ['a' => ['href' => '/index/page:15/sort:Client.name/direction:DESC']], 'last &gt;&gt;', '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->last(1);
        $expected = [
            '...',
            '<span',
            ['a' => ['href' => '/index/page:15/sort:Client.name/direction:DESC']], '15', '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Paginator->last(2);
        $expected = [
            '...',
            '<span',
            ['a' => ['href' => '/index/page:14/sort:Client.name/direction:DESC']], '14', '/a',
            '/span',
            ' | ',
            '<span',
            ['a' => ['href' => '/index/page:15/sort:Client.name/direction:DESC']], '15', '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->options(['url' => ['full_base' => true]]);
        $result = $this->Paginator->first();

        $expected = [
            '<span',
            ['a' => ['href' => FULL_BASE_URL.'/index/page:1/sort:Client.name/direction:DESC']], '&lt;&lt; first', '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testCounter method.
     */
    public function testCounter()
    {
        $this->Paginator->params['paging'] = [
            'Client' => [
                'page' => 1,
                'current' => 3,
                'count' => 13,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 5,
                'defaults' => [
                    'limit' => 3,
                    'step' => 1,
                    'order' => ['Client.name' => 'DESC'],
                    'conditions' => [],
                ],
                'options' => [
                    'page' => 1,
                    'limit' => 3,
                    'order' => ['Client.name' => 'DESC'],
                    'conditions' => [],
                    'separator' => 'of',
                ],
            ],
        ];
        $input = 'Page %page% of %pages%, showing %current% records out of %count% total, ';
        $input .= 'starting on record %start%, ending on %end%';
        $result = $this->Paginator->counter($input);
        $expected = 'Page 1 of 5, showing 3 records out of 13 total, starting on record 1, ';
        $expected .= 'ending on 3';
        $this->assertEqual($result, $expected);

        $input = 'Page {:page} of {:pages}, showing {:current} records out of {:count} total, ';
        $input .= 'starting on record {:start}, ending on {:end}';
        $result = $this->Paginator->counter($input);
        $this->assertEqual($result, $expected);

        $input = 'Page %page% of %pages%';
        $result = $this->Paginator->counter($input);
        $expected = 'Page 1 of 5';
        $this->assertEqual($result, $expected);

        $result = $this->Paginator->counter(['format' => $input]);
        $expected = 'Page 1 of 5';
        $this->assertEqual($result, $expected);

        $result = $this->Paginator->counter(['format' => 'pages']);
        $expected = '1 of 5';
        $this->assertEqual($result, $expected);

        $result = $this->Paginator->counter(['format' => 'range']);
        $expected = '1 - 3 of 13';
        $this->assertEqual($result, $expected);
    }

    /**
     * testHasPage method.
     */
    public function testHasPage()
    {
        $result = $this->Paginator->hasPage('Article', 15);
        $this->assertFalse($result);

        $result = $this->Paginator->hasPage('UndefinedModel', 2);
        $this->assertFalse($result);

        $result = $this->Paginator->hasPage('Article', 2);
        $this->assertTrue($result);

        $result = $this->Paginator->hasPage(2);
        $this->assertTrue($result);
    }

    /**
     * testWithPlugin method.
     */
    public function testWithPlugin()
    {
        Router::reload();
        Router::setRequestInfo([
            [
                'pass' => [], 'named' => [], 'prefix' => null, 'form' => [],
                'controller' => 'magazines', 'plugin' => 'my_plugin', 'action' => 'index',
                'url' => ['ext' => 'html', 'url' => 'my_plugin/magazines'], ],
            ['base' => '', 'here' => '/my_plugin/magazines', 'webroot' => '/'],
        ]);

        $result = $this->Paginator->link('Page 3', ['page' => 3]);
        $expected = [
            'a' => ['href' => '/my_plugin/magazines/index/page:3'], 'Page 3', '/a',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->options(['url' => ['action' => 'another_index']]);
        $result = $this->Paginator->link('Page 3', ['page' => 3]);
        $expected = [
            'a' => ['href' => '/my_plugin/magazines/another_index/page:3'], 'Page 3', '/a',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->options(['url' => ['controller' => 'issues']]);
        $result = $this->Paginator->link('Page 3', ['page' => 3]);
        $expected = [
            'a' => ['href' => '/my_plugin/issues/index/page:3'], 'Page 3', '/a',
        ];
        $this->assertTags($result, $expected);

        $this->Paginator->options(['url' => ['plugin' => null]]);
        $result = $this->Paginator->link('Page 3', ['page' => 3]);
        $expected = [
            'a' => ['/magazines/index/page:3'], 'Page 3', '/a',
        ];

        $this->Paginator->options(['url' => ['plugin' => null, 'controller' => 'issues']]);
        $result = $this->Paginator->link('Page 3', ['page' => 3]);
        $expected = [
            'a' => ['href' => '/issues/index/page:3'], 'Page 3', '/a',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testNextLinkUsingDotNotation method.
     */
    public function testNextLinkUsingDotNotation()
    {
        Router::reload();
        Router::parse('/');
        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'accounts', 'action' => 'index', 'pass' => [], 'form' => [], 'url' => ['url' => 'accounts/', 'mod_rewrite' => 'true'], 'bare' => 0],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '/officespace', 'here' => '/officespace/accounts/', 'webroot' => '/officespace/', 'passedArgs' => []],
        ]);

        $this->Paginator->params['paging']['Article']['options']['order'] = ['Article.title' => 'asc'];
        $this->Paginator->params['paging']['Article']['page'] = 1;

        $test = ['url' => [
            'page' => '1',
            'sort' => 'Article.title',
            'direction' => 'asc',
        ]];
        $this->Paginator->options($test);

        $result = $this->Paginator->next('Next');
        $expected = [
            '<span',
            'a' => ['href' => '/officespace/accounts/index/page:2/sort:Article.title/direction:asc', 'class' => 'next'],
            'Next',
            '/a',
            '/span',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test that mock classes injected into paginatorHelper are called when using link().
     */
    public function testMockAjaxProviderClassInjection()
    {
        $Paginator = new PaginatorHelper(['ajax' => 'PaginatorMockJs']);
        $Paginator->params['paging'] = [
            'Article' => [
                'current' => 9,
                'count' => 62,
                'prevPage' => false,
                'nextPage' => true,
                'pageCount' => 7,
                'defaults' => [],
                'options' => [],
            ],
        ];
        $Paginator->PaginatorMockJs = new PaginatorMockJsHelper();
        $Paginator->PaginatorMockJs->expectOnce('link');
        $result = $Paginator->link('Page 2', ['page' => 2], ['update' => '#content']);

        $this->expectError();
        $Paginator = new PaginatorHelper(['ajax' => 'Form']);
    }
}
