<?php
/**
 * RouterTest file.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html>
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 *	Licensed under The Open Group Test Suite License
 *	Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html CakePHP(tm) Tests
 * @since         CakePHP(tm) v 1.2.0.4206
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Core', ['Router']);

if (!defined('FULL_BASE_URL')) {
    define('FULL_BASE_URL', 'http://cakephp.org');
}

/**
 * RouterTest class.
 */
class RouterTest extends CakeTestCase
{
    /**
     * setUp method.
     */
    public function setUp()
    {
        $this->_routing = Configure::read('Routing');
        Configure::write('Routing', ['admin' => null, 'prefixes' => []]);
        Router::reload();
        $this->router = &Router::getInstance();
    }

    /**
     * end the test and reset the environment.
     */
    public function endTest()
    {
        Configure::write('Routing', $this->_routing);
    }

    /**
     * testReturnedInstanceReference method.
     */
    public function testReturnedInstanceReference()
    {
        $this->router->testVar = 'test';
        $this->assertIdentical($this->router, Router::getInstance());
        unset($this->router->testVar);
    }

    /**
     * testFullBaseURL method.
     */
    public function testFullBaseURL()
    {
        $this->assertPattern('/^http(s)?:\/\//', Router::url('/', true));
        $this->assertPattern('/^http(s)?:\/\//', Router::url(null, true));
        $this->assertPattern('/^http(s)?:\/\//', Router::url(['full_base' => true]));
        $this->assertIdentical(FULL_BASE_URL.'/', Router::url(['full_base' => true]));
    }

    /**
     * testRouteDefaultParams method.
     */
    public function testRouteDefaultParams()
    {
        Router::connect('/:controller', ['controller' => 'posts']);
        $this->assertEqual(Router::url(['action' => 'index']), '/');
    }

    /**
     * testRouterIdentity method.
     */
    public function testRouterIdentity()
    {
        $router2 = new Router();
        $this->assertEqual(get_object_vars($this->router), get_object_vars($router2));
    }

    /**
     * testResourceRoutes method.
     */
    public function testResourceRoutes()
    {
        Router::mapResources('Posts');

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $result = Router::parse('/posts');
        $this->assertEqual($result, ['pass' => [], 'named' => [], 'plugin' => '', 'controller' => 'posts', 'action' => 'index', '[method]' => 'GET']);
        $this->assertEqual($this->router->__resourceMapped, ['posts']);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $result = Router::parse('/posts/13');
        $this->assertEqual($result, ['pass' => ['13'], 'named' => [], 'plugin' => '', 'controller' => 'posts', 'action' => 'view', 'id' => '13', '[method]' => 'GET']);
        $this->assertEqual($this->router->__resourceMapped, ['posts']);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $result = Router::parse('/posts');
        $this->assertEqual($result, ['pass' => [], 'named' => [], 'plugin' => '', 'controller' => 'posts', 'action' => 'add', '[method]' => 'POST']);
        $this->assertEqual($this->router->__resourceMapped, ['posts']);

        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $result = Router::parse('/posts/13');
        $this->assertEqual($result, ['pass' => ['13'], 'named' => [], 'plugin' => '', 'controller' => 'posts', 'action' => 'edit', 'id' => '13', '[method]' => 'PUT']);
        $this->assertEqual($this->router->__resourceMapped, ['posts']);

        $result = Router::parse('/posts/475acc39-a328-44d3-95fb-015000000000');
        $this->assertEqual($result, ['pass' => ['475acc39-a328-44d3-95fb-015000000000'], 'named' => [], 'plugin' => '', 'controller' => 'posts', 'action' => 'edit', 'id' => '475acc39-a328-44d3-95fb-015000000000', '[method]' => 'PUT']);
        $this->assertEqual($this->router->__resourceMapped, ['posts']);

        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $result = Router::parse('/posts/13');
        $this->assertEqual($result, ['pass' => ['13'], 'named' => [], 'plugin' => '', 'controller' => 'posts', 'action' => 'delete', 'id' => '13', '[method]' => 'DELETE']);
        $this->assertEqual($this->router->__resourceMapped, ['posts']);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $result = Router::parse('/posts/add');
        $this->assertEqual($result, ['pass' => [], 'named' => [], 'plugin' => '', 'controller' => 'posts', 'action' => 'add']);
        $this->assertEqual($this->router->__resourceMapped, ['posts']);

        Router::reload();
        Router::mapResources('Posts', ['id' => '[a-z0-9_]+']);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $result = Router::parse('/posts/add');
        $this->assertEqual($result, ['pass' => ['add'], 'named' => [], 'plugin' => '', 'controller' => 'posts', 'action' => 'view', 'id' => 'add', '[method]' => 'GET']);
        $this->assertEqual($this->router->__resourceMapped, ['posts']);

        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $result = Router::parse('/posts/name');
        $this->assertEqual($result, ['pass' => ['name'], 'named' => [], 'plugin' => '', 'controller' => 'posts', 'action' => 'edit', 'id' => 'name', '[method]' => 'PUT']);
        $this->assertEqual($this->router->__resourceMapped, ['posts']);
    }

    /**
     * testMultipleResourceRoute method.
     */
    public function testMultipleResourceRoute()
    {
        Router::connect('/:controller', ['action' => 'index', '[method]' => ['GET', 'POST']]);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $result = Router::parse('/posts');
        $this->assertEqual($result, ['pass' => [], 'named' => [], 'plugin' => '', 'controller' => 'posts', 'action' => 'index', '[method]' => ['GET', 'POST']]);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $result = Router::parse('/posts');
        $this->assertEqual($result, ['pass' => [], 'named' => [], 'plugin' => '', 'controller' => 'posts', 'action' => 'index', '[method]' => ['GET', 'POST']]);
    }

    /**
     * testGenerateUrlResourceRoute method.
     */
    public function testGenerateUrlResourceRoute()
    {
        Router::mapResources('Posts');

        $result = Router::url(['controller' => 'posts', 'action' => 'index', '[method]' => 'GET']);
        $expected = '/posts';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'posts', 'action' => 'view', '[method]' => 'GET', 'id' => 10]);
        $expected = '/posts/10';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'posts', 'action' => 'add', '[method]' => 'POST']);
        $expected = '/posts';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'posts', 'action' => 'edit', '[method]' => 'PUT', 'id' => 10]);
        $expected = '/posts/10';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'posts', 'action' => 'delete', '[method]' => 'DELETE', 'id' => 10]);
        $expected = '/posts/10';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'posts', 'action' => 'edit', '[method]' => 'POST', 'id' => 10]);
        $expected = '/posts/10';
        $this->assertEqual($result, $expected);
    }

    /**
     * testUrlNormalization method.
     */
    public function testUrlNormalization()
    {
        $expected = '/users/logout';

        $result = Router::normalize('/users/logout/');
        $this->assertEqual($result, $expected);

        $result = Router::normalize('//users//logout//');
        $this->assertEqual($result, $expected);

        $result = Router::normalize('users/logout');
        $this->assertEqual($result, $expected);

        $result = Router::normalize(['controller' => 'users', 'action' => 'logout']);
        $this->assertEqual($result, $expected);

        $result = Router::normalize('/');
        $this->assertEqual($result, '/');

        $result = Router::normalize('http://google.com/');
        $this->assertEqual($result, 'http://google.com/');

        $result = Router::normalize('http://google.com//');
        $this->assertEqual($result, 'http://google.com//');

        $result = Router::normalize('/users/login/scope://foo');
        $this->assertEqual($result, '/users/login/scope:/foo');

        $result = Router::normalize('/recipe/recipes/add');
        $this->assertEqual($result, '/recipe/recipes/add');

        Router::setRequestInfo([[], ['base' => '/us']]);
        $result = Router::normalize('/us/users/logout/');
        $this->assertEqual($result, '/users/logout');

        Router::reload();

        Router::setRequestInfo([[], ['base' => '/cake_12']]);
        $result = Router::normalize('/cake_12/users/logout/');
        $this->assertEqual($result, '/users/logout');

        Router::reload();
        $_back = Configure::read('App.baseUrl');
        Configure::write('App.baseUrl', '/');

        Router::setRequestInfo([[], ['base' => '/']]);
        $result = Router::normalize('users/login');
        $this->assertEqual($result, '/users/login');
        Configure::write('App.baseUrl', $_back);

        Router::reload();
        Router::setRequestInfo([[], ['base' => 'beer']]);
        $result = Router::normalize('beer/admin/beers_tags/add');
        $this->assertEqual($result, '/admin/beers_tags/add');

        $result = Router::normalize('/admin/beers_tags/add');
        $this->assertEqual($result, '/admin/beers_tags/add');
    }

    /**
     * test generation of basic urls.
     */
    public function testUrlGenerationBasic()
    {
        extract(Router::getNamedExpressions());

        Router::setRequestInfo([
            [
                'pass' => [], 'action' => 'index', 'plugin' => null, 'controller' => 'subscribe',
                'admin' => true, 'url' => ['url' => ''],
            ],
            [
                'base' => '/magazine', 'here' => '/magazine',
                'webroot' => '/magazine/', 'passedArgs' => ['page' => 2], 'namedArgs' => ['page' => 2],
            ],
        ]);
        $result = Router::url();
        $this->assertEqual('/magazine', $result);

        Router::reload();

        Router::connect('/', ['controller' => 'pages', 'action' => 'display', 'home']);
        $out = Router::url(['controller' => 'pages', 'action' => 'display', 'home']);
        $this->assertEqual($out, '/');

        Router::connect('/pages/*', ['controller' => 'pages', 'action' => 'display']);
        $result = Router::url(['controller' => 'pages', 'action' => 'display', 'about']);
        $expected = '/pages/about';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/:plugin/:id/*', ['controller' => 'posts', 'action' => 'view'], ['id' => $ID]);
        Router::parse('/');

        $result = Router::url(['plugin' => 'cake_plugin', 'controller' => 'posts', 'action' => 'view', 'id' => '1']);
        $expected = '/cake_plugin/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['plugin' => 'cake_plugin', 'controller' => 'posts', 'action' => 'view', 'id' => '1', '0']);
        $expected = '/cake_plugin/1/0';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/:controller/:action/:id', [], ['id' => $ID]);
        Router::parse('/');

        $result = Router::url(['controller' => 'posts', 'action' => 'view', 'id' => '1']);
        $expected = '/posts/view/1';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/:controller/:id', ['action' => 'view']);
        Router::parse('/');

        $result = Router::url(['controller' => 'posts', 'action' => 'view', 'id' => '1']);
        $expected = '/posts/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'posts', 'action' => 'index', '0']);
        $expected = '/posts/index/0';
        $this->assertEqual($result, $expected);

        Router::connect('/view/*', ['controller' => 'posts', 'action' => 'view']);
        Router::promote();
        $result = Router::url(['controller' => 'posts', 'action' => 'view', '1']);
        $expected = '/view/1';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::setRequestInfo([
            ['pass' => [], 'action' => 'index', 'plugin' => null, 'controller' => 'real_controller_name', 'url' => ['url' => '']],
            [
                'base' => '/', 'here' => '/',
                'webroot' => '/', 'passedArgs' => ['page' => 2], 'namedArgs' => ['page' => 2],
            ],
        ]);
        Router::connect('short_controller_name/:action/*', ['controller' => 'real_controller_name']);
        Router::parse('/');

        $result = Router::url(['controller' => 'real_controller_name', 'page' => '1']);
        $expected = '/short_controller_name/index/page:1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['action' => 'add']);
        $expected = '/short_controller_name/add';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::parse('/');
        Router::setRequestInfo([
            ['pass' => [], 'action' => 'index', 'plugin' => null, 'controller' => 'users', 'url' => ['url' => 'users']],
            [
                'base' => '/', 'here' => '/',
                'webroot' => '/', 'passedArgs' => [], 'argSeparator' => ':', 'namedArgs' => [],
            ],
        ]);

        $result = Router::url(['action' => 'login']);
        $expected = '/users/login';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/page/*', ['plugin' => null, 'controller' => 'pages', 'action' => 'view']);
        Router::parse('/');

        $result = Router::url(['plugin' => 'my_plugin', 'controller' => 'pages', 'action' => 'view', 'my-page']);
        $expected = '/my_plugin/pages/view/my-page';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/contact/:action', ['plugin' => 'contact', 'controller' => 'contact']);
        Router::parse('/');

        $result = Router::url(['plugin' => 'contact', 'controller' => 'contact', 'action' => 'me']);

        $expected = '/contact/me';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::setRequestInfo([
            [
                'pass' => [], 'action' => 'index', 'plugin' => 'myplugin', 'controller' => 'mycontroller',
                'admin' => false, 'url' => ['url' => []],
            ],
            [
                'base' => '/', 'here' => '/',
                'webroot' => '/', 'passedArgs' => [], 'namedArgs' => [],
            ],
        ]);

        $result = Router::url(['plugin' => null, 'controller' => 'myothercontroller']);
        $expected = '/myothercontroller';
        $this->assertEqual($result, $expected);
    }

    /**
     * Test generation of routes with query string parameters.
     *
     **/
    public function testUrlGenerationWithQueryStrings()
    {
        $result = Router::url(['controller' => 'posts', 'action' => 'index', '0', '?' => 'var=test&var2=test2']);
        $expected = '/posts/index/0?var=test&var2=test2';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'posts', '0', '?' => 'var=test&var2=test2']);
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'posts', '0', '?' => ['var' => 'test', 'var2' => 'test2']]);
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'posts', '0', '?' => ['var' => null]]);
        $this->assertEqual($result, '/posts/index/0');

        $result = Router::url(['controller' => 'posts', '0', '?' => 'var=test&var2=test2', '#' => 'unencoded string %']);
        $expected = '/posts/index/0?var=test&var2=test2#unencoded+string+%25';
        $this->assertEqual($result, $expected);
    }

    /**
     * test that regex validation of keyed route params is working.
     *
     **/
    public function testUrlGenerationWithRegexQualifiedParams()
    {
        Router::connect(
            ':language/galleries',
            ['controller' => 'galleries', 'action' => 'index'],
            ['language' => '[a-z]{3}']
        );

        Router::connect(
            '/:language/:admin/:controller/:action/*',
            ['admin' => 'admin'],
            ['language' => '[a-z]{3}', 'admin' => 'admin']
        );

        Router::connect('/:language/:controller/:action/*',
            [],
            ['language' => '[a-z]{3}']
        );

        $result = Router::url(['admin' => false, 'language' => 'dan', 'action' => 'index', 'controller' => 'galleries']);
        $expected = '/dan/galleries';
        $this->assertEqual($result, $expected);

        $result = Router::url(['admin' => false, 'language' => 'eng', 'action' => 'index', 'controller' => 'galleries']);
        $expected = '/eng/galleries';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/:language/pages',
            ['controller' => 'pages', 'action' => 'index'],
            ['language' => '[a-z]{3}']
        );
        Router::connect('/:language/:controller/:action/*', [], ['language' => '[a-z]{3}']);

        $result = Router::url(['language' => 'eng', 'action' => 'index', 'controller' => 'pages']);
        $expected = '/eng/pages';
        $this->assertEqual($result, $expected);

        $result = Router::url(['language' => 'eng', 'controller' => 'pages']);
        $this->assertEqual($result, $expected);

        $result = Router::url(['language' => 'eng', 'controller' => 'pages', 'action' => 'add']);
        $expected = '/eng/pages/add';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/forestillinger/:month/:year/*',
            ['plugin' => 'shows', 'controller' => 'shows', 'action' => 'calendar'],
            ['month' => '0[1-9]|1[012]', 'year' => '[12][0-9]{3}']
        );
        Router::parse('/');

        $result = Router::url(['plugin' => 'shows', 'controller' => 'shows', 'action' => 'calendar', 'month' => 10, 'year' => 2007, 'min-forestilling']);
        $expected = '/forestillinger/10/2007/min-forestilling';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/kalender/:month/:year/*',
            ['plugin' => 'shows', 'controller' => 'shows', 'action' => 'calendar'],
            ['month' => '0[1-9]|1[012]', 'year' => '[12][0-9]{3}']
        );
        Router::connect('/kalender/*', ['plugin' => 'shows', 'controller' => 'shows', 'action' => 'calendar']);
        Router::parse('/');

        $result = Router::url(['plugin' => 'shows', 'controller' => 'shows', 'action' => 'calendar', 'min-forestilling']);
        $expected = '/kalender/min-forestilling';
        $this->assertEqual($result, $expected);

        $result = Router::url(['plugin' => 'shows', 'controller' => 'shows', 'action' => 'calendar', 'year' => 2007, 'month' => 10, 'min-forestilling']);
        $expected = '/kalender/10/2007/min-forestilling';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/:controller/:action/*', [], [
            'controller' => 'source|wiki|commits|tickets|comments|view',
            'action' => 'branches|history|branch|logs|view|start|add|edit|modify',
        ]);
        Router::defaults(false);
        $result = Router::parse('/foo/bar');
        $expected = ['pass' => [], 'named' => []];
        $this->assertEqual($result, $expected);
    }

    /**
     * Test url generation with an admin prefix.
     */
    public function testUrlGenerationWithAdminPrefix()
    {
        Configure::write('Routing.admin', 'admin');
        Router::reload();

        Router::connectNamed(['event', 'lang']);
        Router::connect('/', ['controller' => 'pages', 'action' => 'display', 'home']);
        Router::connect('/pages/contact_us', ['controller' => 'pages', 'action' => 'contact_us']);
        Router::connect('/pages/*', ['controller' => 'pages', 'action' => 'display']);
        Router::connect('/reset/*', ['admin' => true, 'controller' => 'users', 'action' => 'reset']);
        Router::connect('/tests', ['controller' => 'tests', 'action' => 'index']);
        Router::parseExtensions('rss');

        Router::setRequestInfo([
            ['pass' => [], 'named' => [], 'controller' => 'registrations', 'action' => 'admin_index', 'plugin' => '', 'prefix' => 'admin', 'admin' => true, 'url' => ['ext' => 'html', 'url' => 'admin/registrations/index'], 'form' => []],
            ['base' => '', 'here' => '/admin/registrations/index', 'webroot' => '/'],
        ]);

        $result = Router::url(['page' => 2]);
        $expected = '/admin/registrations/index/page:2';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::setRequestInfo([
            [
                'pass' => [], 'action' => 'admin_index', 'plugin' => null, 'controller' => 'subscriptions',
                'admin' => true, 'url' => ['url' => 'admin/subscriptions/index/page:2'],
            ],
            [
                'base' => '/magazine', 'here' => '/magazine/admin/subscriptions/index/page:2',
                'webroot' => '/magazine/', 'passedArgs' => ['page' => 2],
            ],
        ]);
        Router::parse('/');

        $result = Router::url(['page' => 3]);
        $expected = '/magazine/admin/subscriptions/index/page:3';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/admin/subscriptions/:action/*', ['controller' => 'subscribe', 'admin' => true, 'prefix' => 'admin']);
        Router::parse('/');
        Router::setRequestInfo([
            [
                'pass' => [], 'action' => 'admin_index', 'plugin' => null, 'controller' => 'subscribe',
                'admin' => true, 'url' => ['url' => 'admin/subscriptions/edit/1'],
            ],
            [
                'base' => '/magazine', 'here' => '/magazine/admin/subscriptions/edit/1',
                'webroot' => '/magazine/', 'passedArgs' => ['page' => 2], 'namedArgs' => ['page' => 2],
            ],
        ]);

        $result = Router::url(['action' => 'edit', 1]);
        $expected = '/magazine/admin/subscriptions/edit/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['admin' => true, 'controller' => 'users', 'action' => 'login']);
        $expected = '/magazine/admin/users/login';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::setRequestInfo([
            ['pass' => [], 'admin' => true, 'action' => 'index', 'plugin' => null, 'controller' => 'users', 'url' => ['url' => 'users']],
            [
                'base' => '/', 'here' => '/',
                'webroot' => '/', 'passedArgs' => [], 'argSeparator' => ':', 'namedArgs' => [],
            ],
        ]);
        Router::connect('/page/*', ['controller' => 'pages', 'action' => 'view', 'admin' => true, 'prefix' => 'admin']);
        Router::parse('/');

        $result = Router::url(['admin' => true, 'controller' => 'pages', 'action' => 'view', 'my-page']);
        $expected = '/page/my-page';
        $this->assertEqual($result, $expected);

        Configure::write('Routing.admin', 'admin');
        Router::reload();

        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'pages', 'action' => 'admin_add', 'pass' => [], 'prefix' => 'admin', 'admin' => true, 'form' => [], 'url' => ['url' => 'admin/pages/add']],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '', 'here' => '/admin/pages/add', 'webroot' => '/'],
        ]);
        Router::parse('/');

        $result = Router::url(['plugin' => null, 'controller' => 'pages', 'action' => 'add', 'id' => false]);
        $expected = '/admin/pages/add';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::parse('/');
        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'pages', 'action' => 'admin_add', 'pass' => [], 'prefix' => 'admin', 'admin' => true, 'form' => [], 'url' => ['url' => 'admin/pages/add']],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '', 'here' => '/admin/pages/add', 'webroot' => '/'],
        ]);

        $result = Router::url(['plugin' => null, 'controller' => 'pages', 'action' => 'add', 'id' => false]);
        $expected = '/admin/pages/add';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/admin/:controller/:action/:id', ['admin' => true], ['id' => '[0-9]+']);
        Router::parse('/');
        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'pages', 'action' => 'admin_edit', 'pass' => ['284'], 'prefix' => 'admin', 'admin' => true, 'form' => [], 'url' => ['url' => 'admin/pages/edit/284']],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '', 'here' => '/admin/pages/edit/284', 'webroot' => '/'],
        ]);

        $result = Router::url(['plugin' => null, 'controller' => 'pages', 'action' => 'edit', 'id' => '284']);
        $expected = '/admin/pages/edit/284';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::parse('/');
        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'pages', 'action' => 'admin_add', 'pass' => [], 'prefix' => 'admin', 'admin' => true, 'form' => [], 'url' => ['url' => 'admin/pages/add']],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '', 'here' => '/admin/pages/add', 'webroot' => '/'],
        ]);

        $result = Router::url(['plugin' => null, 'controller' => 'pages', 'action' => 'add', 'id' => false]);
        $expected = '/admin/pages/add';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::parse('/');
        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'pages', 'action' => 'admin_edit', 'pass' => ['284'], 'prefix' => 'admin', 'admin' => true, 'form' => [], 'url' => ['url' => 'admin/pages/edit/284']],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '', 'here' => '/admin/pages/edit/284', 'webroot' => '/'],
        ]);

        $result = Router::url(['plugin' => null, 'controller' => 'pages', 'action' => 'edit', 284]);
        $expected = '/admin/pages/edit/284';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/admin/posts/*', ['controller' => 'posts', 'action' => 'index', 'admin' => true]);
        Router::parse('/');
        Router::setRequestInfo([
            ['pass' => [], 'action' => 'admin_index', 'plugin' => null, 'controller' => 'posts', 'prefix' => 'admin', 'admin' => true, 'url' => ['url' => 'admin/posts']],
            ['base' => '', 'here' => '/admin/posts', 'webroot' => '/'],
        ]);

        $result = Router::url(['all']);
        $expected = '/admin/posts/all';
        $this->assertEqual($result, $expected);
    }

    /**
     * testUrlGenerationWithExtensions method.
     */
    public function testUrlGenerationWithExtensions()
    {
        Router::parse('/');
        $result = Router::url(['plugin' => null, 'controller' => 'articles', 'action' => 'add', 'id' => null, 'ext' => 'json']);
        $expected = '/articles/add.json';
        $this->assertEqual($result, $expected);

        $result = Router::url(['plugin' => null, 'controller' => 'articles', 'action' => 'add', 'ext' => 'json']);
        $expected = '/articles/add.json';
        $this->assertEqual($result, $expected);

        $result = Router::url(['plugin' => null, 'controller' => 'articles', 'action' => 'index', 'id' => null, 'ext' => 'json']);
        $expected = '/articles.json';
        $this->assertEqual($result, $expected);

        $result = Router::url(['plugin' => null, 'controller' => 'articles', 'action' => 'index', 'ext' => 'json']);
        $expected = '/articles.json';
        $this->assertEqual($result, $expected);
    }

    /**
     * testPluginUrlGeneration method.
     */
    public function testUrlGenerationPlugins()
    {
        Router::setRequestInfo([
            [
                'controller' => 'controller', 'action' => 'index', 'form' => [],
                'url' => [], 'plugin' => 'test',
            ],
            [
                'base' => '/base', 'here' => '/clients/sage/portal/donations', 'webroot' => '/base/',
                'passedArgs' => [], 'argSeparator' => ':', 'namedArgs' => [],
            ],
        ]);

        $this->assertEqual(Router::url('read/1'), '/base/test/controller/read/1');

        Router::reload();
        Router::connect('/:lang/:plugin/:controller/*', ['action' => 'index']);

        Router::setRequestInfo([
            [
                'lang' => 'en',
                'plugin' => 'shows', 'controller' => 'shows', 'action' => 'index', 'pass' => [], 'form' => [], 'url' => ['url' => 'en/shows/'], ],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '',
            'here' => '/en/shows/', 'webroot' => '/', ],
        ]);

        Router::parse('/en/shows/');

        $result = Router::url([
            'lang' => 'en',
            'controller' => 'shows', 'action' => 'index', 'page' => '1',
        ]);
        $expected = '/en/shows/shows/page:1';
        $this->assertEqual($result, $expected);
    }

    /**
     * test that you can leave active plugin routes with plugin = null.
     */
    public function testCanLeavePlugin()
    {
        Router::reload();
        Router::connect(
            '/admin/other/:controller/:action/*',
            [
                'admin' => 1,
                'plugin' => 'aliased',
                'prefix' => 'admin',
            ]
        );
        Router::setRequestInfo([
            [
                'pass' => [],
                'admin' => true,
                'prefix' => 'admin',
                'plugin' => 'this',
                'action' => 'admin_index',
                'controller' => 'interesting',
                'url' => ['url' => 'admin/this/interesting/index'],
            ],
            [
                'base' => '',
                'here' => '/admin/this/interesting/index',
                'webroot' => '/',
                'passedArgs' => [],
            ],
        ]);
        $result = Router::url(['plugin' => null, 'controller' => 'posts', 'action' => 'index']);
        $this->assertEqual($result, '/admin/posts');

        $result = Router::url(['controller' => 'posts', 'action' => 'index']);
        $this->assertEqual($result, '/admin/this/posts');

        $result = Router::url(['plugin' => 'aliased', 'controller' => 'posts', 'action' => 'index']);
        $this->assertEqual($result, '/admin/other/posts/index');
    }

    /**
     * testUrlParsing method.
     */
    public function testUrlParsing()
    {
        extract(Router::getNamedExpressions());

        Router::connect('/posts/:value/:somevalue/:othervalue/*', ['controller' => 'posts', 'action' => 'view'], ['value', 'somevalue', 'othervalue']);
        $result = Router::parse('/posts/2007/08/01/title-of-post-here');
        $expected = ['value' => '2007', 'somevalue' => '08', 'othervalue' => '01', 'controller' => 'posts', 'action' => 'view', 'plugin' => '', 'pass' => ['0' => 'title-of-post-here'], 'named' => []];
        $this->assertEqual($result, $expected);

        $this->router->routes = [];
        Router::connect('/posts/:year/:month/:day/*', ['controller' => 'posts', 'action' => 'view'], ['year' => $Year, 'month' => $Month, 'day' => $Day]);
        $result = Router::parse('/posts/2007/08/01/title-of-post-here');
        $expected = ['year' => '2007', 'month' => '08', 'day' => '01', 'controller' => 'posts', 'action' => 'view', 'plugin' => '', 'pass' => ['0' => 'title-of-post-here'], 'named' => []];
        $this->assertEqual($result, $expected);

        $this->router->routes = [];
        Router::connect('/posts/:day/:year/:month/*', ['controller' => 'posts', 'action' => 'view'], ['year' => $Year, 'month' => $Month, 'day' => $Day]);
        $result = Router::parse('/posts/01/2007/08/title-of-post-here');
        $expected = ['day' => '01', 'year' => '2007', 'month' => '08', 'controller' => 'posts', 'action' => 'view', 'plugin' => '', 'pass' => ['0' => 'title-of-post-here'], 'named' => []];
        $this->assertEqual($result, $expected);

        $this->router->routes = [];
        Router::connect('/posts/:month/:day/:year/*', ['controller' => 'posts', 'action' => 'view'], ['year' => $Year, 'month' => $Month, 'day' => $Day]);
        $result = Router::parse('/posts/08/01/2007/title-of-post-here');
        $expected = ['month' => '08', 'day' => '01', 'year' => '2007', 'controller' => 'posts', 'action' => 'view', 'plugin' => '', 'pass' => ['0' => 'title-of-post-here'], 'named' => []];
        $this->assertEqual($result, $expected);

        $this->router->routes = [];
        Router::connect('/posts/:year/:month/:day/*', ['controller' => 'posts', 'action' => 'view']);
        $result = Router::parse('/posts/2007/08/01/title-of-post-here');
        $expected = ['year' => '2007', 'month' => '08', 'day' => '01', 'controller' => 'posts', 'action' => 'view', 'plugin' => '', 'pass' => ['0' => 'title-of-post-here'], 'named' => []];
        $this->assertEqual($result, $expected);

        Router::reload();
        $result = Router::parse('/pages/display/home');
        $expected = ['plugin' => null, 'pass' => ['home'], 'controller' => 'pages', 'action' => 'display', 'named' => []];
        $this->assertEqual($result, $expected);

        $result = Router::parse('pages/display/home/');
        $this->assertEqual($result, $expected);

        $result = Router::parse('pages/display/home');
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/page/*', ['controller' => 'test']);
        $result = Router::parse('/page/my-page');
        $expected = ['pass' => ['my-page'], 'plugin' => null, 'controller' => 'test', 'action' => 'index'];

        Router::reload();
        Router::connect('/:language/contact', ['language' => 'eng', 'plugin' => 'contact', 'controller' => 'contact', 'action' => 'index'], ['language' => '[a-z]{3}']);
        $result = Router::parse('/eng/contact');
        $expected = ['pass' => [], 'named' => [], 'language' => 'eng', 'plugin' => 'contact', 'controller' => 'contact', 'action' => 'index'];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/forestillinger/:month/:year/*',
            ['plugin' => 'shows', 'controller' => 'shows', 'action' => 'calendar'],
            ['month' => '0[1-9]|1[012]', 'year' => '[12][0-9]{3}']
        );

        $result = Router::parse('/forestillinger/10/2007/min-forestilling');
        $expected = ['pass' => ['min-forestilling'], 'plugin' => 'shows', 'controller' => 'shows', 'action' => 'calendar', 'year' => 2007, 'month' => 10, 'named' => []];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/:controller/:action/*');
        Router::connect('/', ['plugin' => 'pages', 'controller' => 'pages', 'action' => 'display']);
        $result = Router::parse('/');
        $expected = ['pass' => [], 'named' => [], 'controller' => 'pages', 'action' => 'display', 'plugin' => 'pages'];
        $this->assertEqual($result, $expected);

        $result = Router::parse('/posts/edit/0');
        $expected = ['pass' => [0], 'named' => [], 'controller' => 'posts', 'action' => 'edit', 'plugin' => null];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/posts/:id::url_title', ['controller' => 'posts', 'action' => 'view'], ['pass' => ['id', 'url_title'], 'id' => '[\d]+']);
        $result = Router::parse('/posts/5:sample-post-title');
        $expected = ['pass' => ['5', 'sample-post-title'], 'named' => [], 'id' => 5, 'url_title' => 'sample-post-title', 'plugin' => null, 'controller' => 'posts', 'action' => 'view'];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/posts/:id::url_title/*', ['controller' => 'posts', 'action' => 'view'], ['pass' => ['id', 'url_title'], 'id' => '[\d]+']);
        $result = Router::parse('/posts/5:sample-post-title/other/params/4');
        $expected = ['pass' => ['5', 'sample-post-title', 'other', 'params', '4'], 'named' => [], 'id' => 5, 'url_title' => 'sample-post-title', 'plugin' => null, 'controller' => 'posts', 'action' => 'view'];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/posts/:url_title-(uuid::id)', ['controller' => 'posts', 'action' => 'view'], ['pass' => ['id', 'url_title'], 'id' => $UUID]);
        $result = Router::parse('/posts/sample-post-title-(uuid:47fc97a9-019c-41d1-a058-1fa3cbdd56cb)');
        $expected = ['pass' => ['47fc97a9-019c-41d1-a058-1fa3cbdd56cb', 'sample-post-title'], 'named' => [], 'id' => '47fc97a9-019c-41d1-a058-1fa3cbdd56cb', 'url_title' => 'sample-post-title', 'plugin' => null, 'controller' => 'posts', 'action' => 'view'];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/posts/view/*', ['controller' => 'posts', 'action' => 'view'], ['named' => false]);
        $result = Router::parse('/posts/view/foo:bar/routing:fun');
        $expected = ['pass' => ['foo:bar', 'routing:fun'], 'named' => [], 'plugin' => null, 'controller' => 'posts', 'action' => 'view'];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/posts/view/*', ['controller' => 'posts', 'action' => 'view'], ['named' => ['foo', 'answer']]);
        $result = Router::parse('/posts/view/foo:bar/routing:fun/answer:42');
        $expected = ['pass' => ['routing:fun'], 'named' => ['foo' => 'bar', 'answer' => '42'], 'plugin' => null, 'controller' => 'posts', 'action' => 'view'];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/posts/view/*', ['controller' => 'posts', 'action' => 'view'], ['named' => ['foo', 'answer'], 'greedy' => true]);
        $result = Router::parse('/posts/view/foo:bar/routing:fun/answer:42');
        $expected = ['pass' => [], 'named' => ['foo' => 'bar', 'routing' => 'fun', 'answer' => '42'], 'plugin' => null, 'controller' => 'posts', 'action' => 'view'];
        $this->assertEqual($result, $expected);
    }

    /**
     * test that the persist key works.
     */
    public function testPersistentParameters()
    {
        Router::reload();
        Router::connect(
            '/:lang/:color/posts/view/*',
            ['controller' => 'posts', 'action' => 'view'],
            ['persist' => ['lang', 'color'],
        ]);
        Router::connect(
            '/:lang/:color/posts/index',
            ['controller' => 'posts', 'action' => 'index'],
            ['persist' => ['lang'],
        ]);
        Router::connect('/:lang/:color/posts/edit/*', ['controller' => 'posts', 'action' => 'edit']);
        Router::connect('/about', ['controller' => 'pages', 'action' => 'view', 'about']);
        Router::parse('/en/red/posts/view/5');

        Router::setRequestInfo([
            ['controller' => 'posts', 'action' => 'view', 'lang' => 'en', 'color' => 'red', 'form' => [], 'url' => [], 'plugin' => null],
            ['base' => '/', 'here' => '/en/red/posts/view/5', 'webroot' => '/', 'passedArgs' => [], 'argSeparator' => ':', 'namedArgs' => []],
        ]);
        $expected = '/en/red/posts/view/6';
        $result = Router::url(['controller' => 'posts', 'action' => 'view', 6]);
        $this->assertEqual($result, $expected);

        $expected = '/en/blue/posts/index';
        $result = Router::url(['controller' => 'posts', 'action' => 'index', 'color' => 'blue']);
        $this->assertEqual($result, $expected);

        $expected = '/posts/edit/6';
        $result = Router::url(['controller' => 'posts', 'action' => 'edit', 6, 'color' => null, 'lang' => null]);
        $this->assertEqual($result, $expected);

        $expected = '/posts';
        $result = Router::url(['controller' => 'posts', 'action' => 'index']);
        $this->assertEqual($result, $expected);

        $expected = '/posts/edit/7';
        $result = Router::url(['controller' => 'posts', 'action' => 'edit', 7]);
        $this->assertEqual($result, $expected);

        $expected = '/about';
        $result = Router::url(['controller' => 'pages', 'action' => 'view', 'about']);
        $this->assertEqual($result, $expected);
    }

    /**
     * testUuidRoutes method.
     */
    public function testUuidRoutes()
    {
        Router::connect(
            '/subjects/add/:category_id',
            ['controller' => 'subjects', 'action' => 'add'],
            ['category_id' => '\w{8}-\w{4}-\w{4}-\w{4}-\w{12}']
        );
        $result = Router::parse('/subjects/add/4795d601-19c8-49a6-930e-06a8b01d17b7');
        $expected = ['pass' => [], 'named' => [], 'category_id' => '4795d601-19c8-49a6-930e-06a8b01d17b7', 'plugin' => null, 'controller' => 'subjects', 'action' => 'add'];
        $this->assertEqual($result, $expected);
    }

    /**
     * testRouteSymmetry method.
     */
    public function testRouteSymmetry()
    {
        Router::connect(
            '/:extra/page/:slug/*',
            ['controller' => 'pages', 'action' => 'view', 'extra' => null],
            ['extra' => '[a-z1-9_]*', 'slug' => '[a-z1-9_]+', 'action' => 'view']
        );

        $result = Router::parse('/some_extra/page/this_is_the_slug');
        $expected = ['pass' => [], 'named' => [], 'plugin' => null, 'controller' => 'pages', 'action' => 'view', 'slug' => 'this_is_the_slug', 'extra' => 'some_extra'];
        $this->assertEqual($result, $expected);

        $result = Router::parse('/page/this_is_the_slug');
        $expected = ['pass' => [], 'named' => [], 'plugin' => null, 'controller' => 'pages', 'action' => 'view', 'slug' => 'this_is_the_slug', 'extra' => null];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect(
            '/:extra/page/:slug/*',
            ['controller' => 'pages', 'action' => 'view', 'extra' => null],
            ['extra' => '[a-z1-9_]*', 'slug' => '[a-z1-9_]+']
        );
        Router::parse('/');

        $result = Router::url(['admin' => null, 'plugin' => null, 'controller' => 'pages', 'action' => 'view', 'slug' => 'this_is_the_slug', 'extra' => null]);
        $expected = '/page/this_is_the_slug';
        $this->assertEqual($result, $expected);

        $result = Router::url(['admin' => null, 'plugin' => null, 'controller' => 'pages', 'action' => 'view', 'slug' => 'this_is_the_slug', 'extra' => 'some_extra']);
        $expected = '/some_extra/page/this_is_the_slug';
        $this->assertEqual($result, $expected);
    }

    /**
     * Test that Routing.prefixes and Routing.admin are used when a Router instance is created
     * or reset.
     */
    public function testRoutingPrefixesSetting()
    {
        $restore = Configure::read('Routing');

        Configure::write('Routing.admin', 'admin');
        Configure::write('Routing.prefixes', ['member', 'super_user']);
        Router::reload();
        $result = Router::prefixes();
        $expected = ['admin', 'member', 'super_user'];
        $this->assertEqual($result, $expected);

        Configure::write('Routing.prefixes', 'member');
        Router::reload();
        $result = Router::prefixes();
        $expected = ['admin', 'member'];
        $this->assertEqual($result, $expected);

        Configure::write('Routing', $restore);
    }

    /**
     * test compatibility with old Routing.admin config setting.
     *
     * @todo Once Routing.admin is removed update these tests.
     */
    public function testAdminRoutingCompatibility()
    {
        Configure::write('Routing.admin', 'admin');

        Router::reload();
        Router::connect('/admin', ['admin' => true, 'controller' => 'users']);
        $result = Router::parse('/admin');

        $expected = ['pass' => [], 'named' => [], 'plugin' => '', 'controller' => 'users', 'action' => 'index', 'admin' => true, 'prefix' => 'admin'];
        $this->assertEqual($result, $expected);

        $result = Router::url(['admin' => true, 'controller' => 'posts', 'action' => 'index', '0', '?' => 'var=test&var2=test2']);
        $expected = '/admin/posts/index/0?var=test&var2=test2';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::parse('/');
        $result = Router::url(['admin' => false, 'controller' => 'posts', 'action' => 'index', '0', '?' => 'var=test&var2=test2']);
        $expected = '/posts/index/0?var=test&var2=test2';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::setRequestInfo([
            ['admin' => true, 'controller' => 'controller', 'action' => 'index', 'form' => [], 'url' => [], 'plugin' => null],
            ['base' => '/', 'here' => '/', 'webroot' => '/base/', 'passedArgs' => [], 'argSeparator' => ':', 'namedArgs' => []],
        ]);

        Router::parse('/');
        $result = Router::url(['admin' => false, 'controller' => 'posts', 'action' => 'index', '0', '?' => 'var=test&var2=test2']);
        $expected = '/posts/index/0?var=test&var2=test2';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'posts', 'action' => 'index', '0', '?' => 'var=test&var2=test2']);
        $expected = '/admin/posts/index/0?var=test&var2=test2';
        $this->assertEqual($result, $expected);

        Router::reload();
        $result = Router::parse('admin/users/view/');
        $expected = ['pass' => [], 'named' => [], 'controller' => 'users', 'action' => 'view', 'plugin' => null, 'prefix' => 'admin', 'admin' => true];
        $this->assertEqual($result, $expected);

        Configure::write('Routing.admin', 'beheer');

        Router::reload();
        Router::setRequestInfo([
            ['beheer' => true, 'controller' => 'posts', 'action' => 'index', 'form' => [], 'url' => [], 'plugin' => null],
            ['base' => '/', 'here' => '/beheer/posts/index', 'webroot' => '/', 'passedArgs' => [], 'argSeparator' => ':', 'namedArgs' => []],
        ]);

        $result = Router::parse('beheer/users/view/');
        $expected = ['pass' => [], 'named' => [], 'controller' => 'users', 'action' => 'view', 'plugin' => null, 'prefix' => 'beheer', 'beheer' => true];
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'posts', 'action' => 'index', '0', '?' => 'var=test&var2=test2']);
        $expected = '/beheer/posts/index/0?var=test&var2=test2';
        $this->assertEqual($result, $expected);
    }

    /**
     * Test prefix routing and plugin combinations.
     */
    public function testPrefixRoutingAndPlugins()
    {
        Configure::write('Routing.prefixes', ['admin']);
        $paths = App::path('plugins');
        App::build([
            'plugins' => [
                TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS,
            ],
        ], true);
        App::objects('plugin', null, false);

        Router::reload();
        Router::setRequestInfo([
            ['admin' => true, 'controller' => 'controller', 'action' => 'action',
                'form' => [], 'url' => [], 'plugin' => null, 'prefix' => 'admin', ],
            ['base' => '/', 'here' => '/', 'webroot' => '/base/', 'passedArgs' => [],
                'argSeparator' => ':', 'namedArgs' => [], ],
        ]);
        Router::parse('/');

        $result = Router::url(['plugin' => 'test_plugin', 'controller' => 'test_plugin', 'action' => 'index']);
        $expected = '/admin/test_plugin';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::parse('/');
        Router::setRequestInfo([
            [
                'plugin' => 'test_plugin', 'controller' => 'show_tickets', 'action' => 'admin_edit',
                'pass' => ['6'], 'prefix' => 'admin', 'admin' => true, 'form' => [],
                'url' => ['url' => 'admin/shows/show_tickets/edit/6'],
            ],
            [
                'plugin' => null, 'controller' => null, 'action' => null, 'base' => '',
                'here' => '/admin/shows/show_tickets/edit/6', 'webroot' => '/',
            ],
        ]);

        $result = Router::url([
            'plugin' => 'test_plugin', 'controller' => 'show_tickets', 'action' => 'edit', 6,
            'admin' => true, 'prefix' => 'admin',
        ]);
        $expected = '/admin/test_plugin/show_tickets/edit/6';
        $this->assertEqual($result, $expected);

        $result = Router::url([
            'plugin' => 'test_plugin', 'controller' => 'show_tickets', 'action' => 'index', 'admin' => true,
        ]);
        $expected = '/admin/test_plugin/show_tickets';
        $this->assertEqual($result, $expected);

        App::build(['plugins' => $paths]);
    }

    /**
     * testExtensionParsingSetting method.
     */
    public function testExtensionParsingSetting()
    {
        $router = &Router::getInstance();
        $this->assertFalse($this->router->__parseExtensions);

        $router->parseExtensions();
        $this->assertTrue($this->router->__parseExtensions);
    }

    /**
     * testExtensionParsing method.
     */
    public function testExtensionParsing()
    {
        Router::parseExtensions();

        $result = Router::parse('/posts.rss');
        $expected = ['plugin' => null, 'controller' => 'posts', 'action' => 'index', 'url' => ['ext' => 'rss'], 'pass' => [], 'named' => []];
        $this->assertEqual($result, $expected);

        $result = Router::parse('/posts/view/1.rss');
        $expected = ['plugin' => null, 'controller' => 'posts', 'action' => 'view', 'pass' => ['1'], 'named' => [], 'url' => ['ext' => 'rss'], 'named' => []];
        $this->assertEqual($result, $expected);

        $result = Router::parse('/posts/view/1.rss?query=test');
        $this->assertEqual($result, $expected);

        $result = Router::parse('/posts/view/1.atom');
        $expected['url'] = ['ext' => 'atom'];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::parseExtensions('rss', 'xml');

        $result = Router::parse('/posts.xml');
        $expected = ['plugin' => null, 'controller' => 'posts', 'action' => 'index', 'url' => ['ext' => 'xml'], 'pass' => [], 'named' => []];
        $this->assertEqual($result, $expected);

        $result = Router::parse('/posts.atom?hello=goodbye');
        $expected = ['plugin' => null, 'controller' => 'posts.atom', 'action' => 'index', 'pass' => [], 'named' => [], 'url' => ['ext' => 'html']];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::parseExtensions();
        $result = $this->router->__parseExtension('/posts.atom');
        $expected = ['ext' => 'atom', 'url' => '/posts'];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/controller/action', ['controller' => 'controller', 'action' => 'action', 'url' => ['ext' => 'rss']]);
        $result = Router::parse('/controller/action');
        $expected = ['controller' => 'controller', 'action' => 'action', 'plugin' => null, 'url' => ['ext' => 'rss'], 'named' => [], 'pass' => []];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::parseExtensions('rss');
        Router::connect('/controller/action', ['controller' => 'controller', 'action' => 'action', 'url' => ['ext' => 'rss']]);
        $result = Router::parse('/controller/action');
        $expected = ['controller' => 'controller', 'action' => 'action', 'plugin' => null, 'url' => ['ext' => 'rss'], 'named' => [], 'pass' => []];
        $this->assertEqual($result, $expected);
    }

    /**
     * testQuerystringGeneration method.
     */
    public function testQuerystringGeneration()
    {
        $result = Router::url(['controller' => 'posts', 'action' => 'index', '0', '?' => 'var=test&var2=test2']);
        $expected = '/posts/index/0?var=test&var2=test2';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'posts', 'action' => 'index', '0', '?' => ['var' => 'test', 'var2' => 'test2']]);
        $this->assertEqual($result, $expected);

        $expected .= '&more=test+data';
        $result = Router::url(['controller' => 'posts', 'action' => 'index', '0', '?' => ['var' => 'test', 'var2' => 'test2', 'more' => 'test data']]);
        $this->assertEqual($result, $expected);

        // Test bug #4614
        $restore = ini_get('arg_separator.output');
        ini_set('arg_separator.output', '&amp;');
        $result = Router::url(['controller' => 'posts', 'action' => 'index', '0', '?' => ['var' => 'test', 'var2' => 'test2', 'more' => 'test data']]);
        $this->assertEqual($result, $expected);
        ini_set('arg_separator.output', $restore);

        $result = Router::url(['controller' => 'posts', 'action' => 'index', '0', '?' => ['var' => 'test', 'var2' => 'test2']], ['escape' => true]);
        $expected = '/posts/index/0?var=test&amp;var2=test2';
        $this->assertEqual($result, $expected);
    }

    /**
     * testConnectNamed method.
     */
    public function testConnectNamed()
    {
        $named = Router::connectNamed(false, ['default' => true]);
        $this->assertFalse($named['greedy']);
        $this->assertEqual(array_keys($named['rules']), $named['default']);

        Router::reload();
        Router::connect('/foo/*', ['controller' => 'bar', 'action' => 'fubar']);
        Router::connectNamed([], ['argSeparator' => '=']);
        $result = Router::parse('/foo/param1=value1/param2=value2');
        $expected = ['pass' => [], 'named' => ['param1' => 'value1', 'param2' => 'value2'], 'controller' => 'bar', 'action' => 'fubar', 'plugin' => null];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/controller/action/*', ['controller' => 'controller', 'action' => 'action'], ['named' => ['param1' => 'value[\d]']]);
        Router::connectNamed([], ['greedy' => false, 'argSeparator' => '=']);
        $result = Router::parse('/controller/action/param1=value1/param2=value2');
        $expected = ['pass' => ['param2=value2'], 'named' => ['param1' => 'value1'], 'controller' => 'controller', 'action' => 'action', 'plugin' => null];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/:controller/:action/*');
        Router::connectNamed(['page'], ['default' => false, 'greedy' => false]);
        $result = Router::parse('/categories/index?limit=5');
        $this->assertTrue(empty($result['named']));
    }

    /**
     * testNamedArgsUrlGeneration method.
     */
    public function testNamedArgsUrlGeneration()
    {
        $result = Router::url(['controller' => 'posts', 'action' => 'index', 'published' => 1, 'deleted' => 1]);
        $expected = '/posts/index/published:1/deleted:1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'posts', 'action' => 'index', 'published' => 0, 'deleted' => 0]);
        $expected = '/posts/index/published:0/deleted:0';
        $this->assertEqual($result, $expected);

        Router::reload();
        extract(Router::getNamedExpressions());
        Router::connectNamed(['file' => '[\w\.\-]+\.(html|png)']);
        Router::connect('/', ['controller' => 'graphs', 'action' => 'index']);
        Router::connect('/:id/*', ['controller' => 'graphs', 'action' => 'view'], ['id' => $ID]);

        $result = Router::url(['controller' => 'graphs', 'action' => 'view', 'id' => 12, 'file' => 'asdf.png']);
        $expected = '/12/file:asdf.png';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'graphs', 'action' => 'view', 12, 'file' => 'asdf.foo']);
        $expected = '/graphs/view/12/file:asdf.foo';
        $this->assertEqual($result, $expected);

        Configure::write('Routing.admin', 'admin');

        Router::reload();
        Router::setRequestInfo([
            ['admin' => true, 'controller' => 'controller', 'action' => 'index', 'form' => [], 'url' => [], 'plugin' => null],
            ['base' => '/', 'here' => '/', 'webroot' => '/base/', 'passedArgs' => [], 'argSeparator' => ':', 'namedArgs' => []],
        ]);
        Router::parse('/');

        $result = Router::url(['page' => 1, 0 => null, 'sort' => 'controller', 'direction' => 'asc', 'order' => null]);
        $expected = '/admin/controller/index/page:1/sort:controller/direction:asc';
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::setRequestInfo([
            ['admin' => true, 'controller' => 'controller', 'action' => 'index', 'form' => [], 'url' => [], 'plugin' => null],
            ['base' => '/', 'here' => '/', 'webroot' => '/base/', 'passedArgs' => ['type' => 'whatever'], 'argSeparator' => ':', 'namedArgs' => ['type' => 'whatever']],
        ]);

        $result = Router::parse('/admin/controller/index/type:whatever');
        $result = Router::url(['type' => 'new']);
        $expected = '/admin/controller/index/type:new';
        $this->assertEqual($result, $expected);
    }

    /**
     * testNamedArgsUrlParsing method.
     */
    public function testNamedArgsUrlParsing()
    {
        $Router = &Router::getInstance();
        Router::reload();
        $result = Router::parse('/controller/action/param1:value1:1/param2:value2:3/param:value');
        $expected = ['pass' => [], 'named' => ['param1' => 'value1:1', 'param2' => 'value2:3', 'param' => 'value'], 'controller' => 'controller', 'action' => 'action', 'plugin' => null];
        $this->assertEqual($result, $expected);

        Router::reload();
        $result = Router::connectNamed(false);
        $this->assertEqual(array_keys($result['rules']), []);
        $this->assertFalse($result['greedy']);
        $result = Router::parse('/controller/action/param1:value1:1/param2:value2:3/param:value');
        $expected = ['pass' => ['param1:value1:1', 'param2:value2:3', 'param:value'], 'named' => [], 'controller' => 'controller', 'action' => 'action', 'plugin' => null];
        $this->assertEqual($result, $expected);

        Router::reload();
        $result = Router::connectNamed(true);
        $this->assertEqual(array_keys($result['rules']), $Router->named['default']);
        $this->assertTrue($result['greedy']);
        Router::reload();
        Router::connectNamed(['param1' => 'not-matching']);
        $result = Router::parse('/controller/action/param1:value1:1/param2:value2:3/param:value');
        $expected = ['pass' => ['param1:value1:1'], 'named' => ['param2' => 'value2:3', 'param' => 'value'], 'controller' => 'controller', 'action' => 'action', 'plugin' => null];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/foo/:action/*', ['controller' => 'bar'], ['named' => ['param1' => ['action' => 'index']], 'greedy' => true]);
        $result = Router::parse('/foo/index/param1:value1:1/param2:value2:3/param:value');
        $expected = ['pass' => [], 'named' => ['param1' => 'value1:1', 'param2' => 'value2:3', 'param' => 'value'], 'controller' => 'bar', 'action' => 'index', 'plugin' => null];
        $this->assertEqual($result, $expected);

        $result = Router::parse('/foo/view/param1:value1:1/param2:value2:3/param:value');
        $expected = ['pass' => ['param1:value1:1'], 'named' => ['param2' => 'value2:3', 'param' => 'value'], 'controller' => 'bar', 'action' => 'view', 'plugin' => null];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connectNamed(['param1' => '[\d]', 'param2' => '[a-z]', 'param3' => '[\d]']);
        $result = Router::parse('/controller/action/param1:1/param2:2/param3:3');
        $expected = ['pass' => ['param2:2'], 'named' => ['param1' => '1', 'param3' => '3'], 'controller' => 'controller', 'action' => 'action', 'plugin' => null];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connectNamed(['param1' => '[\d]', 'param2' => true, 'param3' => '[\d]']);
        $result = Router::parse('/controller/action/param1:1/param2:2/param3:3');
        $expected = ['pass' => [], 'named' => ['param1' => '1', 'param2' => '2', 'param3' => '3'], 'controller' => 'controller', 'action' => 'action', 'plugin' => null];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connectNamed(['param1' => 'value[\d]+:[\d]+'], ['greedy' => false]);
        $result = Router::parse('/controller/action/param1:value1:1/param2:value2:3/param3:value');
        $expected = ['pass' => ['param2:value2:3', 'param3:value'], 'named' => ['param1' => 'value1:1'], 'controller' => 'controller', 'action' => 'action', 'plugin' => null];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/foo/*', ['controller' => 'bar', 'action' => 'fubar'], ['named' => ['param1' => 'value[\d]:[\d]']]);
        Router::connectNamed([], ['greedy' => false]);
        $result = Router::parse('/foo/param1:value1:1/param2:value2:3/param3:value');
        $expected = ['pass' => ['param2:value2:3', 'param3:value'], 'named' => ['param1' => 'value1:1'], 'controller' => 'bar', 'action' => 'fubar', 'plugin' => null];
        $this->assertEqual($result, $expected);
    }

    /**
     * test url generation with legacy (1.2) style prefix routes.
     *
     * @todo Remove tests related to legacy style routes.
     *
     * @see testUrlGenerationWithAutoPrefixes
     */
    public function testUrlGenerationWithLegacyPrefixes()
    {
        Router::reload();
        Router::connect('/protected/:controller/:action/*', [
            'prefix' => 'protected',
            'protected' => true,
        ]);
        Router::parse('/');

        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'images', 'action' => 'index', 'pass' => [], 'prefix' => null, 'admin' => false, 'form' => [], 'url' => ['url' => 'images/index']],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '', 'here' => '/images/index', 'webroot' => '/'],
        ]);

        $result = Router::url(['protected' => true]);
        $expected = '/protected/images/index';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'images', 'action' => 'add']);
        $expected = '/images/add';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'images', 'action' => 'add', 'protected' => true]);
        $expected = '/protected/images/add';
        $this->assertEqual($result, $expected);

        $result = Router::url(['action' => 'edit', 1]);
        $expected = '/images/edit/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['action' => 'edit', 1, 'protected' => true]);
        $expected = '/protected/images/edit/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['action' => 'protected_edit', 1, 'protected' => true]);
        $expected = '/protected/images/edit/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['action' => 'edit', 1, 'protected' => true]);
        $expected = '/protected/images/edit/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'others', 'action' => 'edit', 1]);
        $expected = '/others/edit/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'others', 'action' => 'edit', 1, 'protected' => true]);
        $expected = '/protected/others/edit/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'others', 'action' => 'edit', 1, 'protected' => true, 'page' => 1]);
        $expected = '/protected/others/edit/1/page:1';
        $this->assertEqual($result, $expected);

        Router::connectNamed(['random']);
        $result = Router::url(['controller' => 'others', 'action' => 'edit', 1, 'protected' => true, 'random' => 'my-value']);
        $expected = '/protected/others/edit/1/random:my-value';
        $this->assertEqual($result, $expected);
    }

    /**
     * test newer style automatically generated prefix routes.
     */
    public function testUrlGenerationWithAutoPrefixes()
    {
        Configure::write('Routing.prefixes', ['protected']);
        Router::reload();
        Router::parse('/');

        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'images', 'action' => 'index', 'pass' => [], 'prefix' => null, 'protected' => false, 'form' => [], 'url' => ['url' => 'images/index']],
            ['base' => '', 'here' => '/images/index', 'webroot' => '/'],
        ]);

        $result = Router::url(['controller' => 'images', 'action' => 'add']);
        $expected = '/images/add';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'images', 'action' => 'add', 'protected' => true]);
        $expected = '/protected/images/add';
        $this->assertEqual($result, $expected);

        $result = Router::url(['action' => 'edit', 1]);
        $expected = '/images/edit/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['action' => 'edit', 1, 'protected' => true]);
        $expected = '/protected/images/edit/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['action' => 'protected_edit', 1, 'protected' => true]);
        $expected = '/protected/images/edit/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['action' => 'protectededit', 1, 'protected' => true]);
        $expected = '/protected/images/protectededit/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['action' => 'edit', 1, 'protected' => true]);
        $expected = '/protected/images/edit/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'others', 'action' => 'edit', 1]);
        $expected = '/others/edit/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'others', 'action' => 'edit', 1, 'protected' => true]);
        $expected = '/protected/others/edit/1';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'others', 'action' => 'edit', 1, 'protected' => true, 'page' => 1]);
        $expected = '/protected/others/edit/1/page:1';
        $this->assertEqual($result, $expected);

        Router::connectNamed(['random']);
        $result = Router::url(['controller' => 'others', 'action' => 'edit', 1, 'protected' => true, 'random' => 'my-value']);
        $expected = '/protected/others/edit/1/random:my-value';
        $this->assertEqual($result, $expected);
    }

    /**
     * test that auto-generated prefix routes persist.
     */
    public function testAutoPrefixRoutePersistence()
    {
        Configure::write('Routing.prefixes', ['protected']);
        Router::reload();
        Router::parse('/');

        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'images', 'action' => 'index', 'pass' => [], 'prefix' => 'protected', 'protected' => true, 'form' => [], 'url' => ['url' => 'protected/images/index']],
            ['base' => '', 'here' => '/protected/images/index', 'webroot' => '/'],
        ]);

        $result = Router::url(['controller' => 'images', 'action' => 'add']);
        $expected = '/protected/images/add';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'images', 'action' => 'add', 'protected' => false]);
        $expected = '/images/add';
        $this->assertEqual($result, $expected);
    }

    /**
     * test that setting a prefix override the current one.
     */
    public function testPrefixOverride()
    {
        Configure::write('Routing.prefixes', ['protected', 'admin']);
        Router::reload();
        Router::parse('/');

        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'images', 'action' => 'index', 'pass' => [], 'prefix' => 'protected', 'protected' => true, 'form' => [], 'url' => ['url' => 'protected/images/index']],
            ['base' => '', 'here' => '/protected/images/index', 'webroot' => '/'],
        ]);

        $result = Router::url(['controller' => 'images', 'action' => 'add', 'admin' => true]);
        $expected = '/admin/images/add';
        $this->assertEqual($result, $expected);

        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'images', 'action' => 'index', 'pass' => [], 'prefix' => 'admin', 'admin' => true, 'form' => [], 'url' => ['url' => 'admin/images/index']],
            ['base' => '', 'here' => '/admin/images/index', 'webroot' => '/'],
        ]);
        $result = Router::url(['controller' => 'images', 'action' => 'add', 'protected' => true]);
        $expected = '/protected/images/add';
        $this->assertEqual($result, $expected);
    }

    /**
     * testRemoveBase method.
     */
    public function testRemoveBase()
    {
        Router::setRequestInfo([
            ['controller' => 'controller', 'action' => 'index', 'form' => [], 'url' => [], 'bare' => 0, 'plugin' => null],
            ['base' => '/base', 'here' => '/', 'webroot' => '/base/', 'passedArgs' => [], 'argSeparator' => ':', 'namedArgs' => []],
        ]);

        $result = Router::url(['controller' => 'my_controller', 'action' => 'my_action']);
        $expected = '/base/my_controller/my_action';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'my_controller', 'action' => 'my_action', 'base' => false]);
        $expected = '/my_controller/my_action';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'my_controller', 'action' => 'my_action', 'base' => true]);
        $expected = '/base/my_controller/my_action/base:1';
        $this->assertEqual($result, $expected);
    }

    /**
     * testPagesUrlParsing method.
     */
    public function testPagesUrlParsing()
    {
        Router::connect('/', ['controller' => 'pages', 'action' => 'display', 'home']);
        Router::connect('/pages/*', ['controller' => 'pages', 'action' => 'display']);

        $result = Router::parse('/');
        $expected = ['pass' => ['home'], 'named' => [], 'plugin' => null, 'controller' => 'pages', 'action' => 'display'];
        $this->assertEqual($result, $expected);

        $result = Router::parse('/pages/home/');
        $expected = ['pass' => ['home'], 'named' => [], 'plugin' => null, 'controller' => 'pages', 'action' => 'display'];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/', ['controller' => 'pages', 'action' => 'display', 'home']);

        $result = Router::parse('/pages/display/home/parameter:value');
        $expected = ['pass' => ['home'], 'named' => ['parameter' => 'value'], 'plugin' => null, 'controller' => 'pages', 'action' => 'display'];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/', ['controller' => 'pages', 'action' => 'display', 'home']);

        $result = Router::parse('/');
        $expected = ['pass' => ['home'], 'named' => [], 'plugin' => null, 'controller' => 'pages', 'action' => 'display'];
        $this->assertEqual($result, $expected);

        $result = Router::parse('/pages/display/home/event:value');
        $expected = ['pass' => ['home'], 'named' => ['event' => 'value'], 'plugin' => null, 'controller' => 'pages', 'action' => 'display'];
        $this->assertEqual($result, $expected);

        $result = Router::parse('/pages/display/home/event:Val_u2');
        $expected = ['pass' => ['home'], 'named' => ['event' => 'Val_u2'], 'plugin' => null, 'controller' => 'pages', 'action' => 'display'];
        $this->assertEqual($result, $expected);

        $result = Router::parse('/pages/display/home/event:val-ue');
        $expected = ['pass' => ['home'], 'named' => ['event' => 'val-ue'], 'plugin' => null, 'controller' => 'pages', 'action' => 'display'];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/', ['controller' => 'posts', 'action' => 'index']);
        Router::connect('/pages/*', ['controller' => 'pages', 'action' => 'display']);
        $result = Router::parse('/pages/contact/');

        $expected = ['pass' => ['contact'], 'named' => [], 'plugin' => null, 'controller' => 'pages', 'action' => 'display'];
        $this->assertEqual($result, $expected);
    }

    /**
     * test that requests with a trailing dot don't loose the do.
     */
    public function testParsingWithTrailingPeriod()
    {
        Router::reload();
        $result = Router::parse('/posts/view/something.');
        $this->assertEqual($result['pass'][0], 'something.', 'Period was chopped off %s');

        $result = Router::parse('/posts/view/something. . .');
        $this->assertEqual($result['pass'][0], 'something. . .', 'Period was chopped off %s');
    }

    /**
     * test that requests with a trailing dot don't loose the do.
     */
    public function testParsingWithTrailingPeriodAndParseExtensions()
    {
        Router::reload();
        Router::parseExtensions('json');

        $result = Router::parse('/posts/view/something.');
        $this->assertEqual($result['pass'][0], 'something.', 'Period was chopped off %s');

        $result = Router::parse('/posts/view/something. . .');
        $this->assertEqual($result['pass'][0], 'something. . .', 'Period was chopped off %s');
    }

    /**
     * test that patterns work for :action.
     */
    public function testParsingWithPatternOnAction()
    {
        Router::reload();
        Router::connect(
            '/blog/:action/*',
            ['controller' => 'blog_posts'],
            ['action' => 'other|actions']
        );
        $result = Router::parse('/blog/other');
        $expected = [
            'plugin' => null,
            'controller' => 'blog_posts',
            'action' => 'other',
            'pass' => [],
            'named' => [],
        ];
        $this->assertEqual($expected, $result);

        $result = Router::parse('/blog/foobar');
        $expected = [
            'plugin' => null,
            'controller' => 'blog',
            'action' => 'foobar',
            'pass' => [],
            'named' => [],
        ];
        $this->assertEqual($expected, $result);

        $result = Router::url(['controller' => 'blog_posts', 'action' => 'foo']);
        $this->assertEqual('/blog_posts/foo', $result);

        $result = Router::url(['controller' => 'blog_posts', 'action' => 'actions']);
        $this->assertEqual('/blog/actions', $result);
    }

    /**
     * testParsingWithPrefixes method.
     */
    public function testParsingWithPrefixes()
    {
        $adminParams = ['prefix' => 'admin', 'admin' => true];
        Router::connect('/admin/:controller', $adminParams);
        Router::connect('/admin/:controller/:action', $adminParams);
        Router::connect('/admin/:controller/:action/*', $adminParams);

        Router::setRequestInfo([
            ['controller' => 'controller', 'action' => 'index', 'form' => [], 'url' => [], 'plugin' => null],
            ['base' => '/base', 'here' => '/', 'webroot' => '/base/', 'passedArgs' => [], 'argSeparator' => ':', 'namedArgs' => []],
        ]);

        $result = Router::parse('/admin/posts/');
        $expected = ['pass' => [], 'named' => [], 'prefix' => 'admin', 'plugin' => null, 'controller' => 'posts', 'action' => 'index', 'admin' => true];
        $this->assertEqual($result, $expected);

        $result = Router::parse('/admin/posts');
        $this->assertEqual($result, $expected);

        $result = Router::url(['admin' => true, 'controller' => 'posts']);
        $expected = '/base/admin/posts';
        $this->assertEqual($result, $expected);

        $result = Router::prefixes();
        $expected = ['admin'];
        $this->assertEqual($result, $expected);

        Router::reload();

        $prefixParams = ['prefix' => 'members', 'members' => true];
        Router::connect('/members/:controller', $prefixParams);
        Router::connect('/members/:controller/:action', $prefixParams);
        Router::connect('/members/:controller/:action/*', $prefixParams);

        Router::setRequestInfo([
            ['controller' => 'controller', 'action' => 'index', 'form' => [], 'url' => [], 'plugin' => null],
            ['base' => '/base', 'here' => '/', 'webroot' => '/', 'passedArgs' => [], 'argSeparator' => ':', 'namedArgs' => []],
        ]);

        $result = Router::parse('/members/posts/index');
        $expected = ['pass' => [], 'named' => [], 'prefix' => 'members', 'plugin' => null, 'controller' => 'posts', 'action' => 'index', 'members' => true];
        $this->assertEqual($result, $expected);

        $result = Router::url(['members' => true, 'controller' => 'posts', 'action' => 'index', 'page' => 2]);
        $expected = '/base/members/posts/index/page:2';
        $this->assertEqual($result, $expected);

        $result = Router::url(['members' => true, 'controller' => 'users', 'action' => 'add']);
        $expected = '/base/members/users/add';
        $this->assertEqual($result, $expected);

        $result = Router::parse('/posts/index');
        $expected = ['pass' => [], 'named' => [], 'plugin' => null, 'controller' => 'posts', 'action' => 'index'];
        $this->assertEqual($result, $expected);
    }

    /**
     * Tests URL generation with flags and prefixes in and out of context.
     */
    public function testUrlWritingWithPrefixes()
    {
        Router::connect('/company/:controller/:action/*', ['prefix' => 'company', 'company' => true]);
        Router::connect('/login', ['controller' => 'users', 'action' => 'login']);

        $result = Router::url(['controller' => 'users', 'action' => 'login', 'company' => true]);
        $expected = '/company/users/login';
        $this->assertEqual($result, $expected);

        Router::setRequestInfo([
            ['controller' => 'users', 'action' => 'login', 'company' => true, 'form' => [], 'url' => [], 'plugin' => null],
            ['base' => '/', 'here' => '/', 'webroot' => '/base/'],
        ]);

        $result = Router::url(['controller' => 'users', 'action' => 'login', 'company' => false]);
        $expected = '/login';
        $this->assertEqual($result, $expected);
    }

    /**
     * test url generation with prefixes and custom routes.
     */
    public function testUrlWritingWithPrefixesAndCustomRoutes()
    {
        Router::connect(
            '/admin/login',
            ['controller' => 'users', 'action' => 'login', 'prefix' => 'admin', 'admin' => true]
        );
        Router::setRequestInfo([
            ['controller' => 'posts', 'action' => 'index', 'admin' => true, 'prefix' => 'admin',
                'form' => [], 'url' => [], 'plugin' => null,
            ],
            ['base' => '/', 'here' => '/', 'webroot' => '/'],
        ]);
        $result = Router::url(['controller' => 'users', 'action' => 'login', 'admin' => true]);
        $this->assertEqual($result, '/admin/login');

        $result = Router::url(['controller' => 'users', 'action' => 'login']);
        $this->assertEqual($result, '/admin/login');

        $result = Router::url(['controller' => 'users', 'action' => 'admin_login']);
        $this->assertEqual($result, '/admin/login');
    }

    /**
     * testPassedArgsOrder method.
     */
    public function testPassedArgsOrder()
    {
        Router::connect('/test-passed/*', ['controller' => 'pages', 'action' => 'display', 'home']);
        Router::connect('/test2/*', ['controller' => 'pages', 'action' => 'display', 2]);
        Router::connect('/test/*', ['controller' => 'pages', 'action' => 'display', 1]);
        Router::parse('/');

        $result = Router::url(['controller' => 'pages', 'action' => 'display', 1, 'whatever']);
        $expected = '/test/whatever';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'pages', 'action' => 'display', 2, 'whatever']);
        $expected = '/test2/whatever';
        $this->assertEqual($result, $expected);

        $result = Router::url(['controller' => 'pages', 'action' => 'display', 'home', 'whatever']);
        $expected = '/test-passed/whatever';
        $this->assertEqual($result, $expected);

        Configure::write('Routing.prefixes', ['admin']);
        Router::reload();

        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'images', 'action' => 'index', 'pass' => [], 'named' => [], 'prefix' => 'protected', 'protected' => true,  'form' => [], 'url' => ['url' => 'protected/images/index']],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '', 'here' => '/protected/images/index', 'webroot' => '/'],
        ]);

        Router::connect('/protected/:controller/:action/*', [
            'controller' => 'users',
            'action' => 'index',
            'prefix' => 'protected',
        ]);

        Router::parse('/');
        $result = Router::url(['controller' => 'images', 'action' => 'add']);
        $expected = '/protected/images/add';
        $this->assertEqual($result, $expected);

        $result = Router::prefixes();
        $expected = ['admin', 'protected'];
        $this->assertEqual($result, $expected);
    }

    /**
     * testRegexRouteMatching method.
     */
    public function testRegexRouteMatching()
    {
        Router::connect('/:locale/:controller/:action/*', [], ['locale' => 'dan|eng']);

        $result = Router::parse('/test/test_action');
        $expected = ['pass' => [], 'named' => [], 'controller' => 'test', 'action' => 'test_action', 'plugin' => null];
        $this->assertEqual($result, $expected);

        $result = Router::parse('/eng/test/test_action');
        $expected = ['pass' => [], 'named' => [], 'locale' => 'eng', 'controller' => 'test', 'action' => 'test_action', 'plugin' => null];
        $this->assertEqual($result, $expected);

        $result = Router::parse('/badness/test/test_action');
        $expected = ['pass' => ['test_action'], 'named' => [], 'controller' => 'badness', 'action' => 'test', 'plugin' => null];
        $this->assertEqual($result, $expected);

        Router::reload();
        Router::connect('/:locale/:controller/:action/*', [], ['locale' => 'dan|eng']);

        Router::setRequestInfo([
            ['plugin' => null, 'controller' => 'test', 'action' => 'index', 'pass' => [], 'form' => [], 'url' => ['url' => 'test/test_action']],
            ['plugin' => null, 'controller' => null, 'action' => null, 'base' => '', 'here' => '/test/test_action', 'webroot' => '/'],
        ]);

        $result = Router::url(['action' => 'test_another_action']);
        $expected = '/test/test_another_action';
        $this->assertEqual($result, $expected);

        $result = Router::url(['action' => 'test_another_action', 'locale' => 'eng']);
        $expected = '/eng/test/test_another_action';
        $this->assertEqual($result, $expected);

        $result = Router::url(['action' => 'test_another_action', 'locale' => 'badness']);
        $expected = '/test/test_another_action/locale:badness';
        $this->assertEqual($result, $expected);
    }

    /**
     * testStripPlugin.
     */
    public function testStripPlugin()
    {
        $pluginName = 'forums';
        $url = 'example.com/'.$pluginName.'/';
        $expected = 'example.com';

        $this->assertEqual(Router::stripPlugin($url, $pluginName), $expected);
        $this->assertEqual(Router::stripPlugin($url), $url);
        $this->assertEqual(Router::stripPlugin($url, null), $url);
    }

    /**
     * testCurentRoute.
     *
     * This test needs some improvement and actual requestAction() usage
     */
    public function testCurrentRoute()
    {
        $url = ['controller' => 'pages', 'action' => 'display', 'government'];
        Router::connect('/government', $url);
        Router::parse('/government');
        $route = &Router::currentRoute();
        $this->assertEqual(array_merge($url, ['plugin' => null]), $route->defaults);
    }

    /**
     * testRequestRoute.
     */
    public function testRequestRoute()
    {
        $url = ['controller' => 'products', 'action' => 'display', 5];
        Router::connect('/government', $url);
        Router::parse('/government');
        $route = &Router::requestRoute();
        $this->assertEqual(array_merge($url, ['plugin' => null]), $route->defaults);

        // test that the first route is matched
        $newUrl = ['controller' => 'products', 'action' => 'display', 6];
        Router::connect('/government', $url);
        Router::parse('/government');
        $route = &Router::requestRoute();
        $this->assertEqual(array_merge($url, ['plugin' => null]), $route->defaults);

        // test that an unmatched route does not change the current route
        $newUrl = ['controller' => 'products', 'action' => 'display', 6];
        Router::connect('/actor', $url);
        Router::parse('/government');
        $route = &Router::requestRoute();
        $this->assertEqual(array_merge($url, ['plugin' => null]), $route->defaults);
    }

    /**
     * testGetParams.
     */
    public function testGetParams()
    {
        $paths = ['base' => '/', 'here' => '/products/display/5', 'webroot' => '/webroot'];
        $params = ['param1' => '1', 'param2' => '2'];
        Router::setRequestInfo([$params, $paths]);
        $expected = [
            'plugin' => null, 'controller' => false, 'action' => false,
            'param1' => '1', 'param2' => '2',
        ];
        $this->assertEqual(Router::getparams(), $expected);
        $this->assertEqual(Router::getparam('controller'), false);
        $this->assertEqual(Router::getparam('param1'), '1');
        $this->assertEqual(Router::getparam('param2'), '2');

        Router::reload();

        $params = ['controller' => 'pages', 'action' => 'display'];
        Router::setRequestInfo([$params, $paths]);
        $expected = ['plugin' => null, 'controller' => 'pages', 'action' => 'display'];
        $this->assertEqual(Router::getparams(), $expected);
        $this->assertEqual(Router::getparams(true), $expected);
    }

    /**
     * test that connectDefaults() can disable default route connection.
     */
    public function testDefaultsMethod()
    {
        Router::defaults(false);
        Router::connect('/test/*', ['controller' => 'pages', 'action' => 'display', 2]);
        $result = Router::parse('/posts/edit/5');
        $this->assertFalse(isset($result['controller']));
        $this->assertFalse(isset($result['action']));
    }

    /**
     * test that the required default routes are connected.
     */
    public function testConnectDefaultRoutes()
    {
        App::build([
            'plugins' => [
                TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS,
            ],
        ], true);
        App::objects('plugin', null, false);
        Router::reload();

        $result = Router::url(['plugin' => 'plugin_js', 'controller' => 'js_file', 'action' => 'index']);
        $this->assertEqual($result, '/plugin_js/js_file');

        $result = Router::parse('/plugin_js/js_file');
        $expected = [
            'plugin' => 'plugin_js', 'controller' => 'js_file', 'action' => 'index',
            'named' => [], 'pass' => [],
        ];
        $this->assertEqual($result, $expected);

        $result = Router::url(['plugin' => 'test_plugin', 'controller' => 'test_plugin', 'action' => 'index']);
        $this->assertEqual($result, '/test_plugin');

        $result = Router::parse('/test_plugin');
        $expected = [
            'plugin' => 'test_plugin', 'controller' => 'test_plugin', 'action' => 'index',
            'named' => [], 'pass' => [],
        ];

        $this->assertEqual($result, $expected, 'Plugin shortcut route broken. %s');
    }

    /**
     * test using a custom route class for route connection.
     */
    public function testUsingCustomRouteClass()
    {
        Mock::generate('CakeRoute', 'MockConnectedRoute');
        $routes = Router::connect(
            '/:slug',
            ['controller' => 'posts', 'action' => 'view'],
            ['routeClass' => 'MockConnectedRoute', 'slug' => '[a-z_-]+']
        );
        $this->assertTrue(is_a($routes[0], 'MockConnectedRoute'), 'Incorrect class used. %s');
        $expected = ['controller' => 'posts', 'action' => 'view', 'slug' => 'test'];
        $routes[0]->setReturnValue('parse', $expected);
        $result = Router::parse('/test');
        $this->assertEqual($result, $expected);
    }

    /**
     * test reversing parameter arrays back into strings.
     */
    public function testRouterReverse()
    {
        $params = [
            'controller' => 'posts',
            'action' => 'view',
            'pass' => [1],
            'named' => [],
            'url' => [],
            'autoRender' => 1,
            'bare' => 1,
            'return' => 1,
            'requested' => 1,
        ];
        $result = Router::reverse($params);
        $this->assertEqual($result, '/posts/view/1');

        $params = [
            'controller' => 'posts',
            'action' => 'index',
            'pass' => [1],
            'named' => ['page' => 1, 'sort' => 'Article.title', 'direction' => 'desc'],
            'url' => [],
        ];
        $result = Router::reverse($params);
        $this->assertEqual($result, '/posts/index/1/page:1/sort:Article.title/direction:desc');

        Router::connect('/:lang/:controller/:action/*', [], ['lang' => '[a-z]{3}']);
        $params = [
            'lang' => 'eng',
            'controller' => 'posts',
            'action' => 'view',
            'pass' => [1],
            'named' => [],
            'url' => ['url' => 'eng/posts/view/1'],
        ];
        $result = Router::reverse($params);
        $this->assertEqual($result, '/eng/posts/view/1');

        $params = [
            'lang' => 'eng',
            'controller' => 'posts',
            'action' => 'view',
            'pass' => [1],
            'named' => [],
            'url' => ['url' => 'eng/posts/view/1', 'foo' => 'bar', 'baz' => 'quu'],
            'paging' => [],
            'models' => [],
        ];
        $result = Router::reverse($params);
        $this->assertEqual($result, '/eng/posts/view/1?foo=bar&baz=quu');

        $params = [
            'lang' => 'eng',
            'controller' => 'posts',
            'action' => 'view',
            'pass' => [1],
            'named' => [],
        ];
        $result = Router::reverse($params);
        $this->assertEqual($result, '/eng/posts/view/1');
    }
}

/**
 * Test case for CakeRoute.
 *
 **/
class CakeRouteTestCase extends CakeTestCase
{
    /**
     * startTest method.
     */
    public function startTest()
    {
        $this->_routing = Configure::read('Routing');
        Configure::write('Routing', ['admin' => null, 'prefixes' => []]);
        Router::reload();
    }

    /**
     * end the test and reset the environment.
     *
     **/
    public function endTest()
    {
        Configure::write('Routing', $this->_routing);
    }

    /**
     * Test the construction of a CakeRoute.
     *
     **/
    public function testConstruction()
    {
        $route = new CakeRoute('/:controller/:action/:id', [], ['id' => '[0-9]+']);

        $this->assertEqual($route->template, '/:controller/:action/:id');
        $this->assertEqual($route->defaults, []);
        $this->assertEqual($route->options, ['id' => '[0-9]+']);
        $this->assertFalse($route->compiled());
    }

    /**
     * test Route compiling.
     *
     **/
    public function testBasicRouteCompiling()
    {
        $route = new CakeRoute('/', ['controller' => 'pages', 'action' => 'display', 'home']);
        $result = $route->compile();
        $expected = '#^/*$#';
        $this->assertEqual($result, $expected);
        $this->assertEqual($route->keys, []);

        $route = new CakeRoute('/:controller/:action', ['controller' => 'posts']);
        $result = $route->compile();

        $this->assertPattern($result, '/posts/edit');
        $this->assertPattern($result, '/posts/super_delete');
        $this->assertNoPattern($result, '/posts');
        $this->assertNoPattern($result, '/posts/super_delete/1');

        $route = new CakeRoute('/posts/foo:id', ['controller' => 'posts', 'action' => 'view']);
        $result = $route->compile();

        $this->assertPattern($result, '/posts/foo:1');
        $this->assertPattern($result, '/posts/foo:param');
        $this->assertNoPattern($result, '/posts');
        $this->assertNoPattern($result, '/posts/');

        $this->assertEqual($route->keys, ['id']);

        $route = new CakeRoute('/:plugin/:controller/:action/*', ['plugin' => 'test_plugin', 'action' => 'index']);
        $result = $route->compile();
        $this->assertPattern($result, '/test_plugin/posts/index');
        $this->assertPattern($result, '/test_plugin/posts/edit/5');
        $this->assertPattern($result, '/test_plugin/posts/edit/5/name:value/nick:name');
    }

    /**
     * test route names with - in them.
     */
    public function testHyphenNames()
    {
        $route = new CakeRoute('/articles/:date-from/:date-to', [
            'controller' => 'articles', 'action' => 'index',
        ]);
        $expected = [
            'controller' => 'articles',
            'action' => 'index',
            'date-from' => '2009-07-31',
            'date-to' => '2010-07-31',
            'named' => [],
            'pass' => [],
        ];
        $result = $route->parse('/articles/2009-07-31/2010-07-31');
        $this->assertEqual($result, $expected);
    }

    /**
     * test that route parameters that overlap don't cause errors.
     */
    public function testRouteParameterOverlap()
    {
        $route = new CakeRoute('/invoices/add/:idd/:id', ['controller' => 'invoices', 'action' => 'add']);
        $result = $route->compile();
        $this->assertPattern($result, '/invoices/add/1/3');

        $route = new CakeRoute('/invoices/add/:id/:idd', ['controller' => 'invoices', 'action' => 'add']);
        $result = $route->compile();
        $this->assertPattern($result, '/invoices/add/1/3');
    }

    /**
     * test compiling routes with keys that have patterns.
     *
     **/
    public function testRouteCompilingWithParamPatterns()
    {
        extract(Router::getNamedExpressions());

        $route = new CakeRoute(
            '/:controller/:action/:id',
            [],
            ['id' => $ID]
        );
        $result = $route->compile();
        $this->assertPattern($result, '/posts/edit/1');
        $this->assertPattern($result, '/posts/view/518098');
        $this->assertNoPattern($result, '/posts/edit/name-of-post');
        $this->assertNoPattern($result, '/posts/edit/4/other:param');
        $this->assertEqual($route->keys, ['controller', 'action', 'id']);

        $route = new CakeRoute(
            '/:lang/:controller/:action/:id',
            ['controller' => 'testing4'],
            ['id' => $ID, 'lang' => '[a-z]{3}']
        );
        $result = $route->compile();
        $this->assertPattern($result, '/eng/posts/edit/1');
        $this->assertPattern($result, '/cze/articles/view/1');
        $this->assertNoPattern($result, '/language/articles/view/2');
        $this->assertNoPattern($result, '/eng/articles/view/name-of-article');
        $this->assertEqual($route->keys, ['lang', 'controller', 'action', 'id']);

        foreach ([':', '@', ';', '$', '-'] as $delim) {
            $route = new CakeRoute('/posts/:id'.$delim.':title');
            $result = $route->compile();

            $this->assertPattern($result, '/posts/1'.$delim.'name-of-article');
            $this->assertPattern($result, '/posts/13244'.$delim.'name-of_Article[]');
            $this->assertNoPattern($result, '/posts/11!nameofarticle');
            $this->assertNoPattern($result, '/posts/11');

            $this->assertEqual($route->keys, ['id', 'title']);
        }

        $route = new CakeRoute(
            '/posts/:id::title/:year',
            ['controller' => 'posts', 'action' => 'view'],
            ['id' => $ID, 'year' => $Year, 'title' => '[a-z-_]+']
        );
        $result = $route->compile();
        $this->assertPattern($result, '/posts/1:name-of-article/2009/');
        $this->assertPattern($result, '/posts/13244:name-of-article/1999');
        $this->assertNoPattern($result, '/posts/hey_now:nameofarticle');
        $this->assertNoPattern($result, '/posts/:nameofarticle/2009');
        $this->assertNoPattern($result, '/posts/:nameofarticle/01');
        $this->assertEqual($route->keys, ['id', 'title', 'year']);

        $route = new CakeRoute(
            '/posts/:url_title-(uuid::id)',
            ['controller' => 'posts', 'action' => 'view'],
            ['pass' => ['id', 'url_title'], 'id' => $ID]
        );
        $result = $route->compile();
        $this->assertPattern($result, '/posts/some_title_for_article-(uuid:12534)/');
        $this->assertPattern($result, '/posts/some_title_for_article-(uuid:12534)');
        $this->assertNoPattern($result, '/posts/');
        $this->assertNoPattern($result, '/posts/nameofarticle');
        $this->assertNoPattern($result, '/posts/nameofarticle-12347');
        $this->assertEqual($route->keys, ['url_title', 'id']);
    }

    /**
     * test more complex route compiling & parsing with mid route greedy stars
     * and optional routing parameters.
     */
    public function testComplexRouteCompilingAndParsing()
    {
        extract(Router::getNamedExpressions());

        $route = new CakeRoute(
            '/posts/:month/:day/:year/*',
            ['controller' => 'posts', 'action' => 'view'],
            ['year' => $Year, 'month' => $Month, 'day' => $Day]
        );
        $result = $route->compile();
        $this->assertPattern($result, '/posts/08/01/2007/title-of-post');
        $result = $route->parse('/posts/08/01/2007/title-of-post');

        $this->assertEqual(count($result), 8);
        $this->assertEqual($result['controller'], 'posts');
        $this->assertEqual($result['action'], 'view');
        $this->assertEqual($result['year'], '2007');
        $this->assertEqual($result['month'], '08');
        $this->assertEqual($result['day'], '01');

        $route = new CakeRoute(
            '/:extra/page/:slug/*',
            ['controller' => 'pages', 'action' => 'view', 'extra' => null],
            ['extra' => '[a-z1-9_]*', 'slug' => '[a-z1-9_]+', 'action' => 'view']
        );
        $result = $route->compile();

        $this->assertPattern($result, '/some_extra/page/this_is_the_slug');
        $this->assertPattern($result, '/page/this_is_the_slug');
        $this->assertEqual($route->keys, ['extra', 'slug']);
        $this->assertEqual($route->options, ['extra' => '[a-z1-9_]*', 'slug' => '[a-z1-9_]+', 'action' => 'view']);
        $expected = [
            'controller' => 'pages',
            'action' => 'view',
            'extra' => null,
        ];
        $this->assertEqual($route->defaults, $expected);

        $route = new CakeRoute(
            '/:controller/:action/*',
            ['project' => false],
            [
                'controller' => 'source|wiki|commits|tickets|comments|view',
                'action' => 'branches|history|branch|logs|view|start|add|edit|modify',
            ]
        );
        $this->assertFalse($route->parse('/chaw_test/wiki'));

        $result = $route->compile();
        $this->assertNoPattern($result, '/some_project/source');
        $this->assertPattern($result, '/source/view');
        $this->assertPattern($result, '/source/view/other/params');
        $this->assertNoPattern($result, '/chaw_test/wiki');
        $this->assertNoPattern($result, '/source/wierd_action');
    }

    /**
     * test that routes match their pattern.
     *
     **/
    public function testMatchBasic()
    {
        $route = new CakeRoute('/:controller/:action/:id', ['plugin' => null]);
        $result = $route->match(['controller' => 'posts', 'action' => 'view', 'plugin' => null]);
        $this->assertFalse($result);

        $result = $route->match(['plugin' => null, 'controller' => 'posts', 'action' => 'view', 0]);
        $this->assertFalse($result);

        $result = $route->match(['plugin' => null, 'controller' => 'posts', 'action' => 'view', 'id' => 1]);
        $this->assertEqual($result, '/posts/view/1');

        $route = new CakeRoute('/', ['controller' => 'pages', 'action' => 'display', 'home']);
        $result = $route->match(['controller' => 'pages', 'action' => 'display', 'home']);
        $this->assertEqual($result, '/');

        $result = $route->match(['controller' => 'pages', 'action' => 'display', 'about']);
        $this->assertFalse($result);

        $route = new CakeRoute('/pages/*', ['controller' => 'pages', 'action' => 'display']);
        $result = $route->match(['controller' => 'pages', 'action' => 'display', 'home']);
        $this->assertEqual($result, '/pages/home');

        $result = $route->match(['controller' => 'pages', 'action' => 'display', 'about']);
        $this->assertEqual($result, '/pages/about');

        $route = new CakeRoute('/blog/:action', ['controller' => 'posts']);
        $result = $route->match(['controller' => 'posts', 'action' => 'view']);
        $this->assertEqual($result, '/blog/view');

        $result = $route->match(['controller' => 'nodes', 'action' => 'view']);
        $this->assertFalse($result);

        $result = $route->match(['controller' => 'posts', 'action' => 'view', 1]);
        $this->assertFalse($result);

        $result = $route->match(['controller' => 'posts', 'action' => 'view', 'id' => 2]);
        $this->assertFalse($result);

        $route = new CakeRoute('/foo/:controller/:action', ['action' => 'index']);
        $result = $route->match(['controller' => 'posts', 'action' => 'view']);
        $this->assertEqual($result, '/foo/posts/view');

        $route = new CakeRoute('/:plugin/:id/*', ['controller' => 'posts', 'action' => 'view']);
        $result = $route->match(['plugin' => 'test', 'controller' => 'posts', 'action' => 'view', 'id' => '1']);
        $this->assertEqual($result, '/test/1/');

        $result = $route->match(['plugin' => 'fo', 'controller' => 'posts', 'action' => 'view', 'id' => '1', '0']);
        $this->assertEqual($result, '/fo/1/0');

        $result = $route->match(['plugin' => 'fo', 'controller' => 'nodes', 'action' => 'view', 'id' => 1]);
        $this->assertFalse($result);

        $result = $route->match(['plugin' => 'fo', 'controller' => 'posts', 'action' => 'edit', 'id' => 1]);
        $this->assertFalse($result);

        $route = new CakeRoute('/admin/subscriptions/:action/*', [
            'controller' => 'subscribe', 'admin' => true, 'prefix' => 'admin',
        ]);

        $url = ['controller' => 'subscribe', 'admin' => true, 'action' => 'edit', 1];
        $result = $route->match($url);
        $expected = '/admin/subscriptions/edit/1';
        $this->assertEqual($result, $expected);

        $route = new CakeRoute('/articles/:date-from/:date-to', [
            'controller' => 'articles', 'action' => 'index',
        ]);
        $url = [
            'controller' => 'articles',
            'action' => 'index',
            'date-from' => '2009-07-31',
            'date-to' => '2010-07-31',
        ];

        $result = $route->match($url);
        $expected = '/articles/2009-07-31/2010-07-31';
        $this->assertEqual($result, $expected);
    }

    /**
     * test match() with greedy routes, named parameters and passed args.
     */
    public function testMatchWithNamedParametersAndPassedArgs()
    {
        Router::connectNamed(true);

        $route = new CakeRoute('/:controller/:action/*', ['plugin' => null]);
        $result = $route->match(['controller' => 'posts', 'action' => 'index', 'plugin' => null, 'page' => 1]);
        $this->assertEqual($result, '/posts/index/page:1');

        $result = $route->match(['controller' => 'posts', 'action' => 'view', 'plugin' => null, 5]);
        $this->assertEqual($result, '/posts/view/5');

        $result = $route->match(['controller' => 'posts', 'action' => 'view', 'plugin' => null, 5, 'page' => 1, 'limit' => 20, 'order' => 'title']);
        $this->assertEqual($result, '/posts/view/5/page:1/limit:20/order:title');

        $route = new CakeRoute('/test2/*', ['controller' => 'pages', 'action' => 'display', 2]);
        $result = $route->match(['controller' => 'pages', 'action' => 'display', 1]);
        $this->assertFalse($result);

        $result = $route->match(['controller' => 'pages', 'action' => 'display', 2, 'something']);
        $this->assertEqual($result, '/test2/something');

        $result = $route->match(['controller' => 'pages', 'action' => 'display', 5, 'something']);
        $this->assertFalse($result);
    }

    /**
     * test that match with patterns works.
     */
    public function testMatchWithPatterns()
    {
        $route = new CakeRoute('/:controller/:action/:id', ['plugin' => null], ['id' => '[0-9]+']);
        $result = $route->match(['controller' => 'posts', 'action' => 'view', 'id' => 'foo']);
        $this->assertFalse($result);

        $result = $route->match(['plugin' => null, 'controller' => 'posts', 'action' => 'view', 'id' => '9']);
        $this->assertEqual($result, '/posts/view/9');

        $result = $route->match(['plugin' => null, 'controller' => 'posts', 'action' => 'view', 'id' => '922']);
        $this->assertEqual($result, '/posts/view/922');

        $result = $route->match(['plugin' => null, 'controller' => 'posts', 'action' => 'view', 'id' => 'a99']);
        $this->assertFalse($result);
    }

    /**
     * Test protocol relative urls.
     */
    public function testProtocolUrls()
    {
        $url = 'svn+ssh://example.com';
        $this->assertEqual($url, Router::url($url));

        $url = '://example.com';
        $this->assertEqual($url, Router::url($url));
    }

    /**
     * test that patterns work for :action.
     */
    public function testPatternOnAction()
    {
        $route = new CakeRoute(
            '/blog/:action/*',
            ['controller' => 'blog_posts'],
            ['action' => 'other|actions']
        );
        $result = $route->match(['controller' => 'blog_posts', 'action' => 'foo']);
        $this->assertFalse($result);

        $result = $route->match(['controller' => 'blog_posts', 'action' => 'actions']);
        $this->assertTrue($result);

        $result = $route->parse('/blog/other');
        $expected = ['controller' => 'blog_posts', 'action' => 'other', 'pass' => [], 'named' => []];
        $this->assertEqual($expected, $result);

        $result = $route->parse('/blog/foobar');
        $this->assertFalse($result);
    }

    /**
     * test persistParams ability to persist parameters from $params and remove params.
     */
    public function testPersistParams()
    {
        $route = new CakeRoute(
            '/:lang/:color/blog/:action',
            ['controller' => 'posts'],
            ['persist' => ['lang', 'color']]
        );
        $url = ['controller' => 'posts', 'action' => 'index'];
        $params = ['lang' => 'en', 'color' => 'blue'];
        $result = $route->persistParams($url, $params);
        $this->assertEqual($result['lang'], 'en');
        $this->assertEqual($result['color'], 'blue');

        $url = ['controller' => 'posts', 'action' => 'index', 'color' => 'red'];
        $params = ['lang' => 'en', 'color' => 'blue'];
        $result = $route->persistParams($url, $params);
        $this->assertEqual($result['lang'], 'en');
        $this->assertEqual($result['color'], 'red');
    }

    /**
     * test the parse method of CakeRoute.
     */
    public function testParse()
    {
        extract(Router::getNamedExpressions());
        $route = new CakeRoute('/:controller/:action/:id', ['controller' => 'testing4', 'id' => null], ['id' => $ID]);
        $route->compile();
        $result = $route->parse('/posts/view/1');
        $this->assertEqual($result['controller'], 'posts');
        $this->assertEqual($result['action'], 'view');
        $this->assertEqual($result['id'], '1');

        $route = new Cakeroute(
            '/admin/:controller',
            ['prefix' => 'admin', 'admin' => 1, 'action' => 'index']
        );
        $route->compile();
        $result = $route->parse('/admin/');
        $this->assertFalse($result);

        $result = $route->parse('/admin/posts');
        $this->assertEqual($result['controller'], 'posts');
        $this->assertEqual($result['action'], 'index');
    }
}

/**
 * test case for PluginShortRoute.
 */
class PluginShortRouteTestCase extends CakeTestCase
{
    /**
     * startTest method.
     */
    public function startTest()
    {
        $this->_routing = Configure::read('Routing');
        Configure::write('Routing', ['admin' => null, 'prefixes' => []]);
        Router::reload();
    }

    /**
     * end the test and reset the environment.
     *
     **/
    public function endTest()
    {
        Configure::write('Routing', $this->_routing);
    }

    /**
     * test the parsing of routes.
     */
    public function testParsing()
    {
        $route = new PluginShortRoute('/:plugin', ['action' => 'index'], ['plugin' => 'foo|bar']);

        $result = $route->parse('/foo');
        $this->assertEqual($result['plugin'], 'foo');
        $this->assertEqual($result['controller'], 'foo');
        $this->assertEqual($result['action'], 'index');

        $result = $route->parse('/wrong');
        $this->assertFalse($result, 'Wrong plugin name matched %s');
    }

    /**
     * test the reverse routing of the plugin shortcut urls.
     */
    public function testMatch()
    {
        $route = new PluginShortRoute('/:plugin', ['action' => 'index'], ['plugin' => 'foo|bar']);

        $result = $route->match(['plugin' => 'foo', 'controller' => 'posts', 'action' => 'index']);
        $this->assertFalse($result, 'plugin controller mismatch was converted. %s');

        $result = $route->match(['plugin' => 'foo', 'controller' => 'foo', 'action' => 'index']);
        $this->assertEqual($result, '/foo');
    }
}
