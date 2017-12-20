<?php
/**
 * TreeBehaviorTest file.
 *
 * Holds several Test Cases
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
 * @since         CakePHP(tm) v 1.2.0.5330
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Core', ['AppModel', 'Model']);
require_once dirname(dirname(__FILE__)).DS.'models.php';

/**
 * NumberTreeTest class.
 */
class NumberTreeTest extends CakeTestCase
{
    /**
     * settings property.
     *
     * @var array
     */
    public $settings = [
        'modelClass' => 'NumberTree',
        'leftField' => 'lft',
        'rightField' => 'rght',
        'parentField' => 'parent_id',
    ];

    /**
     * fixtures property.
     *
     * @var array
     */
    public $fixtures = ['core.number_tree'];

    /**
     * testInitialize method.
     */
    public function testInitialize()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $result = $this->Tree->find('count');
        $this->assertEqual($result, 7);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testDetectInvalidLeft method.
     */
    public function testDetectInvalidLeft()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $result = $this->Tree->findByName('1.1');

        $save[$modelClass]['id'] = $result[$modelClass]['id'];
        $save[$modelClass][$leftField] = 0;

        $this->Tree->save($save);
        $result = $this->Tree->verify();
        $this->assertNotIdentical($result, true);

        $result = $this->Tree->recover();
        $this->assertIdentical($result, true);

        $result = $this->Tree->verify();
        $this->assertIdentical($result, true);
    }

    /**
     * testDetectInvalidRight method.
     */
    public function testDetectInvalidRight()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $result = $this->Tree->findByName('1.1');

        $save[$modelClass]['id'] = $result[$modelClass]['id'];
        $save[$modelClass][$rightField] = 0;

        $this->Tree->save($save);
        $result = $this->Tree->verify();
        $this->assertNotIdentical($result, true);

        $result = $this->Tree->recover();
        $this->assertIdentical($result, true);

        $result = $this->Tree->verify();
        $this->assertIdentical($result, true);
    }

    /**
     * testDetectInvalidParent method.
     */
    public function testDetectInvalidParent()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $result = $this->Tree->findByName('1.1');

        // Bypass behavior and any other logic
        $this->Tree->updateAll([$parentField => null], ['id' => $result[$modelClass]['id']]);

        $result = $this->Tree->verify();
        $this->assertNotIdentical($result, true);

        $result = $this->Tree->recover();
        $this->assertIdentical($result, true);

        $result = $this->Tree->verify();
        $this->assertIdentical($result, true);
    }

    /**
     * testDetectNoneExistantParent method.
     */
    public function testDetectNoneExistantParent()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $result = $this->Tree->findByName('1.1');
        $this->Tree->updateAll([$parentField => 999999], ['id' => $result[$modelClass]['id']]);

        $result = $this->Tree->verify();
        $this->assertNotIdentical($result, true);

        $result = $this->Tree->recover('MPTT');
        $this->assertIdentical($result, true);

        $result = $this->Tree->verify();
        $this->assertIdentical($result, true);
    }

    /**
     * testRecoverUsingParentMode method.
     */
    public function testRecoverUsingParentMode()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->Behaviors->disable('Tree');

        $this->Tree->save(['parent_id' => null, 'name' => 'Main', $parentField => null, $leftField => 0, $rightField => 0]);
        $node1 = $this->Tree->id;

        $this->Tree->create(false);
        $this->Tree->save(['parent_id' => null, 'name' => 'About Us', $parentField => $node1, $leftField => 0, $rightField => 0]);
        $node11 = $this->Tree->id;
        $this->Tree->create(false);
        $this->Tree->save(['parent_id' => null, 'name' => 'Programs', $parentField => $node1, $leftField => 0, $rightField => 0]);
        $node12 = $this->Tree->id;
        $this->Tree->create(false);
        $this->Tree->save(['parent_id' => null, 'name' => 'Mission and History', $parentField => $node11, $leftField => 0, $rightField => 0]);
        $this->Tree->create(false);
        $this->Tree->save(['parent_id' => null, 'name' => 'Overview', $parentField => $node12, $leftField => 0, $rightField => 0]);

        $this->Tree->Behaviors->enable('Tree');

        $result = $this->Tree->verify();
        $this->assertNotIdentical($result, false);

        $result = $this->Tree->recover();
        $this->assertTrue($result);

        $result = $this->Tree->verify();
        $this->assertTrue($result);

        $result = $this->Tree->find('first', [
            'fields' => ['name', $parentField, $leftField, $rightField],
            'conditions' => ['name' => 'Main'],
            'recursive' => -1,
        ]);
        $expected = [
            $modelClass => [
                'name' => 'Main',
                $parentField => null,
                $leftField => 1,
                $rightField => 10,
            ],
        ];
        $this->assertEqual($expected, $result);
    }

    /**
     * testRecoverFromMissingParent method.
     */
    public function testRecoverFromMissingParent()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $result = $this->Tree->findByName('1.1');
        $this->Tree->updateAll([$parentField => 999999], ['id' => $result[$modelClass]['id']]);

        $result = $this->Tree->verify();
        $this->assertNotIdentical($result, true);

        $result = $this->Tree->recover();
        $this->assertIdentical($result, true);

        $result = $this->Tree->verify();
        $this->assertIdentical($result, true);
    }

    /**
     * testDetectInvalidParents method.
     */
    public function testDetectInvalidParents()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $this->Tree->updateAll([$parentField => null]);

        $result = $this->Tree->verify();
        $this->assertNotIdentical($result, true);

        $result = $this->Tree->recover();
        $this->assertIdentical($result, true);

        $result = $this->Tree->verify();
        $this->assertIdentical($result, true);
    }

    /**
     * testDetectInvalidLftsRghts method.
     */
    public function testDetectInvalidLftsRghts()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $this->Tree->updateAll([$leftField => 0, $rightField => 0]);

        $result = $this->Tree->verify();
        $this->assertNotIdentical($result, true);

        $this->Tree->recover();

        $result = $this->Tree->verify();
        $this->assertIdentical($result, true);
    }

    /**
     * Reproduces a situation where a single node has lft= rght, and all other lft and rght fields follow sequentially.
     */
    public function testDetectEqualLftsRghts()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(1, 3);

        $result = $this->Tree->findByName('1.1');
        $this->Tree->updateAll([$rightField => $result[$modelClass][$leftField]], ['id' => $result[$modelClass]['id']]);
        $this->Tree->updateAll([$leftField => $this->Tree->escapeField($leftField).' -1'],
            [$leftField.' >' => $result[$modelClass][$leftField]]);
        $this->Tree->updateAll([$rightField => $this->Tree->escapeField($rightField).' -1'],
            [$rightField.' >' => $result[$modelClass][$leftField]]);

        $result = $this->Tree->verify();
        $this->assertNotIdentical($result, true);

        $result = $this->Tree->recover();
        $this->assertTrue($result);

        $result = $this->Tree->verify();
        $this->assertTrue($result);
    }

    /**
     * testAddOrphan method.
     */
    public function testAddOrphan()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $this->Tree->save([$modelClass => ['name' => 'testAddOrphan', $parentField => null]]);
        $result = $this->Tree->find(null, ['name', $parentField], $modelClass.'.'.$leftField.' desc');
        $expected = [$modelClass => ['name' => 'testAddOrphan', $parentField => null]];
        $this->assertEqual($result, $expected);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testAddMiddle method.
     */
    public function testAddMiddle()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $data = $this->Tree->find([$modelClass.'.name' => '1.1'], ['id']);
        $initialCount = $this->Tree->find('count');

        $this->Tree->create();
        $result = $this->Tree->save([$modelClass => ['name' => 'testAddMiddle', $parentField => $data[$modelClass]['id']]]);
        $expected = array_merge([$modelClass => ['name' => 'testAddMiddle', $parentField => '2']], $result);
        $this->assertIdentical($result, $expected);

        $laterCount = $this->Tree->find('count');

        $laterCount = $this->Tree->find('count');
        $this->assertEqual($initialCount + 1, $laterCount);

        $children = $this->Tree->children($data[$modelClass]['id'], true, ['name']);
        $expects = [[$modelClass => ['name' => '1.1.1']],
            [$modelClass => ['name' => '1.1.2']],
            [$modelClass => ['name' => 'testAddMiddle']], ];
        $this->assertIdentical($children, $expects);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testAddInvalid method.
     */
    public function testAddInvalid()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);
        $this->Tree->id = null;

        $initialCount = $this->Tree->find('count');
        //$this->expectError('Trying to save a node under a none-existant node in TreeBehavior::beforeSave');

        $saveSuccess = $this->Tree->save([$modelClass => ['name' => 'testAddInvalid', $parentField => 99999]]);
        $this->assertIdentical($saveSuccess, false);

        $laterCount = $this->Tree->find('count');
        $this->assertIdentical($initialCount, $laterCount);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testAddNotIndexedByModel method.
     */
    public function testAddNotIndexedByModel()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $this->Tree->save(['name' => 'testAddNotIndexed', $parentField => null]);
        $result = $this->Tree->find(null, ['name', $parentField], $modelClass.'.'.$leftField.' desc');
        $expected = [$modelClass => ['name' => 'testAddNotIndexed', $parentField => null]];
        $this->assertEqual($result, $expected);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testMovePromote method.
     */
    public function testMovePromote()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);
        $this->Tree->id = null;

        $parent = $this->Tree->find([$modelClass.'.name' => '1. Root']);
        $parent_id = $parent[$modelClass]['id'];

        $data = $this->Tree->find([$modelClass.'.name' => '1.1.1'], ['id']);
        $this->Tree->id = $data[$modelClass]['id'];
        $this->Tree->saveField($parentField, $parent_id);
        $direct = $this->Tree->children($parent_id, true, ['id', 'name', $parentField, $leftField, $rightField]);
        $expects = [[$modelClass => ['id' => 2, 'name' => '1.1', $parentField => 1, $leftField => 2, $rightField => 5]],
            [$modelClass => ['id' => 5, 'name' => '1.2', $parentField => 1, $leftField => 6, $rightField => 11]],
            [$modelClass => ['id' => 3, 'name' => '1.1.1', $parentField => 1, $leftField => 12, $rightField => 13]], ];
        $this->assertEqual($direct, $expects);
        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testMoveWithWhitelist method.
     */
    public function testMoveWithWhitelist()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);
        $this->Tree->id = null;

        $parent = $this->Tree->find([$modelClass.'.name' => '1. Root']);
        $parent_id = $parent[$modelClass]['id'];

        $data = $this->Tree->find([$modelClass.'.name' => '1.1.1'], ['id']);
        $this->Tree->id = $data[$modelClass]['id'];
        $this->Tree->whitelist = [$parentField, 'name', 'description'];
        $this->Tree->saveField($parentField, $parent_id);

        $result = $this->Tree->children($parent_id, true, ['id', 'name', $parentField, $leftField, $rightField]);
        $expected = [[$modelClass => ['id' => 2, 'name' => '1.1', $parentField => 1, $leftField => 2, $rightField => 5]],
            [$modelClass => ['id' => 5, 'name' => '1.2', $parentField => 1, $leftField => 6, $rightField => 11]],
            [$modelClass => ['id' => 3, 'name' => '1.1.1', $parentField => 1, $leftField => 12, $rightField => 13]], ];
        $this->assertEqual($result, $expected);
        $this->assertTrue($this->Tree->verify());
    }

    /**
     * testInsertWithWhitelist method.
     */
    public function testInsertWithWhitelist()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $this->Tree->whitelist = ['name', $parentField];
        $this->Tree->save([$modelClass => ['name' => 'testAddOrphan', $parentField => null]]);
        $result = $this->Tree->findByName('testAddOrphan', ['name', $parentField, $leftField, $rightField]);
        $expected = ['name' => 'testAddOrphan', $parentField => null, $leftField => '15', $rightField => 16];
        $this->assertEqual($result[$modelClass], $expected);
        $this->assertIdentical($this->Tree->verify(), true);
    }

    /**
     * testMoveBefore method.
     */
    public function testMoveBefore()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);
        $this->Tree->id = null;

        $parent = $this->Tree->find([$modelClass.'.name' => '1.1']);
        $parent_id = $parent[$modelClass]['id'];

        $data = $this->Tree->find([$modelClass.'.name' => '1.2'], ['id']);
        $this->Tree->id = $data[$modelClass]['id'];
        $this->Tree->saveField($parentField, $parent_id);

        $result = $this->Tree->children($parent_id, true, ['name']);
        $expects = [[$modelClass => ['name' => '1.1.1']],
            [$modelClass => ['name' => '1.1.2']],
            [$modelClass => ['name' => '1.2']], ];
        $this->assertEqual($result, $expects);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testMoveAfter method.
     */
    public function testMoveAfter()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);
        $this->Tree->id = null;

        $parent = $this->Tree->find([$modelClass.'.name' => '1.2']);
        $parent_id = $parent[$modelClass]['id'];

        $data = $this->Tree->find([$modelClass.'.name' => '1.1'], ['id']);
        $this->Tree->id = $data[$modelClass]['id'];
        $this->Tree->saveField($parentField, $parent_id);

        $result = $this->Tree->children($parent_id, true, ['name']);
        $expects = [[$modelClass => ['name' => '1.2.1']],
            [$modelClass => ['name' => '1.2.2']],
            [$modelClass => ['name' => '1.1']], ];
        $this->assertEqual($result, $expects);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testMoveDemoteInvalid method.
     */
    public function testMoveDemoteInvalid()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);
        $this->Tree->id = null;

        $parent = $this->Tree->find([$modelClass.'.name' => '1. Root']);
        $parent_id = $parent[$modelClass]['id'];

        $data = $this->Tree->find([$modelClass.'.name' => '1.1.1'], ['id']);

        $expects = $this->Tree->find('all');
        $before = $this->Tree->read(null, $data[$modelClass]['id']);

        $this->Tree->id = $parent_id;
        //$this->expectError('Trying to save a node under itself in TreeBehavior::beforeSave');
        $this->Tree->saveField($parentField, $data[$modelClass]['id']);

        $results = $this->Tree->find('all');
        $after = $this->Tree->read(null, $data[$modelClass]['id']);

        $this->assertEqual($results, $expects);
        $this->assertEqual($before, $after);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testMoveInvalid method.
     */
    public function testMoveInvalid()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);
        $this->Tree->id = null;

        $initialCount = $this->Tree->find('count');
        $data = $this->Tree->findByName('1.1');

        //$this->expectError('Trying to save a node under a none-existant node in TreeBehavior::beforeSave');
        $this->Tree->id = $data[$modelClass]['id'];
        $this->Tree->saveField($parentField, 999999);

        //$this->assertIdentical($saveSuccess, false);
        $laterCount = $this->Tree->find('count');
        $this->assertIdentical($initialCount, $laterCount);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testMoveSelfInvalid method.
     */
    public function testMoveSelfInvalid()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);
        $this->Tree->id = null;

        $initialCount = $this->Tree->find('count');
        $data = $this->Tree->findByName('1.1');

        //$this->expectError('Trying to set a node to be the parent of itself in TreeBehavior::beforeSave');
        $this->Tree->id = $data[$modelClass]['id'];
        $saveSuccess = $this->Tree->saveField($parentField, $this->Tree->id);

        $this->assertIdentical($saveSuccess, false);
        $laterCount = $this->Tree->find('count');
        $this->assertIdentical($initialCount, $laterCount);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testMoveUpSuccess method.
     */
    public function testMoveUpSuccess()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $data = $this->Tree->find([$modelClass.'.name' => '1.2'], ['id']);
        $this->Tree->moveUp($data[$modelClass]['id']);

        $parent = $this->Tree->findByName('1. Root', ['id']);
        $this->Tree->id = $parent[$modelClass]['id'];
        $result = $this->Tree->children(null, true, ['name']);
        $expected = [[$modelClass => ['name' => '1.2']],
            [$modelClass => ['name' => '1.1']], ];
        $this->assertIdentical($result, $expected);
    }

    /**
     * testMoveUpFail method.
     */
    public function testMoveUpFail()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $data = $this->Tree->find([$modelClass.'.name' => '1.1']);

        $this->Tree->moveUp($data[$modelClass]['id']);

        $parent = $this->Tree->findByName('1. Root', ['id']);
        $this->Tree->id = $parent[$modelClass]['id'];
        $result = $this->Tree->children(null, true, ['name']);
        $expected = [[$modelClass => ['name' => '1.1']],
            [$modelClass => ['name' => '1.2']], ];
        $this->assertIdentical($result, $expected);
    }

    /**
     * testMoveUp2 method.
     */
    public function testMoveUp2()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(1, 10);

        $data = $this->Tree->find([$modelClass.'.name' => '1.5'], ['id']);
        $this->Tree->moveUp($data[$modelClass]['id'], 2);

        $parent = $this->Tree->findByName('1. Root', ['id']);
        $this->Tree->id = $parent[$modelClass]['id'];
        $result = $this->Tree->children(null, true, ['name']);
        $expected = [
            [$modelClass => ['name' => '1.1']],
            [$modelClass => ['name' => '1.2']],
            [$modelClass => ['name' => '1.5']],
            [$modelClass => ['name' => '1.3']],
            [$modelClass => ['name' => '1.4']],
            [$modelClass => ['name' => '1.6']],
            [$modelClass => ['name' => '1.7']],
            [$modelClass => ['name' => '1.8']],
            [$modelClass => ['name' => '1.9']],
            [$modelClass => ['name' => '1.10']], ];
        $this->assertIdentical($result, $expected);
    }

    /**
     * testMoveUpFirst method.
     */
    public function testMoveUpFirst()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(1, 10);

        $data = $this->Tree->find([$modelClass.'.name' => '1.5'], ['id']);
        $this->Tree->moveUp($data[$modelClass]['id'], true);

        $parent = $this->Tree->findByName('1. Root', ['id']);
        $this->Tree->id = $parent[$modelClass]['id'];
        $result = $this->Tree->children(null, true, ['name']);
        $expected = [
            [$modelClass => ['name' => '1.5']],
            [$modelClass => ['name' => '1.1']],
            [$modelClass => ['name' => '1.2']],
            [$modelClass => ['name' => '1.3']],
            [$modelClass => ['name' => '1.4']],
            [$modelClass => ['name' => '1.6']],
            [$modelClass => ['name' => '1.7']],
            [$modelClass => ['name' => '1.8']],
            [$modelClass => ['name' => '1.9']],
            [$modelClass => ['name' => '1.10']], ];
        $this->assertIdentical($result, $expected);
    }

    /**
     * testMoveDownSuccess method.
     */
    public function testMoveDownSuccess()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $data = $this->Tree->find([$modelClass.'.name' => '1.1'], ['id']);
        $this->Tree->moveDown($data[$modelClass]['id']);

        $parent = $this->Tree->findByName('1. Root', ['id']);
        $this->Tree->id = $parent[$modelClass]['id'];
        $result = $this->Tree->children(null, true, ['name']);
        $expected = [[$modelClass => ['name' => '1.2']],
            [$modelClass => ['name' => '1.1']], ];
        $this->assertIdentical($result, $expected);
    }

    /**
     * testMoveDownFail method.
     */
    public function testMoveDownFail()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $data = $this->Tree->find([$modelClass.'.name' => '1.2']);
        $this->Tree->moveDown($data[$modelClass]['id']);

        $parent = $this->Tree->findByName('1. Root', ['id']);
        $this->Tree->id = $parent[$modelClass]['id'];
        $result = $this->Tree->children(null, true, ['name']);
        $expected = [[$modelClass => ['name' => '1.1']],
            [$modelClass => ['name' => '1.2']], ];
        $this->assertIdentical($result, $expected);
    }

    /**
     * testMoveDownLast method.
     */
    public function testMoveDownLast()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(1, 10);

        $data = $this->Tree->find([$modelClass.'.name' => '1.5'], ['id']);
        $this->Tree->moveDown($data[$modelClass]['id'], true);

        $parent = $this->Tree->findByName('1. Root', ['id']);
        $this->Tree->id = $parent[$modelClass]['id'];
        $result = $this->Tree->children(null, true, ['name']);
        $expected = [
            [$modelClass => ['name' => '1.1']],
            [$modelClass => ['name' => '1.2']],
            [$modelClass => ['name' => '1.3']],
            [$modelClass => ['name' => '1.4']],
            [$modelClass => ['name' => '1.6']],
            [$modelClass => ['name' => '1.7']],
            [$modelClass => ['name' => '1.8']],
            [$modelClass => ['name' => '1.9']],
            [$modelClass => ['name' => '1.10']],
            [$modelClass => ['name' => '1.5']], ];
        $this->assertIdentical($result, $expected);
    }

    /**
     * testMoveDown2 method.
     */
    public function testMoveDown2()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(1, 10);

        $data = $this->Tree->find([$modelClass.'.name' => '1.5'], ['id']);
        $this->Tree->moveDown($data[$modelClass]['id'], 2);

        $parent = $this->Tree->findByName('1. Root', ['id']);
        $this->Tree->id = $parent[$modelClass]['id'];
        $result = $this->Tree->children(null, true, ['name']);
        $expected = [
            [$modelClass => ['name' => '1.1']],
            [$modelClass => ['name' => '1.2']],
            [$modelClass => ['name' => '1.3']],
            [$modelClass => ['name' => '1.4']],
            [$modelClass => ['name' => '1.6']],
            [$modelClass => ['name' => '1.7']],
            [$modelClass => ['name' => '1.5']],
            [$modelClass => ['name' => '1.8']],
            [$modelClass => ['name' => '1.9']],
            [$modelClass => ['name' => '1.10']], ];
        $this->assertIdentical($result, $expected);
    }

    /**
     * testSaveNoMove method.
     */
    public function testSaveNoMove()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(1, 10);

        $data = $this->Tree->find([$modelClass.'.name' => '1.5'], ['id']);
        $this->Tree->id = $data[$modelClass]['id'];
        $this->Tree->saveField('name', 'renamed');
        $parent = $this->Tree->findByName('1. Root', ['id']);
        $this->Tree->id = $parent[$modelClass]['id'];
        $result = $this->Tree->children(null, true, ['name']);
        $expected = [
            [$modelClass => ['name' => '1.1']],
            [$modelClass => ['name' => '1.2']],
            [$modelClass => ['name' => '1.3']],
            [$modelClass => ['name' => '1.4']],
            [$modelClass => ['name' => 'renamed']],
            [$modelClass => ['name' => '1.6']],
            [$modelClass => ['name' => '1.7']],
            [$modelClass => ['name' => '1.8']],
            [$modelClass => ['name' => '1.9']],
            [$modelClass => ['name' => '1.10']], ];
        $this->assertIdentical($result, $expected);
    }

    /**
     * testMoveToRootAndMoveUp method.
     */
    public function testMoveToRootAndMoveUp()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(1, 1);
        $data = $this->Tree->find([$modelClass.'.name' => '1.1'], ['id']);
        $this->Tree->id = $data[$modelClass]['id'];
        $this->Tree->save([$parentField => null]);

        $result = $this->Tree->verify();
        $this->assertIdentical($result, true);

        $this->Tree->moveup();

        $result = $this->Tree->find('all', ['fields' => 'name', 'order' => $modelClass.'.'.$leftField.' ASC']);
        $expected = [[$modelClass => ['name' => '1.1']],
            [$modelClass => ['name' => '1. Root']], ];
        $this->assertIdentical($result, $expected);
    }

    /**
     * testDelete method.
     */
    public function testDelete()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $initialCount = $this->Tree->find('count');
        $result = $this->Tree->findByName('1.1.1');

        $return = $this->Tree->delete($result[$modelClass]['id']);
        $this->assertEqual($return, true);

        $laterCount = $this->Tree->find('count');
        $this->assertEqual($initialCount - 1, $laterCount);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);

        $initialCount = $this->Tree->find('count');
        $result = $this->Tree->findByName('1.1');

        $return = $this->Tree->delete($result[$modelClass]['id']);
        $this->assertEqual($return, true);

        $laterCount = $this->Tree->find('count');
        $this->assertEqual($initialCount - 2, $laterCount);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testRemove method.
     */
    public function testRemove()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);
        $initialCount = $this->Tree->find('count');
        $result = $this->Tree->findByName('1.1');

        $this->Tree->removeFromTree($result[$modelClass]['id']);

        $laterCount = $this->Tree->find('count');
        $this->assertEqual($initialCount, $laterCount);

        $children = $this->Tree->children($result[$modelClass][$parentField], true, ['name']);
        $expects = [[$modelClass => ['name' => '1.1.1']],
            [$modelClass => ['name' => '1.1.2']],
            [$modelClass => ['name' => '1.2']], ];
        $this->assertEqual($children, $expects);

        $topNodes = $this->Tree->children(false, true, ['name']);
        $expects = [[$modelClass => ['name' => '1. Root']],
            [$modelClass => ['name' => '1.1']], ];
        $this->assertEqual($topNodes, $expects);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testRemoveLastTopParent method.
     */
    public function testRemoveLastTopParent()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $initialCount = $this->Tree->find('count');
        $initialTopNodes = $this->Tree->childCount(false);

        $result = $this->Tree->findByName('1. Root');
        $this->Tree->removeFromTree($result[$modelClass]['id']);

        $laterCount = $this->Tree->find('count');
        $laterTopNodes = $this->Tree->childCount(false);

        $this->assertEqual($initialCount, $laterCount);
        $this->assertEqual($initialTopNodes, $laterTopNodes);

        $topNodes = $this->Tree->children(false, true, ['name']);
        $expects = [[$modelClass => ['name' => '1.1']],
            [$modelClass => ['name' => '1.2']],
            [$modelClass => ['name' => '1. Root']], ];

        $this->assertEqual($topNodes, $expects);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testRemoveNoChildren method.
     */
    public function testRemoveNoChildren()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);
        $initialCount = $this->Tree->find('count');

        $result = $this->Tree->findByName('1.1.1');
        $this->Tree->removeFromTree($result[$modelClass]['id']);

        $laterCount = $this->Tree->find('count');
        $this->assertEqual($initialCount, $laterCount);

        $nodes = $this->Tree->find('list', ['order' => $leftField]);
        $expects = [
            1 => '1. Root',
            2 => '1.1',
            4 => '1.1.2',
            5 => '1.2',
            6 => '1.2.1',
            7 => '1.2.2',
            3 => '1.1.1',
        ];

        $this->assertEqual($nodes, $expects);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testRemoveAndDelete method.
     */
    public function testRemoveAndDelete()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $initialCount = $this->Tree->find('count');
        $result = $this->Tree->findByName('1.1');

        $this->Tree->removeFromTree($result[$modelClass]['id'], true);

        $laterCount = $this->Tree->find('count');
        $this->assertEqual($initialCount - 1, $laterCount);

        $children = $this->Tree->children($result[$modelClass][$parentField], true, ['name'], $leftField.' asc');
        $expects = [[$modelClass => ['name' => '1.1.1']],
            [$modelClass => ['name' => '1.1.2']],
            [$modelClass => ['name' => '1.2']], ];
        $this->assertEqual($children, $expects);

        $topNodes = $this->Tree->children(false, true, ['name']);
        $expects = [[$modelClass => ['name' => '1. Root']]];
        $this->assertEqual($topNodes, $expects);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testRemoveAndDeleteNoChildren method.
     */
    public function testRemoveAndDeleteNoChildren()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);
        $initialCount = $this->Tree->find('count');

        $result = $this->Tree->findByName('1.1.1');
        $this->Tree->removeFromTree($result[$modelClass]['id'], true);

        $laterCount = $this->Tree->find('count');
        $this->assertEqual($initialCount - 1, $laterCount);

        $nodes = $this->Tree->find('list', ['order' => $leftField]);
        $expects = [
            1 => '1. Root',
            2 => '1.1',
            4 => '1.1.2',
            5 => '1.2',
            6 => '1.2.1',
            7 => '1.2.2',
        ];
        $this->assertEqual($nodes, $expects);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testChildren method.
     */
    public function testChildren()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $data = $this->Tree->find([$modelClass.'.name' => '1. Root']);
        $this->Tree->id = $data[$modelClass]['id'];

        $direct = $this->Tree->children(null, true, ['id', 'name', $parentField, $leftField, $rightField]);
        $expects = [[$modelClass => ['id' => 2, 'name' => '1.1', $parentField => 1, $leftField => 2, $rightField => 7]],
            [$modelClass => ['id' => 5, 'name' => '1.2', $parentField => 1, $leftField => 8, $rightField => 13]], ];
        $this->assertEqual($direct, $expects);

        $total = $this->Tree->children(null, null, ['id', 'name', $parentField, $leftField, $rightField]);
        $expects = [[$modelClass => ['id' => 2, 'name' => '1.1', $parentField => 1, $leftField => 2, $rightField => 7]],
            [$modelClass => ['id' => 3, 'name' => '1.1.1', $parentField => 2, $leftField => 3, $rightField => 4]],
            [$modelClass => ['id' => 4, 'name' => '1.1.2', $parentField => 2, $leftField => 5, $rightField => 6]],
            [$modelClass => ['id' => 5, 'name' => '1.2', $parentField => 1, $leftField => 8, $rightField => 13]],
            [$modelClass => ['id' => 6, 'name' => '1.2.1', $parentField => 5, $leftField => 9, $rightField => 10]],
            [$modelClass => ['id' => 7, 'name' => '1.2.2', $parentField => 5, $leftField => 11, $rightField => 12]], ];
        $this->assertEqual($total, $expects);

        $this->assertEqual([], $this->Tree->children(10000));
    }

    /**
     * testCountChildren method.
     */
    public function testCountChildren()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $data = $this->Tree->find([$modelClass.'.name' => '1. Root']);
        $this->Tree->id = $data[$modelClass]['id'];

        $direct = $this->Tree->childCount(null, true);
        $this->assertEqual($direct, 2);

        $total = $this->Tree->childCount();
        $this->assertEqual($total, 6);

        $this->Tree->read(null, $data[$modelClass]['id']);
        $id = $this->Tree->field('id', [$modelClass.'.name' => '1.2']);
        $total = $this->Tree->childCount($id);
        $this->assertEqual($total, 2);
    }

    /**
     * testGetParentNode method.
     */
    public function testGetParentNode()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $data = $this->Tree->find([$modelClass.'.name' => '1.2.2']);
        $this->Tree->id = $data[$modelClass]['id'];

        $result = $this->Tree->getparentNode(null, ['name']);
        $expects = [$modelClass => ['name' => '1.2']];
        $this->assertIdentical($result, $expects);
    }

    /**
     * testGetPath method.
     */
    public function testGetPath()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $data = $this->Tree->find([$modelClass.'.name' => '1.2.2']);
        $this->Tree->id = $data[$modelClass]['id'];

        $result = $this->Tree->getPath(null, ['name']);
        $expects = [[$modelClass => ['name' => '1. Root']],
            [$modelClass => ['name' => '1.2']],
            [$modelClass => ['name' => '1.2.2']], ];
        $this->assertIdentical($result, $expects);
    }

    /**
     * testNoAmbiguousColumn method.
     */
    public function testNoAmbiguousColumn()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->bindModel(['belongsTo' => ['Dummy' => ['className' => $modelClass, 'foreignKey' => $parentField, 'conditions' => ['Dummy.id' => null]]]], false);
        $this->Tree->initialize(2, 2);

        $data = $this->Tree->find([$modelClass.'.name' => '1. Root']);
        $this->Tree->id = $data[$modelClass]['id'];

        $direct = $this->Tree->children(null, true, ['id', 'name', $parentField, $leftField, $rightField]);
        $expects = [[$modelClass => ['id' => 2, 'name' => '1.1', $parentField => 1, $leftField => 2, $rightField => 7]],
            [$modelClass => ['id' => 5, 'name' => '1.2', $parentField => 1, $leftField => 8, $rightField => 13]], ];
        $this->assertEqual($direct, $expects);

        $total = $this->Tree->children(null, null, ['id', 'name', $parentField, $leftField, $rightField]);
        $expects = [
            [$modelClass => ['id' => 2, 'name' => '1.1', $parentField => 1, $leftField => 2, $rightField => 7]],
            [$modelClass => ['id' => 3, 'name' => '1.1.1', $parentField => 2, $leftField => 3, $rightField => 4]],
            [$modelClass => ['id' => 4, 'name' => '1.1.2', $parentField => 2, $leftField => 5, $rightField => 6]],
            [$modelClass => ['id' => 5, 'name' => '1.2', $parentField => 1, $leftField => 8, $rightField => 13]],
            [$modelClass => ['id' => 6, 'name' => '1.2.1', $parentField => 5, $leftField => 9, $rightField => 10]],
            [$modelClass => ['id' => 7, 'name' => '1.2.2', $parentField => 5, $leftField => 11, $rightField => 12]],
        ];
        $this->assertEqual($total, $expects);
    }

    /**
     * testReorderTree method.
     */
    public function testReorderTree()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(3, 3);
        $nodes = $this->Tree->find('list', ['order' => $leftField]);

        $data = $this->Tree->find([$modelClass.'.name' => '1.1'], ['id']);
        $this->Tree->moveDown($data[$modelClass]['id']);

        $data = $this->Tree->find([$modelClass.'.name' => '1.2.1'], ['id']);
        $this->Tree->moveDown($data[$modelClass]['id']);

        $data = $this->Tree->find([$modelClass.'.name' => '1.3.2.2'], ['id']);
        $this->Tree->moveDown($data[$modelClass]['id']);

        $unsortedNodes = $this->Tree->find('list', ['order' => $leftField]);
        $this->assertNotIdentical($nodes, $unsortedNodes);

        $this->Tree->reorder();
        $sortedNodes = $this->Tree->find('list', ['order' => $leftField]);
        $this->assertIdentical($nodes, $sortedNodes);
    }

    /**
     * test reordering large-ish trees with cacheQueries = true.
     * This caused infinite loops when moving down elements as stale data is returned
     * from the memory cache.
     */
    public function testReorderBigTreeWithQueryCaching()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 10);

        $original = $this->Tree->cacheQueries;
        $this->Tree->cacheQueries = true;
        $this->Tree->reorder(['field' => 'name', 'direction' => 'DESC']);
        $this->assertTrue($this->Tree->cacheQueries, 'cacheQueries was not restored after reorder(). %s');
        $this->Tree->cacheQueries = $original;
    }

    /**
     * testGenerateTreeListWithSelfJoin method.
     */
    public function testGenerateTreeListWithSelfJoin()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->bindModel(['belongsTo' => ['Dummy' => ['className' => $modelClass, 'foreignKey' => $parentField, 'conditions' => ['Dummy.id' => null]]]], false);
        $this->Tree->initialize(2, 2);

        $result = $this->Tree->generateTreeList();
        $expected = [1 => '1. Root', 2 => '_1.1', 3 => '__1.1.1', 4 => '__1.1.2', 5 => '_1.2', 6 => '__1.2.1', 7 => '__1.2.2'];
        $this->assertIdentical($result, $expected);
    }

    /**
     * testArraySyntax method.
     */
    public function testArraySyntax()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(3, 3);
        $this->assertIdentical($this->Tree->childCount(2), $this->Tree->childCount(['id' => 2]));
        $this->assertIdentical($this->Tree->getParentNode(2), $this->Tree->getParentNode(['id' => 2]));
        $this->assertIdentical($this->Tree->getPath(4), $this->Tree->getPath(['id' => 4]));
    }
}

/**
 * ScopedTreeTest class.
 */
class ScopedTreeTest extends NumberTreeTest
{
    /**
     * settings property.
     *
     * @var array
     */
    public $settings = [
        'modelClass' => 'FlagTree',
        'leftField' => 'lft',
        'rightField' => 'rght',
        'parentField' => 'parent_id',
    ];

    /**
     * fixtures property.
     *
     * @var array
     */
    public $fixtures = ['core.flag_tree', 'core.ad', 'core.campaign', 'core.translate', 'core.number_tree_two'];

    /**
     * testStringScope method.
     */
    public function testStringScope()
    {
        $this->Tree = new FlagTree();
        $this->Tree->initialize(2, 3);

        $this->Tree->id = 1;
        $this->Tree->saveField('flag', 1);
        $this->Tree->id = 2;
        $this->Tree->saveField('flag', 1);

        $result = $this->Tree->children();
        $expected = [
            ['FlagTree' => ['id' => '3', 'name' => '1.1.1', 'parent_id' => '2', 'lft' => '3', 'rght' => '4', 'flag' => '0']],
            ['FlagTree' => ['id' => '4', 'name' => '1.1.2', 'parent_id' => '2', 'lft' => '5', 'rght' => '6', 'flag' => '0']],
            ['FlagTree' => ['id' => '5', 'name' => '1.1.3', 'parent_id' => '2', 'lft' => '7', 'rght' => '8', 'flag' => '0']],
        ];
        $this->assertEqual($result, $expected);

        $this->Tree->Behaviors->attach('Tree', ['scope' => 'FlagTree.flag = 1']);
        $this->assertEqual($this->Tree->children(), []);

        $this->Tree->id = 1;
        $this->Tree->Behaviors->attach('Tree', ['scope' => 'FlagTree.flag = 1']);

        $result = $this->Tree->children();
        $expected = [['FlagTree' => ['id' => '2', 'name' => '1.1', 'parent_id' => '1', 'lft' => '2', 'rght' => '9', 'flag' => '1']]];
        $this->assertEqual($result, $expected);

        $this->assertTrue($this->Tree->delete());
        $this->assertEqual($this->Tree->find('count'), 11);
    }

    /**
     * testArrayScope method.
     */
    public function testArrayScope()
    {
        $this->Tree = new FlagTree();
        $this->Tree->initialize(2, 3);

        $this->Tree->id = 1;
        $this->Tree->saveField('flag', 1);
        $this->Tree->id = 2;
        $this->Tree->saveField('flag', 1);

        $result = $this->Tree->children();
        $expected = [
            ['FlagTree' => ['id' => '3', 'name' => '1.1.1', 'parent_id' => '2', 'lft' => '3', 'rght' => '4', 'flag' => '0']],
            ['FlagTree' => ['id' => '4', 'name' => '1.1.2', 'parent_id' => '2', 'lft' => '5', 'rght' => '6', 'flag' => '0']],
            ['FlagTree' => ['id' => '5', 'name' => '1.1.3', 'parent_id' => '2', 'lft' => '7', 'rght' => '8', 'flag' => '0']],
        ];
        $this->assertEqual($result, $expected);

        $this->Tree->Behaviors->attach('Tree', ['scope' => ['FlagTree.flag' => 1]]);
        $this->assertEqual($this->Tree->children(), []);

        $this->Tree->id = 1;
        $this->Tree->Behaviors->attach('Tree', ['scope' => ['FlagTree.flag' => 1]]);

        $result = $this->Tree->children();
        $expected = [['FlagTree' => ['id' => '2', 'name' => '1.1', 'parent_id' => '1', 'lft' => '2', 'rght' => '9', 'flag' => '1']]];
        $this->assertEqual($result, $expected);

        $this->assertTrue($this->Tree->delete());
        $this->assertEqual($this->Tree->find('count'), 11);
    }

    /**
     * testMoveUpWithScope method.
     */
    public function testMoveUpWithScope()
    {
        $this->Ad = new Ad();
        $this->Ad->Behaviors->attach('Tree', ['scope' => 'Campaign']);
        $this->Ad->moveUp(6);

        $this->Ad->id = 4;
        $result = $this->Ad->children();
        $this->assertEqual(Set::extract('/Ad/id', $result), [6, 5]);
        $this->assertEqual(Set::extract('/Campaign/id', $result), [2, 2]);
    }

    /**
     * testMoveDownWithScope method.
     */
    public function testMoveDownWithScope()
    {
        $this->Ad = new Ad();
        $this->Ad->Behaviors->attach('Tree', ['scope' => 'Campaign']);
        $this->Ad->moveDown(6);

        $this->Ad->id = 4;
        $result = $this->Ad->children();
        $this->assertEqual(Set::extract('/Ad/id', $result), [5, 6]);
        $this->assertEqual(Set::extract('/Campaign/id', $result), [2, 2]);
    }

    /**
     * Tests the interaction (non-interference) between TreeBehavior and other behaviors with respect
     * to callback hooks.
     */
    public function testTranslatingTree()
    {
        $this->Tree = new FlagTree();
        $this->Tree->cacheQueries = false;
        $this->Tree->translateModel = 'TranslateTreeTestModel';
        $this->Tree->Behaviors->attach('Translate', ['name']);

        //Save
        $this->Tree->locale = 'eng';
        $data = ['FlagTree' => [
            'name' => 'name #1',
            'locale' => 'eng',
            'parent_id' => null,
        ]];
        $this->Tree->save($data);
        $result = $this->Tree->find('all');
        $expected = [['FlagTree' => [
            'id' => 1,
            'name' => 'name #1',
            'parent_id' => null,
            'lft' => 1,
            'rght' => 2,
            'flag' => 0,
            'locale' => 'eng',
        ]]];
        $this->assertEqual($result, $expected);

        //update existing record, same locale
        $this->Tree->create();
        $data['FlagTree']['name'] = 'Named 2';
        $this->Tree->id = 1;
        $this->Tree->save($data);
        $result = $this->Tree->find('all');
        $expected = [['FlagTree' => [
            'id' => 1,
            'name' => 'Named 2',
            'parent_id' => null,
            'lft' => 1,
            'rght' => 2,
            'flag' => 0,
            'locale' => 'eng',
        ]]];
        $this->assertEqual($result, $expected);

        //update different locale, same record
        $this->Tree->create();
        $this->Tree->locale = 'deu';
        $this->Tree->id = 1;
        $data = ['FlagTree' => [
            'id' => 1,
            'parent_id' => null,
            'name' => 'namen #1',
            'locale' => 'deu',
        ]];
        $this->Tree->save($data);

        $this->Tree->locale = 'deu';
        $result = $this->Tree->find('all');
        $expected = [['FlagTree' => [
            'id' => 1,
            'name' => 'namen #1',
            'parent_id' => null,
            'lft' => 1,
            'rght' => 2,
            'flag' => 0,
            'locale' => 'deu',
        ]]];
        $this->assertEqual($result, $expected);

        //Save with bindTranslation
        $this->Tree->locale = 'eng';
        $data = [
            'name' => ['eng' => 'New title', 'spa' => 'Nuevo leyenda'],
            'parent_id' => null,
        ];
        $this->Tree->create($data);
        $this->Tree->save();

        $this->Tree->unbindTranslation();
        $translations = ['name' => 'Name'];
        $this->Tree->bindTranslation($translations, false);
        $this->Tree->locale = ['eng', 'spa'];

        $result = $this->Tree->read();
        $expected = [
            'FlagTree' => ['id' => 2, 'parent_id' => null, 'locale' => 'eng', 'name' => 'New title', 'flag' => 0, 'lft' => 3, 'rght' => 4],
            'Name' => [
            ['id' => 21, 'locale' => 'eng', 'model' => 'FlagTree', 'foreign_key' => 2, 'field' => 'name', 'content' => 'New title'],
            ['id' => 22, 'locale' => 'spa', 'model' => 'FlagTree', 'foreign_key' => 2, 'field' => 'name', 'content' => 'Nuevo leyenda'],
            ],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testGenerateTreeListWithSelfJoin method.
     */
    public function testAliasesWithScopeInTwoTreeAssociations()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $this->TreeTwo = new NumberTreeTwo();

        $record = $this->Tree->find('first');

        $this->Tree->bindModel([
            'hasMany' => [
                'SecondTree' => [
                    'className' => 'NumberTreeTwo',
                    'foreignKey' => 'number_tree_id',
                ],
            ],
        ]);
        $this->TreeTwo->bindModel([
            'belongsTo' => [
                'FirstTree' => [
                    'className' => $modelClass,
                    'foreignKey' => 'number_tree_id',
                ],
            ],
        ]);
        $this->TreeTwo->Behaviors->attach('Tree', [
            'scope' => 'FirstTree',
        ]);

        $data = [
            'NumberTreeTwo' => [
                'name' => 'First',
                'number_tree_id' => $record['FlagTree']['id'],
            ],
        ];
        $this->TreeTwo->create();
        $this->assertTrue($this->TreeTwo->save($data));

        $result = $this->TreeTwo->find('first');
        $expected = ['NumberTreeTwo' => [
            'id' => 1,
            'name' => 'First',
            'number_tree_id' => $record['FlagTree']['id'],
            'parent_id' => null,
            'lft' => 1,
            'rght' => 2,
        ]];
        $this->assertEqual($result, $expected);
    }
}

/**
 * AfterTreeTest class.
 */
class AfterTreeTest extends NumberTreeTest
{
    /**
     * settings property.
     *
     * @var array
     */
    public $settings = [
        'modelClass' => 'AfterTree',
        'leftField' => 'lft',
        'rightField' => 'rght',
        'parentField' => 'parent_id',
    ];

    /**
     * fixtures property.
     *
     * @var array
     */
    public $fixtures = ['core.after_tree'];

    /**
     * Tests the afterSave callback in the model.
     */
    public function testAftersaveCallback()
    {
        $this->Tree = new AfterTree();

        $expected = ['AfterTree' => ['name' => 'Six and One Half Changed in AfterTree::afterSave() but not in database', 'parent_id' => 6, 'lft' => 11, 'rght' => 12]];
        $result = $this->Tree->save(['AfterTree' => ['name' => 'Six and One Half', 'parent_id' => 6]]);
        $this->assertEqual($result, $expected);

        $expected = ['AfterTree' => ['name' => 'Six and One Half', 'parent_id' => 6, 'lft' => 11, 'rght' => 12, 'id' => 8]];
        $result = $this->Tree->find('all');
        $this->assertEqual($result[7], $expected);
    }
}

/**
 * UnconventionalTreeTest class.
 */
class UnconventionalTreeTest extends NumberTreeTest
{
    /**
     * settings property.
     *
     * @var array
     */
    public $settings = [
        'modelClass' => 'UnconventionalTree',
        'leftField' => 'left',
        'rightField' => 'right',
        'parentField' => 'join',
    ];

    /**
     * fixtures property.
     *
     * @var array
     */
    public $fixtures = ['core.unconventional_tree'];
}

/**
 * UuidTreeTest class.
 */
class UuidTreeTest extends NumberTreeTest
{
    /**
     * settings property.
     *
     * @var array
     */
    public $settings = [
        'modelClass' => 'UuidTree',
        'leftField' => 'lft',
        'rightField' => 'rght',
        'parentField' => 'parent_id',
    ];

    /**
     * fixtures property.
     *
     * @var array
     */
    public $fixtures = ['core.uuid_tree'];

    /**
     * testMovePromote method.
     */
    public function testMovePromote()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);
        $this->Tree->id = null;

        $parent = $this->Tree->find([$modelClass.'.name' => '1. Root']);
        $parent_id = $parent[$modelClass]['id'];

        $data = $this->Tree->find([$modelClass.'.name' => '1.1.1'], ['id']);
        $this->Tree->id = $data[$modelClass]['id'];
        $this->Tree->saveField($parentField, $parent_id);
        $direct = $this->Tree->children($parent_id, true, ['name', $leftField, $rightField]);
        $expects = [[$modelClass => ['name' => '1.1', $leftField => 2, $rightField => 5]],
            [$modelClass => ['name' => '1.2', $leftField => 6, $rightField => 11]],
            [$modelClass => ['name' => '1.1.1', $leftField => 12, $rightField => 13]], ];
        $this->assertEqual($direct, $expects);
        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testMoveWithWhitelist method.
     */
    public function testMoveWithWhitelist()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);
        $this->Tree->id = null;

        $parent = $this->Tree->find([$modelClass.'.name' => '1. Root']);
        $parent_id = $parent[$modelClass]['id'];

        $data = $this->Tree->find([$modelClass.'.name' => '1.1.1'], ['id']);
        $this->Tree->id = $data[$modelClass]['id'];
        $this->Tree->whitelist = [$parentField, 'name', 'description'];
        $this->Tree->saveField($parentField, $parent_id);

        $result = $this->Tree->children($parent_id, true, ['name', $leftField, $rightField]);
        $expected = [[$modelClass => ['name' => '1.1', $leftField => 2, $rightField => 5]],
            [$modelClass => ['name' => '1.2', $leftField => 6, $rightField => 11]],
            [$modelClass => ['name' => '1.1.1', $leftField => 12, $rightField => 13]], ];
        $this->assertEqual($result, $expected);
        $this->assertTrue($this->Tree->verify());
    }

    /**
     * testRemoveNoChildren method.
     */
    public function testRemoveNoChildren()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);
        $initialCount = $this->Tree->find('count');

        $result = $this->Tree->findByName('1.1.1');
        $this->Tree->removeFromTree($result[$modelClass]['id']);

        $laterCount = $this->Tree->find('count');
        $this->assertEqual($initialCount, $laterCount);

        $nodes = $this->Tree->find('list', ['order' => $leftField]);
        $expects = [
            '1. Root',
            '1.1',
            '1.1.2',
            '1.2',
            '1.2.1',
            '1.2.2',
            '1.1.1',
        ];

        $this->assertEqual(array_values($nodes), $expects);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testRemoveAndDeleteNoChildren method.
     */
    public function testRemoveAndDeleteNoChildren()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);
        $initialCount = $this->Tree->find('count');

        $result = $this->Tree->findByName('1.1.1');
        $this->Tree->removeFromTree($result[$modelClass]['id'], true);

        $laterCount = $this->Tree->find('count');
        $this->assertEqual($initialCount - 1, $laterCount);

        $nodes = $this->Tree->find('list', ['order' => $leftField]);
        $expects = [
            '1. Root',
            '1.1',
            '1.1.2',
            '1.2',
            '1.2.1',
            '1.2.2',
        ];
        $this->assertEqual(array_values($nodes), $expects);

        $validTree = $this->Tree->verify();
        $this->assertIdentical($validTree, true);
    }

    /**
     * testChildren method.
     */
    public function testChildren()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $data = $this->Tree->find([$modelClass.'.name' => '1. Root']);
        $this->Tree->id = $data[$modelClass]['id'];

        $direct = $this->Tree->children(null, true, ['name', $leftField, $rightField]);
        $expects = [[$modelClass => ['name' => '1.1', $leftField => 2, $rightField => 7]],
            [$modelClass => ['name' => '1.2', $leftField => 8, $rightField => 13]], ];
        $this->assertEqual($direct, $expects);

        $total = $this->Tree->children(null, null, ['name', $leftField, $rightField]);
        $expects = [[$modelClass => ['name' => '1.1', $leftField => 2, $rightField => 7]],
            [$modelClass => ['name' => '1.1.1', $leftField => 3, $rightField => 4]],
            [$modelClass => ['name' => '1.1.2', $leftField => 5, $rightField => 6]],
            [$modelClass => ['name' => '1.2', $leftField => 8, $rightField => 13]],
            [$modelClass => ['name' => '1.2.1', $leftField => 9, $rightField => 10]],
            [$modelClass => ['name' => '1.2.2', $leftField => 11, $rightField => 12]], ];
        $this->assertEqual($total, $expects);
    }

    /**
     * testNoAmbiguousColumn method.
     */
    public function testNoAmbiguousColumn()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->initialize(2, 2);

        $this->Tree->bindModel(['belongsTo' => ['Dummy' => ['className' => $modelClass, 'foreignKey' => $parentField, 'conditions' => ['Dummy.id' => null]]]], false);

        $data = $this->Tree->find([$modelClass.'.name' => '1. Root']);
        $this->Tree->id = $data[$modelClass]['id'];

        $direct = $this->Tree->children(null, true, ['name', $leftField, $rightField]);
        $expects = [[$modelClass => ['name' => '1.1', $leftField => 2, $rightField => 7]],
            [$modelClass => ['name' => '1.2', $leftField => 8, $rightField => 13]], ];
        $this->assertEqual($direct, $expects);

        $total = $this->Tree->children(null, null, ['name', $leftField, $rightField]);
        $expects = [
            [$modelClass => ['name' => '1.1', $leftField => 2, $rightField => 7]],
            [$modelClass => ['name' => '1.1.1', $leftField => 3, $rightField => 4]],
            [$modelClass => ['name' => '1.1.2', $leftField => 5, $rightField => 6]],
            [$modelClass => ['name' => '1.2', $leftField => 8, $rightField => 13]],
            [$modelClass => ['name' => '1.2.1', $leftField => 9, $rightField => 10]],
            [$modelClass => ['name' => '1.2.2', $leftField => 11, $rightField => 12]],
        ];
        $this->assertEqual($total, $expects);
    }

    /**
     * testGenerateTreeListWithSelfJoin method.
     */
    public function testGenerateTreeListWithSelfJoin()
    {
        extract($this->settings);
        $this->Tree = new $modelClass();
        $this->Tree->bindModel(['belongsTo' => ['Dummy' => ['className' => $modelClass, 'foreignKey' => $parentField, 'conditions' => ['Dummy.id' => null]]]], false);
        $this->Tree->initialize(2, 2);

        $result = $this->Tree->generateTreeList();
        $expected = ['1. Root', '_1.1', '__1.1.1', '__1.1.2', '_1.2', '__1.2.1', '__1.2.2'];
        $this->assertIdentical(array_values($result), $expected);
    }
}
