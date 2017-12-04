<?php
/**
 * BehaviorTest file.
 *
 * Long description for behavior.test.php
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 *
 * @see          http://cakephp.org CakePHP(tm) Project
 * @since         1.2
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Model', 'AppModel');
require_once dirname(__FILE__).DS.'models.php';

Mock::generatePartial('BehaviorCollection', 'MockModelBehaviorCollection', ['cakeError', '_stop']);

/**
 * TestBehavior class.
 */
class TestBehavior extends ModelBehavior
{
    /**
     * mapMethods property.
     *
     * @var array
     */
    public $mapMethods = ['/test(\w+)/' => 'testMethod', '/look for\s+(.+)/' => 'speakEnglish'];

    /**
     * setup method.
     *
     * @param mixed $model
     * @param array $config
     */
    public function setup(&$model, $config = [])
    {
        parent::setup($model, $config);
        if (isset($config['mangle'])) {
            $config['mangle'] .= ' mangled';
        }
        $this->settings[$model->alias] = array_merge(['beforeFind' => 'on', 'afterFind' => 'off'], $config);
    }

    /**
     * beforeFind method.
     *
     * @param mixed $model
     * @param mixed $query
     */
    public function beforeFind(&$model, $query)
    {
        $settings = $this->settings[$model->alias];
        if (!isset($settings['beforeFind']) || 'off' == $settings['beforeFind']) {
            return parent::beforeFind($model, $query);
        }
        switch ($settings['beforeFind']) {
            case 'on':
                return false;
            break;
            case 'test':
                return null;
            break;
            case 'modify':
                $query['fields'] = [$model->alias.'.id', $model->alias.'.name', $model->alias.'.mytime'];
                $query['recursive'] = -1;

                return $query;
            break;
        }
    }

    /**
     * afterFind method.
     *
     * @param mixed $model
     * @param mixed $results
     * @param mixed $primary
     */
    public function afterFind(&$model, $results, $primary)
    {
        $settings = $this->settings[$model->alias];
        if (!isset($settings['afterFind']) || 'off' == $settings['afterFind']) {
            return parent::afterFind($model, $results, $primary);
        }
        switch ($settings['afterFind']) {
            case 'on':
                return [];
            break;
            case 'test':
                return true;
            break;
            case 'test2':
                return null;
            break;
            case 'modify':
                return Set::extract($results, "{n}.{$model->alias}");
            break;
        }
    }

    /**
     * beforeSave method.
     *
     * @param mixed $model
     */
    public function beforeSave(&$model)
    {
        $settings = $this->settings[$model->alias];
        if (!isset($settings['beforeSave']) || 'off' == $settings['beforeSave']) {
            return parent::beforeSave($model);
        }
        switch ($settings['beforeSave']) {
            case 'on':
                return false;
            break;
            case 'test':
                return null;
            break;
            case 'modify':
                $model->data[$model->alias]['name'] .= ' modified before';

                return true;
            break;
        }
    }

    /**
     * afterSave method.
     *
     * @param mixed $model
     * @param mixed $created
     */
    public function afterSave(&$model, $created)
    {
        $settings = $this->settings[$model->alias];
        if (!isset($settings['afterSave']) || 'off' == $settings['afterSave']) {
            return parent::afterSave($model, $created);
        }
        $string = 'modified after';
        if ($created) {
            $string .= ' on create';
        }
        switch ($settings['afterSave']) {
            case 'on':
                $model->data[$model->alias]['aftersave'] = $string;
            break;
            case 'test':
                unset($model->data[$model->alias]['name']);
            break;
            case 'test2':
                return false;
            break;
            case 'modify':
                $model->data[$model->alias]['name'] .= ' '.$string;
            break;
        }
    }

    /**
     * beforeValidate method.
     *
     * @param mixed $model
     */
    public function beforeValidate(&$model)
    {
        $settings = $this->settings[$model->alias];
        if (!isset($settings['validate']) || 'off' == $settings['validate']) {
            return parent::beforeValidate($model);
        }
        switch ($settings['validate']) {
            case 'on':
                $model->invalidate('name');

                return true;
            break;
            case 'test':
                return null;
            break;
            case 'whitelist':
                $this->_addToWhitelist($model, ['name']);

                return true;
            break;
            case 'stop':
                $model->invalidate('name');

                return false;
            break;
        }
    }

    /**
     * beforeDelete method.
     *
     * @param mixed $model
     * @param bool  $cascade
     */
    public function beforeDelete(&$model, $cascade = true)
    {
        $settings = &$this->settings[$model->alias];
        if (!isset($settings['beforeDelete']) || 'off' == $settings['beforeDelete']) {
            return parent::beforeDelete($model, $cascade);
        }
        switch ($settings['beforeDelete']) {
            case 'on':
                return false;
            break;
            case 'test':
                return null;
            break;
            case 'test2':
                echo 'beforeDelete success';
                if ($cascade) {
                    echo ' (cascading) ';
                }
            break;
        }
    }

    /**
     * afterDelete method.
     *
     * @param mixed $model
     */
    public function afterDelete(&$model)
    {
        $settings = &$this->settings[$model->alias];
        if (!isset($settings['afterDelete']) || 'off' == $settings['afterDelete']) {
            return parent::afterDelete($model);
        }
        switch ($settings['afterDelete']) {
            case 'on':
                echo 'afterDelete success';
            break;
        }
    }

    /**
     * onError method.
     *
     * @param mixed $model
     */
    public function onError(&$model)
    {
        $settings = $this->settings[$model->alias];
        if (!isset($settings['onError']) || 'off' == $settings['onError']) {
            return parent::onError($model, $cascade);
        }
        echo 'onError trigger success';
    }

    /**
     * beforeTest method.
     *
     * @param mixed $model
     */
    public function beforeTest(&$model)
    {
        $model->beforeTestResult[] = strtolower(get_class($this));

        return strtolower(get_class($this));
    }

    /**
     * testMethod method.
     *
     * @param mixed $model
     * @param bool  $param
     */
    public function testMethod(&$model, $param = true)
    {
        if (true === $param) {
            return 'working';
        }
    }

    /**
     * testData method.
     *
     * @param mixed $model
     */
    public function testData(&$model)
    {
        if (!isset($model->data['Apple']['field'])) {
            return false;
        }
        $model->data['Apple']['field_2'] = true;

        return true;
    }

    /**
     * validateField method.
     *
     * @param mixed $model
     * @param mixed $field
     */
    public function validateField(&$model, $field)
    {
        return 'Orange' === current($field);
    }

    /**
     * speakEnglish method.
     *
     * @param mixed $model
     * @param mixed $method
     * @param mixed $query
     */
    public function speakEnglish(&$model, $method, $query)
    {
        $method = preg_replace('/look for\s+/', 'Item.name = \'', $method);
        $query = preg_replace('/^in\s+/', 'Location.name = \'', $query);

        return $method.'\' AND '.$query.'\'';
    }
}

/**
 * Test2Behavior class.
 */
class Test2Behavior extends TestBehavior
{
}

/**
 * Test3Behavior class.
 */
class Test3Behavior extends TestBehavior
{
}

/**
 * Test4Behavior class.
 */
class Test4Behavior extends ModelBehavior
{
    public function setup(&$model, $config = null)
    {
        $model->bindModel(
            ['hasMany' => ['Comment']]
        );
    }
}

/**
 * Test5Behavior class.
 */
class Test5Behavior extends ModelBehavior
{
    public function setup(&$model, $config = null)
    {
        $model->bindModel(
            ['belongsTo' => ['User']]
        );
    }
}

/**
 * Test6Behavior class.
 */
class Test6Behavior extends ModelBehavior
{
    public function setup(&$model, $config = null)
    {
        $model->bindModel(
            ['hasAndBelongsToMany' => ['Tag']]
        );
    }
}

/**
 * Test7Behavior class.
 */
class Test7Behavior extends ModelBehavior
{
    public function setup(&$model, $config = null)
    {
        $model->bindModel(
            ['hasOne' => ['Attachment']]
        );
    }
}

/**
 * BehaviorTest class.
 */
class BehaviorTest extends CakeTestCase
{
    /**
     * fixtures property.
     *
     * @var array
     */
    public $fixtures = [
        'core.apple', 'core.sample', 'core.article', 'core.user', 'core.comment',
        'core.attachment', 'core.tag', 'core.articles_tag',
    ];

    /**
     * tearDown method.
     */
    public function endTest()
    {
        ClassRegistry::flush();
    }

    /**
     * testBehaviorBinding method.
     */
    public function testBehaviorBinding()
    {
        $Apple = new Apple();
        $this->assertIdentical($Apple->Behaviors->attached(), []);

        $Apple->Behaviors->attach('Test', ['key' => 'value']);
        $this->assertIdentical($Apple->Behaviors->attached(), ['Test']);
        $this->assertEqual(strtolower(get_class($Apple->Behaviors->Test)), 'testbehavior');
        $expected = ['beforeFind' => 'on', 'afterFind' => 'off', 'key' => 'value'];
        $this->assertEqual($Apple->Behaviors->Test->settings['Apple'], $expected);
        $this->assertEqual(array_keys($Apple->Behaviors->Test->settings), ['Apple']);

        $this->assertIdentical($Apple->Sample->Behaviors->attached(), []);
        $Apple->Sample->Behaviors->attach('Test', ['key2' => 'value2']);
        $this->assertIdentical($Apple->Sample->Behaviors->attached(), ['Test']);
        $this->assertEqual($Apple->Sample->Behaviors->Test->settings['Sample'], ['beforeFind' => 'on', 'afterFind' => 'off', 'key2' => 'value2']);

        $this->assertEqual(array_keys($Apple->Behaviors->Test->settings), ['Apple', 'Sample']);
        $this->assertIdentical(
            $Apple->Sample->Behaviors->Test->settings,
            $Apple->Behaviors->Test->settings
        );
        $this->assertNotIdentical($Apple->Behaviors->Test->settings['Apple'], $Apple->Sample->Behaviors->Test->settings['Sample']);

        $Apple->Behaviors->attach('Test', ['key2' => 'value2', 'key3' => 'value3', 'beforeFind' => 'off']);
        $Apple->Sample->Behaviors->attach('Test', ['key' => 'value', 'key3' => 'value3', 'beforeFind' => 'off']);
        $this->assertEqual($Apple->Behaviors->Test->settings['Apple'], ['beforeFind' => 'off', 'afterFind' => 'off', 'key' => 'value', 'key2' => 'value2', 'key3' => 'value3']);
        $this->assertEqual($Apple->Behaviors->Test->settings['Apple'], $Apple->Sample->Behaviors->Test->settings['Sample']);

        $this->assertFalse(isset($Apple->Child->Behaviors->Test));
        $Apple->Child->Behaviors->attach('Test', ['key' => 'value', 'key2' => 'value2', 'key3' => 'value3', 'beforeFind' => 'off']);
        $this->assertEqual($Apple->Child->Behaviors->Test->settings['Child'], $Apple->Sample->Behaviors->Test->settings['Sample']);

        $this->assertFalse(isset($Apple->Parent->Behaviors->Test));
        $Apple->Parent->Behaviors->attach('Test', ['key' => 'value', 'key2' => 'value2', 'key3' => 'value3', 'beforeFind' => 'off']);
        $this->assertEqual($Apple->Parent->Behaviors->Test->settings['Parent'], $Apple->Sample->Behaviors->Test->settings['Sample']);

        $Apple->Parent->Behaviors->attach('Test', ['key' => 'value', 'key2' => 'value', 'key3' => 'value', 'beforeFind' => 'off']);
        $this->assertNotEqual($Apple->Parent->Behaviors->Test->settings['Parent'], $Apple->Sample->Behaviors->Test->settings['Sample']);

        $Apple->Behaviors->attach('Plugin.Test', ['key' => 'new value']);
        $expected = [
            'beforeFind' => 'off', 'afterFind' => 'off', 'key' => 'new value',
            'key2' => 'value2', 'key3' => 'value3',
        ];
        $this->assertEqual($Apple->Behaviors->Test->settings['Apple'], $expected);

        $current = $Apple->Behaviors->Test->settings['Apple'];
        $expected = array_merge($current, ['mangle' => 'trigger mangled']);
        $Apple->Behaviors->attach('Test', ['mangle' => 'trigger']);
        $this->assertEqual($Apple->Behaviors->Test->settings['Apple'], $expected);

        $Apple->Behaviors->attach('Test');
        $expected = array_merge($current, ['mangle' => 'trigger mangled mangled']);

        $this->assertEqual($Apple->Behaviors->Test->settings['Apple'], $expected);
        $Apple->Behaviors->attach('Test', ['mangle' => 'trigger']);
        $expected = array_merge($current, ['mangle' => 'trigger mangled']);
        $this->assertEqual($Apple->Behaviors->Test->settings['Apple'], $expected);
    }

    /**
     * test that attach()/detach() works with plugin.banana.
     */
    public function testDetachWithPluginNames()
    {
        $Apple = new Apple();
        $Apple->Behaviors->attach('Plugin.Test');
        $this->assertTrue(isset($Apple->Behaviors->Test), 'Missing behavior');
        $this->assertEqual($Apple->Behaviors->attached(), ['Test']);

        $Apple->Behaviors->detach('Plugin.Test');
        $this->assertEqual($Apple->Behaviors->attached(), []);

        $Apple->Behaviors->attach('Plugin.Test');
        $this->assertTrue(isset($Apple->Behaviors->Test), 'Missing behavior');
        $this->assertEqual($Apple->Behaviors->attached(), ['Test']);

        $Apple->Behaviors->detach('Test');
        $this->assertEqual($Apple->Behaviors->attached(), []);
    }

    /**
     * test that attaching a non existant Behavior triggers a cake error.
     */
    public function testInvalidBehaviorCausingCakeError()
    {
        $Apple = new Apple();
        $Apple->Behaviors = new MockModelBehaviorCollection();
        $Apple->Behaviors->expectOnce('cakeError');
        $Apple->Behaviors->expectAt(0, 'cakeError', ['missingBehaviorFile', '*']);
        $this->assertFalse($Apple->Behaviors->attach('NoSuchBehavior'));
    }

    /**
     * testBehaviorToggling method.
     */
    public function testBehaviorToggling()
    {
        $Apple = new Apple();
        $this->assertIdentical($Apple->Behaviors->enabled(), []);

        $Apple->Behaviors->init('Apple', ['Test' => ['key' => 'value']]);
        $this->assertIdentical($Apple->Behaviors->enabled(), ['Test']);

        $Apple->Behaviors->disable('Test');
        $this->assertIdentical($Apple->Behaviors->attached(), ['Test']);
        $this->assertIdentical($Apple->Behaviors->enabled(), []);

        $Apple->Sample->Behaviors->attach('Test');
        $this->assertIdentical($Apple->Sample->Behaviors->enabled('Test'), true);
        $this->assertIdentical($Apple->Behaviors->enabled(), []);

        $Apple->Behaviors->enable('Test');
        $this->assertIdentical($Apple->Behaviors->attached('Test'), true);
        $this->assertIdentical($Apple->Behaviors->enabled(), ['Test']);

        $Apple->Behaviors->disable('Test');
        $this->assertIdentical($Apple->Behaviors->enabled(), []);
        $Apple->Behaviors->attach('Test', ['enabled' => true]);
        $this->assertIdentical($Apple->Behaviors->enabled(), ['Test']);
        $Apple->Behaviors->attach('Test', ['enabled' => false]);
        $this->assertIdentical($Apple->Behaviors->enabled(), []);
        $Apple->Behaviors->detach('Test');
        $this->assertIdentical($Apple->Behaviors->enabled(), []);
    }

    /**
     * testBehaviorFindCallbacks method.
     */
    public function testBehaviorFindCallbacks()
    {
        $Apple = new Apple();
        $expected = $Apple->find('all');

        $Apple->Behaviors->attach('Test');
        $this->assertIdentical($Apple->find('all'), null);

        $Apple->Behaviors->attach('Test', ['beforeFind' => 'off']);
        $this->assertIdentical($Apple->find('all'), $expected);

        $Apple->Behaviors->attach('Test', ['beforeFind' => 'test']);
        $this->assertIdentical($Apple->find('all'), $expected);

        $Apple->Behaviors->attach('Test', ['beforeFind' => 'modify']);
        $expected2 = [
            ['Apple' => ['id' => '1', 'name' => 'Red Apple 1', 'mytime' => '22:57:17']],
            ['Apple' => ['id' => '2', 'name' => 'Bright Red Apple', 'mytime' => '22:57:17']],
            ['Apple' => ['id' => '3', 'name' => 'green blue', 'mytime' => '22:57:17']],
        ];
        $result = $Apple->find('all', ['conditions' => ['Apple.id <' => '4']]);
        $this->assertEqual($result, $expected2);

        $Apple->Behaviors->disable('Test');
        $result = $Apple->find('all');
        $this->assertEqual($result, $expected);

        $Apple->Behaviors->attach('Test', ['beforeFind' => 'off', 'afterFind' => 'on']);
        $this->assertIdentical($Apple->find('all'), []);

        $Apple->Behaviors->attach('Test', ['afterFind' => 'off']);
        $this->assertEqual($Apple->find('all'), $expected);

        $Apple->Behaviors->attach('Test', ['afterFind' => 'test']);
        $this->assertEqual($Apple->find('all'), $expected);

        $Apple->Behaviors->attach('Test', ['afterFind' => 'test2']);
        $this->assertEqual($Apple->find('all'), $expected);

        $Apple->Behaviors->attach('Test', ['afterFind' => 'modify']);
        $expected = [
            ['id' => '1', 'apple_id' => '2', 'color' => 'Red 1', 'name' => 'Red Apple 1', 'created' => '2006-11-22 10:38:58', 'date' => '1951-01-04', 'modified' => '2006-12-01 13:31:26', 'mytime' => '22:57:17'],
            ['id' => '2', 'apple_id' => '1', 'color' => 'Bright Red 1', 'name' => 'Bright Red Apple', 'created' => '2006-11-22 10:43:13', 'date' => '2014-01-01', 'modified' => '2006-11-30 18:38:10', 'mytime' => '22:57:17'],
            ['id' => '3', 'apple_id' => '2', 'color' => 'blue green', 'name' => 'green blue', 'created' => '2006-12-25 05:13:36', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:23:24', 'mytime' => '22:57:17'],
            ['id' => '4', 'apple_id' => '2', 'color' => 'Blue Green', 'name' => 'Test Name', 'created' => '2006-12-25 05:23:36', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:23:36', 'mytime' => '22:57:17'],
            ['id' => '5', 'apple_id' => '5', 'color' => 'Green', 'name' => 'Blue Green', 'created' => '2006-12-25 05:24:06', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:29:16', 'mytime' => '22:57:17'],
            ['id' => '6', 'apple_id' => '4', 'color' => 'My new appleOrange', 'name' => 'My new apple', 'created' => '2006-12-25 05:29:39', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:29:39', 'mytime' => '22:57:17'],
            ['id' => '7', 'apple_id' => '6', 'color' => 'Some wierd color', 'name' => 'Some odd color', 'created' => '2006-12-25 05:34:21', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:34:21', 'mytime' => '22:57:17'],
        ];
        $this->assertEqual($Apple->find('all'), $expected);
    }

    /**
     * testBehaviorHasManyFindCallbacks method.
     */
    public function testBehaviorHasManyFindCallbacks()
    {
        $Apple = new Apple();
        $Apple->unbindModel(['hasOne' => ['Sample'], 'belongsTo' => ['Parent']], false);
        $expected = $Apple->find('all');

        $Apple->unbindModel(['hasMany' => ['Child']]);
        $wellBehaved = $Apple->find('all');
        $Apple->Child->Behaviors->attach('Test', ['afterFind' => 'modify']);
        $Apple->unbindModel(['hasMany' => ['Child']]);
        $this->assertIdentical($Apple->find('all'), $wellBehaved);

        $Apple->Child->Behaviors->attach('Test', ['before' => 'off']);
        $this->assertIdentical($Apple->find('all'), $expected);

        $Apple->Child->Behaviors->attach('Test', ['before' => 'test']);
        $this->assertIdentical($Apple->find('all'), $expected);

        $expected2 = [
            [
                'Apple' => ['id' => 1],
                'Child' => [
                    ['id' => 2, 'name' => 'Bright Red Apple', 'mytime' => '22:57:17'], ], ],
            [
                'Apple' => ['id' => 2],
                'Child' => [
                    ['id' => 1, 'name' => 'Red Apple 1', 'mytime' => '22:57:17'],
                    ['id' => 3, 'name' => 'green blue', 'mytime' => '22:57:17'],
                    ['id' => 4, 'name' => 'Test Name', 'mytime' => '22:57:17'], ], ],
            [
                'Apple' => ['id' => 3],
                'Child' => [], ],
        ];

        $Apple->Child->Behaviors->attach('Test', ['before' => 'modify']);
        $result = $Apple->find('all', ['fields' => ['Apple.id'], 'conditions' => ['Apple.id <' => '4']]);
        //$this->assertEqual($result, $expected2);

        $Apple->Child->Behaviors->disable('Test');
        $result = $Apple->find('all');
        $this->assertEqual($result, $expected);

        $Apple->Child->Behaviors->attach('Test', ['before' => 'off', 'after' => 'on']);
        //$this->assertIdentical($Apple->find('all'), array());

        $Apple->Child->Behaviors->attach('Test', ['after' => 'off']);
        $this->assertEqual($Apple->find('all'), $expected);

        $Apple->Child->Behaviors->attach('Test', ['after' => 'test']);
        $this->assertEqual($Apple->find('all'), $expected);

        $Apple->Child->Behaviors->attach('Test', ['after' => 'test2']);
        $this->assertEqual($Apple->find('all'), $expected);

        $Apple->Child->Behaviors->attach('Test', ['after' => 'modify']);
        $expected = [
            ['id' => '1', 'apple_id' => '2', 'color' => 'Red 1', 'name' => 'Red Apple 1', 'created' => '2006-11-22 10:38:58', 'date' => '1951-01-04', 'modified' => '2006-12-01 13:31:26', 'mytime' => '22:57:17'],
            ['id' => '2', 'apple_id' => '1', 'color' => 'Bright Red 1', 'name' => 'Bright Red Apple', 'created' => '2006-11-22 10:43:13', 'date' => '2014-01-01', 'modified' => '2006-11-30 18:38:10', 'mytime' => '22:57:17'],
            ['id' => '3', 'apple_id' => '2', 'color' => 'blue green', 'name' => 'green blue', 'created' => '2006-12-25 05:13:36', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:23:24', 'mytime' => '22:57:17'],
            ['id' => '4', 'apple_id' => '2', 'color' => 'Blue Green', 'name' => 'Test Name', 'created' => '2006-12-25 05:23:36', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:23:36', 'mytime' => '22:57:17'],
            ['id' => '5', 'apple_id' => '5', 'color' => 'Green', 'name' => 'Blue Green', 'created' => '2006-12-25 05:24:06', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:29:16', 'mytime' => '22:57:17'],
            ['id' => '6', 'apple_id' => '4', 'color' => 'My new appleOrange', 'name' => 'My new apple', 'created' => '2006-12-25 05:29:39', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:29:39', 'mytime' => '22:57:17'],
            ['id' => '7', 'apple_id' => '6', 'color' => 'Some wierd color', 'name' => 'Some odd color', 'created' => '2006-12-25 05:34:21', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:34:21', 'mytime' => '22:57:17'],
        ];
        //$this->assertEqual($Apple->find('all'), $expected);
    }

    /**
     * testBehaviorHasOneFindCallbacks method.
     */
    public function testBehaviorHasOneFindCallbacks()
    {
        $Apple = new Apple();
        $Apple->unbindModel(['hasMany' => ['Child'], 'belongsTo' => ['Parent']], false);
        $expected = $Apple->find('all');

        $Apple->unbindModel(['hasOne' => ['Sample']]);
        $wellBehaved = $Apple->find('all');
        $Apple->Sample->Behaviors->attach('Test');
        $Apple->unbindModel(['hasOne' => ['Sample']]);
        $this->assertIdentical($Apple->find('all'), $wellBehaved);

        $Apple->Sample->Behaviors->attach('Test', ['before' => 'off']);
        $this->assertIdentical($Apple->find('all'), $expected);

        $Apple->Sample->Behaviors->attach('Test', ['before' => 'test']);
        $this->assertIdentical($Apple->find('all'), $expected);

        $Apple->Sample->Behaviors->attach('Test', ['before' => 'modify']);
        $expected2 = [
            [
                'Apple' => ['id' => 1],
                'Child' => [
                    ['id' => 2, 'name' => 'Bright Red Apple', 'mytime' => '22:57:17'], ], ],
            [
                'Apple' => ['id' => 2],
                'Child' => [
                    ['id' => 1, 'name' => 'Red Apple 1', 'mytime' => '22:57:17'],
                    ['id' => 3, 'name' => 'green blue', 'mytime' => '22:57:17'],
                    ['id' => 4, 'name' => 'Test Name', 'mytime' => '22:57:17'], ], ],
            [
                'Apple' => ['id' => 3],
                'Child' => [], ],
        ];
        $result = $Apple->find('all', ['fields' => ['Apple.id'], 'conditions' => ['Apple.id <' => '4']]);
        //$this->assertEqual($result, $expected2);

        $Apple->Sample->Behaviors->disable('Test');
        $result = $Apple->find('all');
        $this->assertEqual($result, $expected);

        $Apple->Sample->Behaviors->attach('Test', ['before' => 'off', 'after' => 'on']);
        //$this->assertIdentical($Apple->find('all'), array());

        $Apple->Sample->Behaviors->attach('Test', ['after' => 'off']);
        $this->assertEqual($Apple->find('all'), $expected);

        $Apple->Sample->Behaviors->attach('Test', ['after' => 'test']);
        $this->assertEqual($Apple->find('all'), $expected);

        $Apple->Sample->Behaviors->attach('Test', ['after' => 'test2']);
        $this->assertEqual($Apple->find('all'), $expected);

        $Apple->Sample->Behaviors->attach('Test', ['after' => 'modify']);
        $expected = [
            ['id' => '1', 'apple_id' => '2', 'color' => 'Red 1', 'name' => 'Red Apple 1', 'created' => '2006-11-22 10:38:58', 'date' => '1951-01-04', 'modified' => '2006-12-01 13:31:26', 'mytime' => '22:57:17'],
            ['id' => '2', 'apple_id' => '1', 'color' => 'Bright Red 1', 'name' => 'Bright Red Apple', 'created' => '2006-11-22 10:43:13', 'date' => '2014-01-01', 'modified' => '2006-11-30 18:38:10', 'mytime' => '22:57:17'],
            ['id' => '3', 'apple_id' => '2', 'color' => 'blue green', 'name' => 'green blue', 'created' => '2006-12-25 05:13:36', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:23:24', 'mytime' => '22:57:17'],
            ['id' => '4', 'apple_id' => '2', 'color' => 'Blue Green', 'name' => 'Test Name', 'created' => '2006-12-25 05:23:36', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:23:36', 'mytime' => '22:57:17'],
            ['id' => '5', 'apple_id' => '5', 'color' => 'Green', 'name' => 'Blue Green', 'created' => '2006-12-25 05:24:06', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:29:16', 'mytime' => '22:57:17'],
            ['id' => '6', 'apple_id' => '4', 'color' => 'My new appleOrange', 'name' => 'My new apple', 'created' => '2006-12-25 05:29:39', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:29:39', 'mytime' => '22:57:17'],
            ['id' => '7', 'apple_id' => '6', 'color' => 'Some wierd color', 'name' => 'Some odd color', 'created' => '2006-12-25 05:34:21', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:34:21', 'mytime' => '22:57:17'],
        ];
        //$this->assertEqual($Apple->find('all'), $expected);
    }

    /**
     * testBehaviorBelongsToFindCallbacks method.
     */
    public function testBehaviorBelongsToFindCallbacks()
    {
        $Apple = new Apple();
        $Apple->unbindModel(['hasMany' => ['Child'], 'hasOne' => ['Sample']], false);
        $expected = $Apple->find('all');

        $Apple->unbindModel(['belongsTo' => ['Parent']]);
        $wellBehaved = $Apple->find('all');
        $Apple->Parent->Behaviors->attach('Test');
        $Apple->unbindModel(['belongsTo' => ['Parent']]);
        $this->assertIdentical($Apple->find('all'), $wellBehaved);

        $Apple->Parent->Behaviors->attach('Test', ['before' => 'off']);
        $this->assertIdentical($Apple->find('all'), $expected);

        $Apple->Parent->Behaviors->attach('Test', ['before' => 'test']);
        $this->assertIdentical($Apple->find('all'), $expected);

        $Apple->Parent->Behaviors->attach('Test', ['before' => 'modify']);
        $expected2 = [
            [
                'Apple' => ['id' => 1],
                'Parent' => ['id' => 2, 'name' => 'Bright Red Apple', 'mytime' => '22:57:17'], ],
            [
                'Apple' => ['id' => 2],
                'Parent' => ['id' => 1, 'name' => 'Red Apple 1', 'mytime' => '22:57:17'], ],
            [
                'Apple' => ['id' => 3],
                'Parent' => ['id' => 2, 'name' => 'Bright Red Apple', 'mytime' => '22:57:17'], ],
        ];
        $result = $Apple->find('all', [
            'fields' => ['Apple.id', 'Parent.id', 'Parent.name', 'Parent.mytime'],
            'conditions' => ['Apple.id <' => '4'],
        ]);
        $this->assertEqual($result, $expected2);

        $Apple->Parent->Behaviors->disable('Test');
        $result = $Apple->find('all');
        $this->assertEqual($result, $expected);

        $Apple->Parent->Behaviors->attach('Test', ['after' => 'off']);
        $this->assertEqual($Apple->find('all'), $expected);

        $Apple->Parent->Behaviors->attach('Test', ['after' => 'test']);
        $this->assertEqual($Apple->find('all'), $expected);

        $Apple->Parent->Behaviors->attach('Test', ['after' => 'test2']);
        $this->assertEqual($Apple->find('all'), $expected);

        $Apple->Parent->Behaviors->attach('Test', ['after' => 'modify']);
        $expected = [
            ['id' => '1', 'apple_id' => '2', 'color' => 'Red 1', 'name' => 'Red Apple 1', 'created' => '2006-11-22 10:38:58', 'date' => '1951-01-04', 'modified' => '2006-12-01 13:31:26', 'mytime' => '22:57:17'],
            ['id' => '2', 'apple_id' => '1', 'color' => 'Bright Red 1', 'name' => 'Bright Red Apple', 'created' => '2006-11-22 10:43:13', 'date' => '2014-01-01', 'modified' => '2006-11-30 18:38:10', 'mytime' => '22:57:17'],
            ['id' => '3', 'apple_id' => '2', 'color' => 'blue green', 'name' => 'green blue', 'created' => '2006-12-25 05:13:36', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:23:24', 'mytime' => '22:57:17'],
            ['id' => '4', 'apple_id' => '2', 'color' => 'Blue Green', 'name' => 'Test Name', 'created' => '2006-12-25 05:23:36', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:23:36', 'mytime' => '22:57:17'],
            ['id' => '5', 'apple_id' => '5', 'color' => 'Green', 'name' => 'Blue Green', 'created' => '2006-12-25 05:24:06', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:29:16', 'mytime' => '22:57:17'],
            ['id' => '6', 'apple_id' => '4', 'color' => 'My new appleOrange', 'name' => 'My new apple', 'created' => '2006-12-25 05:29:39', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:29:39', 'mytime' => '22:57:17'],
            ['id' => '7', 'apple_id' => '6', 'color' => 'Some wierd color', 'name' => 'Some odd color', 'created' => '2006-12-25 05:34:21', 'date' => '2006-12-25', 'modified' => '2006-12-25 05:34:21', 'mytime' => '22:57:17'],
        ];
        //$this->assertEqual($Apple->find('all'), $expected);
    }

    /**
     * testBehaviorSaveCallbacks method.
     */
    public function testBehaviorSaveCallbacks()
    {
        $Sample = new Sample();
        $record = ['Sample' => ['apple_id' => 6, 'name' => 'sample99']];

        $Sample->Behaviors->attach('Test', ['beforeSave' => 'on']);
        $Sample->create();
        $this->assertIdentical($Sample->save($record), false);

        $Sample->Behaviors->attach('Test', ['beforeSave' => 'off']);
        $Sample->create();
        $this->assertIdentical($Sample->save($record), $record);

        $Sample->Behaviors->attach('Test', ['beforeSave' => 'test']);
        $Sample->create();
        $this->assertIdentical($Sample->save($record), $record);

        $Sample->Behaviors->attach('Test', ['beforeSave' => 'modify']);
        $expected = Set::insert($record, 'Sample.name', 'sample99 modified before');
        $Sample->create();
        $this->assertIdentical($Sample->save($record), $expected);

        $Sample->Behaviors->disable('Test');
        $this->assertIdentical($Sample->save($record), $record);

        $Sample->Behaviors->attach('Test', ['beforeSave' => 'off', 'afterSave' => 'on']);
        $expected = Set::merge($record, ['Sample' => ['aftersave' => 'modified after on create']]);
        $Sample->create();
        $this->assertIdentical($Sample->save($record), $expected);

        $Sample->Behaviors->attach('Test', ['beforeSave' => 'modify', 'afterSave' => 'modify']);
        $expected = Set::merge($record, ['Sample' => ['name' => 'sample99 modified before modified after on create']]);
        $Sample->create();
        $this->assertIdentical($Sample->save($record), $expected);

        $Sample->Behaviors->attach('Test', ['beforeSave' => 'off', 'afterSave' => 'test']);
        $Sample->create();
        $this->assertIdentical($Sample->save($record), $record);

        $Sample->Behaviors->attach('Test', ['afterSave' => 'test2']);
        $Sample->create();
        $this->assertIdentical($Sample->save($record), $record);

        $Sample->Behaviors->attach('Test', ['beforeFind' => 'off', 'afterFind' => 'off']);
        $Sample->recursive = -1;
        $record2 = $Sample->read(null, 1);

        $Sample->Behaviors->attach('Test', ['afterSave' => 'on']);
        $expected = Set::merge($record2, ['Sample' => ['aftersave' => 'modified after']]);
        $Sample->create();
        $this->assertIdentical($Sample->save($record2), $expected);

        $Sample->Behaviors->attach('Test', ['afterSave' => 'modify']);
        $expected = Set::merge($record2, ['Sample' => ['name' => 'sample1 modified after']]);
        $Sample->create();
        $this->assertIdentical($Sample->save($record2), $expected);
    }

    /**
     * testBehaviorDeleteCallbacks method.
     */
    public function testBehaviorDeleteCallbacks()
    {
        $Apple = new Apple();

        $Apple->Behaviors->attach('Test', ['beforeFind' => 'off', 'beforeDelete' => 'off']);
        $this->assertIdentical($Apple->delete(6), true);

        $Apple->Behaviors->attach('Test', ['beforeDelete' => 'on']);
        $this->assertIdentical($Apple->delete(4), false);

        $Apple->Behaviors->attach('Test', ['beforeDelete' => 'test2']);
        if (ob_start()) {
            $results = $Apple->delete(4);
            $this->assertIdentical(trim(ob_get_clean()), 'beforeDelete success (cascading)');
            $this->assertIdentical($results, true);
        }
        if (ob_start()) {
            $results = $Apple->delete(3, false);
            $this->assertIdentical(trim(ob_get_clean()), 'beforeDelete success');
            $this->assertIdentical($results, true);
        }

        $Apple->Behaviors->attach('Test', ['beforeDelete' => 'off', 'afterDelete' => 'on']);
        if (ob_start()) {
            $results = $Apple->delete(2, false);
            $this->assertIdentical(trim(ob_get_clean()), 'afterDelete success');
            $this->assertIdentical($results, true);
        }
    }

    /**
     * testBehaviorOnErrorCallback method.
     */
    public function testBehaviorOnErrorCallback()
    {
        $Apple = new Apple();

        $Apple->Behaviors->attach('Test', ['beforeFind' => 'off', 'onError' => 'on']);
        if (ob_start()) {
            $Apple->Behaviors->Test->onError($Apple);
            $this->assertIdentical(trim(ob_get_clean()), 'onError trigger success');
        }

        if (ob_start()) {
            $Apple->delete(99);
            //$this->assertIdentical(trim(ob_get_clean()), 'onError trigger success');
        }
    }

    /**
     * testBehaviorValidateCallback method.
     */
    public function testBehaviorValidateCallback()
    {
        $Apple = new Apple();

        $Apple->Behaviors->attach('Test');
        $this->assertIdentical($Apple->validates(), true);

        $Apple->Behaviors->attach('Test', ['validate' => 'on']);
        $this->assertIdentical($Apple->validates(), false);
        $this->assertIdentical($Apple->validationErrors, ['name' => true]);

        $Apple->Behaviors->attach('Test', ['validate' => 'stop']);
        $this->assertIdentical($Apple->validates(), false);
        $this->assertIdentical($Apple->validationErrors, ['name' => true]);

        $Apple->Behaviors->attach('Test', ['validate' => 'whitelist']);
        $Apple->validates();
        $this->assertIdentical($Apple->whitelist, []);

        $Apple->whitelist = ['unknown'];
        $Apple->validates();
        $this->assertIdentical($Apple->whitelist, ['unknown', 'name']);
    }

    /**
     * testBehaviorValidateMethods method.
     */
    public function testBehaviorValidateMethods()
    {
        $Apple = new Apple();
        $Apple->Behaviors->attach('Test');
        $Apple->validate['color'] = 'validateField';

        $result = $Apple->save(['name' => 'Genetically Modified Apple', 'color' => 'Orange']);
        $this->assertEqual(array_keys($result['Apple']), ['name', 'color', 'modified', 'created']);

        $Apple->create();
        $result = $Apple->save(['name' => 'Regular Apple', 'color' => 'Red']);
        $this->assertFalse($result);
    }

    /**
     * testBehaviorMethodDispatching method.
     */
    public function testBehaviorMethodDispatching()
    {
        $Apple = new Apple();
        $Apple->Behaviors->attach('Test');

        $expected = 'working';
        $this->assertEqual($Apple->testMethod(), $expected);
        $this->assertEqual($Apple->Behaviors->dispatchMethod($Apple, 'testMethod'), $expected);

        $result = $Apple->Behaviors->dispatchMethod($Apple, 'wtf');
        $this->assertEqual($result, ['unhandled']);

        $result = $Apple->{'look for the remote'}('in the couch');
        $expected = "Item.name = 'the remote' AND Location.name = 'the couch'";
        $this->assertEqual($result, $expected);
    }

    /**
     * testBehaviorMethodDispatchingWithData method.
     */
    public function testBehaviorMethodDispatchingWithData()
    {
        $Apple = new Apple();
        $Apple->Behaviors->attach('Test');

        $Apple->set('field', 'value');
        $this->assertTrue($Apple->testData());
        $this->assertTrue($Apple->data['Apple']['field_2']);

        $this->assertTrue($Apple->testData('one', 'two', 'three', 'four', 'five', 'six'));
    }

    /**
     * testBehaviorTrigger method.
     */
    public function testBehaviorTrigger()
    {
        $Apple = new Apple();
        $Apple->Behaviors->attach('Test');
        $Apple->Behaviors->attach('Test2');
        $Apple->Behaviors->attach('Test3');

        $Apple->beforeTestResult = [];
        $Apple->Behaviors->trigger($Apple, 'beforeTest');
        $expected = ['testbehavior', 'test2behavior', 'test3behavior'];
        $this->assertIdentical($Apple->beforeTestResult, $expected);

        $Apple->beforeTestResult = [];
        $Apple->Behaviors->trigger($Apple, 'beforeTest', [], ['break' => true, 'breakOn' => 'test2behavior']);
        $expected = ['testbehavior', 'test2behavior'];
        $this->assertIdentical($Apple->beforeTestResult, $expected);

        $Apple->beforeTestResult = [];
        $Apple->Behaviors->trigger($Apple, 'beforeTest', [], ['break' => true, 'breakOn' => ['test2behavior', 'test3behavior']]);
        $expected = ['testbehavior', 'test2behavior'];
        $this->assertIdentical($Apple->beforeTestResult, $expected);
    }

    /**
     * undocumented function.
     */
    public function testBindModelCallsInBehaviors()
    {
        $this->loadFixtures('Article', 'Comment');

        // hasMany
        $Article = new Article();
        $Article->unbindModel(['hasMany' => ['Comment']]);
        $result = $Article->find('first');
        $this->assertFalse(array_key_exists('Comment', $result));

        $Article->Behaviors->attach('Test4');
        $result = $Article->find('first');
        $this->assertTrue(array_key_exists('Comment', $result));

        // belongsTo
        $Article->unbindModel(['belongsTo' => ['User']]);
        $result = $Article->find('first');
        $this->assertFalse(array_key_exists('User', $result));

        $Article->Behaviors->attach('Test5');
        $result = $Article->find('first');
        $this->assertTrue(array_key_exists('User', $result));

        // hasAndBelongsToMany
        $Article->unbindModel(['hasAndBelongsToMany' => ['Tag']]);
        $result = $Article->find('first');
        $this->assertFalse(array_key_exists('Tag', $result));

        $Article->Behaviors->attach('Test6');
        $result = $Article->find('first');
        $this->assertTrue(array_key_exists('Comment', $result));

        // hasOne
        $Comment = new Comment();
        $Comment->unbindModel(['hasOne' => ['Attachment']]);
        $result = $Comment->find('first');
        $this->assertFalse(array_key_exists('Attachment', $result));

        $Comment->Behaviors->attach('Test7');
        $result = $Comment->find('first');
        $this->assertTrue(array_key_exists('Attachment', $result));
    }

    /**
     * Test attach and detaching.
     */
    public function testBehaviorAttachAndDetach()
    {
        $Sample = new Sample();
        $Sample->actsAs = ['Test3' => ['bar'], 'Test2' => ['foo', 'bar']];
        $Sample->Behaviors->init($Sample->alias, $Sample->actsAs);
        $Sample->Behaviors->attach('Test2');
        $Sample->Behaviors->detach('Test3');

        $Sample->Behaviors->trigger($Sample, 'beforeTest');
    }
}
