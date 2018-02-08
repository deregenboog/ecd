<?php
/**
 * ComponentTest file.
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
 * @since         CakePHP(tm) v 1.2.0.5436
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Controller', 'Controller', false);
App::import('Controller', 'Component', false);

if (!class_exists('AppController')) {
    /**
     * AppController class.
     */
    class AppController extends Controller
    {
        /**
         * name property.
         *
         * @var string 'App'
         */
        public $name = 'App';

        /**
         * uses property.
         *
         * @var array
         */
        public $uses = [];

        /**
         * helpers property.
         *
         * @var array
         */
        public $helpers = [];

        /**
         * components property.
         *
         * @var array
         */
        public $components = ['Orange' => ['colour' => 'blood orange']];
    }
} elseif (!defined('APP_CONTROLLER_EXISTS')) {
    define('APP_CONTROLLER_EXISTS', true);
}

/**
 * ParamTestComponent.
 */
class ParamTestComponent extends Object
{
    /**
     * name property.
     *
     * @var string 'ParamTest'
     */
    public $name = 'ParamTest';

    /**
     * components property.
     *
     * @var array
     */
    public $components = ['Banana' => ['config' => 'value']];

    /**
     * initialize method.
     *
     * @param mixed $controller
     * @param mixed $settings
     */
    public function initialize(&$controller, $settings)
    {
        foreach ($settings as $key => $value) {
            if (is_numeric($key)) {
                $this->{$value} = true;
            } else {
                $this->{$key} = $value;
            }
        }
    }
}

/**
 * ComponentTestController class.
 */
class ComponentTestController extends AppController
{
    /**
     * name property.
     *
     * @var string 'ComponentTest'
     */
    public $name = 'ComponentTest';

    /**
     * uses property.
     *
     * @var array
     */
    public $uses = [];
}

/**
 * AppleComponent class.
 */
class AppleComponent extends Object
{
    /**
     * components property.
     *
     * @var array
     */
    public $components = ['Orange'];

    /**
     * testName property.
     *
     * @var mixed null
     */
    public $testName = null;

    /**
     * startup method.
     *
     * @param mixed $controller
     */
    public function startup(&$controller)
    {
        $this->testName = $controller->name;
    }
}

/**
 * OrangeComponent class.
 */
class OrangeComponent extends Object
{
    /**
     * components property.
     *
     * @var array
     */
    public $components = ['Banana'];

    /**
     * initialize method.
     *
     * @param mixed $controller
     */
    public function initialize(&$controller, $settings)
    {
        $this->Controller = $controller;
        $this->Banana->testField = 'OrangeField';
        $this->settings = $settings;
    }

    /**
     * startup method.
     *
     * @param Controller $controller
     *
     * @return string
     */
    public function startup(&$controller)
    {
        $controller->foo = 'pass';
    }
}

/**
 * BananaComponent class.
 */
class BananaComponent extends Object
{
    /**
     * testField property.
     *
     * @var string 'BananaField'
     */
    public $testField = 'BananaField';

    /**
     * components property.
     *
     * @var array
     */
    public $components = ['Apple'];

    /**
     * startup method.
     *
     * @param Controller $controller
     *
     * @return string
     */
    public function startup(&$controller)
    {
        $controller->bar = 'fail';
    }
}

/**
 * MutuallyReferencingOneComponent class.
 */
class MutuallyReferencingOneComponent extends Object
{
    /**
     * components property.
     *
     * @var array
     */
    public $components = ['MutuallyReferencingTwo'];
}

/**
 * MutuallyReferencingTwoComponent class.
 */
class MutuallyReferencingTwoComponent extends Object
{
    /**
     * components property.
     *
     * @var array
     */
    public $components = ['MutuallyReferencingOne'];
}

/**
 * SomethingWithEmailComponent class.
 */
class SomethingWithEmailComponent extends Object
{
    /**
     * components property.
     *
     * @var array
     */
    public $components = ['Email'];
}

Mock::generate('Object', 'ComponentMockComponent', ['startup', 'beforeFilter', 'beforeRender', 'other']);

/**
 * ComponentTest class.
 */
class ComponentTest extends CakeTestCase
{
    /**
     * setUp method.
     */
    public function setUp()
    {
        $this->_pluginPaths = App::path('plugins');
        App::build([
            'plugins' => [TEST_CAKE_CORE_INCLUDE_PATH.'tests'.DS.'test_app'.DS.'plugins'.DS],
        ]);
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        App::build();
        ClassRegistry::flush();
    }

    /**
     * testLoadComponents method.
     */
    public function testLoadComponents()
    {
        $Controller = new ComponentTestController();
        $Controller->components = ['RequestHandler'];

        $Component = new Component();
        $Component->init($Controller);

        $this->assertTrue(is_a($Controller->RequestHandler, 'RequestHandlerComponent'));

        $Controller = new ComponentTestController();
        $Controller->plugin = 'test_plugin';
        $Controller->components = ['RequestHandler', 'TestPluginComponent'];

        $Component = new Component();
        $Component->init($Controller);

        $this->assertTrue(is_a($Controller->RequestHandler, 'RequestHandlerComponent'));
        $this->assertTrue(is_a($Controller->TestPluginComponent, 'TestPluginComponentComponent'));
        $this->assertTrue(is_a(
            $Controller->TestPluginComponent->TestPluginOtherComponent,
            'TestPluginOtherComponentComponent'
        ));
        $this->assertFalse(isset($Controller->TestPluginOtherComponent));

        $Controller = new ComponentTestController();
        $Controller->components = ['Security'];

        $Component = new Component();
        $Component->init($Controller);

        $this->assertTrue(is_a($Controller->Security, 'SecurityComponent'));
        $this->assertTrue(is_a($Controller->Security->Session, 'SessionComponent'));

        $Controller = new ComponentTestController();
        $Controller->components = ['Security', 'Cookie', 'RequestHandler'];

        $Component = new Component();
        $Component->init($Controller);

        $this->assertTrue(is_a($Controller->Security, 'SecurityComponent'));
        $this->assertTrue(is_a($Controller->Security->RequestHandler, 'RequestHandlerComponent'));
        $this->assertTrue(is_a($Controller->RequestHandler, 'RequestHandlerComponent'));
        $this->assertTrue(is_a($Controller->Cookie, 'CookieComponent'));
    }

    /**
     * test component loading.
     */
    public function testNestedComponentLoading()
    {
        $Controller = new ComponentTestController();
        $Controller->components = ['Apple'];
        $Controller->uses = false;
        $Controller->constructClasses();
        $Controller->Component->initialize($Controller);

        $this->assertTrue(is_a($Controller->Apple, 'AppleComponent'));
        $this->assertTrue(is_a($Controller->Apple->Orange, 'OrangeComponent'));
        $this->assertTrue(is_a($Controller->Apple->Orange->Banana, 'BananaComponent'));
        $this->assertTrue(is_a($Controller->Apple->Orange->Controller, 'ComponentTestController'));
        $this->assertTrue(empty($Controller->Apple->Session));
        $this->assertTrue(empty($Controller->Apple->Orange->Session));
    }

    /**
     * Tests Component::startup() and only running callbacks for components directly attached to
     * the controller.
     */
    public function testComponentStartup()
    {
        $Controller = new ComponentTestController();
        $Controller->components = ['Apple'];
        $Controller->uses = false;
        $Controller->constructClasses();
        $Controller->Component->initialize($Controller);
        $Controller->beforeFilter();
        $Controller->Component->startup($Controller);

        $this->assertTrue(is_a($Controller->Apple, 'AppleComponent'));
        $this->assertEqual($Controller->Apple->testName, 'ComponentTest');

        $expected = !(defined('APP_CONTROLLER_EXISTS') && APP_CONTROLLER_EXISTS);
        $this->assertEqual(isset($Controller->foo), $expected);
        $this->assertFalse(isset($Controller->bar));
    }

    /**
     * test that triggerCallbacks fires methods on all the components, and can trigger any method.
     */
    public function testTriggerCallback()
    {
        $Controller = new ComponentTestController();
        $Controller->components = ['ComponentMock'];
        $Controller->uses = null;
        $Controller->constructClasses();

        $Controller->ComponentMock->expectOnce('beforeRender');
        $Controller->Component->triggerCallback('beforeRender', $Controller);

        $Controller->ComponentMock->expectNever('beforeFilter');
        $Controller->ComponentMock->enabled = false;
        $Controller->Component->triggerCallback('beforeFilter', $Controller);
    }

    /**
     * test a component being used more than once.
     */
    public function testMultipleComponentInitialize()
    {
        $Controller = new ComponentTestController();
        $Controller->uses = false;
        $Controller->components = ['Orange', 'Banana'];
        $Controller->constructClasses();
        $Controller->Component->initialize($Controller);

        $this->assertEqual($Controller->Banana->testField, 'OrangeField');
        $this->assertEqual($Controller->Orange->Banana->testField, 'OrangeField');
    }

    /**
     * Test Component declarations with Parameters
     * tests merging of component parameters and merging / construction of components.
     */
    public function testComponentsWithParams()
    {
        if ($this->skipIf(defined('APP_CONTROLLER_EXISTS'), '%s Need a non-existent AppController')) {
            return;
        }

        $Controller = new ComponentTestController();
        $Controller->components = ['ParamTest' => ['test' => 'value', 'flag'], 'Apple'];
        $Controller->uses = false;
        $Controller->constructClasses();
        $Controller->Component->initialize($Controller);

        $this->assertTrue(is_a($Controller->ParamTest, 'ParamTestComponent'));
        $this->assertTrue(is_a($Controller->ParamTest->Banana, 'BananaComponent'));
        $this->assertTrue(is_a($Controller->Orange, 'OrangeComponent'));
        $this->assertFalse(isset($Controller->Session));
        $this->assertEqual($Controller->Orange->settings, ['colour' => 'blood orange']);
        $this->assertEqual($Controller->ParamTest->test, 'value');
        $this->assertEqual($Controller->ParamTest->flag, true);

        //Settings are merged from app controller and current controller.
        $Controller = new ComponentTestController();
        $Controller->components = [
            'ParamTest' => ['test' => 'value'],
            'Orange' => ['ripeness' => 'perfect'],
        ];
        $Controller->constructClasses();
        $Controller->Component->initialize($Controller);

        $expected = ['colour' => 'blood orange', 'ripeness' => 'perfect'];
        $this->assertEqual($Controller->Orange->settings, $expected);
        $this->assertEqual($Controller->ParamTest->test, 'value');
    }

    /**
     * Ensure that settings are not duplicated when passed into component initialize.
     */
    public function testComponentParamsNoDuplication()
    {
        if ($this->skipIf(defined('APP_CONTROLLER_EXISTS'), '%s Need a non-existent AppController')) {
            return;
        }
        $Controller = new ComponentTestController();
        $Controller->components = ['Orange' => ['setting' => ['itemx']]];
        $Controller->uses = false;

        $Controller->constructClasses();
        $Controller->Component->initialize($Controller);
        $expected = ['setting' => ['itemx'], 'colour' => 'blood orange'];
        $this->assertEqual($Controller->Orange->settings, $expected, 'Params duplication has occured %s');
    }

    /**
     * Test mutually referencing components.
     */
    public function testMutuallyReferencingComponents()
    {
        $Controller = new ComponentTestController();
        $Controller->components = ['MutuallyReferencingOne'];
        $Controller->uses = false;
        $Controller->constructClasses();
        $Controller->Component->initialize($Controller);

        $this->assertTrue(is_a(
            $Controller->MutuallyReferencingOne,
            'MutuallyReferencingOneComponent'
        ));
        $this->assertTrue(is_a(
            $Controller->MutuallyReferencingOne->MutuallyReferencingTwo,
            'MutuallyReferencingTwoComponent'
        ));
        $this->assertTrue(is_a(
            $Controller->MutuallyReferencingOne->MutuallyReferencingTwo->MutuallyReferencingOne,
            'MutuallyReferencingOneComponent'
        ));
    }

    /**
     * Test mutually referencing components.
     */
    public function testSomethingReferencingEmailComponent()
    {
        $Controller = new ComponentTestController();
        $Controller->components = ['SomethingWithEmail'];
        $Controller->uses = false;
        $Controller->constructClasses();
        $Controller->Component->initialize($Controller);
        $Controller->beforeFilter();
        $Controller->Component->startup($Controller);

        $this->assertTrue(is_a(
            $Controller->SomethingWithEmail,
            'SomethingWithEmailComponent'
        ));
        $this->assertTrue(is_a(
            $Controller->SomethingWithEmail->Email,
            'EmailComponent'
        ));
        $this->assertTrue(is_a(
            $Controller->SomethingWithEmail->Email->Controller,
            'ComponentTestController'
        ));
    }

    /**
     * Test that SessionComponent doesn't get added if its already in the components array.
     */
    public function testDoubleLoadingOfSessionComponent()
    {
        if ($this->skipIf(defined('APP_CONTROLLER_EXISTS'), '%s Need a non-existent AppController')) {
            return;
        }

        $Controller = new ComponentTestController();
        $Controller->uses = false;
        $Controller->components = ['Session'];
        $Controller->constructClasses();

        $this->assertEqual($Controller->components, ['Session' => '', 'Orange' => ['colour' => 'blood orange']]);
    }
}
