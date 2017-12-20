<?php
/**
 * SecurityComponentTest file.
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
App::import('Component', 'Security');

/**
 * TestSecurityComponent.
 */
class TestSecurityComponent extends SecurityComponent
{
    /**
     * validatePost method.
     *
     * @param Controller $controller
     *
     * @return unknown
     */
    public function validatePost(&$controller)
    {
        return $this->_validatePost($controller);
    }
}

/**
 * SecurityTestController.
 */
class SecurityTestController extends Controller
{
    /**
     * name property.
     *
     * @var string 'SecurityTest'
     */
    public $name = 'SecurityTest';

    /**
     * components property.
     *
     * @var array
     */
    public $components = ['Session', 'TestSecurity'];

    /**
     * failed property.
     *
     * @var bool false
     */
    public $failed = false;

    /**
     * Used for keeping track of headers in test.
     *
     * @var array
     */
    public $testHeaders = [];

    /**
     * fail method.
     */
    public function fail()
    {
        $this->failed = true;
    }

    /**
     * redirect method.
     *
     * @param mixed $option
     * @param mixed $code
     * @param mixed $exit
     */
    public function redirect($option, $code, $exit)
    {
        return $code;
    }

    /**
     * Conveinence method for header().
     *
     * @param string $status
     */
    public function header($status)
    {
        $this->testHeaders[] = $status;
    }
}

/**
 * SecurityComponentTest class.
 */
class SecurityComponentTest extends CakeTestCase
{
    /**
     * Controller property.
     *
     * @var SecurityTestController
     */
    public $Controller;

    /**
     * oldSalt property.
     *
     * @var string
     */
    public $oldSalt;

    /**
     * setUp method.
     */
    public function startTest()
    {
        $this->Controller = new SecurityTestController();
        $this->Controller->Component->init($this->Controller);
        $this->Controller->Security = &$this->Controller->TestSecurity;
        $this->Controller->Security->blackHoleCallback = 'fail';
        $this->oldSalt = Configure::read('Security.salt');
        Configure::write('Security.salt', 'foo!');
    }

    /**
     * Tear-down method. Resets environment state.
     */
    public function endTest()
    {
        Configure::write('Security.salt', $this->oldSalt);
        $this->Controller->Session->delete('_Token');
        unset($this->Controller->Security);
        unset($this->Controller->Component);
        unset($this->Controller);
    }

    /**
     * test that initalize can set properties.
     */
    public function testInitialize()
    {
        $settings = [
            'requirePost' => ['edit', 'update'],
            'requireSecure' => ['update_account'],
            'requireGet' => ['index'],
            'validatePost' => false,
            'loginUsers' => [
                'mark' => 'password',
            ],
            'requireLogin' => ['login'],
        ];
        $this->Controller->Security->initialize($this->Controller, $settings);
        $this->assertEqual($this->Controller->Security->requirePost, $settings['requirePost']);
        $this->assertEqual($this->Controller->Security->requireSecure, $settings['requireSecure']);
        $this->assertEqual($this->Controller->Security->requireGet, $settings['requireGet']);
        $this->assertEqual($this->Controller->Security->validatePost, $settings['validatePost']);
        $this->assertEqual($this->Controller->Security->loginUsers, $settings['loginUsers']);
        $this->assertEqual($this->Controller->Security->requireLogin, $settings['requireLogin']);
    }

    /**
     * testStartup method.
     */
    public function testStartup()
    {
        $this->Controller->Security->startup($this->Controller);
        $result = $this->Controller->params['_Token']['key'];
        $this->assertNotNull($result);
        $this->assertTrue($this->Controller->Session->check('_Token'));
    }

    /**
     * testRequirePostFail method.
     */
    public function testRequirePostFail()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->Controller->action = 'posted';
        $this->Controller->Security->requirePost(['posted']);
        $this->Controller->Security->startup($this->Controller);
        $this->assertTrue($this->Controller->failed);
    }

    /**
     * testRequirePostSucceed method.
     */
    public function testRequirePostSucceed()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->Controller->action = 'posted';
        $this->Controller->Security->requirePost('posted');
        $this->Controller->Security->startup($this->Controller);
        $this->assertFalse($this->Controller->failed);
    }

    /**
     * testRequireSecureFail method.
     */
    public function testRequireSecureFail()
    {
        $_SERVER['HTTPS'] = 'off';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->Controller->action = 'posted';
        $this->Controller->Security->requireSecure(['posted']);
        $this->Controller->Security->startup($this->Controller);
        $this->assertTrue($this->Controller->failed);
    }

    /**
     * testRequireSecureSucceed method.
     */
    public function testRequireSecureSucceed()
    {
        $_SERVER['REQUEST_METHOD'] = 'Secure';
        $this->Controller->action = 'posted';
        $_SERVER['HTTPS'] = 'on';
        $this->Controller->Security->requireSecure('posted');
        $this->Controller->Security->startup($this->Controller);
        $this->assertFalse($this->Controller->failed);
    }

    /**
     * testRequireAuthFail method.
     */
    public function testRequireAuthFail()
    {
        $_SERVER['REQUEST_METHOD'] = 'AUTH';
        $this->Controller->action = 'posted';
        $this->Controller->data = ['username' => 'willy', 'password' => 'somePass'];
        $this->Controller->Security->requireAuth(['posted']);
        $this->Controller->Security->startup($this->Controller);
        $this->assertTrue($this->Controller->failed);

        $this->Controller->Session->write('_Token', serialize(['allowedControllers' => []]));
        $this->Controller->data = ['username' => 'willy', 'password' => 'somePass'];
        $this->Controller->action = 'posted';
        $this->Controller->Security->requireAuth('posted');
        $this->Controller->Security->startup($this->Controller);
        $this->assertTrue($this->Controller->failed);

        $this->Controller->Session->write('_Token', serialize([
            'allowedControllers' => ['SecurityTest'], 'allowedActions' => ['posted2'],
        ]));
        $this->Controller->data = ['username' => 'willy', 'password' => 'somePass'];
        $this->Controller->action = 'posted';
        $this->Controller->Security->requireAuth('posted');
        $this->Controller->Security->startup($this->Controller);
        $this->assertTrue($this->Controller->failed);
    }

    /**
     * testRequireAuthSucceed method.
     */
    public function testRequireAuthSucceed()
    {
        $_SERVER['REQUEST_METHOD'] = 'AUTH';
        $this->Controller->action = 'posted';
        $this->Controller->Security->requireAuth('posted');
        $this->Controller->Security->startup($this->Controller);
        $this->assertFalse($this->Controller->failed);

        $this->Controller->Security->Session->write('_Token', serialize([
            'allowedControllers' => ['SecurityTest'], 'allowedActions' => ['posted'],
        ]));
        $this->Controller->params['controller'] = 'SecurityTest';
        $this->Controller->params['action'] = 'posted';

        $this->Controller->data = [
            'username' => 'willy', 'password' => 'somePass', '_Token' => '',
        ];
        $this->Controller->action = 'posted';
        $this->Controller->Security->requireAuth('posted');
        $this->Controller->Security->startup($this->Controller);
        $this->assertFalse($this->Controller->failed);
    }

    /**
     * testRequirePostSucceedWrongMethod method.
     */
    public function testRequirePostSucceedWrongMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->Controller->action = 'getted';
        $this->Controller->Security->requirePost('posted');
        $this->Controller->Security->startup($this->Controller);
        $this->assertFalse($this->Controller->failed);
    }

    /**
     * testRequireGetFail method.
     */
    public function testRequireGetFail()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->Controller->action = 'getted';
        $this->Controller->Security->requireGet(['getted']);
        $this->Controller->Security->startup($this->Controller);
        $this->assertTrue($this->Controller->failed);
    }

    /**
     * testRequireGetSucceed method.
     */
    public function testRequireGetSucceed()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->Controller->action = 'getted';
        $this->Controller->Security->requireGet('getted');
        $this->Controller->Security->startup($this->Controller);
        $this->assertFalse($this->Controller->failed);
    }

    /**
     * testRequireLogin method.
     */
    public function testRequireLogin()
    {
        $this->Controller->action = 'posted';
        $this->Controller->Security->requireLogin(
            'posted',
            ['type' => 'basic', 'users' => ['admin' => 'password']]
        );
        $_SERVER['PHP_AUTH_USER'] = 'admin';
        $_SERVER['PHP_AUTH_PW'] = 'password';
        $this->Controller->Security->startup($this->Controller);
        $this->assertFalse($this->Controller->failed);

        $this->Controller->action = 'posted';
        $this->Controller->Security->requireLogin(
            ['posted'],
            ['type' => 'basic', 'users' => ['admin' => 'password']]
        );
        $_SERVER['PHP_AUTH_USER'] = 'admin2';
        $_SERVER['PHP_AUTH_PW'] = 'password';
        $this->Controller->Security->startup($this->Controller);
        $this->assertTrue($this->Controller->failed);

        $this->Controller->action = 'posted';
        $this->Controller->Security->requireLogin(
            'posted',
            ['type' => 'basic', 'users' => ['admin' => 'password']]
        );
        $_SERVER['PHP_AUTH_USER'] = 'admin';
        $_SERVER['PHP_AUTH_PW'] = 'password2';
        $this->Controller->Security->startup($this->Controller);
        $this->assertTrue($this->Controller->failed);
    }

    /**
     * testDigestAuth method.
     */
    public function testDigestAuth()
    {
        $skip = $this->skipIf((version_compare(PHP_VERSION, '5.1') == -1) xor (!function_exists('apache_request_headers')),
            '%s Cannot run Digest Auth test for PHP versions < 5.1'
        );

        if ($skip) {
            return;
        }

        $this->Controller->action = 'posted';
        $_SERVER['PHP_AUTH_DIGEST'] = $digest = <<<DIGEST
		Digest username="Mufasa",
		realm="testrealm@host.com",
		nonce="dcd98b7102dd2f0e8b11d0f600bfb0c093",
		uri="/dir/index.html",
		qop=auth,
		nc=00000001,
		cnonce="0a4f113b",
		response="460d0d3c6867c2f1ab85b1ada1aece48",
		opaque="5ccc069c403ebaf9f0171e9517f40e41"
DIGEST;
        $this->Controller->Security->requireLogin('posted', [
            'type' => 'digest', 'users' => ['Mufasa' => 'password'],
            'realm' => 'testrealm@host.com',
        ]);
        $this->Controller->Security->startup($this->Controller);
        $this->assertFalse($this->Controller->failed);
    }

    /**
     * testRequireGetSucceedWrongMethod method.
     */
    public function testRequireGetSucceedWrongMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->Controller->action = 'posted';
        $this->Controller->Security->requireGet('getted');
        $this->Controller->Security->startup($this->Controller);
        $this->assertFalse($this->Controller->failed);
    }

    /**
     * testRequirePutFail method.
     */
    public function testRequirePutFail()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->Controller->action = 'putted';
        $this->Controller->Security->requirePut(['putted']);
        $this->Controller->Security->startup($this->Controller);
        $this->assertTrue($this->Controller->failed);
    }

    /**
     * testRequirePutSucceed method.
     */
    public function testRequirePutSucceed()
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $this->Controller->action = 'putted';
        $this->Controller->Security->requirePut('putted');
        $this->Controller->Security->startup($this->Controller);
        $this->assertFalse($this->Controller->failed);
    }

    /**
     * testRequirePutSucceedWrongMethod method.
     */
    public function testRequirePutSucceedWrongMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->Controller->action = 'posted';
        $this->Controller->Security->requirePut('putted');
        $this->Controller->Security->startup($this->Controller);
        $this->assertFalse($this->Controller->failed);
    }

    /**
     * testRequireDeleteFail method.
     */
    public function testRequireDeleteFail()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->Controller->action = 'deleted';
        $this->Controller->Security->requireDelete(['deleted', 'other_method']);
        $this->Controller->Security->startup($this->Controller);
        $this->assertTrue($this->Controller->failed);
    }

    /**
     * testRequireDeleteSucceed method.
     */
    public function testRequireDeleteSucceed()
    {
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $this->Controller->action = 'deleted';
        $this->Controller->Security->requireDelete('deleted');
        $this->Controller->Security->startup($this->Controller);
        $this->assertFalse($this->Controller->failed);
    }

    /**
     * testRequireDeleteSucceedWrongMethod method.
     */
    public function testRequireDeleteSucceedWrongMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->Controller->action = 'posted';
        $this->Controller->Security->requireDelete('deleted');
        $this->Controller->Security->startup($this->Controller);
        $this->assertFalse($this->Controller->failed);
    }

    /**
     * testRequireLoginSettings method.
     */
    public function testRequireLoginSettings()
    {
        $this->Controller->Security->requireLogin(
            'add', 'edit',
            ['type' => 'basic', 'users' => ['admin' => 'password']]
        );
        $this->assertEqual($this->Controller->Security->requireLogin, ['add', 'edit']);
        $this->assertEqual($this->Controller->Security->loginUsers, ['admin' => 'password']);
    }

    /**
     * testRequireLoginAllActions method.
     */
    public function testRequireLoginAllActions()
    {
        $this->Controller->Security->requireLogin(
            ['type' => 'basic', 'users' => ['admin' => 'password']]
        );
        $this->assertEqual($this->Controller->Security->requireLogin, ['*']);
        $this->assertEqual($this->Controller->Security->loginUsers, ['admin' => 'password']);
    }

    /**
     * Simple hash validation test.
     */
    public function testValidatePost()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = 'a5475372b40f6e3ccbf9f8af191f20e1642fd877%3AModel.valid';

        $this->Controller->data = [
            'Model' => ['username' => 'nate', 'password' => 'foo', 'valid' => '0'],
            '_Token' => compact('key', 'fields'),
        ];
        $this->assertTrue($this->Controller->Security->validatePost($this->Controller));
    }

    /**
     * Test that the controller->here is part of the hash.
     */
    public function testValidatePostUsesControllerHere()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = 'a5475372b40f6e3ccbf9f8af191f20e1642fd877%3AModel.valid';

        $this->Controller->data = [
            'Model' => ['username' => 'nate', 'password' => 'foo', 'valid' => '0'],
            '_Token' => compact('key', 'fields'),
        ];
        $this->assertTrue($this->Controller->Security->validatePost($this->Controller));

        $this->Controller->here = '/cake_13/tasks';
        $this->assertFalse($this->Controller->Security->validatePost($this->Controller));
    }

    /**
     * Test that validatePost fails if you are missing the session information.
     */
    public function testValidatePostNoSession()
    {
        $this->Controller->Security->startup($this->Controller);
        $this->Controller->Session->delete('_Token');

        $key = $this->Controller->params['_Token']['key'];
        $fields = 'a5475372b40f6e3ccbf9f8af191f20e1642fd877%3AModel.valid';

        $this->Controller->data = [
            'Model' => ['username' => 'nate', 'password' => 'foo', 'valid' => '0'],
            '_Token' => compact('key', 'fields'),
        ];
        $this->assertFalse($this->Controller->Security->validatePost($this->Controller));
    }

    /**
     * test that validatePost fails if any of its required fields are missing.
     */
    public function testValidatePostFormHacking()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = 'a5475372b40f6e3ccbf9f8af191f20e1642fd877%3AModel.valid';

        $this->Controller->data = [
            'Model' => ['username' => 'nate', 'password' => 'foo', 'valid' => '0'],
            '_Token' => compact('key'),
        ];
        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertFalse($result, 'validatePost passed when fields were missing. %s');

        $this->Controller->data = [
            'Model' => ['username' => 'nate', 'password' => 'foo', 'valid' => '0'],
            '_Token' => compact('fields'),
        ];
        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertFalse($result, 'validatePost passed when key was missing. %s');
    }

    /**
     * Test that objects can't be passed into the serialized string. This was a vector for RFI and LFI
     * attacks. Thanks to Felix Wilhelm.
     */
    public function testValidatePostObjectDeserialize()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = 'a5475372b40f6e3ccbf9f8af191f20e1642fd877';

        // a corrupted serialized object, so we can see if it ever gets to deserialize
        $attack = 'O:3:"App":1:{s:5:"__map";a:1:{s:3:"foo";s:7:"Hacked!";s:1:"fail"}}';
        $fields .= urlencode(':'.str_rot13($attack));

        $this->Controller->data = [
            'Model' => ['username' => 'mark', 'password' => 'foo', 'valid' => '0'],
            '_Token' => compact('key', 'fields'),
        ];
        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertFalse($result, 'validatePost passed when key was missing. %s');
    }

    /**
     * Tests validation of checkbox arrays.
     */
    public function testValidatePostArray()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = 'f7d573650a295b94e0938d32b323fde775e5f32b%3A';

        $this->Controller->data = [
            'Model' => ['multi_field' => ['1', '3']],
            '_Token' => compact('key', 'fields'),
        ];
        $this->assertTrue($this->Controller->Security->validatePost($this->Controller));
    }

    /**
     * testValidatePostNoModel method.
     */
    public function testValidatePostNoModel()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = '540ac9c60d323c22bafe997b72c0790f39a8bdef%3A';

        $this->Controller->data = [
            'anything' => 'some_data',
            '_Token' => compact('key', 'fields'),
        ];

        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);
    }

    /**
     * testValidatePostSimple method.
     */
    public function testValidatePostSimple()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = '69f493434187b867ea14b901fdf58b55d27c935d%3A';

        $this->Controller->data = $data = [
            'Model' => ['username' => '', 'password' => ''],
            '_Token' => compact('key', 'fields'),
        ];

        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);
    }

    /**
     * Tests hash validation for multiple records, including locked fields.
     */
    public function testValidatePostComplex()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = 'c9118120e680a7201b543f562e5301006ccfcbe2%3AAddresses.0.id%7CAddresses.1.id';

        $this->Controller->data = [
            'Addresses' => [
                '0' => [
                    'id' => '123456', 'title' => '', 'first_name' => '', 'last_name' => '',
                    'address' => '', 'city' => '', 'phone' => '', 'primary' => '',
                ],
                '1' => [
                    'id' => '654321', 'title' => '', 'first_name' => '', 'last_name' => '',
                    'address' => '', 'city' => '', 'phone' => '', 'primary' => '',
                ],
            ],
            '_Token' => compact('key', 'fields'),
        ];
        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);
    }

    /**
     * test ValidatePost with multiple select elements.
     */
    public function testValidatePostMultipleSelect()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = '422cde416475abc171568be690a98cad20e66079%3A';

        $this->Controller->data = [
            'Tag' => ['Tag' => [1, 2]],
            '_Token' => compact('key', 'fields'),
        ];
        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);

        $this->Controller->data = [
            'Tag' => ['Tag' => [1, 2, 3]],
            '_Token' => compact('key', 'fields'),
        ];
        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);

        $this->Controller->data = [
            'Tag' => ['Tag' => [1, 2, 3, 4]],
            '_Token' => compact('key', 'fields'),
        ];
        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);

        $fields = '19464422eafe977ee729c59222af07f983010c5f%3A';
        $this->Controller->data = [
            'User.password' => 'bar', 'User.name' => 'foo', 'User.is_valid' => '1',
            'Tag' => ['Tag' => [1]], '_Token' => compact('key', 'fields'),
        ];
        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);
    }

    /**
     * testValidatePostCheckbox method.
     *
     * First block tests un-checked checkbox
     * Second block tests checked checkbox
     */
    public function testValidatePostCheckbox()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = 'a5475372b40f6e3ccbf9f8af191f20e1642fd877%3AModel.valid';

        $this->Controller->data = [
            'Model' => ['username' => '', 'password' => '', 'valid' => '0'],
            '_Token' => compact('key', 'fields'),
        ];

        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);

        $fields = '874439ca69f89b4c4a5f50fb9c36ff56a28f5d42%3A';

        $this->Controller->data = [
            'Model' => ['username' => '', 'password' => '', 'valid' => '0'],
            '_Token' => compact('key', 'fields'),
        ];

        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);

        $this->Controller->data = [];
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];

        $this->Controller->data = $data = [
            'Model' => ['username' => '', 'password' => '', 'valid' => '0'],
            '_Token' => compact('key', 'fields'),
        ];

        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);
    }

    /**
     * testValidatePostHidden method.
     */
    public function testValidatePostHidden()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = '51ccd8cb0997c7b3d4523ecde5a109318405ef8c%3AModel.hidden%7CModel.other_hidden';
        $fields .= '';

        $this->Controller->data = [
            'Model' => [
                'username' => '', 'password' => '', 'hidden' => '0',
                'other_hidden' => 'some hidden value',
            ],
            '_Token' => compact('key', 'fields'),
        ];
        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);
    }

    /**
     * testValidatePostWithDisabledFields method.
     */
    public function testValidatePostWithDisabledFields()
    {
        $this->Controller->Security->disabledFields = ['Model.username', 'Model.password'];
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = 'ef1082968c449397bcd849f963636864383278b1%3AModel.hidden';

        $this->Controller->data = [
            'Model' => [
                'username' => '', 'password' => '', 'hidden' => '0',
            ],
            '_Token' => compact('fields', 'key'),
        ];

        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);
    }

    /**
     * testValidateHiddenMultipleModel method.
     */
    public function testValidateHiddenMultipleModel()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = 'a2d01072dc4660eea9d15007025f35a7a5b58e18%3AModel.valid%7CModel2.valid%7CModel3.valid';

        $this->Controller->data = [
            'Model' => ['username' => '', 'password' => '', 'valid' => '0'],
            'Model2' => ['valid' => '0'],
            'Model3' => ['valid' => '0'],
            '_Token' => compact('key', 'fields'),
        ];
        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);
    }

    /**
     * testLoginValidation method.
     */
    public function testLoginValidation()
    {
    }

    /**
     * testValidateHasManyModel method.
     */
    public function testValidateHasManyModel()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = '51e3b55a6edd82020b3f29c9ae200e14bbeb7ee5%3AModel.0.hidden%7CModel.0.valid';
        $fields .= '%7CModel.1.hidden%7CModel.1.valid';

        $this->Controller->data = [
            'Model' => [
                [
                    'username' => 'username', 'password' => 'password',
                    'hidden' => 'value', 'valid' => '0',
                ],
                [
                    'username' => 'username', 'password' => 'password',
                    'hidden' => 'value', 'valid' => '0',
                ],
            ],
            '_Token' => compact('key', 'fields'),
        ];

        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);
    }

    /**
     * testValidateHasManyRecordsPass method.
     */
    public function testValidateHasManyRecordsPass()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = '7a203edb3d345bbf38fe0dccae960da8842e11d7%3AAddress.0.id%7CAddress.0.primary%7C';
        $fields .= 'Address.1.id%7CAddress.1.primary';

        $this->Controller->data = [
            'Address' => [
                0 => [
                    'id' => '123',
                    'title' => 'home',
                    'first_name' => 'Bilbo',
                    'last_name' => 'Baggins',
                    'address' => '23 Bag end way',
                    'city' => 'the shire',
                    'phone' => 'N/A',
                    'primary' => '1',
                ],
                1 => [
                    'id' => '124',
                    'title' => 'home',
                    'first_name' => 'Frodo',
                    'last_name' => 'Baggins',
                    'address' => '50 Bag end way',
                    'city' => 'the shire',
                    'phone' => 'N/A',
                    'primary' => '1',
                ],
            ],
            '_Token' => compact('key', 'fields'),
        ];

        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);
    }

    /**
     * testValidateHasManyRecords method.
     *
     * validatePost should fail, hidden fields have been changed.
     */
    public function testValidateHasManyRecordsFail()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = '7a203edb3d345bbf38fe0dccae960da8842e11d7%3AAddress.0.id%7CAddress.0.primary%7C';
        $fields .= 'Address.1.id%7CAddress.1.primary';

        $this->Controller->data = [
            'Address' => [
                0 => [
                    'id' => '123',
                    'title' => 'home',
                    'first_name' => 'Bilbo',
                    'last_name' => 'Baggins',
                    'address' => '23 Bag end way',
                    'city' => 'the shire',
                    'phone' => 'N/A',
                    'primary' => '5',
                ],
                1 => [
                    'id' => '124',
                    'title' => 'home',
                    'first_name' => 'Frodo',
                    'last_name' => 'Baggins',
                    'address' => '50 Bag end way',
                    'city' => 'the shire',
                    'phone' => 'N/A',
                    'primary' => '1',
                ],
            ],
            '_Token' => compact('key', 'fields'),
        ];

        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertFalse($result);
    }

    /**
     * testLoginRequest method.
     */
    public function testLoginRequest()
    {
        $this->Controller->Security->startup($this->Controller);
        $realm = 'cakephp.org';
        $options = ['realm' => $realm, 'type' => 'basic'];
        $result = $this->Controller->Security->loginRequest($options);
        $expected = 'WWW-Authenticate: Basic realm="'.$realm.'"';
        $this->assertEqual($result, $expected);

        $this->Controller->Security->startup($this->Controller);
        $options = ['realm' => $realm, 'type' => 'digest'];
        $result = $this->Controller->Security->loginRequest($options);
        $this->assertPattern('/realm="'.$realm.'"/', $result);
        $this->assertPattern('/qop="auth"/', $result);
    }

    /**
     * testGenerateDigestResponseHash method.
     */
    public function testGenerateDigestResponseHash()
    {
        $this->Controller->Security->startup($this->Controller);
        $realm = 'cakephp.org';
        $loginData = ['realm' => $realm, 'users' => ['Willy Smith' => 'password']];
        $this->Controller->Security->requireLogin($loginData);

        $data = [
            'username' => 'Willy Smith',
            'password' => 'password',
            'nonce' => String::uuid(),
            'nc' => 1,
            'cnonce' => 1,
            'realm' => $realm,
            'uri' => 'path_to_identifier',
            'qop' => 'testme',
        ];
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $result = $this->Controller->Security->generateDigestResponseHash($data);
        $expected = md5(
            md5($data['username'].':'.$loginData['realm'].':'.$data['password']).':'.
            $data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.
            md5(env('REQUEST_METHOD').':'.$data['uri'])
        );
        $this->assertIdentical($result, $expected);
    }

    /**
     * testLoginCredentials method.
     */
    public function testLoginCredentials()
    {
        $this->Controller->Security->startup($this->Controller);
        $_SERVER['PHP_AUTH_USER'] = $user = 'Willy Test';
        $_SERVER['PHP_AUTH_PW'] = $pw = 'some password for the nice test';

        $result = $this->Controller->Security->loginCredentials('basic');
        $expected = ['username' => $user, 'password' => $pw];
        $this->assertIdentical($result, $expected);

        if (version_compare(PHP_VERSION, '5.1') != -1) {
            $_SERVER['PHP_AUTH_DIGEST'] = $digest = <<<DIGEST
				Digest username="Mufasa",
				realm="testrealm@host.com",
				nonce="dcd98b7102dd2f0e8b11d0f600bfb0c093",
				uri="/dir/index.html",
				qop=auth,
				nc=00000001,
				cnonce="0a4f113b",
				response="6629fae49393a05397450978507c4ef1",
				opaque="5ccc069c403ebaf9f0171e9517f40e41"
DIGEST;
            $expected = [
                'username' => 'Mufasa',
                'realm' => 'testrealm@host.com',
                'nonce' => 'dcd98b7102dd2f0e8b11d0f600bfb0c093',
                'uri' => '/dir/index.html',
                'qop' => 'auth',
                'nc' => '00000001',
                'cnonce' => '0a4f113b',
                'response' => '6629fae49393a05397450978507c4ef1',
                'opaque' => '5ccc069c403ebaf9f0171e9517f40e41',
            ];
            $result = $this->Controller->Security->loginCredentials('digest');
            $this->assertIdentical($result, $expected);
        }
    }

    /**
     * testParseDigestAuthData method.
     */
    public function testParseDigestAuthData()
    {
        $this->Controller->Security->startup($this->Controller);
        $digest = <<<DIGEST
			Digest username="Mufasa",
			realm="testrealm@host.com",
			nonce="dcd98b7102dd2f0e8b11d0f600bfb0c093",
			uri="/dir/index.html",
			qop=auth,
			nc=00000001,
			cnonce="0a4f113b",
			response="6629fae49393a05397450978507c4ef1",
			opaque="5ccc069c403ebaf9f0171e9517f40e41"
DIGEST;
        $expected = [
            'username' => 'Mufasa',
            'realm' => 'testrealm@host.com',
            'nonce' => 'dcd98b7102dd2f0e8b11d0f600bfb0c093',
            'uri' => '/dir/index.html',
            'qop' => 'auth',
            'nc' => '00000001',
            'cnonce' => '0a4f113b',
            'response' => '6629fae49393a05397450978507c4ef1',
            'opaque' => '5ccc069c403ebaf9f0171e9517f40e41',
        ];
        $result = $this->Controller->Security->parseDigestAuthData($digest);
        $this->assertIdentical($result, $expected);

        $result = $this->Controller->Security->parseDigestAuthData('');
        $this->assertNull($result);
    }

    /**
     * test parsing digest information with email addresses.
     */
    public function testParseDigestAuthEmailAddress()
    {
        $this->Controller->Security->startup($this->Controller);
        $digest = <<<DIGEST
			Digest username="mark@example.com",
			realm="testrealm@host.com",
			nonce="dcd98b7102dd2f0e8b11d0f600bfb0c093",
			uri="/dir/index.html",
			qop=auth,
			nc=00000001,
			cnonce="0a4f113b",
			response="6629fae49393a05397450978507c4ef1",
			opaque="5ccc069c403ebaf9f0171e9517f40e41"
DIGEST;
        $expected = [
            'username' => 'mark@example.com',
            'realm' => 'testrealm@host.com',
            'nonce' => 'dcd98b7102dd2f0e8b11d0f600bfb0c093',
            'uri' => '/dir/index.html',
            'qop' => 'auth',
            'nc' => '00000001',
            'cnonce' => '0a4f113b',
            'response' => '6629fae49393a05397450978507c4ef1',
            'opaque' => '5ccc069c403ebaf9f0171e9517f40e41',
        ];
        $result = $this->Controller->Security->parseDigestAuthData($digest);
        $this->assertIdentical($result, $expected);
    }

    /**
     * testFormDisabledFields method.
     */
    public function testFormDisabledFields()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = '11842060341b9d0fc3808b90ba29fdea7054d6ad%3An%3A0%3A%7B%7D';

        $this->Controller->data = [
            'MyModel' => ['name' => 'some data'],
            '_Token' => compact('key', 'fields'),
        ];
        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertFalse($result);

        $this->Controller->Security->startup($this->Controller);
        $this->Controller->Security->disabledFields = ['MyModel.name'];
        $key = $this->Controller->params['_Token']['key'];

        $this->Controller->data = [
            'MyModel' => ['name' => 'some data'],
            '_Token' => compact('key', 'fields'),
        ];

        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);
    }

    /**
     * testRadio method.
     */
    public function testRadio()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];
        $fields = '575ef54ca4fc8cab468d6d898e9acd3a9671c17e%3An%3A0%3A%7B%7D';

        $this->Controller->data = [
            '_Token' => compact('key', 'fields'),
        ];
        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertFalse($result);

        $this->Controller->data = [
            '_Token' => compact('key', 'fields'),
            'Test' => ['test' => ''],
        ];
        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);

        $this->Controller->data = [
            '_Token' => compact('key', 'fields'),
            'Test' => ['test' => '1'],
        ];
        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);

        $this->Controller->data = [
            '_Token' => compact('key', 'fields'),
            'Test' => ['test' => '2'],
        ];
        $result = $this->Controller->Security->validatePost($this->Controller);
        $this->assertTrue($result);
    }

    /**
     * testInvalidAuthHeaders method.
     */
    public function testInvalidAuthHeaders()
    {
        $this->Controller->Security->blackHoleCallback = null;
        $_SERVER['PHP_AUTH_USER'] = 'admin';
        $_SERVER['PHP_AUTH_PW'] = 'password';
        $realm = 'cakephp.org';
        $loginData = ['type' => 'basic', 'realm' => $realm];
        $this->Controller->Security->requireLogin($loginData);
        $this->Controller->Security->startup($this->Controller);

        $expected = 'WWW-Authenticate: Basic realm="'.$realm.'"';
        $this->assertEqual(count($this->Controller->testHeaders), 1);
        $this->assertEqual(current($this->Controller->testHeaders), $expected);
    }

    /**
     * test that a requestAction's controller will have the _Token appended to
     * the params.
     *
     * @see http://cakephp.lighthouseapp.com/projects/42648/tickets/68
     */
    public function testSettingTokenForRequestAction()
    {
        $this->Controller->Security->startup($this->Controller);
        $key = $this->Controller->params['_Token']['key'];

        $this->Controller->params['requested'] = 1;
        unset($this->Controller->params['_Token']);

        $this->Controller->Security->startup($this->Controller);
        $this->assertEqual($this->Controller->params['_Token']['key'], $key);
    }

    /**
     * test that blackhole doesn't delete the _Token session key so repeat data submissions
     * stay blackholed.
     *
     * @see http://cakephp.lighthouseapp.com/projects/42648/tickets/214
     */
    public function testBlackHoleNotDeletingSessionInformation()
    {
        $this->Controller->Security->startup($this->Controller);

        $this->Controller->Security->blackHole($this->Controller, 'auth');
        $this->assertTrue($this->Controller->Security->Session->check('_Token'), '_Token was deleted by blackHole %s');
    }
}
