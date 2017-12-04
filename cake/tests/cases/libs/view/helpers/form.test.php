<?php
/**
 * FormHelperTest file.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html>
 * Copyright 2005-2012, Cake Software Foundation, Inc.
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc.
 *
 * @see          http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html CakePHP(tm) Tests
 * @since         CakePHP(tm) v 1.2.0.4206
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Core', ['ClassRegistry', 'Controller', 'View', 'Model', 'Security']);
App::import('Helper', 'Html');
App::import('Helper', 'Form');

/**
 * ContactTestController class.
 */
class ContactTestController extends Controller
{
    /**
     * name property.
     *
     * @var string 'ContactTest'
     */
    public $name = 'ContactTest';

    /**
     * uses property.
     *
     * @var mixed null
     */
    public $uses = null;
}

/**
 * Contact class.
 */
class Contact extends CakeTestModel
{
    /**
     * primaryKey property.
     *
     * @var string 'id'
     */
    public $primaryKey = 'id';

    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * name property.
     *
     * @var string 'Contact'
     */
    public $name = 'Contact';

    /**
     * Default schema.
     *
     * @var array
     */
    public $_schema = [
        'id' => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8'],
        'name' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'email' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'phone' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'password' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'published' => ['type' => 'date', 'null' => true, 'default' => null, 'length' => null],
        'created' => ['type' => 'date', 'null' => '1', 'default' => '', 'length' => ''],
        'updated' => ['type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null],
    ];

    /**
     * validate property.
     *
     * @var array
     */
    public $validate = [
        'non_existing' => [],
        'idontexist' => [],
        'imrequired' => ['rule' => ['between', 5, 30], 'allowEmpty' => false],
        'imalsorequired' => ['rule' => 'alphaNumeric', 'allowEmpty' => false],
        'imrequiredtoo' => ['rule' => 'notEmpty'],
        'required_one' => ['required' => ['rule' => ['notEmpty']]],
        'imnotrequired' => ['required' => false, 'rule' => 'alphaNumeric', 'allowEmpty' => true],
        'imalsonotrequired' => [
            'alpha' => ['rule' => 'alphaNumeric', 'allowEmpty' => true],
            'between' => ['rule' => ['between', 5, 30]],
        ],
        'imnotrequiredeither' => ['required' => true, 'rule' => ['between', 5, 30], 'allowEmpty' => true],
    ];

    /**
     * schema method.
     */
    public function setSchema($schema)
    {
        $this->_schema = $schema;
    }

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $hasAndBelongsToMany = ['ContactTag' => ['with' => 'ContactTagsContact']];

    /**
     * hasAndBelongsToMany property.
     *
     * @var array
     */
    public $belongsTo = ['User' => ['className' => 'UserForm']];
}

/**
 * ContactTagsContact class.
 */
class ContactTagsContact extends CakeTestModel
{
    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * name property.
     *
     * @var string 'Contact'
     */
    public $name = 'ContactTagsContact';

    /**
     * Default schema.
     *
     * @var array
     */
    public $_schema = [
        'contact_id' => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8'],
        'contact_tag_id' => [
            'type' => 'integer', 'null' => '', 'default' => '', 'length' => '8',
        ],
    ];

    /**
     * schema method.
     */
    public function setSchema($schema)
    {
        $this->_schema = $schema;
    }
}

/**
 * ContactNonStandardPk class.
 */
class ContactNonStandardPk extends Contact
{
    /**
     * primaryKey property.
     *
     * @var string 'pk'
     */
    public $primaryKey = 'pk';

    /**
     * name property.
     *
     * @var string 'ContactNonStandardPk'
     */
    public $name = 'ContactNonStandardPk';

    /**
     * schema method.
     */
    public function schema()
    {
        $this->_schema = parent::schema();
        $this->_schema['pk'] = $this->_schema['id'];
        unset($this->_schema['id']);

        return $this->_schema;
    }
}

/**
 * ContactTag class.
 */
class ContactTag extends Model
{
    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * schema definition.
     *
     * @var array
     */
    public $_schema = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => '', 'length' => '8'],
        'name' => ['type' => 'string', 'null' => false, 'default' => '', 'length' => '255'],
        'created' => ['type' => 'date', 'null' => true, 'default' => '', 'length' => ''],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => '', 'length' => null],
    ];
}

/**
 * UserForm class.
 */
class UserForm extends CakeTestModel
{
    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * primaryKey property.
     *
     * @var string 'id'
     */
    public $primaryKey = 'id';

    /**
     * name property.
     *
     * @var string 'UserForm'
     */
    public $name = 'UserForm';

    /**
     * hasMany property.
     *
     * @var array
     */
    public $hasMany = [
        'OpenidUrl' => ['className' => 'OpenidUrl', 'foreignKey' => 'user_form_id',
    ], ];

    /**
     * schema definition.
     *
     * @var array
     */
    public $_schema = [
        'id' => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8'],
        'published' => ['type' => 'date', 'null' => true, 'default' => null, 'length' => null],
        'other' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => null],
        'stuff' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 10],
        'something' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 255],
        'active' => ['type' => 'boolean', 'null' => false, 'default' => false],
        'created' => ['type' => 'date', 'null' => '1', 'default' => '', 'length' => ''],
        'updated' => ['type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null],
    ];
}

/**
 * OpenidUrl class.
 */
class OpenidUrl extends CakeTestModel
{
    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * primaryKey property.
     *
     * @var string 'id'
     */
    public $primaryKey = 'id';

    /**
     * name property.
     *
     * @var string 'OpenidUrl'
     */
    public $name = 'OpenidUrl';

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['UserForm' => [
        'className' => 'UserForm', 'foreignKey' => 'user_form_id',
    ]];

    /**
     * validate property.
     *
     * @var array
     */
    public $validate = ['openid_not_registered' => []];

    /**
     * schema method.
     *
     * @var array
     */
    public $_schema = [
        'id' => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8'],
        'user_form_id' => [
            'type' => 'user_form_id', 'null' => '', 'default' => '', 'length' => '8',
        ],
        'url' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
    ];

    /**
     * beforeValidate method.
     */
    public function beforeValidate()
    {
        $this->invalidate('openid_not_registered');

        return true;
    }
}

/**
 * ValidateUser class.
 */
class ValidateUser extends CakeTestModel
{
    /**
     * primaryKey property.
     *
     * @var string 'id'
     */
    public $primaryKey = 'id';

    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * name property.
     *
     * @var string 'ValidateUser'
     */
    public $name = 'ValidateUser';

    /**
     * hasOne property.
     *
     * @var array
     */
    public $hasOne = ['ValidateProfile' => [
        'className' => 'ValidateProfile', 'foreignKey' => 'user_id',
    ]];

    /**
     * schema method.
     *
     * @var array
     */
    public $_schema = [
        'id' => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8'],
        'name' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'email' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'balance' => ['type' => 'float', 'null' => false, 'length' => '5,2'],
        'created' => ['type' => 'date', 'null' => '1', 'default' => '', 'length' => ''],
        'updated' => ['type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null],
    ];

    /**
     * beforeValidate method.
     */
    public function beforeValidate()
    {
        $this->invalidate('email');

        return false;
    }
}

/**
 * ValidateProfile class.
 */
class ValidateProfile extends CakeTestModel
{
    /**
     * primaryKey property.
     *
     * @var string 'id'
     */
    public $primaryKey = 'id';

    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * schema property.
     *
     * @var array
     */
    public $_schema = [
        'id' => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8'],
        'user_id' => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8'],
        'full_name' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'city' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'created' => ['type' => 'date', 'null' => '1', 'default' => '', 'length' => ''],
        'updated' => ['type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null],
    ];

    /**
     * name property.
     *
     * @var string 'ValidateProfile'
     */
    public $name = 'ValidateProfile';

    /**
     * hasOne property.
     *
     * @var array
     */
    public $hasOne = ['ValidateItem' => [
        'className' => 'ValidateItem', 'foreignKey' => 'profile_id',
    ]];

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['ValidateUser' => [
        'className' => 'ValidateUser', 'foreignKey' => 'user_id',
    ]];

    /**
     * beforeValidate method.
     */
    public function beforeValidate()
    {
        $this->invalidate('full_name');
        $this->invalidate('city');

        return false;
    }
}

/**
 * ValidateItem class.
 */
class ValidateItem extends CakeTestModel
{
    /**
     * primaryKey property.
     *
     * @var string 'id'
     */
    public $primaryKey = 'id';

    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * name property.
     *
     * @var string 'ValidateItem'
     */
    public $name = 'ValidateItem';

    /**
     * schema property.
     *
     * @var array
     */
    public $_schema = [
        'id' => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8'],
        'profile_id' => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '8'],
        'name' => ['type' => 'text', 'null' => '', 'default' => '', 'length' => '255'],
        'description' => [
            'type' => 'string', 'null' => '', 'default' => '', 'length' => '255',
        ],
        'created' => ['type' => 'date', 'null' => '1', 'default' => '', 'length' => ''],
        'updated' => ['type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null],
    ];

    /**
     * belongsTo property.
     *
     * @var array
     */
    public $belongsTo = ['ValidateProfile' => ['foreignKey' => 'profile_id']];

    /**
     * beforeValidate method.
     */
    public function beforeValidate()
    {
        $this->invalidate('description');

        return false;
    }
}

/**
 * TestMail class.
 */
class TestMail extends CakeTestModel
{
    /**
     * primaryKey property.
     *
     * @var string 'id'
     */
    public $primaryKey = 'id';

    /**
     * useTable property.
     *
     * @var bool false
     */
    public $useTable = false;

    /**
     * name property.
     *
     * @var string 'TestMail'
     */
    public $name = 'TestMail';
}

/**
 * FormHelperTest class.
 */
class FormHelperTest extends CakeTestCase
{
    /**
     * fixtures property.
     *
     * @var array
     */
    public $fixtures = [null];

    /**
     * setUp method.
     */
    public function setUp()
    {
        parent::setUp();
        Router::reload();

        $this->Form = new FormHelper();
        $this->Form->Html = new HtmlHelper();
        $this->Controller = new ContactTestController();
        $this->View = new View($this->Controller);
        $this->Form->params['action'] = 'add';

        ClassRegistry::addObject('view', $view);
        ClassRegistry::addObject('Contact', new Contact());
        ClassRegistry::addObject('ContactNonStandardPk', new ContactNonStandardPk());
        ClassRegistry::addObject('OpenidUrl', new OpenidUrl());
        ClassRegistry::addObject('UserForm', new UserForm());
        ClassRegistry::addObject('ValidateItem', new ValidateItem());
        ClassRegistry::addObject('ValidateUser', new ValidateUser());
        ClassRegistry::addObject('ValidateProfile', new ValidateProfile());

        $this->oldSalt = Configure::read('Security.salt');

        $this->dateRegex = [
            'daysRegex' => 'preg:/(?:<option value="0?([\d]+)">\\1<\/option>[\r\n]*)*/',
            'monthsRegex' => 'preg:/(?:<option value="[\d]+">[\w]+<\/option>[\r\n]*)*/',
            'yearsRegex' => 'preg:/(?:<option value="([\d]+)">\\1<\/option>[\r\n]*)*/',
            'hoursRegex' => 'preg:/(?:<option value="0?([\d]+)">\\1<\/option>[\r\n]*)*/',
            'minutesRegex' => 'preg:/(?:<option value="([\d]+)">0?\\1<\/option>[\r\n]*)*/',
            'meridianRegex' => 'preg:/(?:<option value="(am|pm)">\\1<\/option>[\r\n]*)*/',
        ];

        Configure::write('Security.salt', 'foo!');
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        ClassRegistry::removeObject('view');
        ClassRegistry::removeObject('Contact');
        ClassRegistry::removeObject('ContactNonStandardPk');
        ClassRegistry::removeObject('ContactTag');
        ClassRegistry::removeObject('OpenidUrl');
        ClassRegistry::removeObject('UserForm');
        ClassRegistry::removeObject('ValidateItem');
        ClassRegistry::removeObject('ValidateUser');
        ClassRegistry::removeObject('ValidateProfile');
        unset($this->Form->Html, $this->Form, $this->Controller, $this->View);
        Configure::write('Security.salt', $this->oldSalt);
    }

    /**
     * testFormCreateWithSecurity method.
     *
     * Test form->create() with security key.
     */
    public function testCreateWithSecurity()
    {
        $this->Form->params['_Token'] = ['key' => 'testKey'];
        $encoding = strtolower(Configure::read('App.encoding'));
        $result = $this->Form->create('Contact', ['url' => '/contacts/add']);
        $expected = [
            'form' => ['method' => 'post', 'action' => '/contacts/add', 'accept-charset' => $encoding],
            'div' => ['style' => 'display:none;'],
            ['input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST']],
            ['input' => [
                'type' => 'hidden', 'name' => 'data[_Token][key]', 'value' => 'testKey', 'id',
            ]],
            '/div',
        ];
        $this->assertTags($result, $expected, true);

        $result = $this->Form->create('Contact', ['url' => '/contacts/add', 'id' => 'MyForm']);
        $expected['form']['id'] = 'MyForm';
        $this->assertTags($result, $expected);
    }

    /**
     * test that create() clears the fields property so it starts fresh.
     */
    public function testCreateClearingFields()
    {
        $this->Form->fields = ['model_id'];
        $this->Form->create('Contact');
        $this->assertEqual($this->Form->fields, []);
    }

    /**
     * Tests form hash generation with model-less data.
     */
    public function testValidateHashNoModel()
    {
        $this->Form->params['_Token'] = ['key' => 'foo'];
        $result = $this->Form->secure(['anything']);
        $this->assertPattern('/540ac9c60d323c22bafe997b72c0790f39a8bdef/', $result);
    }

    /**
     * Tests that models with identical field names get resolved properly.
     */
    public function testDuplicateFieldNameResolution()
    {
        $result = $this->Form->create('ValidateUser');
        $this->assertEqual($this->View->entity(), ['ValidateUser']);

        $result = $this->Form->input('ValidateItem.name');
        $this->assertEqual($this->View->entity(), ['ValidateItem', 'name']);

        $result = $this->Form->input('ValidateUser.name');
        $this->assertEqual($this->View->entity(), ['ValidateUser', 'name']);
        $this->assertPattern('/name="data\[ValidateUser\]\[name\]"/', $result);
        $this->assertPattern('/type="text"/', $result);

        $result = $this->Form->input('ValidateItem.name');
        $this->assertEqual($this->View->entity(), ['ValidateItem', 'name']);
        $this->assertPattern('/name="data\[ValidateItem\]\[name\]"/', $result);
        $this->assertPattern('/<textarea/', $result);

        $result = $this->Form->input('name');
        $this->assertEqual($this->View->entity(), ['ValidateUser', 'name']);
        $this->assertPattern('/name="data\[ValidateUser\]\[name\]"/', $result);
        $this->assertPattern('/type="text"/', $result);
    }

    /**
     * Tests that hidden fields generated for checkboxes don't get locked.
     */
    public function testNoCheckboxLocking()
    {
        $this->Form->params['_Token'] = ['key' => 'foo'];
        $this->assertIdentical($this->Form->fields, []);

        $this->Form->checkbox('check', ['value' => '1']);
        $this->assertIdentical($this->Form->fields, ['check']);
    }

    /**
     * testFormSecurityFields method.
     *
     * Test generation of secure form hash generation.
     */
    public function testFormSecurityFields()
    {
        $key = 'testKey';
        $fields = ['Model.password', 'Model.username', 'Model.valid' => '0'];
        $this->Form->params['_Token']['key'] = $key;
        $result = $this->Form->secure($fields);

        $expected = Security::hash(serialize($fields).Configure::read('Security.salt'));
        $expected .= ':'.'Model.valid';

        $expected = [
            'div' => ['style' => 'display:none;'],
            'input' => [
                'type' => 'hidden', 'name' => 'data[_Token][fields]',
                'value' => urlencode($expected), 'id' => 'preg:/TokenFields\d+/',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * Tests correct generation of text fields for double and float fields.
     */
    public function testTextFieldGenerationForFloats()
    {
        $model = ClassRegistry::getObject('Contact');
        $model->setSchema(['foo' => [
            'type' => 'float',
            'null' => false,
            'default' => null,
            'length' => null,
        ]]);

        $this->Form->create('Contact');
        $result = $this->Form->input('foo');
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'ContactFoo'],
            'Foo',
            '/label',
            ['input' => [
                'type' => 'text', 'name' => 'data[Contact][foo]',
                'id' => 'ContactFoo',
            ]],
            '/div',
        ];
    }

    /**
     * testFormSecurityMultipleFields method.
     *
     * Test secure() with multiple row form. Ensure hash is correct.
     */
    public function testFormSecurityMultipleFields()
    {
        $key = 'testKey';

        $fields = [
            'Model.0.password', 'Model.0.username', 'Model.0.hidden' => 'value',
            'Model.0.valid' => '0', 'Model.1.password', 'Model.1.username',
            'Model.1.hidden' => 'value', 'Model.1.valid' => '0',
        ];
        $this->Form->params['_Token']['key'] = $key;
        $result = $this->Form->secure($fields);

        $hash = '51e3b55a6edd82020b3f29c9ae200e14bbeb7ee5%3AModel.0.hidden%7CModel.0.valid';
        $hash .= '%7CModel.1.hidden%7CModel.1.valid';

        $expected = [
            'div' => ['style' => 'display:none;'],
            'input' => [
                'type' => 'hidden', 'name' => 'data[_Token][fields]',
                'value' => $hash, 'id' => 'preg:/TokenFields\d+/',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testFormSecurityMultipleSubmitButtons.
     *
     * test form submit generation and ensure that _Token is only created on end()
     */
    public function testFormSecurityMultipleSubmitButtons()
    {
        $key = 'testKey';
        $this->Form->params['_Token']['key'] = $key;

        $this->Form->create('Addresses');
        $this->Form->input('Address.title');
        $this->Form->input('Address.first_name');

        $result = $this->Form->submit('Save', ['name' => 'save']);
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'name' => 'save', 'value' => 'Save'],
            '/div',
        ];
        $this->assertTags($result, $expected);
        $result = $this->Form->submit('Cancel', ['name' => 'cancel']);
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'name' => 'cancel', 'value' => 'Cancel'],
            '/div',
        ];
        $this->assertTags($result, $expected);
        $result = $this->Form->end(null);

        $expected = [
            'div' => ['style' => 'display:none;'],
            'input' => [
                'type' => 'hidden', 'name' => 'data[_Token][fields]',
                'value' => 'preg:/.+/', 'id' => 'preg:/TokenFields\d+/',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testFormSecurityMultipleInputFields method.
     *
     * Test secure form creation with multiple row creation.  Checks hidden, text, checkbox field types
     */
    public function testFormSecurityMultipleInputFields()
    {
        $key = 'testKey';

        $this->Form->params['_Token']['key'] = $key;
        $this->Form->create('Addresses');

        $this->Form->hidden('Addresses.0.id', ['value' => '123456']);
        $this->Form->input('Addresses.0.title');
        $this->Form->input('Addresses.0.first_name');
        $this->Form->input('Addresses.0.last_name');
        $this->Form->input('Addresses.0.address');
        $this->Form->input('Addresses.0.city');
        $this->Form->input('Addresses.0.phone');
        $this->Form->input('Addresses.0.primary', ['type' => 'checkbox']);

        $this->Form->hidden('Addresses.1.id', ['value' => '654321']);
        $this->Form->input('Addresses.1.title');
        $this->Form->input('Addresses.1.first_name');
        $this->Form->input('Addresses.1.last_name');
        $this->Form->input('Addresses.1.address');
        $this->Form->input('Addresses.1.city');
        $this->Form->input('Addresses.1.phone');
        $this->Form->input('Addresses.1.primary', ['type' => 'checkbox']);

        $result = $this->Form->secure($this->Form->fields);

        $hash = 'f88bdc351fa388569210cf55ba6b8879763fca13%3AAddresses.0.id%7CAddresses.1.id';

        $expected = [
            'div' => ['style' => 'display:none;'],
            'input' => [
                'type' => 'hidden', 'name' => 'data[_Token][fields]',
                'value' => $hash, 'id' => 'preg:/TokenFields\d+/',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * Test form security with Model.field.0 style inputs.
     */
    public function testFormSecurityArrayFields()
    {
        $key = 'testKey';

        $this->Form->params['_Token']['key'] = $key;
        $this->Form->create('Address');
        $this->Form->input('Address.primary.1');
        $this->assertEqual('Address.primary', $this->Form->fields[0]);
    }

    /**
     * Test form security hash generation with relative urls.
     */
    public function testFormSecurityRelativeUrl()
    {
        $key = 'testKey';
        $this->Form->params['_Token']['key'] = $key;

        $expected = Security::hash(
            '/posts/edit/type:5'.
            serialize([]).
            Configure::read('Security.salt')
        );
        $this->Form->create('Post', [
            'url' => ['controller' => 'posts', 'action' => 'edit', 'type' => 5],
        ]);
        $result = $this->Form->secure($this->Form->fields);
        $this->assertTrue(false !== strpos($result, $expected));
    }

    /**
     * testFormSecurityMultipleInputDisabledFields method.
     *
     * test secure form generation with multiple records and disabled fields.
     */
    public function testFormSecurityMultipleInputDisabledFields()
    {
        $key = 'testKey';
        $this->Form->params['_Token']['key'] = $key;
        $this->Form->params['_Token']['disabledFields'] = ['first_name', 'address'];
        $this->Form->create();

        $this->Form->hidden('Addresses.0.id', ['value' => '123456']);
        $this->Form->input('Addresses.0.title');
        $this->Form->input('Addresses.0.first_name');
        $this->Form->input('Addresses.0.last_name');
        $this->Form->input('Addresses.0.address');
        $this->Form->input('Addresses.0.city');
        $this->Form->input('Addresses.0.phone');
        $this->Form->hidden('Addresses.1.id', ['value' => '654321']);
        $this->Form->input('Addresses.1.title');
        $this->Form->input('Addresses.1.first_name');
        $this->Form->input('Addresses.1.last_name');
        $this->Form->input('Addresses.1.address');
        $this->Form->input('Addresses.1.city');
        $this->Form->input('Addresses.1.phone');

        $result = $this->Form->secure($this->Form->fields);
        $hash = 'cdf15a1cf5192aaa25a2ce85957e9f27c0a1a006%3AAddresses.0.id%7CAddresses.1.id';

        $expected = [
            'div' => ['style' => 'display:none;'],
            'input' => [
                'type' => 'hidden', 'name' => 'data[_Token][fields]',
                'value' => $hash, 'id' => 'preg:/TokenFields\d+/',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testFormSecurityInputDisabledFields method.
     *
     * Test single record form with disabled fields.
     */
    public function testFormSecurityInputDisabledFields()
    {
        $key = 'testKey';
        $this->Form->params['_Token']['key'] = $key;
        $this->Form->params['_Token']['disabledFields'] = ['first_name', 'address'];
        $this->Form->create();

        $this->Form->hidden('Addresses.id', ['value' => '123456']);
        $this->Form->input('Addresses.title');
        $this->Form->input('Addresses.first_name');
        $this->Form->input('Addresses.last_name');
        $this->Form->input('Addresses.address');
        $this->Form->input('Addresses.city');
        $this->Form->input('Addresses.phone');

        $result = $this->Form->fields;
        $expected = [
            'Addresses.id' => '123456', 'Addresses.title', 'Addresses.last_name',
            'Addresses.city', 'Addresses.phone',
        ];
        $this->assertEqual($result, $expected);

        $result = $this->Form->secure($expected);

        $hash = 'baf05f9b1087725d8adf49a847c3a9174b2df7bf%3AAddresses.id';
        $expected = [
            'div' => ['style' => 'display:none;'],
            'input' => [
                'type' => 'hidden', 'name' => 'data[_Token][fields]',
                'value' => $hash, 'id' => 'preg:/TokenFields\d+/',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test securing inputs with custom name attributes.
     */
    public function testFormSecureWithCustomNameAttribute()
    {
        $this->Form->params['_Token']['key'] = 'testKey';

        $this->Form->text('UserForm.published', ['name' => 'data[User][custom]']);
        $this->assertEqual('User.custom', $this->Form->fields[0]);

        $this->Form->text('UserForm.published', ['name' => 'data[User][custom][another][value]']);
        $this->assertEqual('User.custom.another.value', $this->Form->fields[1]);
    }

    /**
     * testFormSecuredInput method.
     *
     * Test generation of entire secure form, assertions made on input() output.
     */
    public function testFormSecuredInput()
    {
        $this->Form->params['_Token']['key'] = 'testKey';

        $result = $this->Form->create('Contact', ['url' => '/contacts/add']);
        $encoding = strtolower(Configure::read('App.encoding'));
        $expected = [
            'form' => ['method' => 'post', 'action' => '/contacts/add', 'accept-charset' => $encoding],
            'div' => ['style' => 'display:none;'],
            ['input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST']],
            ['input' => [
                'type' => 'hidden', 'name' => 'data[_Token][key]',
                'value' => 'testKey', 'id' => 'preg:/Token\d+/',
            ]],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('UserForm.published', ['type' => 'text']);
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'UserFormPublished'],
            'Published',
            '/label',
            ['input' => [
                'type' => 'text', 'name' => 'data[UserForm][published]',
                'id' => 'UserFormPublished',
            ]],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('UserForm.other', ['type' => 'text']);
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'UserFormOther'],
            'Other',
            '/label',
            ['input' => [
                'type' => 'text', 'name' => 'data[UserForm][other]',
                'id' => 'UserFormOther',
            ]],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->hidden('UserForm.stuff');
        $expected = ['input' => [
                'type' => 'hidden', 'name' => 'data[UserForm][stuff]',
                'id' => 'UserFormStuff',
        ]];
        $this->assertTags($result, $expected);

        $result = $this->Form->hidden('UserForm.hidden', ['value' => '0']);
        $expected = ['input' => [
            'type' => 'hidden', 'name' => 'data[UserForm][hidden]',
            'value' => '0', 'id' => 'UserFormHidden',
        ]];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('UserForm.something', ['type' => 'checkbox']);
        $expected = [
            'div' => ['class' => 'input checkbox'],
            ['input' => [
                'type' => 'hidden', 'name' => 'data[UserForm][something]',
                'value' => '0', 'id' => 'UserFormSomething_',
            ]],
            ['input' => [
                'type' => 'checkbox', 'name' => 'data[UserForm][something]',
                'value' => '1', 'id' => 'UserFormSomething',
            ]],
            'label' => ['for' => 'UserFormSomething'],
            'Something',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->fields;
        $expected = [
            'UserForm.published', 'UserForm.other', 'UserForm.stuff' => '',
            'UserForm.hidden' => '0', 'UserForm.something',
        ];
        $this->assertEqual($result, $expected);

        $hash = '6014b4e1c4f39eb62389712111dbe6435bec66cb%3AUserForm.hidden%7CUserForm.stuff';
        $result = $this->Form->secure($this->Form->fields);
        $expected = [
            'div' => ['style' => 'display:none;'],
            ['input' => [
                'type' => 'hidden', 'name' => 'data[_Token][fields]',
                'value' => $hash, 'id' => 'preg:/TokenFields\d+/',
            ]],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * Tests that the correct keys are added to the field hash index.
     */
    public function testFormSecuredFileInput()
    {
        $this->Form->params['_Token']['key'] = 'testKey';
        $this->assertEqual($this->Form->fields, []);

        $result = $this->Form->file('Attachment.file');
        $expected = [
            'Attachment.file.name', 'Attachment.file.type', 'Attachment.file.tmp_name',
            'Attachment.file.error', 'Attachment.file.size',
        ];
        $this->assertEqual($this->Form->fields, $expected);
    }

    /**
     * test that multiple selects keys are added to field hash.
     */
    public function testFormSecuredMultipleSelect()
    {
        $this->Form->params['_Token']['key'] = 'testKey';
        $this->assertEqual($this->Form->fields, []);
        $options = ['1' => 'one', '2' => 'two'];

        $this->Form->select('Model.select', $options);
        $expected = ['Model.select'];
        $this->assertEqual($this->Form->fields, $expected);

        $this->Form->fields = [];
        $this->Form->select('Model.select', $options, null, ['multiple' => true]);
        $this->assertEqual($this->Form->fields, $expected);
    }

    /**
     * testFormSecuredRadio method.
     */
    public function testFormSecuredRadio()
    {
        $this->Form->params['_Token']['key'] = 'testKey';
        $this->assertEqual($this->Form->fields, []);
        $options = ['1' => 'option1', '2' => 'option2'];

        $this->Form->radio('Test.test', $options);
        $expected = ['Test.test'];
        $this->assertEqual($this->Form->fields, $expected);
    }

    /**
     * testDisableSecurityUsingForm method.
     */
    public function testDisableSecurityUsingForm()
    {
        $this->Form->params['_Token']['key'] = 'testKey';
        $this->Form->params['_Token']['disabledFields'] = [];
        $this->Form->create();

        $this->Form->hidden('Addresses.id', ['value' => '123456']);
        $this->Form->input('Addresses.title');
        $this->Form->input('Addresses.first_name', ['secure' => false]);
        $this->Form->input('Addresses.city', ['type' => 'textarea', 'secure' => false]);
        $this->Form->input('Addresses.zip', [
            'type' => 'select', 'options' => [1, 2], 'secure' => false,
        ]);

        $result = $this->Form->fields;
        $expected = [
            'Addresses.id' => '123456', 'Addresses.title',
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testPasswordValidation method.
     *
     * test validation errors on password input.
     */
    public function testPasswordValidation()
    {
        $this->Form->validationErrors['Contact']['password'] = 'Please provide a password';
        $result = $this->Form->input('Contact.password');
        $expected = [
            'div' => ['class' => 'input password error'],
            'label' => ['for' => 'ContactPassword'],
            'Password',
            '/label',
            'input' => [
                'type' => 'password', 'name' => 'data[Contact][password]',
                'id' => 'ContactPassword', 'class' => 'form-error',
            ],
            ['div' => ['class' => 'error-message']],
            'Please provide a password',
            '/div',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testEmptyErrorValidation method.
     *
     * test validation error div when validation message is an empty string
     */
    public function testEmptyErrorValidation()
    {
        $this->Form->validationErrors['Contact']['password'] = '';
        $result = $this->Form->input('Contact.password');
        $expected = [
            'div' => ['class' => 'input password error'],
            'label' => ['for' => 'ContactPassword'],
            'Password',
            '/label',
            'input' => [
                'type' => 'password', 'name' => 'data[Contact][password]',
                'id' => 'ContactPassword', 'class' => 'form-error',
            ],
            ['div' => ['class' => 'error-message']],
            [],
            '/div',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testEmptyInputErrorValidation method.
     *
     * test validation error div when validation message is overriden by an empty string when calling input()
     */
    public function testEmptyInputErrorValidation()
    {
        $this->Form->validationErrors['Contact']['password'] = 'Please provide a password';
        $result = $this->Form->input('Contact.password', ['error' => '']);
        $expected = [
            'div' => ['class' => 'input password error'],
            'label' => ['for' => 'ContactPassword'],
            'Password',
            '/label',
            'input' => [
                'type' => 'password', 'name' => 'data[Contact][password]',
                'id' => 'ContactPassword', 'class' => 'form-error',
            ],
            ['div' => ['class' => 'error-message']],
            [],
            '/div',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testFormValidationAssociated method.
     *
     * test display of form errors in conjunction with model::validates.
     */
    public function testFormValidationAssociated()
    {
        $this->UserForm = &ClassRegistry::getObject('UserForm');
        $this->UserForm->OpenidUrl = &ClassRegistry::getObject('OpenidUrl');

        $data = [
            'UserForm' => ['name' => 'user'],
            'OpenidUrl' => ['url' => 'http://www.cakephp.org'],
        ];

        $this->assertTrue($this->UserForm->OpenidUrl->create($data));
        $this->assertFalse($this->UserForm->OpenidUrl->validates());

        $result = $this->Form->create('UserForm', ['type' => 'post', 'action' => 'login']);
        $encoding = strtolower(Configure::read('App.encoding'));
        $expected = [
            'form' => [
                'method' => 'post', 'action' => '/user_forms/login', 'id' => 'UserFormLoginForm',
                'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $expected = ['OpenidUrl' => ['openid_not_registered' => 1]];
        $this->assertEqual($this->Form->validationErrors, $expected);

        $result = $this->Form->error(
            'OpenidUrl.openid_not_registered', 'Error, not registered', ['wrap' => false]
        );
        $this->assertEqual($result, 'Error, not registered');

        unset($this->UserForm->OpenidUrl, $this->UserForm);
    }

    /**
     * testFormValidationAssociatedFirstLevel method.
     *
     * test form error display with associated model.
     */
    public function testFormValidationAssociatedFirstLevel()
    {
        $this->ValidateUser = &ClassRegistry::getObject('ValidateUser');
        $this->ValidateUser->ValidateProfile = &ClassRegistry::getObject('ValidateProfile');

        $data = [
            'ValidateUser' => ['name' => 'mariano'],
            'ValidateProfile' => ['full_name' => 'Mariano Iglesias'],
        ];

        $this->assertTrue($this->ValidateUser->create($data));
        $this->assertFalse($this->ValidateUser->validates());
        $this->assertFalse($this->ValidateUser->ValidateProfile->validates());

        $result = $this->Form->create('ValidateUser', ['type' => 'post', 'action' => 'add']);
        $encoding = strtolower(Configure::read('App.encoding'));
        $expected = [
            'form' => ['method' => 'post', 'action' => '/validate_users/add', 'id', 'accept-charset' => $encoding],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $expected = [
            'ValidateUser' => ['email' => 1],
            'ValidateProfile' => ['full_name' => 1, 'city' => 1],
        ];
        $this->assertEqual($this->Form->validationErrors, $expected);

        unset($this->ValidateUser->ValidateProfile);
        unset($this->ValidateUser);
    }

    /**
     * testFormValidationAssociatedSecondLevel method.
     *
     * test form error display with associated model.
     */
    public function testFormValidationAssociatedSecondLevel()
    {
        $this->ValidateUser = &ClassRegistry::getObject('ValidateUser');
        $this->ValidateUser->ValidateProfile = &ClassRegistry::getObject('ValidateProfile');
        $this->ValidateUser->ValidateProfile->ValidateItem = &ClassRegistry::getObject('ValidateItem');

        $data = [
            'ValidateUser' => ['name' => 'mariano'],
            'ValidateProfile' => ['full_name' => 'Mariano Iglesias'],
            'ValidateItem' => ['name' => 'Item'],
        ];

        $this->assertTrue($this->ValidateUser->create($data));
        $this->assertFalse($this->ValidateUser->validates());
        $this->assertFalse($this->ValidateUser->ValidateProfile->validates());
        $this->assertFalse($this->ValidateUser->ValidateProfile->ValidateItem->validates());

        $result = $this->Form->create('ValidateUser', ['type' => 'post', 'action' => 'add']);
        $encoding = strtolower(Configure::read('App.encoding'));
        $expected = [
            'form' => ['method' => 'post', 'action' => '/validate_users/add', 'id', 'accept-charset' => $encoding],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $expected = [
            'ValidateUser' => ['email' => 1],
            'ValidateProfile' => ['full_name' => 1, 'city' => 1],
            'ValidateItem' => ['description' => 1],
        ];
        $this->assertEqual($this->Form->validationErrors, $expected);

        unset($this->ValidateUser->ValidateProfile->ValidateItem);
        unset($this->ValidateUser->ValidateProfile);
        unset($this->ValidateUser);
    }

    /**
     * testFormValidationMultiRecord method.
     *
     * test form error display with multiple records.
     */
    public function testFormValidationMultiRecord()
    {
        $this->Form->validationErrors['Contact'] = [2 => [
            'name' => 'This field cannot be left blank',
        ]];
        $result = $this->Form->input('Contact.2.name');
        $expected = [
            'div' => ['class'],
            'label' => ['for'],
            'preg:/[^<]+/',
            '/label',
            'input' => [
                'type' => 'text', 'name', 'id',
                'class' => 'form-error', 'maxlength' => 255,
            ],
            ['div' => ['class' => 'error-message']],
            'This field cannot be left blank',
            '/div',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->validationErrors['UserForm'] = [
            'OpenidUrl' => ['url' => 'You must provide a URL',
        ], ];
        $this->Form->create('UserForm');
        $result = $this->Form->input('OpenidUrl.url');
        $expected = [
            'div' => ['class'],
            'label' => ['for'],
            'preg:/[^<]+/',
            '/label',
            'input' => [
                'type' => 'text', 'name', 'id', 'class' => 'form-error',
            ],
            ['div' => ['class' => 'error-message']],
            'You must provide a URL',
            '/div',
            '/div',
        ];
    }

    /**
     * testMultipleInputValidation method.
     *
     * test multiple record form validation error display.
     */
    public function testMultipleInputValidation()
    {
        $this->Form->create();
        $this->Form->validationErrors['Address'][0]['title'] = 'This field cannot be empty';
        $this->Form->validationErrors['Address'][0]['first_name'] = 'This field cannot be empty';
        $this->Form->validationErrors['Address'][1]['last_name'] = 'You must have a last name';

        $result = $this->Form->input('Address.0.title');
        $expected = [
            'div' => ['class'],
            'label' => ['for'],
            'preg:/[^<]+/',
            '/label',
            'input' => [
                'type' => 'text', 'name', 'id', 'class' => 'form-error',
            ],
            ['div' => ['class' => 'error-message']],
            'This field cannot be empty',
            '/div',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Address.0.first_name');
        $expected = [
            'div' => ['class'],
            'label' => ['for'],
            'preg:/[^<]+/',
            '/label',
            'input' => ['type' => 'text', 'name', 'id', 'class' => 'form-error'],
            ['div' => ['class' => 'error-message']],
            'This field cannot be empty',
            '/div',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Address.0.last_name');
        $expected = [
            'div' => ['class'],
            'label' => ['for'],
            'preg:/[^<]+/',
            '/label',
            'input' => ['type' => 'text', 'name', 'id'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Address.1.last_name');
        $expected = [
            'div' => ['class'],
            'label' => ['for'],
            'preg:/[^<]+/',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'preg:/[^<]+/',
                'id' => 'preg:/[^<]+/', 'class' => 'form-error',
            ],
            ['div' => ['class' => 'error-message']],
            'You must have a last name',
            '/div',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testInput method.
     *
     * Test various incarnations of input().
     */
    public function testInput()
    {
        $result = $this->Form->input('ValidateUser.balance');
        $expected = [
            'div' => ['class'],
            'label' => ['for'],
            'Balance',
            '/label',
            'input' => ['name', 'type' => 'text', 'maxlength' => 8, 'id'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.email', ['id' => 'custom']);
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'custom'],
            'Email',
            '/label',
            ['input' => [
                'type' => 'text', 'name' => 'data[Contact][email]',
                'id' => 'custom', 'maxlength' => 255,
            ]],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.email', ['div' => ['class' => false]]);
        $expected = [
            '<div',
            'label' => ['for' => 'ContactEmail'],
            'Email',
            '/label',
            ['input' => [
                'type' => 'text', 'name' => 'data[Contact][email]',
                'id' => 'ContactEmail', 'maxlength' => 255,
            ]],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->hidden('Contact.idontexist');
        $expected = ['input' => [
                'type' => 'hidden', 'name' => 'data[Contact][idontexist]',
                'id' => 'ContactIdontexist',
        ]];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.email', ['type' => 'text']);
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'ContactEmail'],
            'Email',
            '/label',
            ['input' => [
                'type' => 'text', 'name' => 'data[Contact][email]',
                'id' => 'ContactEmail',
            ]],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.5.email', ['type' => 'text']);
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'Contact5Email'],
            'Email',
            '/label',
            ['input' => [
                'type' => 'text', 'name' => 'data[Contact][5][email]',
                'id' => 'Contact5Email',
            ]],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.password');
        $expected = [
            'div' => ['class' => 'input password'],
            'label' => ['for' => 'ContactPassword'],
            'Password',
            '/label',
            ['input' => [
                'type' => 'password', 'name' => 'data[Contact][password]',
                'id' => 'ContactPassword',
            ]],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.email', [
            'type' => 'file', 'class' => 'textbox',
        ]);
        $expected = [
            'div' => ['class' => 'input file'],
            'label' => ['for' => 'ContactEmail'],
            'Email',
            '/label',
            ['input' => [
                'type' => 'file', 'name' => 'data[Contact][email]', 'class' => 'textbox',
                'id' => 'ContactEmail',
            ]],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = ['Contact' => ['phone' => 'Hello & World > weird chars']];
        $result = $this->Form->input('Contact.phone');
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'ContactPhone'],
            'Phone',
            '/label',
            ['input' => [
                'type' => 'text', 'name' => 'data[Contact][phone]',
                'value' => 'Hello &amp; World &gt; weird chars',
                'id' => 'ContactPhone', 'maxlength' => 255,
            ]],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Model']['0']['OtherModel']['field'] = 'My value';
        $result = $this->Form->input('Model.0.OtherModel.field', ['id' => 'myId']);
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'myId'],
            'Field',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Model][0][OtherModel][field]',
                'value' => 'My value', 'id' => 'myId',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        unset($this->Form->data);

        $this->Form->validationErrors['Model']['field'] = 'Badness!';
        $result = $this->Form->input('Model.field');
        $expected = [
            'div' => ['class' => 'input text error'],
            'label' => ['for' => 'ModelField'],
            'Field',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Model][field]',
                'id' => 'ModelField', 'class' => 'form-error',
            ],
            ['div' => ['class' => 'error-message']],
            'Badness!',
            '/div',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Model.field', [
            'div' => false, 'error' => ['wrap' => 'span'],
        ]);
        $expected = [
            'label' => ['for' => 'ModelField'],
            'Field',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Model][field]',
                'id' => 'ModelField', 'class' => 'form-error',
            ],
            ['span' => ['class' => 'error-message']],
            'Badness!',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Model.field', [
            'div' => ['tag' => 'span'], 'error' => ['wrap' => false],
        ]);
        $expected = [
            'span' => ['class' => 'input text error'],
            'label' => ['for' => 'ModelField'],
            'Field',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Model][field]',
                'id' => 'ModelField', 'class' => 'form-error',
            ],
            'Badness!',
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Model.field', ['after' => 'A message to you, Rudy']);
        $expected = [
            'div' => ['class' => 'input text error'],
            'label' => ['for' => 'ModelField'],
            'Field',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Model][field]',
                'id' => 'ModelField', 'class' => 'form-error',
            ],
            'A message to you, Rudy',
            ['div' => ['class' => 'error-message']],
            'Badness!',
            '/div',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->setEntity(null);
        $this->Form->setEntity('Model.field');
        $result = $this->Form->input('Model.field', [
            'after' => 'A message to you, Rudy', 'error' => false,
        ]);
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'ModelField'],
            'Field',
            '/label',
            'input' => ['type' => 'text', 'name' => 'data[Model][field]', 'id' => 'ModelField', 'class' => 'form-error'],
            'A message to you, Rudy',
            '/div',
        ];
        $this->assertTags($result, $expected);

        unset($this->Form->validationErrors['Model']['field']);
        $result = $this->Form->input('Model.field', ['after' => 'A message to you, Rudy']);
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'ModelField'],
            'Field',
            '/label',
            'input' => ['type' => 'text', 'name' => 'data[Model][field]', 'id' => 'ModelField'],
            'A message to you, Rudy',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->validationErrors['Model']['field'] = 'minLength';
        $result = $this->Form->input('Model.field', [
            'error' => [
                'minLength' => 'Le login doit contenir au moins 2 caractères',
                'maxLength' => 'login too large',
            ],
        ]);
        $expected = [
            'div' => ['class' => 'input text error'],
            'label' => ['for' => 'ModelField'],
            'Field',
            '/label',
            'input' => ['type' => 'text', 'name' => 'data[Model][field]', 'id' => 'ModelField', 'class' => 'form-error'],
            ['div' => ['class' => 'error-message']],
            'Le login doit contenir au moins 2 caractères',
            '/div',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->validationErrors['Model']['field'] = 'maxLength';
        $result = $this->Form->input('Model.field', [
            'error' => [
                'wrap' => 'span',
                'attributes' => ['rel' => 'fake'],
                'minLength' => 'Le login doit contenir au moins 2 caractères',
                'maxLength' => 'login too large',
            ],
        ]);
        $expected = [
            'div' => ['class' => 'input text error'],
            'label' => ['for' => 'ModelField'],
            'Field',
            '/label',
            'input' => ['type' => 'text', 'name' => 'data[Model][field]', 'id' => 'ModelField', 'class' => 'form-error'],
            ['span' => ['class' => 'error-message', 'rel' => 'fake']],
            'login too large',
            '/span',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test input() with checkbox creation.
     */
    public function testInputCheckbox()
    {
        $result = $this->Form->input('User.active', ['label' => false, 'checked' => true]);
        $expected = [
            'div' => ['class' => 'input checkbox'],
            'input' => ['type' => 'hidden', 'name' => 'data[User][active]', 'value' => '0', 'id' => 'UserActive_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[User][active]', 'value' => '1', 'id' => 'UserActive', 'checked' => 'checked']],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('User.active', ['label' => false, 'checked' => 1]);
        $expected = [
            'div' => ['class' => 'input checkbox'],
            'input' => ['type' => 'hidden', 'name' => 'data[User][active]', 'value' => '0', 'id' => 'UserActive_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[User][active]', 'value' => '1', 'id' => 'UserActive', 'checked' => 'checked']],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('User.active', ['label' => false, 'checked' => '1']);
        $expected = [
            'div' => ['class' => 'input checkbox'],
            'input' => ['type' => 'hidden', 'name' => 'data[User][active]', 'value' => '0', 'id' => 'UserActive_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[User][active]', 'value' => '1', 'id' => 'UserActive', 'checked' => 'checked']],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test form->input() with time types.
     */
    public function testInputTime()
    {
        extract($this->dateRegex);
        $result = $this->Form->input('Contact.created', ['type' => 'time', 'timeFormat' => 24]);
        $result = explode(':', $result);
        $this->assertPattern('/option value="23"/', $result[0]);
        $this->assertNoPattern('/option value="24"/', $result[0]);

        $result = $this->Form->input('Contact.created', ['type' => 'time', 'timeFormat' => 24]);
        $result = explode(':', $result);
        $this->assertPattern('/option value="23"/', $result[0]);
        $this->assertNoPattern('/option value="24"/', $result[0]);

        $result = $this->Form->input('Model.field', [
            'type' => 'time', 'timeFormat' => 24, 'interval' => 15,
        ]);
        $result = explode(':', $result);
        $this->assertNoPattern('#<option value="12"[^>]*>12</option>#', $result[1]);
        $this->assertNoPattern('#<option value="50"[^>]*>50</option>#', $result[1]);
        $this->assertPattern('#<option value="15"[^>]*>15</option>#', $result[1]);

        $result = $this->Form->input('Model.field', [
            'type' => 'time', 'timeFormat' => 12, 'interval' => 15,
        ]);
        $result = explode(':', $result);
        $this->assertNoPattern('#<option value="12"[^>]*>12</option>#', $result[1]);
        $this->assertNoPattern('#<option value="50"[^>]*>50</option>#', $result[1]);
        $this->assertPattern('#<option value="15"[^>]*>15</option>#', $result[1]);

        $result = $this->Form->input('prueba', [
            'type' => 'time', 'timeFormat' => 24, 'dateFormat' => 'DMY', 'minYear' => 2008,
            'maxYear' => date('Y') + 1, 'interval' => 15,
        ]);
        $result = explode(':', $result);
        $this->assertNoPattern('#<option value="12"[^>]*>12</option>#', $result[1]);
        $this->assertNoPattern('#<option value="50"[^>]*>50</option>#', $result[1]);
        $this->assertPattern('#<option value="15"[^>]*>15</option>#', $result[1]);
        $this->assertPattern('#<option value="30"[^>]*>30</option>#', $result[1]);

        $result = $this->Form->input('Random.start_time', [
            'type' => 'time',
            'selected' => '18:15',
        ]);
        $this->assertPattern('#<option value="06"[^>]*>6</option>#', $result);
        $this->assertPattern('#<option value="15"[^>]*>15</option>#', $result);
        $this->assertPattern('#<option value="pm"[^>]*>pm</option>#', $result);
    }

    /**
     * test form->input() with datetime, date and time types.
     */
    public function testInputDatetime()
    {
        extract($this->dateRegex);
        $result = $this->Form->input('prueba', [
            'type' => 'datetime', 'timeFormat' => 24, 'dateFormat' => 'DMY', 'minYear' => 2008,
            'maxYear' => date('Y') + 1, 'interval' => 15,
        ]);
        $result = explode(':', $result);
        $this->assertNoPattern('#<option value="12"[^>]*>12</option>#', $result[1]);
        $this->assertNoPattern('#<option value="50"[^>]*>50</option>#', $result[1]);
        $this->assertPattern('#<option value="15"[^>]*>15</option>#', $result[1]);
        $this->assertPattern('#<option value="30"[^>]*>30</option>#', $result[1]);

        //related to ticket #5013
        $result = $this->Form->input('Contact.date', [
            'type' => 'date', 'class' => 'customClass', 'onChange' => 'function(){}',
        ]);
        $this->assertPattern('/class="customClass"/', $result);
        $this->assertPattern('/onChange="function\(\)\{\}"/', $result);

        $result = $this->Form->input('Contact.date', [
            'type' => 'date', 'id' => 'customId', 'onChange' => 'function(){}',
        ]);
        $this->assertPattern('/id="customIdDay"/', $result);
        $this->assertPattern('/id="customIdMonth"/', $result);
        $this->assertPattern('/onChange="function\(\)\{\}"/', $result);

        $result = $this->Form->input('Model.field', [
            'type' => 'datetime', 'timeFormat' => 24, 'id' => 'customID',
        ]);
        $this->assertPattern('/id="customIDDay"/', $result);
        $this->assertPattern('/id="customIDHour"/', $result);
        $result = explode('</select><select', $result);
        $result = explode(':', $result[1]);
        $this->assertPattern('/option value="23"/', $result[0]);
        $this->assertNoPattern('/option value="24"/', $result[0]);

        $result = $this->Form->input('Model.field', [
            'type' => 'datetime', 'timeFormat' => 12,
        ]);
        $result = explode('</select><select', $result);
        $result = explode(':', $result[1]);
        $this->assertPattern('/option value="12"/', $result[0]);
        $this->assertNoPattern('/option value="13"/', $result[0]);

        $this->Form->data = ['Contact' => ['created' => null]];
        $result = $this->Form->input('Contact.created', ['empty' => 'Date Unknown']);
        $expected = [
            'div' => ['class' => 'input date'],
            'label' => ['for' => 'ContactCreatedMonth'],
            'Created',
            '/label',
            ['select' => ['name' => 'data[Contact][created][month]', 'id' => 'ContactCreatedMonth']],
            ['option' => ['value' => '']], 'Date Unknown', '/option',
            $monthsRegex,
            '/select', '-',
            ['select' => ['name' => 'data[Contact][created][day]', 'id' => 'ContactCreatedDay']],
            ['option' => ['value' => '']], 'Date Unknown', '/option',
            $daysRegex,
            '/select', '-',
            ['select' => ['name' => 'data[Contact][created][year]', 'id' => 'ContactCreatedYear']],
            ['option' => ['value' => '']], 'Date Unknown', '/option',
            $yearsRegex,
            '/select',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = ['Contact' => ['created' => null]];
        $result = $this->Form->input('Contact.created', ['type' => 'datetime', 'dateFormat' => 'NONE']);
        $this->assertPattern('/for\="ContactCreatedHour"/', $result);

        $this->Form->data = ['Contact' => ['created' => null]];
        $result = $this->Form->input('Contact.created', ['type' => 'datetime', 'timeFormat' => 'NONE']);
        $this->assertPattern('/for\="ContactCreatedMonth"/', $result);

        $result = $this->Form->input('Contact.created', [
            'type' => 'date',
            'id' => ['day' => 'created-day', 'month' => 'created-month', 'year' => 'created-year'],
            'timeFormat' => 'NONE',
        ]);
        $this->assertPattern('/for\="created-month"/', $result);
    }

    /**
     * Test generating checkboxes in a loop.
     */
    public function testInputCheckboxesInLoop()
    {
        for ($i = 1; $i < 5; ++$i) {
            $result = $this->Form->input("Contact.{$i}.email", ['type' => 'checkbox', 'value' => $i]);
            $expected = [
                'div' => ['class' => 'input checkbox'],
                'input' => ['type' => 'hidden', 'name' => "data[Contact][{$i}][email]", 'value' => '0', 'id' => "Contact{$i}Email_"],
                ['input' => ['type' => 'checkbox', 'name' => "data[Contact][{$i}][email]", 'value' => $i, 'id' => "Contact{$i}Email"]],
                'label' => ['for' => "Contact{$i}Email"],
                'Email',
                '/label',
                '/div',
            ];
            $this->assertTags($result, $expected);
        }
    }

    /**
     * test input name with leading integer, ensure attributes are generated correctly.
     */
    public function testInputWithLeadingInteger()
    {
        $result = $this->Form->text('0.Node.title');
        $expected = [
            'input' => ['name' => 'data[0][Node][title]', 'id' => '0NodeTitle', 'type' => 'text'],
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test form->input() with select type inputs.
     */
    public function testInputSelectType()
    {
        $result = $this->Form->input('email', [
            'options' => ['è' => 'Firést', 'é' => 'Secoènd'], 'empty' => true, ]
        );
        $expected = [
            'div' => ['class' => 'input select'],
            'label' => ['for' => 'email'],
            'Email',
            '/label',
            ['select' => ['name' => 'data[email]', 'id' => 'email']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => 'è']],
            'Firést',
            '/option',
            ['option' => ['value' => 'é']],
            'Secoènd',
            '/option',
            '/select',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('email', [
            'options' => ['First', 'Second'], 'empty' => true, ]
        );
        $expected = [
            'div' => ['class' => 'input select'],
            'label' => ['for' => 'email'],
            'Email',
            '/label',
            ['select' => ['name' => 'data[email]', 'id' => 'email']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '0']],
            'First',
            '/option',
            ['option' => ['value' => '1']],
            'Second',
            '/option',
            '/select',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = ['Model' => ['user_id' => 'value']];
        $view = &ClassRegistry::getObject('view');
        $view->viewVars['users'] = ['value' => 'good', 'other' => 'bad'];
        $result = $this->Form->input('Model.user_id', ['empty' => true]);
        $expected = [
            'div' => ['class' => 'input select'],
            'label' => ['for' => 'ModelUserId'],
            'User',
            '/label',
            'select' => ['name' => 'data[Model][user_id]', 'id' => 'ModelUserId'],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => 'value', 'selected' => 'selected']],
            'good',
            '/option',
            ['option' => ['value' => 'other']],
            'bad',
            '/option',
            '/select',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = ['Model' => ['user_id' => null]];
        $view = &ClassRegistry::getObject('view');
        $view->viewVars['users'] = ['value' => 'good', 'other' => 'bad'];
        $result = $this->Form->input('Model.user_id', ['empty' => 'Some Empty']);
        $expected = [
            'div' => ['class' => 'input select'],
            'label' => ['for' => 'ModelUserId'],
            'User',
            '/label',
            'select' => ['name' => 'data[Model][user_id]', 'id' => 'ModelUserId'],
            ['option' => ['value' => '']],
            'Some Empty',
            '/option',
            ['option' => ['value' => 'value']],
            'good',
            '/option',
            ['option' => ['value' => 'other']],
            'bad',
            '/option',
            '/select',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = ['Model' => ['user_id' => 'value']];
        $view = &ClassRegistry::getObject('view');
        $view->viewVars['users'] = ['value' => 'good', 'other' => 'bad'];
        $result = $this->Form->input('Model.user_id', ['empty' => 'Some Empty']);
        $expected = [
            'div' => ['class' => 'input select'],
            'label' => ['for' => 'ModelUserId'],
            'User',
            '/label',
            'select' => ['name' => 'data[Model][user_id]', 'id' => 'ModelUserId'],
            ['option' => ['value' => '']],
            'Some Empty',
            '/option',
            ['option' => ['value' => 'value', 'selected' => 'selected']],
            'good',
            '/option',
            ['option' => ['value' => 'other']],
            'bad',
            '/option',
            '/select',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = ['User' => ['User' => ['value']]];
        $view = &ClassRegistry::getObject('view');
        $view->viewVars['users'] = ['value' => 'good', 'other' => 'bad'];
        $result = $this->Form->input('User.User', ['empty' => true]);
        $expected = [
            'div' => ['class' => 'input select'],
            'label' => ['for' => 'UserUser'],
            'User',
            '/label',
            'input' => ['type' => 'hidden', 'name' => 'data[User][User]', 'value' => '', 'id' => 'UserUser_'],
            'select' => ['name' => 'data[User][User][]', 'id' => 'UserUser', 'multiple' => 'multiple'],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => 'value', 'selected' => 'selected']],
            'good',
            '/option',
            ['option' => ['value' => 'other']],
            'bad',
            '/option',
            '/select',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = [];
        $result = $this->Form->input('Publisher.id', [
                'label' => 'Publisher',
                'type' => 'select',
                'multiple' => 'checkbox',
                'options' => ['Value 1' => 'Label 1', 'Value 2' => 'Label 2'],
        ]);
        $expected = [
            ['div' => ['class' => 'input select']],
                ['label' => ['for' => 'PublisherId']],
                'Publisher',
                '/label',
                'input' => ['type' => 'hidden', 'name' => 'data[Publisher][id]', 'value' => '', 'id' => 'PublisherId'],
                ['div' => ['class' => 'checkbox']],
                ['input' => ['type' => 'checkbox', 'name' => 'data[Publisher][id][]', 'value' => 'Value 1', 'id' => 'PublisherIdValue1']],
                ['label' => ['for' => 'PublisherIdValue1']],
                'Label 1',
                '/label',
                '/div',
                ['div' => ['class' => 'checkbox']],
                ['input' => ['type' => 'checkbox', 'name' => 'data[Publisher][id][]', 'value' => 'Value 2', 'id' => 'PublisherIdValue2']],
                ['label' => ['for' => 'PublisherIdValue2']],
                'Label 2',
                '/label',
                '/div',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test that input() and a non standard primary key makes a hidden input by default.
     */
    public function testInputWithNonStandardPrimaryKeyMakesHidden()
    {
        $this->Form->create('User');
        $this->Form->fieldset = [
            'User' => [
                'fields' => [
                    'model_id' => ['type' => 'integer'],
                ],
                'validates' => [],
                'key' => 'model_id',
            ],
        ];
        $result = $this->Form->input('model_id');
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[User][model_id]', 'id' => 'UserModelId'],
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test that overriding the magic select type widget is possible.
     */
    public function testInputOverridingMagicSelectType()
    {
        $view = &ClassRegistry::getObject('view');
        $view->viewVars['users'] = ['value' => 'good', 'other' => 'bad'];
        $result = $this->Form->input('Model.user_id', ['type' => 'text']);
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'ModelUserId'], 'User', '/label',
            'input' => ['name' => 'data[Model][user_id]', 'type' => 'text', 'id' => 'ModelUserId'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        //Check that magic types still work for plural/singular vars
        $view = &ClassRegistry::getObject('view');
        $view->viewVars['types'] = ['value' => 'good', 'other' => 'bad'];
        $result = $this->Form->input('Model.type');
        $expected = [
            'div' => ['class' => 'input select'],
            'label' => ['for' => 'ModelType'], 'Type', '/label',
            'select' => ['name' => 'data[Model][type]', 'id' => 'ModelType'],
            ['option' => ['value' => 'value']], 'good', '/option',
            ['option' => ['value' => 'other']], 'bad', '/option',
            '/select',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * Test that magic input() selects can easily be converted into radio types without error.
     */
    public function testInputMagicSelectChangeToRadio()
    {
        $view = &ClassRegistry::getObject('view');
        $view->viewVars['users'] = ['value' => 'good', 'other' => 'bad'];
        $result = $this->Form->input('Model.user_id', ['type' => 'radio']);
        $this->assertPattern('/input type="radio"/', $result);
    }

    /**
     * fields with the same name as the model should work.
     */
    public function testInputWithMatchingFieldAndModelName()
    {
        $this->Form->create('User');
        $this->Form->fieldset = [
            'User' => [
                'fields' => [
                    'User' => ['type' => 'text'],
                ],
                'validates' => [],
                'key' => 'id',
            ],
        ];
        $this->Form->data['User']['User'] = 'ABC, Inc.';
        $result = $this->Form->input('User', ['type' => 'text']);
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'UserUser'], 'User', '/label',
            'input' => ['name' => 'data[User][User]', 'type' => 'text', 'id' => 'UserUser', 'value' => 'ABC, Inc.'],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testFormInputs method.
     *
     * test correct results from form::inputs().
     */
    public function testFormInputs()
    {
        $this->Form->create('Contact');
        $result = $this->Form->inputs('The Legend');
        $expected = [
            '<fieldset',
            '<legend',
            'The Legend',
            '/legend',
            '*/fieldset',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->inputs(['legend' => 'Field of Dreams', 'fieldset' => 'classy-stuff']);
        $expected = [
            'fieldset' => ['class' => 'classy-stuff'],
            '<legend',
            'Field of Dreams',
            '/legend',
            '*/fieldset',
        ];
        $this->assertTags($result, $expected);

        $View = ClassRegistry::getObject('view');
        $this->Form->create('Contact');
        $this->Form->params['prefix'] = 'admin';
        $this->Form->action = 'admin_edit';
        $result = $this->Form->inputs();
        $expected = [
            '<fieldset',
            '<legend',
            'Edit Contact',
            '/legend',
            '*/fieldset',
        ];
        $this->assertTags($result, $expected);

        $this->Form->create('Contact');
        $result = $this->Form->inputs(false);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Contact][id]', 'id' => 'ContactId'],
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input password']],
            '*/div',
            ['div' => ['class' => 'input date']],
            '*/div',
            ['div' => ['class' => 'input date']],
            '*/div',
            ['div' => ['class' => 'input datetime']],
            '*/div',
            ['div' => ['class' => 'input select']],
            '*/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->create('Contact');
        $result = $this->Form->inputs(['fieldset' => false, 'legend' => false]);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Contact][id]', 'id' => 'ContactId'],
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input password']],
            '*/div',
            ['div' => ['class' => 'input date']],
            '*/div',
            ['div' => ['class' => 'input date']],
            '*/div',
            ['div' => ['class' => 'input datetime']],
            '*/div',
            ['div' => ['class' => 'input select']],
            '*/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->create('Contact');
        $result = $this->Form->inputs(['fieldset' => true, 'legend' => false]);
        $expected = [
            'fieldset' => [],
            'input' => ['type' => 'hidden', 'name' => 'data[Contact][id]', 'id' => 'ContactId'],
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input password']],
            '*/div',
            ['div' => ['class' => 'input date']],
            '*/div',
            ['div' => ['class' => 'input date']],
            '*/div',
            ['div' => ['class' => 'input datetime']],
            '*/div',
            ['div' => ['class' => 'input select']],
            '*/div',
            '/fieldset',
        ];
        $this->assertTags($result, $expected);

        $this->Form->create('Contact');
        $result = $this->Form->inputs(['fieldset' => false, 'legend' => 'Hello']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Contact][id]', 'id' => 'ContactId'],
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input password']],
            '*/div',
            ['div' => ['class' => 'input date']],
            '*/div',
            ['div' => ['class' => 'input date']],
            '*/div',
            ['div' => ['class' => 'input datetime']],
            '*/div',
            ['div' => ['class' => 'input select']],
            '*/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->create('Contact');
        $result = $this->Form->inputs('Hello');
        $expected = [
            'fieldset' => [],
            'legend' => [],
            'Hello',
            '/legend',
            'input' => ['type' => 'hidden', 'name' => 'data[Contact][id]', 'id' => 'ContactId'],
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input password']],
            '*/div',
            ['div' => ['class' => 'input date']],
            '*/div',
            ['div' => ['class' => 'input date']],
            '*/div',
            ['div' => ['class' => 'input datetime']],
            '*/div',
            ['div' => ['class' => 'input select']],
            '*/div',
            '/fieldset',
        ];
        $this->assertTags($result, $expected);

        $this->Form->create('Contact');
        $result = $this->Form->inputs(['legend' => 'Hello']);
        $expected = [
            'fieldset' => [],
            'legend' => [],
            'Hello',
            '/legend',
            'input' => ['type' => 'hidden', 'name' => 'data[Contact][id]', 'id' => 'ContactId'],
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input text']],
            '*/div',
            ['div' => ['class' => 'input password']],
            '*/div',
            ['div' => ['class' => 'input date']],
            '*/div',
            ['div' => ['class' => 'input date']],
            '*/div',
            ['div' => ['class' => 'input datetime']],
            '*/div',
            ['div' => ['class' => 'input select']],
            '*/div',
            '/fieldset',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testSelectAsCheckbox method.
     *
     * test multi-select widget with checkbox formatting.
     */
    public function testSelectAsCheckbox()
    {
        $result = $this->Form->select('Model.multi_field', ['first', 'second', 'third'], [0, 1], ['multiple' => 'checkbox']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][multi_field]', 'value' => '', 'id' => 'ModelMultiField'],
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'checked' => 'checked', 'value' => '0', 'id' => 'ModelMultiField0']],
            ['label' => ['for' => 'ModelMultiField0', 'class' => 'selected']],
            'first',
            '/label',
            '/div',
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'checked' => 'checked', 'value' => '1', 'id' => 'ModelMultiField1']],
            ['label' => ['for' => 'ModelMultiField1', 'class' => 'selected']],
            'second',
            '/label',
            '/div',
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'value' => '2', 'id' => 'ModelMultiField2']],
            ['label' => ['for' => 'ModelMultiField2']],
            'third',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->select('Model.multi_field', ['1/2' => 'half'], null, ['multiple' => 'checkbox']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][multi_field]', 'value' => '', 'id' => 'ModelMultiField'],
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'value' => '1/2', 'id' => 'ModelMultiField12']],
            ['label' => ['for' => 'ModelMultiField12']],
            'half',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testLabel method.
     *
     * test label generation.
     */
    public function testLabel()
    {
        $this->Form->text('Person.name');
        $result = $this->Form->label();
        $this->assertTags($result, ['label' => ['for' => 'PersonName'], 'Name', '/label']);

        $this->Form->text('Person.name');
        $result = $this->Form->label();
        $this->assertTags($result, ['label' => ['for' => 'PersonName'], 'Name', '/label']);

        $result = $this->Form->label('Person.first_name');
        $this->assertTags($result, ['label' => ['for' => 'PersonFirstName'], 'First Name', '/label']);

        $result = $this->Form->label('Person.first_name', 'Your first name');
        $this->assertTags($result, ['label' => ['for' => 'PersonFirstName'], 'Your first name', '/label']);

        $result = $this->Form->label('Person.first_name', 'Your first name', ['class' => 'my-class']);
        $this->assertTags($result, ['label' => ['for' => 'PersonFirstName', 'class' => 'my-class'], 'Your first name', '/label']);

        $result = $this->Form->label('Person.first_name', 'Your first name', ['class' => 'my-class', 'id' => 'LabelID']);
        $this->assertTags($result, ['label' => ['for' => 'PersonFirstName', 'class' => 'my-class', 'id' => 'LabelID'], 'Your first name', '/label']);

        $result = $this->Form->label('Person.first_name', '');
        $this->assertTags($result, ['label' => ['for' => 'PersonFirstName'], '/label']);

        $result = $this->Form->label('Person.2.name', '');
        $this->assertTags($result, ['label' => ['for' => 'Person2Name'], '/label']);
    }

    /**
     * testTextbox method.
     *
     * test textbox element generation
     */
    public function testTextbox()
    {
        $result = $this->Form->text('Model.field');
        $this->assertTags($result, ['input' => ['type' => 'text', 'name' => 'data[Model][field]', 'id' => 'ModelField']]);

        $result = $this->Form->text('Model.field', ['type' => 'password']);
        $this->assertTags($result, ['input' => ['type' => 'password', 'name' => 'data[Model][field]', 'id' => 'ModelField']]);

        $result = $this->Form->text('Model.field', ['id' => 'theID']);
        $this->assertTags($result, ['input' => ['type' => 'text', 'name' => 'data[Model][field]', 'id' => 'theID']]);

        $this->Form->data['Model']['text'] = 'test <strong>HTML</strong> values';
        $result = $this->Form->text('Model.text');
        $this->assertTags($result, ['input' => ['type' => 'text', 'name' => 'data[Model][text]', 'value' => 'test &lt;strong&gt;HTML&lt;/strong&gt; values', 'id' => 'ModelText']]);

        $this->Form->validationErrors['Model']['text'] = 1;
        $this->Form->data['Model']['text'] = 'test';
        $result = $this->Form->text('Model.text', ['id' => 'theID']);
        $this->assertTags($result, ['input' => ['type' => 'text', 'name' => 'data[Model][text]', 'value' => 'test', 'id' => 'theID', 'class' => 'form-error']]);

        $this->Form->data['Model']['0']['OtherModel']['field'] = 'My value';
        $result = $this->Form->text('Model.0.OtherModel.field', ['id' => 'myId']);
        $expected = [
            'input' => ['type' => 'text', 'name' => 'data[Model][0][OtherModel][field]', 'value' => 'My value', 'id' => 'myId'],
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testDefaultValue method.
     *
     * Test default value setting
     */
    public function testDefaultValue()
    {
        $this->Form->data['Model']['field'] = 'test';
        $result = $this->Form->text('Model.field', ['default' => 'default value']);
        $this->assertTags($result, ['input' => ['type' => 'text', 'name' => 'data[Model][field]', 'value' => 'test', 'id' => 'ModelField']]);

        unset($this->Form->data['Model']['field']);
        $result = $this->Form->text('Model.field', ['default' => 'default value']);
        $this->assertTags($result, ['input' => ['type' => 'text', 'name' => 'data[Model][field]', 'value' => 'default value', 'id' => 'ModelField']]);
    }

    /**
     * testError method.
     *
     * Test field error generation
     */
    public function testError()
    {
        $this->Form->validationErrors['Model']['field'] = 1;
        $result = $this->Form->error('Model.field');
        $this->assertTags($result, ['div' => ['class' => 'error-message'], 'Error in field Field', '/div']);

        $result = $this->Form->error('Model.field', null, ['wrap' => false]);
        $this->assertEqual($result, 'Error in field Field');

        $this->Form->validationErrors['Model']['field'] = 'This field contains invalid input';
        $result = $this->Form->error('Model.field', null, ['wrap' => false]);
        $this->assertEqual($result, 'This field contains invalid input');

        $this->Form->validationErrors['Model']['field'] = 'This field contains invalid input';
        $result = $this->Form->error('Model.field', null, ['wrap' => 'span']);
        $this->assertTags($result, ['span' => ['class' => 'error-message'], 'This field contains invalid input', '/span']);

        $result = $this->Form->error('Model.field', 'There is an error fool!', ['wrap' => 'span']);
        $this->assertTags($result, ['span' => ['class' => 'error-message'], 'There is an error fool!', '/span']);

        $result = $this->Form->error('Model.field', '<strong>Badness!</strong>', ['wrap' => false]);
        $this->assertEqual($result, '&lt;strong&gt;Badness!&lt;/strong&gt;');

        $result = $this->Form->error('Model.field', '<strong>Badness!</strong>', ['wrap' => false, 'escape' => true]);
        $this->assertEqual($result, '&lt;strong&gt;Badness!&lt;/strong&gt;');

        $result = $this->Form->error('Model.field', '<strong>Badness!</strong>', ['wrap' => false, 'escape' => false]);
        $this->assertEqual($result, '<strong>Badness!</strong>');

        $this->Form->validationErrors['Model']['field'] = 'email';
        $result = $this->Form->error('Model.field', ['class' => 'field-error', 'email' => 'No good!']);
        $expected = [
            'div' => ['class' => 'field-error'],
            'No good!',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test error options when using form->input();.
     */
    public function testInputErrorEscape()
    {
        $this->Form->create('ValidateProfile');
        $this->Form->validationErrors['ValidateProfile']['city'] = 'required<br>';
        $result = $this->Form->input('city', ['error' => ['escape' => true]]);
        $this->assertPattern('/required&lt;br&gt;/', $result);

        $result = $this->Form->input('city', ['error' => ['escape' => false]]);
        $this->assertPattern('/required<br>/', $result);
    }

    /**
     * testPassword method.
     *
     * Test password element generation
     */
    public function testPassword()
    {
        $result = $this->Form->password('Model.field');
        $this->assertTags($result, ['input' => ['type' => 'password', 'name' => 'data[Model][field]', 'id' => 'ModelField']]);

        $this->Form->validationErrors['Model']['passwd'] = 1;
        $this->Form->data['Model']['passwd'] = 'test';
        $result = $this->Form->password('Model.passwd', ['id' => 'theID']);
        $this->assertTags($result, ['input' => ['type' => 'password', 'name' => 'data[Model][passwd]', 'value' => 'test', 'id' => 'theID', 'class' => 'form-error']]);
    }

    /**
     * testRadio method.
     *
     * Test radio element set generation
     */
    public function testRadio()
    {
        $result = $this->Form->radio('Model.field', ['option A']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '', 'id' => 'ModelField_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField0']],
            'label' => ['for' => 'ModelField0'],
            'option A',
            '/label',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->radio('Model.field', ['1/2' => 'half']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '', 'id' => 'ModelField_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '1/2', 'id' => 'ModelField12']],
            'label' => ['for' => 'ModelField12'],
            'half',
            '/label',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->radio('Model.field', ['option A', 'option B']);
        $expected = [
            'fieldset' => [],
            'legend' => [],
            'Field',
            '/legend',
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '', 'id' => 'ModelField_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField0']],
            ['label' => ['for' => 'ModelField0']],
            'option A',
            '/label',
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '1', 'id' => 'ModelField1']],
            ['label' => ['for' => 'ModelField1']],
            'option B',
            '/label',
            '/fieldset',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->radio('Model.field', ['option A', 'option B'], ['separator' => '<br/>']);
        $expected = [
            'fieldset' => [],
            'legend' => [],
            'Field',
            '/legend',
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '', 'id' => 'ModelField_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField0']],
            ['label' => ['for' => 'ModelField0']],
            'option A',
            '/label',
            'br' => [],
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '1', 'id' => 'ModelField1']],
            ['label' => ['for' => 'ModelField1']],
            'option B',
            '/label',
            '/fieldset',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->radio('Model.field', ['1' => 'Yes', '0' => 'No'], ['value' => '1']);
        $expected = [
            'fieldset' => [],
            'legend' => [],
            'Field',
            '/legend',
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '1', 'id' => 'ModelField1', 'checked' => 'checked']],
            ['label' => ['for' => 'ModelField1']],
            'Yes',
            '/label',
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField0']],
            ['label' => ['for' => 'ModelField0']],
            'No',
            '/label',
            '/fieldset',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->radio('Model.field', ['1' => 'Yes', '0' => 'No'], ['value' => '0']);
        $expected = [
            'fieldset' => [],
            'legend' => [],
            'Field',
            '/legend',
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '1', 'id' => 'ModelField1']],
            ['label' => ['for' => 'ModelField1']],
            'Yes',
            '/label',
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField0', 'checked' => 'checked']],
            ['label' => ['for' => 'ModelField0']],
            'No',
            '/label',
            '/fieldset',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->radio('Model.field', ['1' => 'Yes', '0' => 'No'], ['value' => null]);
        $expected = [
            'fieldset' => [],
            'legend' => [],
            'Field',
            '/legend',
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '', 'id' => 'ModelField_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '1', 'id' => 'ModelField1']],
            ['label' => ['for' => 'ModelField1']],
            'Yes',
            '/label',
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField0']],
            ['label' => ['for' => 'ModelField0']],
            'No',
            '/label',
            '/fieldset',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->radio('Model.field', ['1' => 'Yes', '0' => 'No']);
        $expected = [
            'fieldset' => [],
            'legend' => [],
            'Field',
            '/legend',
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '', 'id' => 'ModelField_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '1', 'id' => 'ModelField1']],
            ['label' => ['for' => 'ModelField1']],
            'Yes',
            '/label',
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField0']],
            ['label' => ['for' => 'ModelField0']],
            'No',
            '/label',
            '/fieldset',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = ['Model' => ['field' => '']];
        $result = $this->Form->radio('Model.field', ['1' => 'Yes', '0' => 'No']);
        $expected = [
            'fieldset' => [],
            'legend' => [],
            'Field',
            '/legend',
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '', 'id' => 'ModelField_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '1', 'id' => 'ModelField1']],
            ['label' => ['for' => 'ModelField1']],
            'Yes',
            '/label',
            ['input' => ['type' => 'radio', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField0']],
            ['label' => ['for' => 'ModelField0']],
            'No',
            '/label',
            '/fieldset',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Newsletter.subscribe', ['legend' => 'Legend title', 'type' => 'radio', 'options' => ['0' => 'Unsubscribe', '1' => 'Subscribe']]);
        $expected = [
            'div' => ['class' => 'input radio'],
            'fieldset' => [],
            'legend' => [],
            'Legend title',
            '/legend',
            'input' => ['type' => 'hidden', 'name' => 'data[Newsletter][subscribe]', 'value' => '', 'id' => 'NewsletterSubscribe_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Newsletter][subscribe]', 'value' => '0', 'id' => 'NewsletterSubscribe0']],
            ['label' => ['for' => 'NewsletterSubscribe0']],
            'Unsubscribe',
            '/label',
            ['input' => ['type' => 'radio', 'name' => 'data[Newsletter][subscribe]', 'value' => '1', 'id' => 'NewsletterSubscribe1']],
            ['label' => ['for' => 'NewsletterSubscribe1']],
            'Subscribe',
            '/label',
            '/fieldset',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Newsletter.subscribe', ['legend' => false, 'type' => 'radio', 'options' => ['0' => 'Unsubscribe', '1' => 'Subscribe']]);
        $expected = [
            'div' => ['class' => 'input radio'],
            'input' => ['type' => 'hidden', 'name' => 'data[Newsletter][subscribe]', 'value' => '', 'id' => 'NewsletterSubscribe_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Newsletter][subscribe]', 'value' => '0', 'id' => 'NewsletterSubscribe0']],
            ['label' => ['for' => 'NewsletterSubscribe0']],
            'Unsubscribe',
            '/label',
            ['input' => ['type' => 'radio', 'name' => 'data[Newsletter][subscribe]', 'value' => '1', 'id' => 'NewsletterSubscribe1']],
            ['label' => ['for' => 'NewsletterSubscribe1']],
            'Subscribe',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Newsletter.subscribe', ['legend' => 'Legend title', 'label' => false, 'type' => 'radio', 'options' => ['0' => 'Unsubscribe', '1' => 'Subscribe']]);
        $expected = [
            'div' => ['class' => 'input radio'],
            'fieldset' => [],
            'legend' => [],
            'Legend title',
            '/legend',
            'input' => ['type' => 'hidden', 'name' => 'data[Newsletter][subscribe]', 'value' => '', 'id' => 'NewsletterSubscribe_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Newsletter][subscribe]', 'value' => '0', 'id' => 'NewsletterSubscribe0']],
            'Unsubscribe',
            ['input' => ['type' => 'radio', 'name' => 'data[Newsletter][subscribe]', 'value' => '1', 'id' => 'NewsletterSubscribe1']],
            'Subscribe',
            '/fieldset',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Newsletter.subscribe', ['legend' => false, 'label' => false, 'type' => 'radio', 'options' => ['0' => 'Unsubscribe', '1' => 'Subscribe']]);
        $expected = [
            'div' => ['class' => 'input radio'],
            'input' => ['type' => 'hidden', 'name' => 'data[Newsletter][subscribe]', 'value' => '', 'id' => 'NewsletterSubscribe_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Newsletter][subscribe]', 'value' => '0', 'id' => 'NewsletterSubscribe0']],
            'Unsubscribe',
            ['input' => ['type' => 'radio', 'name' => 'data[Newsletter][subscribe]', 'value' => '1', 'id' => 'NewsletterSubscribe1']],
            'Subscribe',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Newsletter.subscribe', ['legend' => false, 'label' => false, 'type' => 'radio', 'value' => '1', 'options' => ['0' => 'Unsubscribe', '1' => 'Subscribe']]);
        $expected = [
            'div' => ['class' => 'input radio'],
            ['input' => ['type' => 'radio', 'name' => 'data[Newsletter][subscribe]', 'value' => '0', 'id' => 'NewsletterSubscribe0']],
            'Unsubscribe',
            ['input' => ['type' => 'radio', 'name' => 'data[Newsletter][subscribe]', 'value' => '1', 'id' => 'NewsletterSubscribe1', 'checked' => 'checked']],
            'Subscribe',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->radio('Employee.gender', ['male' => 'Male', 'female' => 'Female']);
        $expected = [
            'fieldset' => [],
            'legend' => [],
            'Gender',
            '/legend',
            'input' => ['type' => 'hidden', 'name' => 'data[Employee][gender]', 'value' => '', 'id' => 'EmployeeGender_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Employee][gender]', 'value' => 'male', 'id' => 'EmployeeGenderMale']],
            ['label' => ['for' => 'EmployeeGenderMale']],
            'Male',
            '/label',
            ['input' => ['type' => 'radio', 'name' => 'data[Employee][gender]', 'value' => 'female', 'id' => 'EmployeeGenderFemale']],
            ['label' => ['for' => 'EmployeeGenderFemale']],
            'Female',
            '/label',
            '/fieldset',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->radio('Officer.gender', ['male' => 'Male', 'female' => 'Female']);
        $expected = [
            'fieldset' => [],
            'legend' => [],
            'Gender',
            '/legend',
            'input' => ['type' => 'hidden', 'name' => 'data[Officer][gender]', 'value' => '', 'id' => 'OfficerGender_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Officer][gender]', 'value' => 'male', 'id' => 'OfficerGenderMale']],
            ['label' => ['for' => 'OfficerGenderMale']],
            'Male',
            '/label',
            ['input' => ['type' => 'radio', 'name' => 'data[Officer][gender]', 'value' => 'female', 'id' => 'OfficerGenderFemale']],
            ['label' => ['for' => 'OfficerGenderFemale']],
            'Female',
            '/label',
            '/fieldset',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->radio('Contact.1.imrequired', ['option A']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Contact][1][imrequired]', 'value' => '', 'id' => 'Contact1Imrequired_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Contact][1][imrequired]', 'value' => '0', 'id' => 'Contact1Imrequired0']],
            'label' => ['for' => 'Contact1Imrequired0'],
            'option A',
            '/label',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->radio('Model.1.field', ['option A']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][1][field]', 'value' => '', 'id' => 'Model1Field_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Model][1][field]', 'value' => '0', 'id' => 'Model1Field0']],
            'label' => ['for' => 'Model1Field0'],
            'option A',
            '/label',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->radio('Model.field', ['option A', 'option B'], ['name' => 'data[Model][custom]']);
        $expected = [
            'fieldset' => [],
            'legend' => [],
            'Field',
            '/legend',
            'input' => ['type' => 'hidden', 'name' => 'data[Model][custom]', 'value' => '', 'id' => 'ModelField_'],
            ['input' => ['type' => 'radio', 'name' => 'data[Model][custom]', 'value' => '0', 'id' => 'ModelField0']],
            ['label' => ['for' => 'ModelField0']],
            'option A',
            '/label',
            ['input' => ['type' => 'radio', 'name' => 'data[Model][custom]', 'value' => '1', 'id' => 'ModelField1']],
            ['label' => ['for' => 'ModelField1']],
            'option B',
            '/label',
            '/fieldset',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test disabling the hidden input for radio buttons.
     */
    public function testRadioHiddenInputDisabling()
    {
        $result = $this->Form->input('Model.1.field', [
                'type' => 'radio',
                'options' => ['option A'],
                'hiddenField' => false,
            ]
        );
        $expected = [
            'div' => ['class' => 'input radio'],
            'input' => ['type' => 'radio', 'name' => 'data[Model][1][field]', 'value' => '0', 'id' => 'Model1Field0'],
            'label' => ['for' => 'Model1Field0'],
            'option A',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->radio('Model.1.field', ['option A'], ['hiddenField' => false]);
        $expected = [
            'input' => ['type' => 'radio', 'name' => 'data[Model][1][field]', 'value' => '0', 'id' => 'Model1Field0'],
            'label' => ['for' => 'Model1Field0'],
            'option A',
            '/label',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testSelect method.
     *
     * Test select element generation.
     */
    public function testSelect()
    {
        $result = $this->Form->select('Model.field', []);
        $expected = [
            'select' => ['name' => 'data[Model][field]', 'id' => 'ModelField'],
            ['option' => ['value' => '']],
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = ['Model' => ['field' => 'value']];
        $result = $this->Form->select('Model.field', ['value' => 'good', 'other' => 'bad']);
        $expected = [
            'select' => ['name' => 'data[Model][field]', 'id' => 'ModelField'],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => 'value', 'selected' => 'selected']],
            'good',
            '/option',
            ['option' => ['value' => 'other']],
            'bad',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = [];
        $result = $this->Form->select('Model.field', ['value' => 'good', 'other' => 'bad']);
        $expected = [
            'select' => ['name' => 'data[Model][field]', 'id' => 'ModelField'],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => 'value']],
            'good',
            '/option',
            ['option' => ['value' => 'other']],
            'bad',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->select(
            'Model.field', ['first' => 'first "html" <chars>', 'second' => 'value'],
            null, ['empty' => false]
        );
        $expected = [
            'select' => ['name' => 'data[Model][field]', 'id' => 'ModelField'],
            ['option' => ['value' => 'first']],
            'first &quot;html&quot; &lt;chars&gt;',
            '/option',
            ['option' => ['value' => 'second']],
            'value',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->select(
            'Model.field',
            ['first' => 'first "html" <chars>', 'second' => 'value'],
            null, ['escape' => false, 'empty' => false]
        );
        $expected = [
            'select' => ['name' => 'data[Model][field]', 'id' => 'ModelField'],
            ['option' => ['value' => 'first']],
            'first "html" <chars>',
            '/option',
            ['option' => ['value' => 'second']],
            'value',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = ['Model' => ['contact_id' => 228]];
        $result = $this->Form->select(
            'Model.contact_id',
            ['228' => '228 value', '228-1' => '228-1 value', '228-2' => '228-2 value'],
            null, ['escape' => false, 'empty' => 'pick something']
        );

        $expected = [
            'select' => ['name' => 'data[Model][contact_id]', 'id' => 'ModelContactId'],
            ['option' => ['value' => '']], 'pick something', '/option',
            ['option' => ['value' => '228', 'selected' => 'selected']], '228 value', '/option',
            ['option' => ['value' => '228-1']], '228-1 value', '/option',
            ['option' => ['value' => '228-2']], '228-2 value', '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test that select() with optiongroups listens to the escape param.
     */
    public function testSelectOptionGroupEscaping()
    {
        $options = [
            '>< Key' => [
                1 => 'One',
                2 => 'Two',
            ],
        ];
        $result = $this->Form->select('Model.field', $options, null, ['empty' => false]);
        $expected = [
            'select' => ['name' => 'data[Model][field]', 'id' => 'ModelField'],
            'optgroup' => ['label' => '&gt;&lt; Key'],
            ['option' => ['value' => '1']], 'One', '/option',
            ['option' => ['value' => '2']], 'Two', '/option',
            '/optgroup',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $options = [
            '>< Key' => [
                1 => 'One',
                2 => 'Two',
            ],
        ];
        $result = $this->Form->select('Model.field', $options, null, ['empty' => false, 'escape' => false]);
        $expected = [
            'select' => ['name' => 'data[Model][field]', 'id' => 'ModelField'],
            'optgroup' => ['label' => '>< Key'],
            ['option' => ['value' => '1']], 'One', '/option',
            ['option' => ['value' => '2']], 'Two', '/option',
            '/optgroup',
            '/select',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * Tests that FormHelper::select() allows null to be passed in the $attributes parameter.
     */
    public function testSelectWithNullAttributes()
    {
        $result = $this->Form->select('Model.field', ['first', 'second'], null, ['empty' => false]);
        $expected = [
            'select' => ['name' => 'data[Model][field]', 'id' => 'ModelField'],
            ['option' => ['value' => '0']],
            'first',
            '/option',
            ['option' => ['value' => '1']],
            'second',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testNestedSelect method.
     *
     * test select element generation with optgroups
     */
    public function testNestedSelect()
    {
        $result = $this->Form->select(
            'Model.field',
            [1 => 'One', 2 => 'Two', 'Three' => [
                3 => 'Three', 4 => 'Four', 5 => 'Five',
            ]], null, ['empty' => false]
        );
        $expected = [
            'select' => ['name' => 'data[Model][field]',
                    'id' => 'ModelField', ],
                    ['option' => ['value' => 1]],
                    'One',
                    '/option',
                    ['option' => ['value' => 2]],
                    'Two',
                    '/option',
                    ['optgroup' => ['label' => 'Three']],
                        ['option' => ['value' => 4]],
                        'Four',
                        '/option',
                        ['option' => ['value' => 5]],
                        'Five',
                        '/option',
                    '/optgroup',
                    '/select',
                    ];
        $this->assertTags($result, $expected);

        $result = $this->Form->select(
            'Model.field',
            [1 => 'One', 2 => 'Two', 'Three' => [3 => 'Three', 4 => 'Four']], null,
            ['showParents' => true, 'empty' => false]
        );

        $expected = [
            'select' => ['name' => 'data[Model][field]', 'id' => 'ModelField'],
                ['option' => ['value' => 1]],
                'One',
                '/option',
                ['option' => ['value' => 2]],
                'Two',
                '/option',
                ['optgroup' => ['label' => 'Three']],
                    ['option' => ['value' => 3]],
                    'Three',
                    '/option',
                    ['option' => ['value' => 4]],
                    'Four',
                    '/option',
                '/optgroup',
            '/select',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testSelectMultiple method.
     *
     * test generation of multiple select elements
     */
    public function testSelectMultiple()
    {
        $options = ['first', 'second', 'third'];
        $result = $this->Form->select(
            'Model.multi_field', $options, null, ['multiple' => true]
        );
        $expected = [
            'input' => [
                'type' => 'hidden', 'name' => 'data[Model][multi_field]', 'value' => '', 'id' => 'ModelMultiField_',
            ],
            'select' => [
                'name' => 'data[Model][multi_field][]',
                'id' => 'ModelMultiField', 'multiple' => 'multiple',
            ],
            ['option' => ['value' => '0']],
            'first',
            '/option',
            ['option' => ['value' => '1']],
            'second',
            '/option',
            ['option' => ['value' => '2']],
            'third',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->select(
            'Model.multi_field', $options, null, ['multiple' => 'multiple']
        );
        $expected = [
            'input' => [
                'type' => 'hidden', 'name' => 'data[Model][multi_field]', 'value' => '', 'id' => 'ModelMultiField_',
            ],
            'select' => [
                'name' => 'data[Model][multi_field][]',
                'id' => 'ModelMultiField', 'multiple' => 'multiple',
            ],
            ['option' => ['value' => '0']],
            'first',
            '/option',
            ['option' => ['value' => '1']],
            'second',
            '/option',
            ['option' => ['value' => '2']],
            'third',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->select(
            'Model.multi_field', $options, [0, 1], ['multiple' => true]
        );
        $expected = [
            'input' => [
                'type' => 'hidden', 'name' => 'data[Model][multi_field]', 'value' => '', 'id' => 'ModelMultiField_',
            ],
            'select' => [
                'name' => 'data[Model][multi_field][]', 'id' => 'ModelMultiField',
                'multiple' => 'multiple',
            ],
            ['option' => ['value' => '0', 'selected' => 'selected']],
            'first',
            '/option',
            ['option' => ['value' => '1', 'selected' => 'selected']],
            'second',
            '/option',
            ['option' => ['value' => '2']],
            'third',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->select(
            'Model.multi_field', $options, [0, 1], ['multiple' => false]
        );
        $expected = [
            'select' => [
                'name' => 'data[Model][multi_field]', 'id' => 'ModelMultiField',
            ],
            ['option' => ['value' => '0', 'selected' => 'selected']],
            'first',
            '/option',
            ['option' => ['value' => '1', 'selected' => 'selected']],
            'second',
            '/option',
            ['option' => ['value' => '2']],
            'third',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test generation of habtm select boxes.
     */
    public function testHabtmSelectBox()
    {
        $view = &ClassRegistry::getObject('view');
        $view->viewVars['contactTags'] = [
            1 => 'blue',
            2 => 'red',
            3 => 'green',
        ];
        $this->Form->data = [
            'Contact' => [],
            'ContactTag' => [
                [
                    'id' => 1,
                    'name' => 'blue',
                ],
                [
                    'id' => 3,
                    'name' => 'green',
                ],
            ],
        ];
        $this->Form->create('Contact');
        $result = $this->Form->input('ContactTag', ['div' => false, 'label' => false]);
        $expected = [
            'input' => [
                'type' => 'hidden', 'name' => 'data[ContactTag][ContactTag]', 'value' => '', 'id' => 'ContactTagContactTag_',
            ],
            'select' => [
                'name' => 'data[ContactTag][ContactTag][]', 'id' => 'ContactTagContactTag',
                'multiple' => 'multiple',
            ],
            ['option' => ['value' => '1', 'selected' => 'selected']],
            'blue',
            '/option',
            ['option' => ['value' => '2']],
            'red',
            '/option',
            ['option' => ['value' => '3', 'selected' => 'selected']],
            'green',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test generation of multi select elements in checkbox format.
     */
    public function testSelectMultipleCheckboxes()
    {
        $result = $this->Form->select(
            'Model.multi_field',
            ['first', 'second', 'third'], null,
            ['multiple' => 'checkbox']
        );

        $expected = [
            'input' => [
                'type' => 'hidden', 'name' => 'data[Model][multi_field]', 'value' => '', 'id' => 'ModelMultiField',
            ],
            ['div' => ['class' => 'checkbox']],
            ['input' => [
                'type' => 'checkbox', 'name' => 'data[Model][multi_field][]',
                'value' => '0', 'id' => 'ModelMultiField0',
            ]],
            ['label' => ['for' => 'ModelMultiField0']],
            'first',
            '/label',
            '/div',
            ['div' => ['class' => 'checkbox']],
            ['input' => [
                'type' => 'checkbox', 'name' => 'data[Model][multi_field][]',
                'value' => '1', 'id' => 'ModelMultiField1',
            ]],
            ['label' => ['for' => 'ModelMultiField1']],
            'second',
            '/label',
            '/div',
            ['div' => ['class' => 'checkbox']],
            ['input' => [
                'type' => 'checkbox', 'name' => 'data[Model][multi_field][]',
                'value' => '2', 'id' => 'ModelMultiField2',
            ]],
            ['label' => ['for' => 'ModelMultiField2']],
            'third',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->select(
            'Model.multi_field',
            ['a' => 'first', 'b' => 'second', 'c' => 'third'], null,
            ['multiple' => 'checkbox']
        );
        $expected = [
            'input' => [
                'type' => 'hidden', 'name' => 'data[Model][multi_field]', 'value' => '', 'id' => 'ModelMultiField',
            ],
            ['div' => ['class' => 'checkbox']],
            ['input' => [
                'type' => 'checkbox', 'name' => 'data[Model][multi_field][]',
                'value' => 'a', 'id' => 'ModelMultiFieldA',
            ]],
            ['label' => ['for' => 'ModelMultiFieldA']],
            'first',
            '/label',
            '/div',
            ['div' => ['class' => 'checkbox']],
            ['input' => [
                'type' => 'checkbox', 'name' => 'data[Model][multi_field][]',
                'value' => 'b', 'id' => 'ModelMultiFieldB',
            ]],
            ['label' => ['for' => 'ModelMultiFieldB']],
            'second',
            '/label',
            '/div',
            ['div' => ['class' => 'checkbox']],
            ['input' => [
                'type' => 'checkbox', 'name' => 'data[Model][multi_field][]',
                'value' => 'c', 'id' => 'ModelMultiFieldC',
            ]],
            ['label' => ['for' => 'ModelMultiFieldC']],
            'third',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->select(
            'Model.multi_field', ['1' => 'first'], null, ['multiple' => 'checkbox']
        );
        $expected = [
            'input' => [
                'type' => 'hidden', 'name' => 'data[Model][multi_field]', 'value' => '', 'id' => 'ModelMultiField',
            ],
            ['div' => ['class' => 'checkbox']],
            ['input' => [
                'type' => 'checkbox', 'name' => 'data[Model][multi_field][]',
                'value' => '1', 'id' => 'ModelMultiField1',
            ]],
            ['label' => ['for' => 'ModelMultiField1']],
            'first',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = ['Model' => ['tags' => [1]]];
        $result = $this->Form->select(
            'Model.tags', ['1' => 'first', 'Array' => 'Array'], null, ['multiple' => 'checkbox']
        );
        $expected = [
            'input' => [
                'type' => 'hidden', 'name' => 'data[Model][tags]', 'value' => '', 'id' => 'ModelTags',
            ],
            ['div' => ['class' => 'checkbox']],
            ['input' => [
                'type' => 'checkbox', 'name' => 'data[Model][tags][]',
                'value' => '1', 'id' => 'ModelTags1', 'checked' => 'checked',
            ]],
            ['label' => ['for' => 'ModelTags1', 'class' => 'selected']],
            'first',
            '/label',
            '/div',

            ['div' => ['class' => 'checkbox']],
            ['input' => [
                'type' => 'checkbox', 'name' => 'data[Model][tags][]',
                'value' => 'Array', 'id' => 'ModelTagsArray',
            ]],
            ['label' => ['for' => 'ModelTagsArray']],
            'Array',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test multiple checkboxes with div styles.
     */
    public function testSelectMultipleCheckboxDiv()
    {
        $result = $this->Form->select(
            'Model.tags',
            ['first', 'second'],
            null,
            ['multiple' => 'checkbox', 'class' => 'my-class']
        );
        $expected = [
            'input' => [
                'type' => 'hidden', 'name' => 'data[Model][tags]', 'value' => '', 'id' => 'ModelTags',
            ],
            ['div' => ['class' => 'my-class']],
            ['input' => [
                'type' => 'checkbox', 'name' => 'data[Model][tags][]',
                'value' => '0', 'id' => 'ModelTags0',
            ]],
            ['label' => ['for' => 'ModelTags0']], 'first', '/label',
            '/div',

            ['div' => ['class' => 'my-class']],
            ['input' => [
                'type' => 'checkbox', 'name' => 'data[Model][tags][]',
                'value' => '1', 'id' => 'ModelTags1',
            ]],
            ['label' => ['for' => 'ModelTags1']], 'second', '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Model.tags', [
            'options' => ['first', 'second'],
            'multiple' => 'checkbox',
            'class' => 'my-class',
            'div' => false,
            'label' => false,
        ]);
        $this->assertTags($result, $expected);

        $this->Form->validationErrors['Model']['tags'] = 'Select atleast one option';
        $result = $this->Form->input('Model.tags', [
            'options' => ['one'],
            'multiple' => 'checkbox',
            'label' => false,
            'div' => false,
        ]);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][tags]', 'value' => '', 'id' => 'ModelTags'],
            ['div' => ['class' => 'checkbox form-error']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][tags][]', 'value' => '0', 'id' => 'ModelTags0']],
            ['label' => ['for' => 'ModelTags0']],
            'one',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Model.tags', [
            'options' => ['one'],
            'multiple' => 'checkbox',
            'class' => 'mycheckbox',
            'label' => false,
            'div' => false,
        ]);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][tags]', 'value' => '', 'id' => 'ModelTags'],
            ['div' => ['class' => 'mycheckbox form-error']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][tags][]', 'value' => '0', 'id' => 'ModelTags0']],
            ['label' => ['for' => 'ModelTags0']],
            'one',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * Checks the security hash array generated for multiple-input checkbox elements.
     */
    public function testSelectMultipleCheckboxSecurity()
    {
        $this->Form->params['_Token']['key'] = 'testKey';
        $this->assertEqual($this->Form->fields, []);

        $result = $this->Form->select(
            'Model.multi_field', ['1' => 'first', '2' => 'second', '3' => 'third'],
            null, ['multiple' => 'checkbox']
        );
        $this->assertEqual($this->Form->fields, ['Model.multi_field']);

        $result = $this->Form->secure($this->Form->fields);
        $key = 'f7d573650a295b94e0938d32b323fde775e5f32b%3A';
        $this->assertPattern('/"'.$key.'"/', $result);
    }

    /**
     * testInputMultipleCheckboxes method.
     *
     * test input() resulting in multi select elements being generated.
     */
    public function testInputMultipleCheckboxes()
    {
        $result = $this->Form->input('Model.multi_field', [
            'options' => ['first', 'second', 'third'],
            'multiple' => 'checkbox',
        ]);
        $expected = [
            ['div' => ['class' => 'input select']],
            ['label' => ['for' => 'ModelMultiField']],
            'Multi Field',
            '/label',
            'input' => ['type' => 'hidden', 'name' => 'data[Model][multi_field]', 'value' => '', 'id' => 'ModelMultiField'],
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'value' => '0', 'id' => 'ModelMultiField0']],
            ['label' => ['for' => 'ModelMultiField0']],
            'first',
            '/label',
            '/div',
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'value' => '1', 'id' => 'ModelMultiField1']],
            ['label' => ['for' => 'ModelMultiField1']],
            'second',
            '/label',
            '/div',
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'value' => '2', 'id' => 'ModelMultiField2']],
            ['label' => ['for' => 'ModelMultiField2']],
            'third',
            '/label',
            '/div',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Model.multi_field', [
            'options' => ['a' => 'first', 'b' => 'second', 'c' => 'third'],
            'multiple' => 'checkbox',
        ]);
        $expected = [
            ['div' => ['class' => 'input select']],
            ['label' => ['for' => 'ModelMultiField']],
            'Multi Field',
            '/label',
            'input' => ['type' => 'hidden', 'name' => 'data[Model][multi_field]', 'value' => '', 'id' => 'ModelMultiField'],
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'value' => 'a', 'id' => 'ModelMultiFieldA']],
            ['label' => ['for' => 'ModelMultiFieldA']],
            'first',
            '/label',
            '/div',
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'value' => 'b', 'id' => 'ModelMultiFieldB']],
            ['label' => ['for' => 'ModelMultiFieldB']],
            'second',
            '/label',
            '/div',
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'value' => 'c', 'id' => 'ModelMultiFieldC']],
            ['label' => ['for' => 'ModelMultiFieldC']],
            'third',
            '/label',
            '/div',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Model.multi_field', [
            'options' => ['1' => 'first'],
            'multiple' => 'checkbox',
            'label' => false,
            'div' => false,
        ]);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][multi_field]', 'value' => '', 'id' => 'ModelMultiField'],
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'value' => '1', 'id' => 'ModelMultiField1']],
            ['label' => ['for' => 'ModelMultiField1']],
            'first',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Model.multi_field', [
            'options' => ['2' => 'second'],
            'multiple' => 'checkbox',
            'label' => false,
            'div' => false,
        ]);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][multi_field]', 'value' => '', 'id' => 'ModelMultiField'],
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'value' => '2', 'id' => 'ModelMultiField2']],
            ['label' => ['for' => 'ModelMultiField2']],
            'second',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testSelectHiddenFieldOmission method.
     *
     * test that select() with 'hiddenField' => false omits the hidden field
     */
    public function testSelectHiddenFieldOmission()
    {
        $result = $this->Form->select('Model.multi_field',
            ['first', 'second'],
            null,
            ['multiple' => 'checkbox', 'hiddenField' => false]
        );
        $expected = [
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'value' => '0', 'id' => 'ModelMultiField0']],
            ['label' => ['for' => 'ModelMultiField0']],
            'first',
            '/label',
            '/div',
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'value' => '1', 'id' => 'ModelMultiField1']],
            ['label' => ['for' => 'ModelMultiField1']],
            'second',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Model.multi_field', [
            'options' => ['first', 'second'],
            'multiple' => 'checkbox',
            'hiddenField' => false,
        ]);
        $expected = [
            ['div' => ['class' => 'input select']],
            ['label' => ['for' => 'ModelMultiField']],
            'Multi Field',
            '/label',
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'value' => '0', 'id' => 'ModelMultiField0']],
            ['label' => ['for' => 'ModelMultiField0']],
            'first',
            '/label',
            '/div',
            ['div' => ['class' => 'checkbox']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][multi_field][]', 'value' => '1', 'id' => 'ModelMultiField1']],
            ['label' => ['for' => 'ModelMultiField1']],
            'second',
            '/label',
            '/div',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test that select() with multiple = checkbox works with overriding name attribute.
     */
    public function testSelectCheckboxMultipleOverrideName()
    {
        $result = $this->Form->input('category', [
            'type' => 'select',
            'multiple' => 'checkbox',
            'name' => 'data[fish]',
            'options' => ['1', '2'],
            'div' => false,
            'label' => false,
        ]);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[fish]', 'value' => '', 'id' => 'category'],
            ['div' => ['class' => 'checkbox']],
                ['input' => ['type' => 'checkbox', 'name' => 'data[fish][]', 'value' => '0', 'id' => 'Category0']],
                ['label' => ['for' => 'Category0']], '1', '/label',
            '/div',
            ['div' => ['class' => 'checkbox']],
                ['input' => ['type' => 'checkbox', 'name' => 'data[fish][]', 'value' => '1', 'id' => 'Category1']],
                ['label' => ['for' => 'Category1']], '2', '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testCheckbox method.
     *
     * Test generation of checkboxes
     */
    public function testCheckbox()
    {
        $result = $this->Form->checkbox('Model.field', ['id' => 'theID', 'value' => 'myvalue']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'theID_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][field]', 'value' => 'myvalue', 'id' => 'theID']],
        ];
        $this->assertTags($result, $expected);

        $this->Form->validationErrors['Model']['field'] = 1;
        $this->Form->data['Model']['field'] = 'myvalue';
        $result = $this->Form->checkbox('Model.field', ['id' => 'theID', 'value' => 'myvalue']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'theID_'],
            ['input' => ['preg:/[^<]+/', 'value' => 'myvalue', 'id' => 'theID', 'checked' => 'checked', 'class' => 'form-error']],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->checkbox('Model.field', ['value' => 'myvalue']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField_'],
            ['input' => ['preg:/[^<]+/', 'value' => 'myvalue', 'id' => 'ModelField', 'checked' => 'checked', 'class' => 'form-error']],
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Model']['field'] = '';
        $result = $this->Form->checkbox('Model.field', ['id' => 'theID']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'theID_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][field]', 'value' => '1', 'id' => 'theID', 'class' => 'form-error']],
        ];
        $this->assertTags($result, $expected);

        unset($this->Form->validationErrors['Model']['field']);
        $result = $this->Form->checkbox('Model.field', ['value' => 'myvalue']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][field]', 'value' => 'myvalue', 'id' => 'ModelField']],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->checkbox('Contact.name', ['value' => 'myvalue']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Contact][name]', 'value' => '0', 'id' => 'ContactName_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Contact][name]', 'value' => 'myvalue', 'id' => 'ContactName']],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->checkbox('Model.field');
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][field]', 'value' => '1', 'id' => 'ModelField']],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->checkbox('Model.field', ['checked' => false]);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][field]', 'value' => '1', 'id' => 'ModelField']],
        ];
        $this->assertTags($result, $expected);

        $this->Form->validationErrors['Model']['field'] = 1;
        $this->Form->data['Contact']['published'] = 1;
        $result = $this->Form->checkbox('Contact.published', ['id' => 'theID']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Contact][published]', 'value' => '0', 'id' => 'theID_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Contact][published]', 'value' => '1', 'id' => 'theID', 'checked' => 'checked']],
        ];
        $this->assertTags($result, $expected);

        $this->Form->validationErrors['Model']['field'] = 1;
        $this->Form->data['Contact']['published'] = 0;
        $result = $this->Form->checkbox('Contact.published', ['id' => 'theID']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Contact][published]', 'value' => '0', 'id' => 'theID_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Contact][published]', 'value' => '1', 'id' => 'theID']],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->checkbox('Model.CustomField.1.value');
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][CustomField][1][value]', 'value' => '0', 'id' => 'ModelCustomField1Value_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][CustomField][1][value]', 'value' => '1', 'id' => 'ModelCustomField1Value']],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->checkbox('CustomField.1.value');
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[CustomField][1][value]', 'value' => '0', 'id' => 'CustomField1Value_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[CustomField][1][value]', 'value' => '1', 'id' => 'CustomField1Value']],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->checkbox('Test.test', ['name' => 'myField']);
        $expected = [
                'input' => ['type' => 'hidden', 'name' => 'myField', 'value' => '0', 'id' => 'TestTest_'],
                ['input' => ['type' => 'checkbox', 'name' => 'myField', 'value' => '1', 'id' => 'TestTest']],
            ];
        $this->assertTags($result, $expected);
    }

    /**
     * test the checked option for checkboxes.
     */
    public function testCheckboxCheckedOption()
    {
        $result = $this->Form->checkbox('Model.field', ['checked' => 'checked']);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][field]', 'value' => '1', 'id' => 'ModelField', 'checked' => 'checked']],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->checkbox('Model.field', ['checked' => 1]);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][field]', 'value' => '1', 'id' => 'ModelField', 'checked' => 'checked']],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->checkbox('Model.field', ['checked' => true]);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][field]', 'value' => '1', 'id' => 'ModelField', 'checked' => 'checked']],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->checkbox('Model.field', ['checked' => false]);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][field]', 'value' => '1', 'id' => 'ModelField']],
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Model']['field'] = 1;
        $result = $this->Form->checkbox('Model.field', ['checked' => false]);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'value' => '0', 'id' => 'ModelField_'],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Model][field]', 'value' => '1', 'id' => 'ModelField']],
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * Test that disabling a checkbox also disables the hidden input so no value is submitted.
     */
    public function testCheckboxDisabling()
    {
        $result = $this->Form->checkbox('Account.show_name', ['disabled' => 'disabled']);
        $expected = [
            ['input' => ['type' => 'hidden', 'name' => 'data[Account][show_name]', 'value' => '0', 'id' => 'AccountShowName_', 'disabled' => 'disabled']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Account][show_name]', 'value' => '1', 'id' => 'AccountShowName', 'disabled' => 'disabled']],
        ];
        $this->assertTags($result, $expected, true);
    }

    /**
     * Test that specifying false in the 'disabled' option will not disable either the hidden input or the checkbox input.
     */
    public function testCheckboxHiddenDisabling()
    {
        $result = $this->Form->checkbox('Account.show_name', ['disabled' => false]);
        $expected = [
            ['input' => ['type' => 'hidden', 'name' => 'data[Account][show_name]', 'value' => '0', 'id' => 'AccountShowName_']],
            ['input' => ['type' => 'checkbox', 'name' => 'data[Account][show_name]', 'value' => '1', 'id' => 'AccountShowName']],
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * Test that the hidden input for checkboxes can be removed/omitted from the output.
     */
    public function testCheckboxHiddenFieldOmission()
    {
        $result = $this->Form->input('UserForm.something', [
                'type' => 'checkbox',
                'hiddenField' => false,
            ]
        );
        $expected = [
            'div' => ['class' => 'input checkbox'],
            ['input' => [
                'type' => 'checkbox', 'name' => 'data[UserForm][something]',
                'value' => '1', 'id' => 'UserFormSomething',
            ]],
            'label' => ['for' => 'UserFormSomething'],
            'Something',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testDateTime method.
     *
     * Test generation of date/time select elements
     */
    public function testDateTime()
    {
        extract($this->dateRegex);

        $result = $this->Form->dateTime('Contact.date', 'DMY', '12', null, ['empty' => false]);
        $now = strtotime('now');
        $expected = [
            ['select' => ['name' => 'data[Contact][date][day]', 'id' => 'ContactDateDay']],
            $daysRegex,
            ['option' => ['value' => date('d', $now), 'selected' => 'selected']],
            date('j', $now),
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][date][month]', 'id' => 'ContactDateMonth']],
            $monthsRegex,
            ['option' => ['value' => date('m', $now), 'selected' => 'selected']],
            date('F', $now),
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][date][year]', 'id' => 'ContactDateYear']],
            $yearsRegex,
            ['option' => ['value' => date('Y', $now), 'selected' => 'selected']],
            date('Y', $now),
            '/option',
            '*/select',
            ['select' => ['name' => 'data[Contact][date][hour]', 'id' => 'ContactDateHour']],
            $hoursRegex,
            ['option' => ['value' => date('h', $now), 'selected' => 'selected']],
            date('g', $now),
            '/option',
            '*/select',
            ':',
            ['select' => ['name' => 'data[Contact][date][min]', 'id' => 'ContactDateMin']],
            $minutesRegex,
            ['option' => ['value' => date('i', $now), 'selected' => 'selected']],
            date('i', $now),
            '/option',
            '*/select',
            ' ',
            ['select' => ['name' => 'data[Contact][date][meridian]', 'id' => 'ContactDateMeridian']],
            $meridianRegex,
            ['option' => ['value' => date('a', $now), 'selected' => 'selected']],
            date('a', $now),
            '/option',
            '*/select',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->dateTime('Contact.date', 'DMY', '12');
        $expected = [
            ['select' => ['name' => 'data[Contact][date][day]', 'id' => 'ContactDateDay']],
            $daysRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][date][month]', 'id' => 'ContactDateMonth']],
            $monthsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][date][year]', 'id' => 'ContactDateYear']],
            $yearsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ['select' => ['name' => 'data[Contact][date][hour]', 'id' => 'ContactDateHour']],
            $hoursRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ':',
            ['select' => ['name' => 'data[Contact][date][min]', 'id' => 'ContactDateMin']],
            $minutesRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ' ',
            ['select' => ['name' => 'data[Contact][date][meridian]', 'id' => 'ContactDateMeridian']],
            $meridianRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
        ];
        $this->assertTags($result, $expected);
        $this->assertNoPattern('/<option[^<>]+value=""[^<>]+selected="selected"[^>]*>/', $result);

        $result = $this->Form->dateTime('Contact.date', 'DMY', '12', false);
        $expected = [
            ['select' => ['name' => 'data[Contact][date][day]', 'id' => 'ContactDateDay']],
            $daysRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][date][month]', 'id' => 'ContactDateMonth']],
            $monthsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][date][year]', 'id' => 'ContactDateYear']],
            $yearsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ['select' => ['name' => 'data[Contact][date][hour]', 'id' => 'ContactDateHour']],
            $hoursRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ':',
            ['select' => ['name' => 'data[Contact][date][min]', 'id' => 'ContactDateMin']],
            $minutesRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ' ',
            ['select' => ['name' => 'data[Contact][date][meridian]', 'id' => 'ContactDateMeridian']],
            $meridianRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
        ];
        $this->assertTags($result, $expected);
        $this->assertNoPattern('/<option[^<>]+value=""[^<>]+selected="selected"[^>]*>/', $result);

        $result = $this->Form->dateTime('Contact.date', 'DMY', '12', '');
        $expected = [
            ['select' => ['name' => 'data[Contact][date][day]', 'id' => 'ContactDateDay']],
            $daysRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][date][month]', 'id' => 'ContactDateMonth']],
            $monthsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][date][year]', 'id' => 'ContactDateYear']],
            $yearsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ['select' => ['name' => 'data[Contact][date][hour]', 'id' => 'ContactDateHour']],
            $hoursRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ':',
            ['select' => ['name' => 'data[Contact][date][min]', 'id' => 'ContactDateMin']],
            $minutesRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ' ',
            ['select' => ['name' => 'data[Contact][date][meridian]', 'id' => 'ContactDateMeridian']],
            $meridianRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
        ];
        $this->assertTags($result, $expected);
        $this->assertNoPattern('/<option[^<>]+value=""[^<>]+selected="selected"[^>]*>/', $result);

        $result = $this->Form->dateTime('Contact.date', 'DMY', '12', '', ['interval' => 5]);
        $expected = [
            ['select' => ['name' => 'data[Contact][date][day]', 'id' => 'ContactDateDay']],
            $daysRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][date][month]', 'id' => 'ContactDateMonth']],
            $monthsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][date][year]', 'id' => 'ContactDateYear']],
            $yearsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ['select' => ['name' => 'data[Contact][date][hour]', 'id' => 'ContactDateHour']],
            $hoursRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ':',
            ['select' => ['name' => 'data[Contact][date][min]', 'id' => 'ContactDateMin']],
            $minutesRegex,
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '00']],
            '00',
            '/option',
            ['option' => ['value' => '05']],
            '05',
            '/option',
            ['option' => ['value' => '10']],
            '10',
            '/option',
            '*/select',
            ' ',
            ['select' => ['name' => 'data[Contact][date][meridian]', 'id' => 'ContactDateMeridian']],
            $meridianRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
        ];
        $this->assertTags($result, $expected);
        $this->assertNoPattern('/<option[^<>]+value=""[^<>]+selected="selected"[^>]*>/', $result);

        $result = $this->Form->dateTime('Contact.date', 'DMY', '12', '', ['minuteInterval' => 5]);
        $expected = [
            ['select' => ['name' => 'data[Contact][date][day]', 'id' => 'ContactDateDay']],
            $daysRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][date][month]', 'id' => 'ContactDateMonth']],
            $monthsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][date][year]', 'id' => 'ContactDateYear']],
            $yearsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ['select' => ['name' => 'data[Contact][date][hour]', 'id' => 'ContactDateHour']],
            $hoursRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ':',
            ['select' => ['name' => 'data[Contact][date][min]', 'id' => 'ContactDateMin']],
            $minutesRegex,
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '00']],
            '00',
            '/option',
            ['option' => ['value' => '05']],
            '05',
            '/option',
            ['option' => ['value' => '10']],
            '10',
            '/option',
            '*/select',
            ' ',
            ['select' => ['name' => 'data[Contact][date][meridian]', 'id' => 'ContactDateMeridian']],
            $meridianRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
        ];
        $this->assertTags($result, $expected);
        $this->assertNoPattern('/<option[^<>]+value=""[^<>]+selected="selected"[^>]*>/', $result);

        $this->Form->data['Contact']['data'] = null;
        $result = $this->Form->dateTime('Contact.date', 'DMY', '12');
        $expected = [
            ['select' => ['name' => 'data[Contact][date][day]', 'id' => 'ContactDateDay']],
            $daysRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][date][month]', 'id' => 'ContactDateMonth']],
            $monthsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][date][year]', 'id' => 'ContactDateYear']],
            $yearsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ['select' => ['name' => 'data[Contact][date][hour]', 'id' => 'ContactDateHour']],
            $hoursRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ':',
            ['select' => ['name' => 'data[Contact][date][min]', 'id' => 'ContactDateMin']],
            $minutesRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ' ',
            ['select' => ['name' => 'data[Contact][date][meridian]', 'id' => 'ContactDateMeridian']],
            $meridianRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
        ];
        $this->assertTags($result, $expected);
        $this->assertNoPattern('/<option[^<>]+value=""[^<>]+selected="selected"[^>]*>/', $result);

        $this->Form->data['Model']['field'] = date('Y').'-01-01 00:00:00';
        $now = strtotime($this->Form->data['Model']['field']);
        $result = $this->Form->dateTime('Model.field', 'DMY', '12', null, ['empty' => false]);
        $expected = [
            ['select' => ['name' => 'data[Model][field][day]', 'id' => 'ModelFieldDay']],
            $daysRegex,
            ['option' => ['value' => date('d', $now), 'selected' => 'selected']],
            date('j', $now),
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Model][field][month]', 'id' => 'ModelFieldMonth']],
            $monthsRegex,
            ['option' => ['value' => date('m', $now), 'selected' => 'selected']],
            date('F', $now),
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Model][field][year]', 'id' => 'ModelFieldYear']],
            $yearsRegex,
            ['option' => ['value' => date('Y', $now), 'selected' => 'selected']],
            date('Y', $now),
            '/option',
            '*/select',
            ['select' => ['name' => 'data[Model][field][hour]', 'id' => 'ModelFieldHour']],
            $hoursRegex,
            ['option' => ['value' => date('h', $now), 'selected' => 'selected']],
            date('g', $now),
            '/option',
            '*/select',
            ':',
            ['select' => ['name' => 'data[Model][field][min]', 'id' => 'ModelFieldMin']],
            $minutesRegex,
            ['option' => ['value' => date('i', $now), 'selected' => 'selected']],
            date('i', $now),
            '/option',
            '*/select',
            ' ',
            ['select' => ['name' => 'data[Model][field][meridian]', 'id' => 'ModelFieldMeridian']],
            $meridianRegex,
            ['option' => ['value' => date('a', $now), 'selected' => 'selected']],
            date('a', $now),
            '/option',
            '*/select',
        ];
        $this->assertTags($result, $expected);

        $selected = strtotime('2008-10-26 12:33:00');
        $result = $this->Form->dateTime('Model.field', 'DMY', '12', $selected);
        $this->assertPattern('/<option[^<>]+value="2008"[^<>]+selected="selected"[^>]*>2008<\/option>/', $result);
        $this->assertPattern('/<option[^<>]+value="10"[^<>]+selected="selected"[^>]*>October<\/option>/', $result);
        $this->assertPattern('/<option[^<>]+value="26"[^<>]+selected="selected"[^>]*>26<\/option>/', $result);
        $this->assertPattern('/<option[^<>]+value="12"[^<>]+selected="selected"[^>]*>12<\/option>/', $result);
        $this->assertPattern('/<option[^<>]+value="33"[^<>]+selected="selected"[^>]*>33<\/option>/', $result);
        $this->assertPattern('/<option[^<>]+value="pm"[^<>]+selected="selected"[^>]*>pm<\/option>/', $result);

        $this->Form->create('Contact');
        $result = $this->Form->input('published');
        $now = strtotime('now');
        $expected = [
            'div' => ['class' => 'input date'],
            'label' => ['for' => 'ContactPublishedMonth'],
            'Published',
            '/label',
            ['select' => ['name' => 'data[Contact][published][month]', 'id' => 'ContactPublishedMonth']],
            $monthsRegex,
            ['option' => ['value' => date('m', $now), 'selected' => 'selected']],
            date('F', $now),
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][published][day]', 'id' => 'ContactPublishedDay']],
            $daysRegex,
            ['option' => ['value' => date('d', $now), 'selected' => 'selected']],
            date('j', $now),
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][published][year]', 'id' => 'ContactPublishedYear']],
            $yearsRegex,
            ['option' => ['value' => date('Y', $now), 'selected' => 'selected']],
            date('Y', $now),
            '/option',
            '*/select',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('published2', ['type' => 'date']);
        $now = strtotime('now');
        $expected = [
            'div' => ['class' => 'input date'],
            'label' => ['for' => 'ContactPublished2Month'],
            'Published2',
            '/label',
            ['select' => ['name' => 'data[Contact][published2][month]', 'id' => 'ContactPublished2Month']],
            $monthsRegex,
            ['option' => ['value' => date('m', $now), 'selected' => 'selected']],
            date('F', $now),
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][published2][day]', 'id' => 'ContactPublished2Day']],
            $daysRegex,
            ['option' => ['value' => date('d', $now), 'selected' => 'selected']],
            date('j', $now),
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][published2][year]', 'id' => 'ContactPublished2Year']],
            $yearsRegex,
            ['option' => ['value' => date('Y', $now), 'selected' => 'selected']],
            date('Y', $now),
            '/option',
            '*/select',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('ContactTag');
        $expected = [
            'div' => ['class' => 'input select'],
            'label' => ['for' => 'ContactTagContactTag'],
            'Contact Tag',
            '/label',
            ['input' => ['type' => 'hidden', 'name' => 'data[ContactTag][ContactTag]', 'value' => '', 'id' => 'ContactTagContactTag_']],
            ['select' => ['name' => 'data[ContactTag][ContactTag][]', 'multiple' => 'multiple', 'id' => 'ContactTagContactTag']],
            '/select',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->create('Contact');
        $result = $this->Form->input('published', ['monthNames' => false]);
        $now = strtotime('now');
        $expected = [
            'div' => ['class' => 'input date'],
            'label' => ['for' => 'ContactPublishedMonth'],
            'Published',
            '/label',
            ['select' => ['name' => 'data[Contact][published][month]', 'id' => 'ContactPublishedMonth']],
            'preg:/(?:<option value="([\d])+">[\d]+<\/option>[\r\n]*)*/',
            ['option' => ['value' => date('m', $now), 'selected' => 'selected']],
            date('m', $now),
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][published][day]', 'id' => 'ContactPublishedDay']],
            $daysRegex,
            ['option' => ['value' => date('d', $now), 'selected' => 'selected']],
            date('j', $now),
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][published][year]', 'id' => 'ContactPublishedYear']],
            $yearsRegex,
            ['option' => ['value' => date('Y', $now), 'selected' => 'selected']],
            date('Y', $now),
            '/option',
            '*/select',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('published', ['type' => 'time']);
        $now = strtotime('now');
        $expected = [
            'div' => ['class' => 'input time'],
            'label' => ['for' => 'ContactPublishedHour'],
            'Published',
            '/label',
            ['select' => ['name' => 'data[Contact][published][hour]', 'id' => 'ContactPublishedHour']],
            'preg:/(?:<option value="([\d])+">[\d]+<\/option>[\r\n]*)*/',
            ['option' => ['value' => date('h', $now), 'selected' => 'selected']],
            date('g', $now),
            '/option',
            '*/select',
            ':',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('published', [
            'timeFormat' => 24,
            'interval' => 5,
            'selected' => strtotime('2009-09-03 13:37:00'),
            'type' => 'datetime',
        ]);
        $this->assertPattern('/<option[^<>]+value="2009"[^<>]+selected="selected"[^>]*>2009<\/option>/', $result);
        $this->assertPattern('/<option[^<>]+value="09"[^<>]+selected="selected"[^>]*>September<\/option>/', $result);
        $this->assertPattern('/<option[^<>]+value="03"[^<>]+selected="selected"[^>]*>3<\/option>/', $result);
        $this->assertPattern('/<option[^<>]+value="13"[^<>]+selected="selected"[^>]*>13<\/option>/', $result);
        $this->assertPattern('/<option[^<>]+value="35"[^<>]+selected="selected"[^>]*>35<\/option>/', $result);

        $this->assertNoErrors();
        $this->Form->data['Contact'] = [
            'date' => [
                'day' => '',
                'month' => '',
                'year' => '',
                'hour' => '',
                'min' => '',
                'meridian' => '',
            ],
        ];
        $result = $this->Form->dateTime('Contact.date', 'DMY', '12', null, ['empty' => false]);
    }

    /**
     * test that datetime() and default values work.
     */
    public function testDatetimeWithDefault()
    {
        $result = $this->Form->dateTime('Contact.updated', 'DMY', '12', null, ['value' => '2009-06-01 11:15:30']);
        $this->assertPattern('/<option[^<>]+value="2009"[^<>]+selected="selected"[^>]*>2009<\/option>/', $result);
        $this->assertPattern('/<option[^<>]+value="01"[^<>]+selected="selected"[^>]*>1<\/option>/', $result);
        $this->assertPattern('/<option[^<>]+value="06"[^<>]+selected="selected"[^>]*>June<\/option>/', $result);

        $result = $this->Form->dateTime('Contact.updated', 'DMY', '12', null, [
            'default' => '2009-06-01 11:15:30',
        ]);
        $this->assertPattern('/<option[^<>]+value="2009"[^<>]+selected="selected"[^>]*>2009<\/option>/', $result);
        $this->assertPattern('/<option[^<>]+value="01"[^<>]+selected="selected"[^>]*>1<\/option>/', $result);
        $this->assertPattern('/<option[^<>]+value="06"[^<>]+selected="selected"[^>]*>June<\/option>/', $result);
    }

    /**
     * test that bogus non-date time data doesn't cause errors.
     */
    public function testDateTimeWithBogusData()
    {
        $result = $this->Form->dateTime('Contact.updated', 'DMY', '12', 'CURRENT_TIMESTAMP');
        $this->assertNoPattern('/selected="selected">\d/', $result);
    }

    /**
     * testFormDateTimeMulti method.
     *
     * test multiple datetime element generation
     */
    public function testFormDateTimeMulti()
    {
        extract($this->dateRegex);

        $result = $this->Form->dateTime('Contact.1.updated');
        $expected = [
            ['select' => ['name' => 'data[Contact][1][updated][day]', 'id' => 'Contact1UpdatedDay']],
            $daysRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][1][updated][month]', 'id' => 'Contact1UpdatedMonth']],
            $monthsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][1][updated][year]', 'id' => 'Contact1UpdatedYear']],
            $yearsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ['select' => ['name' => 'data[Contact][1][updated][hour]', 'id' => 'Contact1UpdatedHour']],
            $hoursRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ':',
            ['select' => ['name' => 'data[Contact][1][updated][min]', 'id' => 'Contact1UpdatedMin']],
            $minutesRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ' ',
            ['select' => ['name' => 'data[Contact][1][updated][meridian]', 'id' => 'Contact1UpdatedMeridian']],
            $meridianRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->dateTime('Contact.2.updated');
        $expected = [
            ['select' => ['name' => 'data[Contact][2][updated][day]', 'id' => 'Contact2UpdatedDay']],
            $daysRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][2][updated][month]', 'id' => 'Contact2UpdatedMonth']],
            $monthsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            '-',
            ['select' => ['name' => 'data[Contact][2][updated][year]', 'id' => 'Contact2UpdatedYear']],
            $yearsRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ['select' => ['name' => 'data[Contact][2][updated][hour]', 'id' => 'Contact2UpdatedHour']],
            $hoursRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ':',
            ['select' => ['name' => 'data[Contact][2][updated][min]', 'id' => 'Contact2UpdatedMin']],
            $minutesRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
            ' ',
            ['select' => ['name' => 'data[Contact][2][updated][meridian]', 'id' => 'Contact2UpdatedMeridian']],
            $meridianRegex,
            ['option' => ['value' => '']],
            '/option',
            '*/select',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testMonth method.
     */
    public function testMonth()
    {
        $result = $this->Form->month('Model.field');
        $expected = [
            ['select' => ['name' => 'data[Model][field][month]', 'id' => 'ModelFieldMonth']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '01']],
            date('F', strtotime('2008-01-01 00:00:00')),
            '/option',
            ['option' => ['value' => '02']],
            date('F', strtotime('2008-02-01 00:00:00')),
            '/option',
            '*/select',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->month('Model.field', null, ['empty' => true]);
        $expected = [
            ['select' => ['name' => 'data[Model][field][month]', 'id' => 'ModelFieldMonth']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '01']],
            date('F', strtotime('2008-01-01 00:00:00')),
            '/option',
            ['option' => ['value' => '02']],
            date('F', strtotime('2008-02-01 00:00:00')),
            '/option',
            '*/select',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->month('Model.field', null, ['monthNames' => false]);
        $expected = [
            ['select' => ['name' => 'data[Model][field][month]', 'id' => 'ModelFieldMonth']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '01']],
            '01',
            '/option',
            ['option' => ['value' => '02']],
            '02',
            '/option',
            '*/select',
        ];
        $this->assertTags($result, $expected);

        $monthNames = [
            '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun',
            '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec', ];
        $result = $this->Form->month('Model.field', null, ['monthNames' => $monthNames]);
        $expected = [
            ['select' => ['name' => 'data[Model][field][month]', 'id' => 'ModelFieldMonth']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '01']],
            'Jan',
            '/option',
            ['option' => ['value' => '02']],
            'Feb',
            '/option',
            '*/select',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testDay method.
     */
    public function testDay()
    {
        extract($this->dateRegex);

        $result = $this->Form->day('Model.field', false);
        $expected = [
            ['select' => ['name' => 'data[Model][field][day]', 'id' => 'ModelFieldDay']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '01']],
            '1',
            '/option',
            ['option' => ['value' => '02']],
            '2',
            '/option',
            $daysRegex,
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Model']['field'] = '2006-10-10 23:12:32';
        $result = $this->Form->day('Model.field');
        $expected = [
            ['select' => ['name' => 'data[Model][field][day]', 'id' => 'ModelFieldDay']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '01']],
            '1',
            '/option',
            ['option' => ['value' => '02']],
            '2',
            '/option',
            $daysRegex,
            ['option' => ['value' => '10', 'selected' => 'selected']],
            '10',
            '/option',
            $daysRegex,
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Model']['field'] = '';
        $result = $this->Form->day('Model.field', '10');
        $expected = [
            ['select' => ['name' => 'data[Model][field][day]', 'id' => 'ModelFieldDay']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '01']],
            '1',
            '/option',
            ['option' => ['value' => '02']],
            '2',
            '/option',
            $daysRegex,
            ['option' => ['value' => '10', 'selected' => 'selected']],
            '10',
            '/option',
            $daysRegex,
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Model']['field'] = '2006-10-10 23:12:32';
        $result = $this->Form->day('Model.field', true);
        $expected = [
            ['select' => ['name' => 'data[Model][field][day]', 'id' => 'ModelFieldDay']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '01']],
            '1',
            '/option',
            ['option' => ['value' => '02']],
            '2',
            '/option',
            $daysRegex,
            ['option' => ['value' => '10', 'selected' => 'selected']],
            '10',
            '/option',
            $daysRegex,
            '/select',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testMinute method.
     */
    public function testMinute()
    {
        extract($this->dateRegex);

        $result = $this->Form->minute('Model.field');
        $expected = [
            ['select' => ['name' => 'data[Model][field][min]', 'id' => 'ModelFieldMin']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '00']],
            '00',
            '/option',
            ['option' => ['value' => '01']],
            '01',
            '/option',
            ['option' => ['value' => '02']],
            '02',
            '/option',
            $minutesRegex,
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Model']['field'] = '2006-10-10 00:12:32';
        $result = $this->Form->minute('Model.field');
        $expected = [
            ['select' => ['name' => 'data[Model][field][min]', 'id' => 'ModelFieldMin']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '00']],
            '00',
            '/option',
            ['option' => ['value' => '01']],
            '01',
            '/option',
            ['option' => ['value' => '02']],
            '02',
            '/option',
            $minutesRegex,
            ['option' => ['value' => '12', 'selected' => 'selected']],
            '12',
            '/option',
            $minutesRegex,
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Model']['field'] = '';
        $result = $this->Form->minute('Model.field', null, ['interval' => 5]);
        $expected = [
            ['select' => ['name' => 'data[Model][field][min]', 'id' => 'ModelFieldMin']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '00']],
            '00',
            '/option',
            ['option' => ['value' => '05']],
            '05',
            '/option',
            ['option' => ['value' => '10']],
            '10',
            '/option',
            $minutesRegex,
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Model']['field'] = '2006-10-10 00:10:32';
        $result = $this->Form->minute('Model.field', null, ['interval' => 5]);
        $expected = [
            ['select' => ['name' => 'data[Model][field][min]', 'id' => 'ModelFieldMin']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '00']],
            '00',
            '/option',
            ['option' => ['value' => '05']],
            '05',
            '/option',
            ['option' => ['value' => '10', 'selected' => 'selected']],
            '10',
            '/option',
            $minutesRegex,
            '/select',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testHour method.
     */
    public function testHour()
    {
        extract($this->dateRegex);

        $result = $this->Form->hour('Model.field', false);
        $expected = [
            ['select' => ['name' => 'data[Model][field][hour]', 'id' => 'ModelFieldHour']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '01']],
            '1',
            '/option',
            ['option' => ['value' => '02']],
            '2',
            '/option',
            $hoursRegex,
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Model']['field'] = '2006-10-10 00:12:32';
        $result = $this->Form->hour('Model.field', false);
        $expected = [
            ['select' => ['name' => 'data[Model][field][hour]', 'id' => 'ModelFieldHour']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '01']],
            '1',
            '/option',
            ['option' => ['value' => '02']],
            '2',
            '/option',
            $hoursRegex,
            ['option' => ['value' => '12', 'selected' => 'selected']],
            '12',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Model']['field'] = '';
        $result = $this->Form->hour('Model.field', true, '23');
        $expected = [
            ['select' => ['name' => 'data[Model][field][hour]', 'id' => 'ModelFieldHour']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '00']],
            '0',
            '/option',
            ['option' => ['value' => '01']],
            '1',
            '/option',
            ['option' => ['value' => '02']],
            '2',
            '/option',
            $hoursRegex,
            ['option' => ['value' => '23', 'selected' => 'selected']],
            '23',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Model']['field'] = '2006-10-10 00:12:32';
        $result = $this->Form->hour('Model.field', true);
        $expected = [
            ['select' => ['name' => 'data[Model][field][hour]', 'id' => 'ModelFieldHour']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '00', 'selected' => 'selected']],
            '0',
            '/option',
            ['option' => ['value' => '01']],
            '1',
            '/option',
            ['option' => ['value' => '02']],
            '2',
            '/option',
            $hoursRegex,
            '/select',
        ];
        $this->assertTags($result, $expected);

        unset($this->Form->data['Model']['field']);
        $result = $this->Form->hour('Model.field', true, 'now');
        $thisHour = date('H');
        $optValue = date('G');
        $this->assertPattern('/<option value="'.$thisHour.'" selected="selected">'.$optValue.'<\/option>/', $result);
    }

    /**
     * testYear method.
     */
    public function testYear()
    {
        $result = $this->Form->year('Model.field', 2006, 2007);
        $expected = [
            ['select' => ['name' => 'data[Model][field][year]', 'id' => 'ModelFieldYear']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '2007']],
            '2007',
            '/option',
            ['option' => ['value' => '2006']],
            '2006',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->year('Model.field', 2006, 2007, null, ['orderYear' => 'asc']);
        $expected = [
            ['select' => ['name' => 'data[Model][field][year]', 'id' => 'ModelFieldYear']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '2006']],
            '2006',
            '/option',
            ['option' => ['value' => '2007']],
            '2007',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->data['Contact']['published'] = '';
        $result = $this->Form->year('Contact.published', 2006, 2007, null, ['class' => 'year']);
        $expected = [
            ['select' => ['name' => 'data[Contact][published][year]', 'id' => 'ContactPublishedYear', 'class' => 'year']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '2007']],
            '2007',
            '/option',
            ['option' => ['value' => '2006']],
            '2006',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Contact']['published'] = '2006-10-10';
        $result = $this->Form->year('Contact.published', 2006, 2007, null, ['empty' => false]);
        $expected = [
            ['select' => ['name' => 'data[Contact][published][year]', 'id' => 'ContactPublishedYear']],
            ['option' => ['value' => '2007']],
            '2007',
            '/option',
            ['option' => ['value' => '2006', 'selected' => 'selected']],
            '2006',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Contact']['published'] = '';
        $result = $this->Form->year('Contact.published', 2006, 2007, false);
        $expected = [
            ['select' => ['name' => 'data[Contact][published][year]', 'id' => 'ContactPublishedYear']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '2007']],
            '2007',
            '/option',
            ['option' => ['value' => '2006']],
            '2006',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Contact']['published'] = '2006-10-10';
        $result = $this->Form->year('Contact.published', 2006, 2007, false, ['empty' => false]);
        $expected = [
            ['select' => ['name' => 'data[Contact][published][year]', 'id' => 'ContactPublishedYear']],
            ['option' => ['value' => '2007']],
            '2007',
            '/option',
            ['option' => ['value' => '2006', 'selected' => 'selected']],
            '2006',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Contact']['published'] = '';
        $result = $this->Form->year('Contact.published', 2006, 2007, 2007);
        $expected = [
            ['select' => ['name' => 'data[Contact][published][year]', 'id' => 'ContactPublishedYear']],
            ['option' => ['value' => '']],
            '/option',
            ['option' => ['value' => '2007', 'selected' => 'selected']],
            '2007',
            '/option',
            ['option' => ['value' => '2006']],
            '2006',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Contact']['published'] = '2006-10-10';
        $result = $this->Form->year('Contact.published', 2006, 2007, 2007, ['empty' => false]);
        $expected = [
            ['select' => ['name' => 'data[Contact][published][year]', 'id' => 'ContactPublishedYear']],
            ['option' => ['value' => '2007', 'selected' => 'selected']],
            '2007',
            '/option',
            ['option' => ['value' => '2006']],
            '2006',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Contact']['published'] = '';
        $result = $this->Form->year('Contact.published', 2006, 2008, 2007, ['empty' => false]);
        $expected = [
            ['select' => ['name' => 'data[Contact][published][year]', 'id' => 'ContactPublishedYear']],
            ['option' => ['value' => '2008']],
            '2008',
            '/option',
            ['option' => ['value' => '2007', 'selected' => 'selected']],
            '2007',
            '/option',
            ['option' => ['value' => '2006']],
            '2006',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Contact']['published'] = '2006-10-10';
        $result = $this->Form->year('Contact.published', 2006, 2008, null, ['empty' => false]);
        $expected = [
            ['select' => ['name' => 'data[Contact][published][year]', 'id' => 'ContactPublishedYear']],
            ['option' => ['value' => '2008']],
            '2008',
            '/option',
            ['option' => ['value' => '2007']],
            '2007',
            '/option',
            ['option' => ['value' => '2006', 'selected' => 'selected']],
            '2006',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = [];
        $this->Form->create('Contact');
        $result = $this->Form->year('published', 2006, 2008, null, ['empty' => false]);
        $expected = [
            ['select' => ['name' => 'data[Contact][published][year]', 'id' => 'ContactPublishedYear']],
            ['option' => ['value' => '2008']],
            '2008',
            '/option',
            ['option' => ['value' => '2007']],
            '2007',
            '/option',
            ['option' => ['value' => '2006']],
            '2006',
            '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testTextArea method.
     */
    public function testTextArea()
    {
        $this->Form->data = ['Model' => ['field' => 'some test data']];
        $result = $this->Form->textarea('Model.field');
        $expected = [
            'textarea' => ['name' => 'data[Model][field]', 'id' => 'ModelField'],
            'some test data',
            '/textarea',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->textarea('Model.tmp');
        $expected = [
            'textarea' => ['name' => 'data[Model][tmp]', 'id' => 'ModelTmp'],
            '/textarea',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = ['Model' => ['field' => 'some <strong>test</strong> data with <a href="#">HTML</a> chars']];
        $result = $this->Form->textarea('Model.field');
        $expected = [
            'textarea' => ['name' => 'data[Model][field]', 'id' => 'ModelField'],
            htmlentities('some <strong>test</strong> data with <a href="#">HTML</a> chars'),
            '/textarea',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = ['Model' => ['field' => 'some <strong>test</strong> data with <a href="#">HTML</a> chars']];
        $result = $this->Form->textarea('Model.field', ['escape' => false]);
        $expected = [
            'textarea' => ['name' => 'data[Model][field]', 'id' => 'ModelField'],
            'some <strong>test</strong> data with <a href="#">HTML</a> chars',
            '/textarea',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Model']['0']['OtherModel']['field'] = null;
        $result = $this->Form->textarea('Model.0.OtherModel.field');
        $expected = [
            'textarea' => ['name' => 'data[Model][0][OtherModel][field]', 'id' => 'Model0OtherModelField'],
            '/textarea',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testTextAreaWithStupidCharacters method.
     *
     * test text area with non-ascii characters
     */
    public function testTextAreaWithStupidCharacters()
    {
        $result = $this->Form->input('Post.content', [
            'label' => 'Current Text', 'value' => 'GREAT®', 'rows' => '15', 'cols' => '75',
        ]);
        $expected = [
            'div' => ['class' => 'input text'],
                'label' => ['for' => 'PostContent'],
                    'Current Text',
                '/label',
                'textarea' => ['name' => 'data[Post][content]', 'id' => 'PostContent', 'rows' => '15', 'cols' => '75'],
                'GREAT®',
                '/textarea',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testHiddenField method.
     */
    public function testHiddenField()
    {
        $this->Form->validationErrors['Model']['field'] = 1;
        $this->Form->data['Model']['field'] = 'test';
        $result = $this->Form->hidden('Model.field', ['id' => 'theID']);
        $this->assertTags($result, ['input' => ['type' => 'hidden', 'name' => 'data[Model][field]', 'id' => 'theID', 'value' => 'test']]);
    }

    /**
     * testFileUploadField method.
     */
    public function testFileUploadField()
    {
        $result = $this->Form->file('Model.upload');
        $this->assertTags($result, ['input' => ['type' => 'file', 'name' => 'data[Model][upload]', 'id' => 'ModelUpload']]);

        $this->Form->data['Model.upload'] = ['name' => '', 'type' => '', 'tmp_name' => '', 'error' => 4, 'size' => 0];
        $result = $this->Form->input('Model.upload', ['type' => 'file']);
        $expected = [
            'div' => ['class' => 'input file'],
            'label' => ['for' => 'ModelUpload'],
            'Upload',
            '/label',
            'input' => ['type' => 'file', 'name' => 'data[Model][upload]', 'id' => 'ModelUpload'],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test File upload input on a model not used in create();.
     */
    public function testFileUploadOnOtherModel()
    {
        ClassRegistry::removeObject('view');
        $controller = new Controller();
        $controller->name = 'ValidateUsers';
        $controller->uses = ['ValidateUser'];
        $controller->constructClasses();
        $view = new View($controller, true);

        $this->Form->create('ValidateUser', ['type' => 'file']);
        $result = $this->Form->file('ValidateProfile.city');
        $expected = [
            'input' => ['type' => 'file', 'name' => 'data[ValidateProfile][city]', 'id' => 'ValidateProfileCity'],
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testButton method.
     */
    public function testButton()
    {
        $result = $this->Form->button('Hi');
        $this->assertTags($result, ['button' => ['type' => 'submit'], 'Hi', '/button']);

        $result = $this->Form->button('Clear Form >', ['type' => 'reset']);
        $this->assertTags($result, ['button' => ['type' => 'reset'], 'Clear Form >', '/button']);

        $result = $this->Form->button('Clear Form >', ['type' => 'reset', 'id' => 'clearForm']);
        $this->assertTags($result, ['button' => ['type' => 'reset', 'id' => 'clearForm'], 'Clear Form >', '/button']);

        $result = $this->Form->button('<Clear Form>', ['type' => 'reset', 'escape' => true]);
        $this->assertTags($result, ['button' => ['type' => 'reset'], '&lt;Clear Form&gt;', '/button']);

        $result = $this->Form->button('Upload Text', ['onClick' => "$('#postAddForm').ajaxSubmit({target: '#postTextUpload', url: '/posts/text'});return false;'", 'escape' => false]);
        $this->assertNoPattern('/\&039/', $result);
    }

    /**
     * testSubmitButton method.
     */
    public function testSubmitButton()
    {
        $result = $this->Form->submit('');
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'value' => ''],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->submit('Test Submit');
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'value' => 'Test Submit'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->submit('Test Submit', ['div' => ['tag' => 'span']]);
        $expected = [
            'span' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'value' => 'Test Submit'],
            '/span',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->submit('Test Submit', ['class' => 'save', 'div' => false]);
        $expected = ['input' => ['type' => 'submit', 'value' => 'Test Submit', 'class' => 'save']];
        $this->assertTags($result, $expected);

        $result = $this->Form->submit('Test Submit', ['div' => ['id' => 'SaveButton']]);
        $expected = [
            'div' => ['class' => 'submit', 'id' => 'SaveButton'],
            'input' => ['type' => 'submit', 'value' => 'Test Submit'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->submit('Next >');
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'value' => 'Next &gt;'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->submit('Next >', ['escape' => false]);
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'value' => 'Next >'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->submit('Reset!', ['type' => 'reset']);
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'reset', 'value' => 'Reset!'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $before = '--before--';
        $after = '--after--';
        $result = $this->Form->submit('Test', ['before' => $before]);
        $expected = [
            'div' => ['class' => 'submit'],
            '--before--',
            'input' => ['type' => 'submit', 'value' => 'Test'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->submit('Test', ['after' => $after]);
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'value' => 'Test'],
            '--after--',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->submit('Test', ['before' => $before, 'after' => $after]);
        $expected = [
            'div' => ['class' => 'submit'],
            '--before--',
            'input' => ['type' => 'submit', 'value' => 'Test'],
            '--after--',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test image submit types.
     */
    public function testSubmitImage()
    {
        $result = $this->Form->submit('http://example.com/cake.power.gif');
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'image', 'src' => 'http://example.com/cake.power.gif'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->submit('/relative/cake.power.gif');
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'image', 'src' => 'relative/cake.power.gif'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->submit('cake.power.gif');
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'image', 'src' => 'img/cake.power.gif'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->submit('Not.an.image');
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'value' => 'Not.an.image'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $after = '--after--';
        $before = '--before--';
        $result = $this->Form->submit('cake.power.gif', ['after' => $after]);
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'image', 'src' => 'img/cake.power.gif'],
            '--after--',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->submit('cake.power.gif', ['before' => $before]);
        $expected = [
            'div' => ['class' => 'submit'],
            '--before--',
            'input' => ['type' => 'image', 'src' => 'img/cake.power.gif'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->submit('cake.power.gif', ['before' => $before, 'after' => $after]);
        $expected = [
            'div' => ['class' => 'submit'],
            '--before--',
            'input' => ['type' => 'image', 'src' => 'img/cake.power.gif'],
            '--after--',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->submit('Not.an.image', ['before' => $before, 'after' => $after]);
        $expected = [
            'div' => ['class' => 'submit'],
            '--before--',
            'input' => ['type' => 'submit', 'value' => 'Not.an.image'],
            '--after--',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * Test submit image with timestamps.
     */
    public function testSubmitImageTimestamp()
    {
        Configure::write('Asset.timestamp', 'force');

        $result = $this->Form->submit('cake.power.gif');
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'image', 'src' => 'preg:/img\/cake\.power\.gif\?\d*/'],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test the create() method.
     */
    public function testCreate()
    {
        $result = $this->Form->create('Contact');
        $encoding = strtolower(Configure::read('App.encoding'));
        $expected = [
            'form' => [
                'id' => 'ContactAddForm', 'method' => 'post', 'action' => '/contacts/add',
                'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'preg:/display\s*\:\s*none;\s*/'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->create('Contact', ['type' => 'GET']);
        $expected = ['form' => [
            'id' => 'ContactAddForm', 'method' => 'get', 'action' => '/contacts/add',
            'accept-charset' => $encoding,
        ]];
        $this->assertTags($result, $expected);

        $result = $this->Form->create('Contact', ['type' => 'get']);
        $expected = ['form' => [
            'id' => 'ContactAddForm', 'method' => 'get', 'action' => '/contacts/add',
            'accept-charset' => $encoding,
        ]];
        $this->assertTags($result, $expected);

        $result = $this->Form->create('Contact', ['type' => 'put']);
        $expected = [
            'form' => [
                'id' => 'ContactAddForm', 'method' => 'post', 'action' => '/contacts/add',
                'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'PUT'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->create('Contact', ['type' => 'file']);
        $expected = [
            'form' => [
                'id' => 'ContactAddForm', 'method' => 'post', 'action' => '/contacts/add',
                'accept-charset' => $encoding, 'enctype' => 'multipart/form-data',
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Contact']['id'] = 1;
        $this->Form->params['action'] = 'edit';
        $result = $this->Form->create('Contact');
        $expected = [
            'form' => [
                'id' => 'ContactEditForm', 'method' => 'post', 'action' => '/contacts/edit/1',
                'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'PUT'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['Contact']['id'] = 1;
        $this->Form->params['action'] = 'edit';
        $result = $this->Form->create('Contact', ['type' => 'file']);
        $expected = [
            'form' => [
                'id' => 'ContactEditForm', 'method' => 'post', 'action' => '/contacts/edit/1',
                'accept-charset' => $encoding, 'enctype' => 'multipart/form-data',
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'PUT'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data['ContactNonStandardPk']['pk'] = 1;
        $result = $this->Form->create('ContactNonStandardPk');
        $expected = [
            'form' => [
                'id' => 'ContactNonStandardPkEditForm', 'method' => 'post',
                'action' => '/contact_non_standard_pks/edit/1', 'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'PUT'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->create('Contact', ['id' => 'TestId']);
        $expected = [
            'form' => [
                'id' => 'TestId', 'method' => 'post', 'action' => '/contacts/edit/1',
                'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'PUT'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->params['action'] = 'add';
        $result = $this->Form->create('User', ['url' => ['action' => 'login']]);
        $expected = [
            'form' => [
                'id' => 'UserAddForm', 'method' => 'post', 'action' => '/users/login',
                'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->create('User', ['action' => 'login']);
        $expected = [
            'form' => [
                'id' => 'UserLoginForm', 'method' => 'post', 'action' => '/users/login',
                'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->create('User', ['url' => '/users/login']);
        $expected = [
            'form' => ['method' => 'post', 'action' => '/users/login', 'accept-charset' => $encoding],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->params['controller'] = 'pages';
        $this->Form->params['models'] = ['User', 'Post'];
        $result = $this->Form->create('User', ['action' => 'signup']);
        $expected = [
            'form' => [
                'id' => 'UserSignupForm', 'method' => 'post', 'action' => '/users/signup',
                'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = [];
        $this->Form->params['controller'] = 'contacts';
        $this->Form->params['models'] = ['Contact'];
        $result = $this->Form->create(['url' => ['action' => 'index', 'param']]);
        $expected = [
            'form' => [
                'id' => 'ContactAddForm', 'method' => 'post', 'action' => '/contacts/index/param',
                'accept-charset' => 'utf-8',
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * Test the onsubmit option for create().
     */
    public function testCreateOnSubmit()
    {
        $this->Form->data = [];
        $this->Form->params['controller'] = 'contacts';
        $this->Form->params['models'] = ['Contact'];
        $result = $this->Form->create(['url' => ['action' => 'index', 'param'], 'default' => false]);
        $expected = [
            'form' => [
                'id' => 'ContactAddForm', 'method' => 'post', 'onsubmit' => 'event.returnValue = false; return false;', 'action' => '/contacts/index/param',
                'accept-charset' => 'utf-8',
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = [];
        $this->Form->params['controller'] = 'contacts';
        $this->Form->params['models'] = ['Contact'];
        $result = $this->Form->create([
            'url' => ['action' => 'index', 'param'],
            'default' => false,
            'onsubmit' => 'someFunction();',
        ]);

        $expected = [
            'form' => [
                'id' => 'ContactAddForm', 'method' => 'post',
                'onsubmit' => 'someFunction();event.returnValue = false; return false;',
                'action' => '/contacts/index/param',
                'accept-charset' => 'utf-8',
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test that inputDefaults are stored and used.
     */
    public function testCreateWithInputDefaults()
    {
        $this->Form->create('User', [
            'inputDefaults' => ['div' => false, 'label' => false],
        ]);
        $result = $this->Form->input('username');
        $expected = [
            'input' => ['type' => 'text', 'name' => 'data[User][username]', 'id' => 'UserUsername'],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('username', ['div' => true, 'label' => 'username']);
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'UserUsername'], 'username', '/label',
            'input' => ['type' => 'text', 'name' => 'data[User][username]', 'id' => 'UserUsername'],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test automatic accept-charset overriding.
     */
    public function testCreateWithAcceptCharset()
    {
        $result = $this->Form->create('UserForm', [
                'type' => 'post', 'action' => 'login', 'encoding' => 'iso-8859-1',
            ]
        );
        $expected = [
            'form' => [
                'method' => 'post', 'action' => '/user_forms/login', 'id' => 'UserFormLoginForm',
                'accept-charset' => 'iso-8859-1',
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * Test base form url when url param is passed with multiple parameters (&).
     */
    public function testCreateQuerystringParams()
    {
        $encoding = strtolower(Configure::read('App.encoding'));
        $result = $this->Form->create('Contact', [
            'type' => 'post',
            'escape' => false,
            'url' => [
                'controller' => 'controller',
                'action' => 'action',
                '?' => ['param1' => 'value1', 'param2' => 'value2'],
            ],
        ]);
        $expected = [
            'form' => [
                'id' => 'ContactAddForm',
                'method' => 'post',
                'action' => '/controller/action?param1=value1&amp;param2=value2',
                'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->create('Contact', [
            'type' => 'post',
            'url' => [
                'controller' => 'controller',
                'action' => 'action',
                '?' => ['param1' => 'value1', 'param2' => 'value2'],
            ],
        ]);
        $expected = [
            'form' => [
                'id' => 'ContactAddForm',
                'method' => 'post',
                'action' => '/controller/action?param1=value1&amp;param2=value2',
                'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test that create() doesn't cause errors by multiple id's being in the primary key
     * as could happen with multiple select or checkboxes.
     */
    public function testCreateWithMultipleIdInData()
    {
        $encoding = strtolower(Configure::read('App.encoding'));

        $this->Form->data['Contact']['id'] = [1, 2];
        $result = $this->Form->create('Contact');
        $expected = [
            'form' => [
                'id' => 'ContactAddForm',
                'method' => 'post',
                'action' => '/contacts/add',
                'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test that create() doesn't add in extra passed params.
     */
    public function testCreatePassedArgs()
    {
        $encoding = strtolower(Configure::read('App.encoding'));
        $this->Form->data['Contact']['id'] = 1;
        $result = $this->Form->create('Contact', [
            'type' => 'post',
            'escape' => false,
            'url' => [
                'action' => 'edit',
                'myparam',
            ],
        ]);
        $expected = [
            'form' => [
                'id' => 'ContactAddForm',
                'method' => 'post',
                'action' => '/contacts/edit/myparam',
                'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected, true);
    }

    /**
     * test creating a get form, and get form inputs.
     */
    public function testGetFormCreate()
    {
        $encoding = strtolower(Configure::read('App.encoding'));
        $result = $this->Form->create('Contact', ['type' => 'get']);
        $this->assertTags($result, ['form' => [
            'id' => 'ContactAddForm', 'method' => 'get', 'action' => '/contacts/add',
            'accept-charset' => $encoding,
        ]]);

        $result = $this->Form->text('Contact.name');
        $this->assertTags($result, ['input' => [
            'name' => 'name', 'type' => 'text', 'id' => 'ContactName',
        ]]);

        $result = $this->Form->password('password');
        $this->assertTags($result, ['input' => [
            'name' => 'password', 'type' => 'password', 'id' => 'ContactPassword',
        ]]);
        $this->assertNoPattern('/<input[^<>]+[^id|name|type|value]=[^<>]*>$/', $result);

        $result = $this->Form->text('user_form');
        $this->assertTags($result, ['input' => [
            'name' => 'user_form', 'type' => 'text', 'id' => 'ContactUserForm',
        ]]);
    }

    /**
     * test get form, and inputs when the model param is false.
     */
    public function testGetFormWithFalseModel()
    {
        $encoding = strtolower(Configure::read('App.encoding'));
        $result = $this->Form->create(false, ['type' => 'get']);

        $expected = ['form' => [
            'id' => 'addForm', 'method' => 'get', 'action' => '/contact_test/add',
            'accept-charset' => $encoding,
        ]];
        $this->assertTags($result, $expected);

        $result = $this->Form->text('reason');
        $expected = [
            'input' => ['type' => 'text', 'name' => 'reason', 'id' => 'reason'],
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test that datetime() works with GET style forms.
     */
    public function testDateTimeWithGetForms()
    {
        extract($this->dateRegex);
        $this->Form->create('Contact', ['type' => 'get']);
        $result = $this->Form->datetime('created');

        $this->assertPattern('/name="created\[year\]"/', $result, 'year name attribute is wrong.');
        $this->assertPattern('/name="created\[month\]"/', $result, 'month name attribute is wrong.');
        $this->assertPattern('/name="created\[day\]"/', $result, 'day name attribute is wrong.');
        $this->assertPattern('/name="created\[hour\]"/', $result, 'hour name attribute is wrong.');
        $this->assertPattern('/name="created\[min\]"/', $result, 'min name attribute is wrong.');
        $this->assertPattern('/name="created\[meridian\]"/', $result, 'meridian name attribute is wrong.');
    }

    /**
     * testEditFormWithData method.
     *
     * test auto populating form elements from submitted data.
     */
    public function testEditFormWithData()
    {
        $this->Form->data = ['Person' => [
            'id' => 1,
            'first_name' => 'Nate',
            'last_name' => 'Abele',
            'email' => 'nate@example.com',
        ]];
        $this->Form->params = ['models' => ['Person'], 'controller' => 'people', 'action' => 'add'];
        $options = [1 => 'Nate', 2 => 'Garrett', 3 => 'Larry'];

        $this->Form->create();
        $result = $this->Form->select('People.People', $options, null, ['multiple' => true]);
        $expected = [
            'input' => ['type' => 'hidden', 'name' => 'data[People][People]', 'value' => '', 'id' => 'PeoplePeople_'],
            'select' => [
                'name' => 'data[People][People][]', 'multiple' => 'multiple', 'id' => 'PeoplePeople',
            ],
            ['option' => ['value' => 1]], 'Nate', '/option',
            ['option' => ['value' => 2]], 'Garrett', '/option',
            ['option' => ['value' => 3]], 'Larry', '/option',
            '/select',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testFormMagicInput method.
     */
    public function testFormMagicInput()
    {
        $encoding = strtolower(Configure::read('App.encoding'));
        $result = $this->Form->create('Contact');
        $expected = [
            'form' => [
                'id' => 'ContactAddForm', 'method' => 'post', 'action' => '/contacts/add',
                'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('name');
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'ContactName'],
            'Name',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][name]',
                'id' => 'ContactName', 'maxlength' => '255',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('non_existing_field_in_contact_model');
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'ContactNonExistingFieldInContactModel'],
            'Non Existing Field In Contact Model',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][non_existing_field_in_contact_model]',
                'id' => 'ContactNonExistingFieldInContactModel',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Address.street');
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'AddressStreet'],
            'Street',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Address][street]',
                'id' => 'AddressStreet',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Address.non_existing_field_in_model');
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'AddressNonExistingFieldInModel'],
            'Non Existing Field In Model',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Address][non_existing_field_in_model]',
                'id' => 'AddressNonExistingFieldInModel',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('name', ['div' => false]);
        $expected = [
            'label' => ['for' => 'ContactName'],
            'Name',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][name]',
                'id' => 'ContactName', 'maxlength' => '255',
            ],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.non_existing');
        $expected = [
            'div' => ['class' => 'input text required'],
            'label' => ['for' => 'ContactNonExisting'],
            'Non Existing',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][non_existing]',
                'id' => 'ContactNonExisting',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.imrequired');
        $expected = [
            'div' => ['class' => 'input text required'],
            'label' => ['for' => 'ContactImrequired'],
            'Imrequired',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][imrequired]',
                'id' => 'ContactImrequired',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.imalsorequired');
        $expected = [
            'div' => ['class' => 'input text required'],
            'label' => ['for' => 'ContactImalsorequired'],
            'Imalsorequired',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][imalsorequired]',
                'id' => 'ContactImalsorequired',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.imrequiredtoo');
        $expected = [
            'div' => ['class' => 'input text required'],
            'label' => ['for' => 'ContactImrequiredtoo'],
            'Imrequiredtoo',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][imrequiredtoo]',
                'id' => 'ContactImrequiredtoo',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.required_one');
        $expected = [
            'div' => ['class' => 'input text required'],
            'label' => ['for' => 'ContactRequiredOne'],
            'Required One',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][required_one]',
                'id' => 'ContactRequiredOne',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.imnotrequired');
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'ContactImnotrequired'],
            'Imnotrequired',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][imnotrequired]',
                'id' => 'ContactImnotrequired',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.imalsonotrequired');
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'ContactImalsonotrequired'],
            'Imalsonotrequired',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][imalsonotrequired]',
                'id' => 'ContactImalsonotrequired',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.imnotrequiredeither');
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'ContactImnotrequiredeither'],
            'Imnotrequiredeither',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][imnotrequiredeither]',
                'id' => 'ContactImnotrequiredeither',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        extract($this->dateRegex);
        $now = strtotime('now');

        $result = $this->Form->input('Contact.published', ['div' => false]);
        $expected = [
            'label' => ['for' => 'ContactPublishedMonth'],
            'Published',
            '/label',
            ['select' => [
                'name' => 'data[Contact][published][month]', 'id' => 'ContactPublishedMonth',
            ]],
            $monthsRegex,
            ['option' => ['value' => date('m', $now), 'selected' => 'selected']],
            date('F', $now),
            '/option',
            '*/select',
            '-',
            ['select' => [
                'name' => 'data[Contact][published][day]', 'id' => 'ContactPublishedDay',
            ]],
            $daysRegex,
            ['option' => ['value' => date('d', $now), 'selected' => 'selected']],
            date('j', $now),
            '/option',
            '*/select',
            '-',
            ['select' => [
                'name' => 'data[Contact][published][year]', 'id' => 'ContactPublishedYear',
            ]],
            $yearsRegex,
            ['option' => ['value' => date('Y', $now), 'selected' => 'selected']],
            date('Y', $now),
            '*/select',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.updated', ['div' => false]);
        $expected = [
            'label' => ['for' => 'ContactUpdatedMonth'],
            'Updated',
            '/label',
            ['select' => [
                'name' => 'data[Contact][updated][month]', 'id' => 'ContactUpdatedMonth',
            ]],
            $monthsRegex,
            ['option' => ['value' => date('m', $now), 'selected' => 'selected']],
            date('F', $now),
            '/option',
            '*/select',
            '-',
            ['select' => [
                'name' => 'data[Contact][updated][day]', 'id' => 'ContactUpdatedDay',
            ]],
            $daysRegex,
            ['option' => ['value' => date('d', $now), 'selected' => 'selected']],
            date('j', $now),
            '/option',
            '*/select',
            '-',
            ['select' => [
                'name' => 'data[Contact][updated][year]', 'id' => 'ContactUpdatedYear',
            ]],
            $yearsRegex,
            ['option' => ['value' => date('Y', $now), 'selected' => 'selected']],
            date('Y', $now),
            '*/select',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('User.stuff');
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'UserStuff'],
            'Stuff',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[User][stuff]',
                'id' => 'UserStuff', 'maxlength' => 10,
            ],
            '/div',
        ];
        $this->assertTags($result, $expected, true);
    }

    /**
     * testForMagicInputNonExistingNorValidated method.
     */
    public function testForMagicInputNonExistingNorValidated()
    {
        $encoding = strtolower(Configure::read('App.encoding'));
        $result = $this->Form->create('Contact');
        $expected = [
            'form' => [
                'id' => 'ContactAddForm', 'method' => 'post', 'action' => '/contacts/add',
                'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.non_existing_nor_validated', ['div' => false]);
        $expected = [
            'label' => ['for' => 'ContactNonExistingNorValidated'],
            'Non Existing Nor Validated',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][non_existing_nor_validated]',
                'id' => 'ContactNonExistingNorValidated',
            ],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.non_existing_nor_validated', [
            'div' => false, 'value' => 'my value',
        ]);
        $expected = [
            'label' => ['for' => 'ContactNonExistingNorValidated'],
            'Non Existing Nor Validated',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][non_existing_nor_validated]',
                'value' => 'my value', 'id' => 'ContactNonExistingNorValidated',
            ],
        ];
        $this->assertTags($result, $expected);

        $this->Form->data = [
            'Contact' => ['non_existing_nor_validated' => 'CakePHP magic',
        ], ];
        $result = $this->Form->input('Contact.non_existing_nor_validated', ['div' => false]);
        $expected = [
            'label' => ['for' => 'ContactNonExistingNorValidated'],
            'Non Existing Nor Validated',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][non_existing_nor_validated]',
                'value' => 'CakePHP magic', 'id' => 'ContactNonExistingNorValidated',
            ],
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testFormMagicInputLabel method.
     */
    public function testFormMagicInputLabel()
    {
        $encoding = strtolower(Configure::read('App.encoding'));
        $result = $this->Form->create('Contact');
        $expected = [
            'form' => [
                'id' => 'ContactAddForm', 'method' => 'post', 'action' => '/contacts/add',
                'accept-charset' => $encoding,
            ],
            'div' => ['style' => 'display:none;'],
            'input' => ['type' => 'hidden', 'name' => '_method', 'value' => 'POST'],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.name', ['div' => false, 'label' => false]);
        $this->assertTags($result, ['input' => [
            'name' => 'data[Contact][name]', 'type' => 'text',
            'id' => 'ContactName', 'maxlength' => '255', ],
        ]);

        $result = $this->Form->input('Contact.name', ['div' => false, 'label' => 'My label']);
        $expected = [
            'label' => ['for' => 'ContactName'],
            'My label',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][name]',
                'id' => 'ContactName', 'maxlength' => '255',
            ],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.name', [
            'div' => false, 'label' => ['class' => 'mandatory'],
        ]);
        $expected = [
            'label' => ['for' => 'ContactName', 'class' => 'mandatory'],
            'Name',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][name]',
                'id' => 'ContactName', 'maxlength' => '255',
            ],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.name', [
            'div' => false, 'label' => ['class' => 'mandatory', 'text' => 'My label'],
        ]);
        $expected = [
            'label' => ['for' => 'ContactName', 'class' => 'mandatory'],
            'My label',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][name]',
                'id' => 'ContactName', 'maxlength' => '255',
            ],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.name', [
            'div' => false, 'id' => 'my_id', 'label' => ['for' => 'my_id'],
        ]);
        $expected = [
            'label' => ['for' => 'my_id'],
            'Name',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][name]',
                'id' => 'my_id', 'maxlength' => '255',
            ],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('1.id');
        $this->assertTags($result, ['input' => [
            'type' => 'hidden', 'name' => 'data[Contact][1][id]',
            'id' => 'Contact1Id',
        ]]);

        $result = $this->Form->input('1.name');
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'Contact1Name'],
            'Name',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][1][name]',
                'id' => 'Contact1Name', 'maxlength' => '255',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.1.id');
        $this->assertTags($result, [
            'input' => [
                'type' => 'hidden', 'name' => 'data[Contact][1][id]',
                'id' => 'Contact1Id',
            ],
        ]);

        $result = $this->Form->input('Model.1.name');
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'Model1Name'],
            'Name',
            '/label',
            'input' => [
                'type' => 'text', 'name' => 'data[Model][1][name]',
                'id' => 'Model1Name',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testFormEnd method.
     */
    public function testFormEnd()
    {
        $this->assertEqual($this->Form->end(), '</form>');

        $result = $this->Form->end('');
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'value' => ''],
            '/div',
            '/form',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->end(['label' => '']);
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'value' => ''],
            '/div',
            '/form',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->end('save');
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'value' => 'save'],
            '/div',
            '/form',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->end(['label' => 'save']);
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'value' => 'save'],
            '/div',
            '/form',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->end(['label' => 'save', 'name' => 'Whatever']);
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'value' => 'save', 'name' => 'Whatever'],
            '/div',
            '/form',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->end(['name' => 'Whatever']);
        $expected = [
            'div' => ['class' => 'submit'],
            'input' => ['type' => 'submit', 'value' => 'Submit', 'name' => 'Whatever'],
            '/div',
            '/form',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->end(['label' => 'save', 'name' => 'Whatever', 'div' => 'good']);
        $expected = [
            'div' => ['class' => 'good'],
            'input' => ['type' => 'submit', 'value' => 'save', 'name' => 'Whatever'],
            '/div',
            '/form',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->end([
            'label' => 'save', 'name' => 'Whatever', 'div' => ['class' => 'good'],
        ]);
        $expected = [
            'div' => ['class' => 'good'],
            'input' => ['type' => 'submit', 'value' => 'save', 'name' => 'Whatever'],
            '/div',
            '/form',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testMultipleFormWithIdFields method.
     */
    public function testMultipleFormWithIdFields()
    {
        $this->Form->create('UserForm');

        $result = $this->Form->input('id');
        $this->assertTags($result, ['input' => [
            'type' => 'hidden', 'name' => 'data[UserForm][id]', 'id' => 'UserFormId',
        ]]);

        $result = $this->Form->input('ValidateItem.id');
        $this->assertTags($result, ['input' => [
            'type' => 'hidden', 'name' => 'data[ValidateItem][id]',
            'id' => 'ValidateItemId',
        ]]);

        $result = $this->Form->input('ValidateUser.id');
        $this->assertTags($result, ['input' => [
            'type' => 'hidden', 'name' => 'data[ValidateUser][id]',
            'id' => 'ValidateUserId',
        ]]);
    }

    /**
     * testDbLessModel method.
     */
    public function testDbLessModel()
    {
        $this->Form->create('TestMail');

        $result = $this->Form->input('name');
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'TestMailName'],
            'Name',
            '/label',
            'input' => [
                'name' => 'data[TestMail][name]', 'type' => 'text',
                'id' => 'TestMailName',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        ClassRegistry::init('TestMail');
        $this->Form->create('TestMail');
        $result = $this->Form->input('name');
        $expected = [
            'div' => ['class' => 'input text'],
            'label' => ['for' => 'TestMailName'],
            'Name',
            '/label',
            'input' => [
                'name' => 'data[TestMail][name]', 'type' => 'text',
                'id' => 'TestMailName',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testBrokenness method.
     */
    public function testBrokenness()
    {
        /*
         * #4 This test has two parents and four children. By default (as of r7117) both
         * parents are show but the first parent is missing a child. This is the inconsistency
         * in the default behaviour - one parent has all children, the other does not - dependent
         * on the data values.
         */
        $result = $this->Form->select('Model.field', [
            'Fred' => [
                'freds_son_1' => 'Fred',
                'freds_son_2' => 'Freddie',
            ],
            'Bert' => [
                'berts_son_1' => 'Albert',
                'berts_son_2' => 'Bertie', ],
            ],
            null,
            ['showParents' => true, 'empty' => false]
        );

        $expected = [
            'select' => ['name' => 'data[Model][field]', 'id' => 'ModelField'],
                ['optgroup' => ['label' => 'Fred']],
                    ['option' => ['value' => 'freds_son_1']],
                        'Fred',
                    '/option',
                    ['option' => ['value' => 'freds_son_2']],
                        'Freddie',
                    '/option',
                '/optgroup',
                ['optgroup' => ['label' => 'Bert']],
                    ['option' => ['value' => 'berts_son_1']],
                        'Albert',
                    '/option',
                    ['option' => ['value' => 'berts_son_2']],
                        'Bertie',
                    '/option',
                '/optgroup',
            '/select',
        ];
        $this->assertTags($result, $expected);

        /*
         * #2 This is structurally identical to the test above (#1) - only the parent name has
         * changed, so we should expect the same select list data, just with a different name
         * for the parent.  As of #7117, this test fails because option 3 => 'Three' disappears.
         * This is where data corruption can occur, because when a select value is missing from
         * a list a form will substitute the first value in the list - without the user knowing.
         * If the optgroup name 'Parent' (above) is updated to 'Three' (below), this should not
         * affect the availability of 3 => 'Three' as a valid option.
         */
        $options = [1 => 'One', 2 => 'Two', 'Three' => [
            3 => 'Three', 4 => 'Four', 5 => 'Five',
        ]];
        $result = $this->Form->select(
            'Model.field', $options, null, ['showParents' => true, 'empty' => false]
        );

        $expected = [
            'select' => ['name' => 'data[Model][field]', 'id' => 'ModelField'],
                ['option' => ['value' => 1]],
                    'One',
                '/option',
                ['option' => ['value' => 2]],
                    'Two',
                '/option',
                ['optgroup' => ['label' => 'Three']],
                    ['option' => ['value' => 3]],
                        'Three',
                    '/option',
                    ['option' => ['value' => 4]],
                        'Four',
                    '/option',
                    ['option' => ['value' => 5]],
                        'Five',
                    '/option',
                '/optgroup',
            '/select',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * Test the generation of fields for a multi record form.
     */
    public function testMultiRecordForm()
    {
        $this->Form->create('ValidateProfile');
        $this->Form->data['ValidateProfile'][1]['ValidateItem'][2]['name'] = 'Value';
        $result = $this->Form->input('ValidateProfile.1.ValidateItem.2.name');
        $expected = [
            'div' => ['class' => 'input textarea'],
                'label' => ['for' => 'ValidateProfile1ValidateItem2Name'],
                    'Name',
                '/label',
                'textarea' => [
                    'id' => 'ValidateProfile1ValidateItem2Name',
                    'name' => 'data[ValidateProfile][1][ValidateItem][2][name]',
                    'cols' => 30,
                    'rows' => 6,
                ],
                'Value',
                '/textarea',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('ValidateProfile.1.ValidateItem.2.created', ['empty' => true]);
        $expected = [
            'div' => ['class' => 'input date'],
            'label' => ['for' => 'ValidateProfile1ValidateItem2CreatedMonth'],
            'Created',
            '/label',
            ['select' => [
                'name' => 'data[ValidateProfile][1][ValidateItem][2][created][month]',
                'id' => 'ValidateProfile1ValidateItem2CreatedMonth',
                ],
            ],
            ['option' => ['value' => '']], '/option',
            $this->dateRegex['monthsRegex'],
            '/select', '-',
            ['select' => [
                'name' => 'data[ValidateProfile][1][ValidateItem][2][created][day]',
                'id' => 'ValidateProfile1ValidateItem2CreatedDay',
                ],
            ],
            ['option' => ['value' => '']], '/option',
            $this->dateRegex['daysRegex'],
            '/select', '-',
            ['select' => [
                'name' => 'data[ValidateProfile][1][ValidateItem][2][created][year]',
                'id' => 'ValidateProfile1ValidateItem2CreatedYear',
                ],
            ],
            ['option' => ['value' => '']], '/option',
            $this->dateRegex['yearsRegex'],
            '/select',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $this->Form->validationErrors['ValidateProfile'][1]['ValidateItem'][2]['profile_id'] = 'Error';
        $this->Form->data['ValidateProfile'][1]['ValidateItem'][2]['profile_id'] = '1';
        $result = $this->Form->input('ValidateProfile.1.ValidateItem.2.profile_id');
        $expected = [
            'div' => ['class' => 'input select error'],
            'label' => ['for' => 'ValidateProfile1ValidateItem2ProfileId'],
            'Profile',
            '/label',
            'select' => [
                'name' => 'data[ValidateProfile][1][ValidateItem][2][profile_id]',
                'id' => 'ValidateProfile1ValidateItem2ProfileId',
                'class' => 'form-error',
            ],
            '/select',
            ['div' => ['class' => 'error-message']],
            'Error',
            '/div',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test the correct display of multi-record form validation errors.
     */
    public function testMultiRecordFormValidationErrors()
    {
        $this->Form->create('ValidateProfile');
        $this->Form->validationErrors['ValidateProfile'][2]['ValidateItem'][1]['name'] = 'Error in field name';
        $result = $this->Form->error('ValidateProfile.2.ValidateItem.1.name');
        $this->assertTags($result, ['div' => ['class' => 'error-message'], 'Error in field name', '/div']);

        $this->Form->validationErrors['ValidateProfile'][2]['city'] = 'Error in field city';
        $result = $this->Form->error('ValidateProfile.2.city');
        $this->assertTags($result, ['div' => ['class' => 'error-message'], 'Error in field city', '/div']);

        $result = $this->Form->error('2.city');
        $this->assertTags($result, ['div' => ['class' => 'error-message'], 'Error in field city', '/div']);
    }

    /**
     * tests the ability to change the order of the form input placeholder "input", "label", "before", "between", "after", "error".
     */
    public function testInputTemplate()
    {
        $result = $this->Form->input('Contact.email', [
            'type' => 'text', 'format' => ['input'],
        ]);
        $expected = [
            'div' => ['class' => 'input text'],
            'input' => [
                'type' => 'text', 'name' => 'data[Contact][email]',
                'id' => 'ContactEmail',
            ],
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.email', [
            'type' => 'text', 'format' => ['input', 'label'],
            'label' => '<em>Email (required)</em>',
        ]);
        $expected = [
            'div' => ['class' => 'input text'],
            ['input' => [
                'type' => 'text', 'name' => 'data[Contact][email]',
                'id' => 'ContactEmail',
            ]],
            'label' => ['for' => 'ContactEmail'],
            'em' => [],
            'Email (required)',
            '/em',
            '/label',
            '/div',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Form->input('Contact.email', [
            'type' => 'text', 'format' => ['input', 'between', 'label', 'after'],
            'between' => '<div>Something in the middle</div>',
            'after' => '<span>Some text at the end</span>',
        ]);
        $expected = [
            'div' => ['class' => 'input text'],
            ['input' => [
                'type' => 'text', 'name' => 'data[Contact][email]',
                'id' => 'ContactEmail',
            ]],
            ['div' => []],
            'Something in the middle',
            '/div',
            'label' => ['for' => 'ContactEmail'],
            'Email',
            '/label',
            'span' => [],
            'Some text at the end',
            '/span',
            '/div',
        ];
        $this->assertTags($result, $expected);
    }
}
