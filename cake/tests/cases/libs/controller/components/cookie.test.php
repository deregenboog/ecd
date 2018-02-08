<?php
/**
 * CookieComponentTest file.
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
 * @since         CakePHP(tm) v 1.2.0.5435
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Controller', ['Component', 'Controller'], false);
App::import('Component', 'Cookie');

/**
 * CookieComponentTestController class.
 */
class CookieComponentTestController extends Controller
{
    /**
     * components property.
     *
     * @var array
     */
    public $components = ['Cookie'];

    /**
     * beforeFilter method.
     */
    public function beforeFilter()
    {
        $this->Cookie->name = 'CakeTestCookie';
        $this->Cookie->time = 10;
        $this->Cookie->path = '/';
        $this->Cookie->domain = '';
        $this->Cookie->secure = false;
        $this->Cookie->key = 'somerandomhaskey';
    }
}

/**
 * CookieComponentTest class.
 */
class CookieComponentTest extends CakeTestCase
{
    /**
     * Controller property.
     *
     * @var CookieComponentTestController
     */
    public $Controller;

    /**
     * start.
     */
    public function start()
    {
        $this->Controller = new CookieComponentTestController();
        $this->Controller->constructClasses();
        $this->Controller->Component->initialize($this->Controller);
        $this->Controller->beforeFilter();
        $this->Controller->Component->startup($this->Controller);
        $this->Controller->Cookie->destroy();
    }

    /**
     * end.
     */
    public function end()
    {
        $this->Controller->Cookie->destroy();
    }

    /**
     * test that initialize sets settings from components array.
     */
    public function testInitialize()
    {
        $settings = [
            'time' => '5 days',
            'path' => '/',
        ];
        $this->Controller->Cookie->initialize($this->Controller, $settings);
        $this->assertEqual($this->Controller->Cookie->time, $settings['time']);
        $this->assertEqual($this->Controller->Cookie->path, $settings['path']);
    }

    /**
     * testCookieName.
     */
    public function testCookieName()
    {
        $this->assertEqual($this->Controller->Cookie->name, 'CakeTestCookie');
    }

    /**
     * testSettingEncryptedCookieData.
     */
    public function testSettingEncryptedCookieData()
    {
        $this->Controller->Cookie->write('Encrytped_array', ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!']);
        $this->Controller->Cookie->write('Encrytped_multi_cookies.name', 'CakePHP');
        $this->Controller->Cookie->write('Encrytped_multi_cookies.version', '1.2.0.x');
        $this->Controller->Cookie->write('Encrytped_multi_cookies.tag', 'CakePHP Rocks!');
    }

    /**
     * testReadEncryptedCookieData.
     */
    public function testReadEncryptedCookieData()
    {
        $data = $this->Controller->Cookie->read('Encrytped_array');
        $expected = ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'];
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Encrytped_multi_cookies');
        $expected = ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'];
        $this->assertEqual($data, $expected);
    }

    /**
     * testSettingPlainCookieData.
     */
    public function testSettingPlainCookieData()
    {
        $this->Controller->Cookie->write('Plain_array', ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'], false);
        $this->Controller->Cookie->write('Plain_multi_cookies.name', 'CakePHP', false);
        $this->Controller->Cookie->write('Plain_multi_cookies.version', '1.2.0.x', false);
        $this->Controller->Cookie->write('Plain_multi_cookies.tag', 'CakePHP Rocks!', false);
    }

    /**
     * testReadPlainCookieData.
     */
    public function testReadPlainCookieData()
    {
        $data = $this->Controller->Cookie->read('Plain_array');
        $expected = ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'];
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Plain_multi_cookies');
        $expected = ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'];
        $this->assertEqual($data, $expected);
    }

    /**
     * testWritePlainCookieArray.
     */
    public function testWritePlainCookieArray()
    {
        $this->Controller->Cookie->write(['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'], null, false);

        $this->assertEqual($this->Controller->Cookie->read('name'), 'CakePHP');
        $this->assertEqual($this->Controller->Cookie->read('version'), '1.2.0.x');
        $this->assertEqual($this->Controller->Cookie->read('tag'), 'CakePHP Rocks!');

        $this->Controller->Cookie->delete('name');
        $this->Controller->Cookie->delete('version');
        $this->Controller->Cookie->delete('tag');
    }

    /**
     * testReadingCookieValue.
     */
    public function testReadingCookieValue()
    {
        $data = $this->Controller->Cookie->read();
        $expected = [
            'Encrytped_array' => [
                'name' => 'CakePHP',
                'version' => '1.2.0.x',
                'tag' => 'CakePHP Rocks!', ],
            'Encrytped_multi_cookies' => [
                'name' => 'CakePHP',
                'version' => '1.2.0.x',
                'tag' => 'CakePHP Rocks!', ],
            'Plain_array' => [
                'name' => 'CakePHP',
                'version' => '1.2.0.x',
                'tag' => 'CakePHP Rocks!', ],
            'Plain_multi_cookies' => [
                'name' => 'CakePHP',
                'version' => '1.2.0.x',
                'tag' => 'CakePHP Rocks!', ], ];
        $this->assertEqual($data, $expected);
    }

    /**
     * testDeleteCookieValue.
     */
    public function testDeleteCookieValue()
    {
        $this->Controller->Cookie->delete('Encrytped_multi_cookies.name');
        $data = $this->Controller->Cookie->read('Encrytped_multi_cookies');
        $expected = ['version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'];
        $this->assertEqual($data, $expected);

        $this->Controller->Cookie->delete('Encrytped_array');
        $data = $this->Controller->Cookie->read('Encrytped_array');
        $expected = [];
        $this->assertEqual($data, $expected);

        $this->Controller->Cookie->delete('Plain_multi_cookies.name');
        $data = $this->Controller->Cookie->read('Plain_multi_cookies');
        $expected = ['version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'];
        $this->assertEqual($data, $expected);

        $this->Controller->Cookie->delete('Plain_array');
        $data = $this->Controller->Cookie->read('Plain_array');
        $expected = [];
        $this->assertEqual($data, $expected);
    }

    /**
     * testSettingCookiesWithArray.
     */
    public function testSettingCookiesWithArray()
    {
        $this->Controller->Cookie->destroy();

        $this->Controller->Cookie->write(['Encrytped_array' => ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!']]);
        $this->Controller->Cookie->write(['Encrytped_multi_cookies.name' => 'CakePHP']);
        $this->Controller->Cookie->write(['Encrytped_multi_cookies.version' => '1.2.0.x']);
        $this->Controller->Cookie->write(['Encrytped_multi_cookies.tag' => 'CakePHP Rocks!']);

        $this->Controller->Cookie->write(['Plain_array' => ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!']], null, false);
        $this->Controller->Cookie->write(['Plain_multi_cookies.name' => 'CakePHP'], null, false);
        $this->Controller->Cookie->write(['Plain_multi_cookies.version' => '1.2.0.x'], null, false);
        $this->Controller->Cookie->write(['Plain_multi_cookies.tag' => 'CakePHP Rocks!'], null, false);
    }

    /**
     * testReadingCookieArray.
     */
    public function testReadingCookieArray()
    {
        $data = $this->Controller->Cookie->read('Encrytped_array.name');
        $expected = 'CakePHP';
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Encrytped_array.version');
        $expected = '1.2.0.x';
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Encrytped_array.tag');
        $expected = 'CakePHP Rocks!';
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Encrytped_multi_cookies.name');
        $expected = 'CakePHP';
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Encrytped_multi_cookies.version');
        $expected = '1.2.0.x';
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Encrytped_multi_cookies.tag');
        $expected = 'CakePHP Rocks!';
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Plain_array.name');
        $expected = 'CakePHP';
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Plain_array.version');
        $expected = '1.2.0.x';
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Plain_array.tag');
        $expected = 'CakePHP Rocks!';
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Plain_multi_cookies.name');
        $expected = 'CakePHP';
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Plain_multi_cookies.version');
        $expected = '1.2.0.x';
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Plain_multi_cookies.tag');
        $expected = 'CakePHP Rocks!';
        $this->assertEqual($data, $expected);
    }

    /**
     * testReadingCookieDataOnStartup.
     */
    public function testReadingCookieDataOnStartup()
    {
        $this->Controller->Cookie->destroy();

        $data = $this->Controller->Cookie->read('Encrytped_array');
        $expected = [];
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Encrytped_multi_cookies');
        $expected = [];
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Plain_array');
        $expected = [];
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Plain_multi_cookies');
        $expected = [];
        $this->assertEqual($data, $expected);

        $_COOKIE['CakeTestCookie'] = [
                'Encrytped_array' => $this->__encrypt(['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!']),
                'Encrytped_multi_cookies' => [
                        'name' => $this->__encrypt('CakePHP'),
                        'version' => $this->__encrypt('1.2.0.x'),
                        'tag' => $this->__encrypt('CakePHP Rocks!'), ],
                'Plain_array' => 'name|CakePHP,version|1.2.0.x,tag|CakePHP Rocks!',
                'Plain_multi_cookies' => [
                        'name' => 'CakePHP',
                        'version' => '1.2.0.x',
                        'tag' => 'CakePHP Rocks!', ], ];
        $this->Controller->Cookie->startup();

        $data = $this->Controller->Cookie->read('Encrytped_array');
        $expected = ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'];
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Encrytped_multi_cookies');
        $expected = ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'];
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Plain_array');
        $expected = ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'];
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Plain_multi_cookies');
        $expected = ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'];
        $this->assertEqual($data, $expected);
        $this->Controller->Cookie->destroy();
        unset($_COOKIE['CakeTestCookie']);
    }

    /**
     * testReadingCookieDataWithoutStartup.
     */
    public function testReadingCookieDataWithoutStartup()
    {
        $data = $this->Controller->Cookie->read('Encrytped_array');
        $expected = [];
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Encrytped_multi_cookies');
        $expected = [];
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Plain_array');
        $expected = [];
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Plain_multi_cookies');
        $expected = [];
        $this->assertEqual($data, $expected);

        $_COOKIE['CakeTestCookie'] = [
                'Encrytped_array' => $this->__encrypt(['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!']),
                'Encrytped_multi_cookies' => [
                        'name' => $this->__encrypt('CakePHP'),
                        'version' => $this->__encrypt('1.2.0.x'),
                        'tag' => $this->__encrypt('CakePHP Rocks!'), ],
                'Plain_array' => 'name|CakePHP,version|1.2.0.x,tag|CakePHP Rocks!',
                'Plain_multi_cookies' => [
                        'name' => 'CakePHP',
                        'version' => '1.2.0.x',
                        'tag' => 'CakePHP Rocks!', ], ];

        $data = $this->Controller->Cookie->read('Encrytped_array');
        $expected = ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'];
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Encrytped_multi_cookies');
        $expected = ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'];
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Plain_array');
        $expected = ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'];
        $this->assertEqual($data, $expected);

        $data = $this->Controller->Cookie->read('Plain_multi_cookies');
        $expected = ['name' => 'CakePHP', 'version' => '1.2.0.x', 'tag' => 'CakePHP Rocks!'];
        $this->assertEqual($data, $expected);
        $this->Controller->Cookie->destroy();
        unset($_COOKIE['CakeTestCookie']);
    }

    /**
     * test that no error is issued for non array data.
     */
    public function testNoErrorOnNonArrayData()
    {
        $this->Controller->Cookie->destroy();
        $_COOKIE['CakeTestCookie'] = 'kaboom';

        $this->assertNull($this->Controller->Cookie->read('value'));
    }

    /**
     * test that deleting a top level keys kills the child elements too.
     */
    public function testDeleteRemovesChildren()
    {
        $_COOKIE['CakeTestCookie'] = [
            'User' => ['email' => 'example@example.com', 'name' => 'mark'],
            'other' => 'value',
        ];
        $this->Controller->Cookie->startup();
        $this->assertEqual('mark', $this->Controller->Cookie->read('User.name'));

        $this->Controller->Cookie->delete('User');
        $this->assertFalse($this->Controller->Cookie->read('User.email'));
        $this->Controller->Cookie->destroy();
    }

    /**
     * Test deleting recursively with keys that don't exist.
     */
    public function testDeleteChildrenNotExist()
    {
        $this->assertNull($this->Controller->Cookie->delete('NotFound'));
        $this->assertNull($this->Controller->Cookie->delete('Not.Found'));
    }

    /**
     * Test that 1.3 can read 2.0 format codes if json_encode exists.
     */
    public function testForwardsCompatibility()
    {
        if ($this->skipIf(!function_exists('json_decode'), 'no json_decode, skipping.')) {
            return;
        }
        $_COOKIE['CakeTestCookie'] = [
            'JSON' => '{"name":"value"}',
            'Empty' => '',
            'String' => '{"somewhat:"broken"}',
        ];
        $this->Controller->Cookie->startup($this->Controller);
        $this->assertEqual(['name' => 'value'], $this->Controller->Cookie->read('JSON'));
        $this->assertEqual('value', $this->Controller->Cookie->read('JSON.name'));
        $this->assertEqual('', $this->Controller->Cookie->read('Empty'));
        $this->assertEqual('{"somewhat:"broken"}', $this->Controller->Cookie->read('String'));
    }

    /**
     * encrypt method.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function __encrypt($value)
    {
        if (is_array($value)) {
            $value = $this->__implode($value);
        }

        return 'Q2FrZQ==.'.base64_encode(Security::cipher($value, $this->Controller->Cookie->key));
    }

    /**
     * implode method.
     *
     * @param array $value
     *
     * @return string
     */
    public function __implode($array)
    {
        $string = '';
        foreach ($array as $key => $value) {
            $string .= ','.$key.'|'.$value;
        }

        return substr($string, 1);
    }
}
