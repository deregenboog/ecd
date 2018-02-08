<?php
/**
 * SetTest file.
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
App::import('Core', 'Set');

/**
 * SetTest class.
 */
class SetTest extends CakeTestCase
{
    /**
     * testNumericKeyExtraction method.
     */
    public function testNumericKeyExtraction()
    {
        $data = ['plugin' => null, 'controller' => '', 'action' => '', 1, 'whatever'];
        $this->assertIdentical(Set::extract($data, '{n}'), [1, 'whatever']);
        $this->assertIdentical(Set::diff($data, Set::extract($data, '{n}')), ['plugin' => null, 'controller' => '', 'action' => '']);
    }

    /**
     * testEnum method.
     */
    public function testEnum()
    {
        $result = Set::enum(1, 'one, two');
        $this->assertIdentical($result, 'two');
        $result = Set::enum(2, 'one, two');
        $this->assertNull($result);

        $set = ['one', 'two'];
        $result = Set::enum(0, $set);
        $this->assertIdentical($result, 'one');
        $result = Set::enum(1, $set);
        $this->assertIdentical($result, 'two');

        $result = Set::enum(1, ['one', 'two']);
        $this->assertIdentical($result, 'two');
        $result = Set::enum(2, ['one', 'two']);
        $this->assertNull($result);

        $result = Set::enum('first', ['first' => 'one', 'second' => 'two']);
        $this->assertIdentical($result, 'one');
        $result = Set::enum('third', ['first' => 'one', 'second' => 'two']);
        $this->assertNull($result);

        $result = Set::enum('no', ['no' => 0, 'yes' => 1]);
        $this->assertIdentical($result, 0);
        $result = Set::enum('not sure', ['no' => 0, 'yes' => 1]);
        $this->assertNull($result);

        $result = Set::enum(0);
        $this->assertIdentical($result, 'no');
        $result = Set::enum(1);
        $this->assertIdentical($result, 'yes');
        $result = Set::enum(2);
        $this->assertNull($result);
    }

    /**
     * testFilter method.
     */
    public function testFilter()
    {
        $result = Set::filter(['0', false, true, 0, ['one thing', 'I can tell you', 'is you got to be', false]]);
        $expected = ['0', 2 => true, 3 => 0, 4 => ['one thing', 'I can tell you', 'is you got to be', false]];
        $this->assertIdentical($result, $expected);
    }

    /**
     * testNumericArrayCheck method.
     */
    public function testNumericArrayCheck()
    {
        $data = ['one'];
        $this->assertTrue(Set::numeric(array_keys($data)));

        $data = [1 => 'one'];
        $this->assertFalse(Set::numeric($data));

        $data = ['one'];
        $this->assertFalse(Set::numeric($data));

        $data = ['one' => 'two'];
        $this->assertFalse(Set::numeric($data));

        $data = ['one' => 1];
        $this->assertTrue(Set::numeric($data));

        $data = [0];
        $this->assertTrue(Set::numeric($data));

        $data = ['one', 'two', 'three', 'four', 'five'];
        $this->assertTrue(Set::numeric(array_keys($data)));

        $data = [1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five'];
        $this->assertTrue(Set::numeric(array_keys($data)));

        $data = ['1' => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five'];
        $this->assertTrue(Set::numeric(array_keys($data)));

        $data = ['one', 2 => 'two', 3 => 'three', 4 => 'four', 'a' => 'five'];
        $this->assertFalse(Set::numeric(array_keys($data)));
    }

    /**
     * testKeyCheck method.
     */
    public function testKeyCheck()
    {
        $data = ['Multi' => ['dimensonal' => ['array']]];
        $this->assertTrue(Set::check($data, 'Multi.dimensonal'));
        $this->assertFalse(Set::check($data, 'Multi.dimensonal.array'));

        $data = [
            [
                'Article' => ['id' => '1', 'user_id' => '1', 'title' => 'First Article', 'body' => 'First Article Body', 'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'],
                'User' => ['id' => '1', 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'],
                'Comment' => [
                    ['id' => '1', 'article_id' => '1', 'user_id' => '2', 'comment' => 'First Comment for First Article', 'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31'],
                    ['id' => '2', 'article_id' => '1', 'user_id' => '4', 'comment' => 'Second Comment for First Article', 'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31'],
                ],
                'Tag' => [
                    ['id' => '1', 'tag' => 'tag1', 'created' => '2007-03-18 12:22:23', 'updated' => '2007-03-18 12:24:31'],
                    ['id' => '2', 'tag' => 'tag2', 'created' => '2007-03-18 12:24:23', 'updated' => '2007-03-18 12:26:31'],
                ],
            ],
            [
                'Article' => ['id' => '3', 'user_id' => '1', 'title' => 'Third Article', 'body' => 'Third Article Body', 'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'],
                'User' => ['id' => '1', 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'],
                'Comment' => [],
                'Tag' => [],
            ],
        ];
        $this->assertTrue(Set::check($data, '0.Article.user_id'));
        $this->assertTrue(Set::check($data, '0.Comment.0.id'));
        $this->assertFalse(Set::check($data, '0.Comment.0.id.0'));
        $this->assertTrue(Set::check($data, '0.Article.user_id'));
        $this->assertFalse(Set::check($data, '0.Article.user_id.a'));
    }

    /**
     * testMerge method.
     */
    public function testMerge()
    {
        $r = Set::merge(['foo']);
        $this->assertIdentical($r, ['foo']);

        $r = Set::merge('foo');
        $this->assertIdentical($r, ['foo']);

        $r = Set::merge('foo', 'bar');
        $this->assertIdentical($r, ['foo', 'bar']);

        if (substr(PHP_VERSION, 0, 1) >= 5) {
            $r = eval('class StaticSetCaller{static function merge($a, $b){return Set::merge($a, $b);}} return StaticSetCaller::merge("foo", "bar");');
            $this->assertIdentical($r, ['foo', 'bar']);
        }

        $r = Set::merge('foo', ['user' => 'bob', 'no-bar'], 'bar');
        $this->assertIdentical($r, ['foo', 'user' => 'bob', 'no-bar', 'bar']);

        $a = ['foo', 'foo2'];
        $b = ['bar', 'bar2'];
        $this->assertIdentical(Set::merge($a, $b), ['foo', 'foo2', 'bar', 'bar2']);

        $a = ['foo' => 'bar', 'bar' => 'foo'];
        $b = ['foo' => 'no-bar', 'bar' => 'no-foo'];
        $this->assertIdentical(Set::merge($a, $b), ['foo' => 'no-bar', 'bar' => 'no-foo']);

        $a = ['users' => ['bob', 'jim']];
        $b = ['users' => ['lisa', 'tina']];
        $this->assertIdentical(Set::merge($a, $b), ['users' => ['bob', 'jim', 'lisa', 'tina']]);

        $a = ['users' => ['jim', 'bob']];
        $b = ['users' => 'none'];
        $this->assertIdentical(Set::merge($a, $b), ['users' => 'none']);

        $a = ['users' => ['lisa' => ['id' => 5, 'pw' => 'secret']], 'cakephp'];
        $b = ['users' => ['lisa' => ['pw' => 'new-pass', 'age' => 23]], 'ice-cream'];
        $this->assertIdentical(Set::merge($a, $b), ['users' => ['lisa' => ['id' => 5, 'pw' => 'new-pass', 'age' => 23]], 'cakephp', 'ice-cream']);

        $c = ['users' => ['lisa' => ['pw' => 'you-will-never-guess', 'age' => 25, 'pet' => 'dog']], 'chocolate'];
        $expected = ['users' => ['lisa' => ['id' => 5, 'pw' => 'you-will-never-guess', 'age' => 25, 'pet' => 'dog']], 'cakephp', 'ice-cream', 'chocolate'];
        $this->assertIdentical(Set::merge($a, $b, $c), $expected);

        $this->assertIdentical(Set::merge($a, $b, [], $c), $expected);

        $r = Set::merge($a, $b, $c);
        $this->assertIdentical($r, $expected);

        $a = ['Tree', 'CounterCache',
                'Upload' => ['folder' => 'products',
                    'fields' => ['image_1_id', 'image_2_id', 'image_3_id', 'image_4_id', 'image_5_id'], ], ];
        $b = ['Cacheable' => ['enabled' => false],
                'Limit',
                'Bindable',
                'Validator',
                'Transactional', ];

        $expected = ['Tree', 'CounterCache',
                'Upload' => ['folder' => 'products',
                    'fields' => ['image_1_id', 'image_2_id', 'image_3_id', 'image_4_id', 'image_5_id'], ],
                'Cacheable' => ['enabled' => false],
                'Limit',
                'Bindable',
                'Validator',
                'Transactional', ];

        $this->assertIdentical(Set::merge($a, $b), $expected);

        $expected = ['Tree' => null, 'CounterCache' => null,
                'Upload' => ['folder' => 'products',
                    'fields' => ['image_1_id', 'image_2_id', 'image_3_id', 'image_4_id', 'image_5_id'], ],
                'Cacheable' => ['enabled' => false],
                'Limit' => null,
                'Bindable' => null,
                'Validator' => null,
                'Transactional' => null, ];

        $this->assertIdentical(Set::normalize(Set::merge($a, $b)), $expected);
    }

    /**
     * testSort method.
     */
    public function testSort()
    {
        $a = [
            0 => ['Person' => ['name' => 'Jeff'], 'Friend' => [['name' => 'Nate']]],
            1 => ['Person' => ['name' => 'Tracy'], 'Friend' => [['name' => 'Lindsay']]],
        ];
        $b = [
            0 => ['Person' => ['name' => 'Tracy'], 'Friend' => [['name' => 'Lindsay']]],
            1 => ['Person' => ['name' => 'Jeff'], 'Friend' => [['name' => 'Nate']]],
        ];
        $a = Set::sort($a, '{n}.Friend.{n}.name', 'asc');
        $this->assertIdentical($a, $b);

        $b = [
            0 => ['Person' => ['name' => 'Jeff'], 'Friend' => [['name' => 'Nate']]],
            1 => ['Person' => ['name' => 'Tracy'], 'Friend' => [['name' => 'Lindsay']]],
        ];
        $a = [
            0 => ['Person' => ['name' => 'Tracy'], 'Friend' => [['name' => 'Lindsay']]],
            1 => ['Person' => ['name' => 'Jeff'], 'Friend' => [['name' => 'Nate']]],
        ];
        $a = Set::sort($a, '{n}.Friend.{n}.name', 'desc');
        $this->assertIdentical($a, $b);

        $a = [
            0 => ['Person' => ['name' => 'Jeff'], 'Friend' => [['name' => 'Nate']]],
            1 => ['Person' => ['name' => 'Tracy'], 'Friend' => [['name' => 'Lindsay']]],
            2 => ['Person' => ['name' => 'Adam'], 'Friend' => [['name' => 'Bob']]],
        ];
        $b = [
            0 => ['Person' => ['name' => 'Adam'], 'Friend' => [['name' => 'Bob']]],
            1 => ['Person' => ['name' => 'Jeff'], 'Friend' => [['name' => 'Nate']]],
            2 => ['Person' => ['name' => 'Tracy'], 'Friend' => [['name' => 'Lindsay']]],
        ];
        $a = Set::sort($a, '{n}.Person.name', 'asc');
        $this->assertIdentical($a, $b);

        $a = [
            [7, 6, 4],
            [3, 4, 5],
            [3, 2, 1],
        ];

        $b = [
            [3, 2, 1],
            [3, 4, 5],
            [7, 6, 4],
        ];

        $a = Set::sort($a, '{n}.{n}', 'asc');
        $this->assertIdentical($a, $b);

        $a = [
            [7, 6, 4],
            [3, 4, 5],
            [3, 2, [1, 1, 1]],
        ];

        $b = [
            [3, 2, [1, 1, 1]],
            [3, 4, 5],
            [7, 6, 4],
        ];

        $a = Set::sort($a, '{n}', 'asc');
        $this->assertIdentical($a, $b);

        $a = [
            0 => ['Person' => ['name' => 'Jeff']],
            1 => ['Shirt' => ['color' => 'black']],
        ];
        $b = [
            0 => ['Shirt' => ['color' => 'black']],
            1 => ['Person' => ['name' => 'Jeff']],
        ];
        $a = Set::sort($a, '{n}.Person.name', 'ASC');
        $this->assertIdentical($a, $b);

        $names = [
            ['employees' => [['name' => ['first' => 'John', 'last' => 'Doe']]]],
            ['employees' => [['name' => ['first' => 'Jane', 'last' => 'Doe']]]],
            ['employees' => [['name' => []]]],
            ['employees' => [['name' => []]]],
        ];
        $result = Set::sort($names, '{n}.employees.0.name', 'asc', 1);
        $expected = [
            ['employees' => [['name' => ['first' => 'John', 'last' => 'Doe']]]],
            ['employees' => [['name' => ['first' => 'Jane', 'last' => 'Doe']]]],
            ['employees' => [['name' => []]]],
            ['employees' => [['name' => []]]],
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * test sorting with out of order keys.
     */
    public function testSortWithOutOfOrderKeys()
    {
        $data = [
            9 => ['class' => 510, 'test2' => 2],
            1 => ['class' => 500, 'test2' => 1],
            2 => ['class' => 600, 'test2' => 2],
            5 => ['class' => 625, 'test2' => 4],
            0 => ['class' => 605, 'test2' => 3],
        ];
        $expected = [
            ['class' => 500, 'test2' => 1],
            ['class' => 510, 'test2' => 2],
            ['class' => 600, 'test2' => 2],
            ['class' => 605, 'test2' => 3],
            ['class' => 625, 'test2' => 4],
        ];
        $result = Set::sort($data, '{n}.class', 'asc');
        $this->assertEqual($expected, $result);

        $result = Set::sort($data, '{n}.test2', 'asc');
        $this->assertEqual($expected, $result);
    }

    /**
     * testExtract method.
     */
    public function testExtract()
    {
        $a = [
            [
                'Article' => ['id' => '1', 'user_id' => '1', 'title' => 'First Article', 'body' => 'First Article Body', 'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'],
                'User' => ['id' => '1', 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'],
                'Comment' => [
                    ['id' => '1', 'article_id' => '1', 'user_id' => '2', 'comment' => 'First Comment for First Article', 'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31'],
                    ['id' => '2', 'article_id' => '1', 'user_id' => '4', 'comment' => 'Second Comment for First Article', 'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31'],
                ],
                'Tag' => [
                    ['id' => '1', 'tag' => 'tag1', 'created' => '2007-03-18 12:22:23', 'updated' => '2007-03-18 12:24:31'],
                    ['id' => '2', 'tag' => 'tag2', 'created' => '2007-03-18 12:24:23', 'updated' => '2007-03-18 12:26:31'],
                ],
                'Deep' => [
                    'Nesting' => [
                        'test' => [
                            1 => 'foo',
                            2 => [
                                'and' => ['more' => 'stuff'],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'Article' => ['id' => '3', 'user_id' => '1', 'title' => 'Third Article', 'body' => 'Third Article Body', 'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'],
                'User' => ['id' => '2', 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'],
                'Comment' => [],
                'Tag' => [],
            ],
            [
                'Article' => ['id' => '3', 'user_id' => '1', 'title' => 'Third Article', 'body' => 'Third Article Body', 'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'],
                'User' => ['id' => '3', 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'],
                'Comment' => [],
                'Tag' => [],
            ],
            [
                'Article' => ['id' => '3', 'user_id' => '1', 'title' => 'Third Article', 'body' => 'Third Article Body', 'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'],
                'User' => ['id' => '4', 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'],
                'Comment' => [],
                'Tag' => [],
            ],
            [
                'Article' => ['id' => '3', 'user_id' => '1', 'title' => 'Third Article', 'body' => 'Third Article Body', 'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'],
                'User' => ['id' => '5', 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'],
                'Comment' => [],
                'Tag' => [],
            ],
        ];
        $b = ['Deep' => $a[0]['Deep']];
        $c = [
            ['a' => ['I' => ['a' => 1]]],
            [
                'a' => [
                    2,
                ],
            ],
            ['a' => ['II' => ['a' => 3, 'III' => ['a' => ['foo' => 4]]]]],
        ];

        $expected = [['a' => $c[2]['a']]];
        $r = Set::extract('/a/II[a=3]/..', $c);
        $this->assertEqual($r, $expected);

        $expected = [1, 2, 3, 4, 5];
        $this->assertEqual(Set::extract('/User/id', $a), $expected);

        $expected = [1, 2, 3, 4, 5];
        $this->assertEqual(Set::extract('/User/id', $a), $expected);

        $expected = [
            ['id' => 1], ['id' => 2], ['id' => 3], ['id' => 4], ['id' => 5],
        ];

        $r = Set::extract('/User/id', $a, ['flatten' => false]);
        $this->assertEqual($r, $expected);

        $expected = [['test' => $a[0]['Deep']['Nesting']['test']]];
        $this->assertEqual(Set::extract('/Deep/Nesting/test', $a), $expected);
        $this->assertEqual(Set::extract('/Deep/Nesting/test', $b), $expected);

        $expected = [['test' => $a[0]['Deep']['Nesting']['test']]];
        $r = Set::extract('/Deep/Nesting/test/1/..', $a);
        $this->assertEqual($r, $expected);

        $expected = [['test' => $a[0]['Deep']['Nesting']['test']]];
        $r = Set::extract('/Deep/Nesting/test/2/and/../..', $a);
        $this->assertEqual($r, $expected);

        $expected = [['test' => $a[0]['Deep']['Nesting']['test']]];
        $r = Set::extract('/Deep/Nesting/test/2/../../../Nesting/test/2/..', $a);
        $this->assertEqual($r, $expected);

        $expected = [2];
        $r = Set::extract('/User[2]/id', $a);
        $this->assertEqual($r, $expected);

        $expected = [4, 5];
        $r = Set::extract('/User[id>3]/id', $a);
        $this->assertEqual($r, $expected);

        $expected = [2, 3];
        $r = Set::extract('/User[id>1][id<=3]/id', $a);
        $this->assertEqual($r, $expected);

        $expected = [['I'], ['II']];
        $r = Set::extract('/a/@*', $c);
        $this->assertEqual($r, $expected);

        $single = [
            'User' => [
                'id' => 4,
                'name' => 'Neo',
            ],
        ];
        $tricky = [
            0 => [
                'User' => [
                    'id' => 1,
                    'name' => 'John',
                ],
            ],
            1 => [
                'User' => [
                    'id' => 2,
                    'name' => 'Bob',
                ],
            ],
            2 => [
                'User' => [
                    'id' => 3,
                    'name' => 'Tony',
                ],
            ],
            'User' => [
                'id' => 4,
                'name' => 'Neo',
            ],
        ];

        $expected = [1, 2, 3, 4];
        $r = Set::extract('/User/id', $tricky);
        $this->assertEqual($r, $expected);

        $expected = [4];
        $r = Set::extract('/User/id', $single);
        $this->assertEqual($r, $expected);

        $expected = [1, 3];
        $r = Set::extract('/User[name=/n/]/id', $tricky);
        $this->assertEqual($r, $expected);

        $expected = [4];
        $r = Set::extract('/User[name=/N/]/id', $tricky);
        $this->assertEqual($r, $expected);

        $expected = [1, 3, 4];
        $r = Set::extract('/User[name=/N/i]/id', $tricky);
        $this->assertEqual($r, $expected);

        $expected = [['id', 'name'], ['id', 'name'], ['id', 'name'], ['id', 'name']];
        $r = Set::extract('/User/@*', $tricky);
        $this->assertEqual($r, $expected);

        $common = [
            [
                'Article' => [
                    'id' => 1,
                    'name' => 'Article 1',
                ],
                'Comment' => [
                    [
                        'id' => 1,
                        'user_id' => 5,
                        'article_id' => 1,
                        'text' => 'Comment 1',
                    ],
                    [
                        'id' => 2,
                        'user_id' => 23,
                        'article_id' => 1,
                        'text' => 'Comment 2',
                    ],
                    [
                        'id' => 3,
                        'user_id' => 17,
                        'article_id' => 1,
                        'text' => 'Comment 3',
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 2,
                    'name' => 'Article 2',
                ],
                'Comment' => [
                    [
                        'id' => 4,
                        'user_id' => 2,
                        'article_id' => 2,
                        'text' => 'Comment 4',
                        'addition' => '',
                    ],
                    [
                        'id' => 5,
                        'user_id' => 23,
                        'article_id' => 2,
                        'text' => 'Comment 5',
                        'addition' => 'foo',
                    ],
                ],
            ],
            [
                'Article' => [
                    'id' => 3,
                    'name' => 'Article 3',
                ],
                'Comment' => [],
            ],
        ];

        $r = Set::extract('/Comment/id', $common);
        $expected = [1, 2, 3, 4, 5];
        $this->assertEqual($r, $expected);

        $expected = [1, 2, 4, 5];
        $r = Set::extract('/Comment[id!=3]/id', $common);
        $this->assertEqual($r, $expected);

        $r = Set::extract('/', $common);
        $this->assertEqual($r, $common);

        $expected = [1, 2, 4, 5];
        $r = Set::extract($common, '/Comment[id!=3]/id');
        $this->assertEqual($r, $expected);

        $expected = [$common[0]['Comment'][2]];
        $r = Set::extract($common, '/Comment/2');
        $this->assertEqual($r, $expected);

        $expected = [$common[0]['Comment'][0]];
        $r = Set::extract($common, '/Comment[1]/.[id=1]');
        $this->assertEqual($r, $expected);

        $expected = [$common[1]['Comment'][1]];
        $r = Set::extract($common, '/1/Comment/.[2]');
        $this->assertEqual($r, $expected);

        $expected = [];
        $r = Set::extract('/User/id', []);
        $this->assertEqual($r, $expected);

        $expected = [5];
        $r = Set::extract('/Comment/id[:last]', $common);
        $this->assertEqual($r, $expected);

        $expected = [1];
        $r = Set::extract('/Comment/id[:first]', $common);
        $this->assertEqual($r, $expected);

        $expected = [3];
        $r = Set::extract('/Article[:last]/id', $common);
        $this->assertEqual($r, $expected);

        $expected = [['Comment' => $common[1]['Comment'][0]]];
        $r = Set::extract('/Comment[addition=]', $common);
        $this->assertEqual($r, $expected);

        $habtm = [
            [
                'Post' => [
                    'id' => 1,
                    'title' => 'great post',
                ],
                'Comment' => [
                    [
                        'id' => 1,
                        'text' => 'foo',
                        'User' => [
                            'id' => 1,
                            'name' => 'bob',
                        ],
                    ],
                    [
                        'id' => 2,
                        'text' => 'bar',
                        'User' => [
                            'id' => 2,
                            'name' => 'tod',
                        ],
                    ],
                ],
            ],
            [
                'Post' => [
                    'id' => 2,
                    'title' => 'fun post',
                ],
                'Comment' => [
                    [
                        'id' => 3,
                        'text' => '123',
                        'User' => [
                            'id' => 3,
                            'name' => 'dan',
                        ],
                    ],
                    [
                        'id' => 4,
                        'text' => '987',
                        'User' => [
                            'id' => 4,
                            'name' => 'jim',
                        ],
                    ],
                ],
            ],
        ];

        $r = Set::extract('/Comment/User[name=/bob|dan/]/..', $habtm);
        $this->assertEqual($r[0]['Comment']['User']['name'], 'bob');
        $this->assertEqual($r[1]['Comment']['User']['name'], 'dan');
        $this->assertEqual(count($r), 2);

        $r = Set::extract('/Comment/User[name=/bob|tod/]/..', $habtm);
        $this->assertEqual($r[0]['Comment']['User']['name'], 'bob');

        $this->assertEqual($r[1]['Comment']['User']['name'], 'tod');
        $this->assertEqual(count($r), 2);

        $tree = [
            [
                'Category' => ['name' => 'Category 1'],
                'children' => [['Category' => ['name' => 'Category 1.1']]],
            ],
            [
                'Category' => ['name' => 'Category 2'],
                'children' => [
                    ['Category' => ['name' => 'Category 2.1']],
                    ['Category' => ['name' => 'Category 2.2']],
                ],
            ],
            [
                'Category' => ['name' => 'Category 3'],
                'children' => [['Category' => ['name' => 'Category 3.1']]],
            ],
        ];

        $expected = [['Category' => $tree[1]['Category']]];
        $r = Set::extract('/Category[name=Category 2]', $tree);
        $this->assertEqual($r, $expected);

        $expected = [
            ['Category' => $tree[1]['Category'], 'children' => $tree[1]['children']],
        ];
        $r = Set::extract('/Category[name=Category 2]/..', $tree);
        $this->assertEqual($r, $expected);

        $expected = [
            ['children' => $tree[1]['children'][0]],
            ['children' => $tree[1]['children'][1]],
        ];
        $r = Set::extract('/Category[name=Category 2]/../children', $tree);
        $this->assertEqual($r, $expected);

        $habtm = [
            [
                'Post' => [
                    'id' => 1,
                    'title' => 'great post',
                ],
                'Comment' => [
                    [
                        'id' => 1,
                        'text' => 'foo',
                        'User' => [
                            'id' => 1,
                            'name' => 'bob',
                        ],
                    ],
                    [
                        'id' => 2,
                        'text' => 'bar',
                        'User' => [
                            'id' => 2,
                            'name' => 'tod',
                        ],
                    ],
                ],
            ],
            [
                'Post' => [
                    'id' => 2,
                    'title' => 'fun post',
                ],
                'Comment' => [
                    [
                        'id' => 3,
                        'text' => '123',
                        'User' => [
                            'id' => 3,
                            'name' => 'dan',
                        ],
                    ],
                    [
                        'id' => 4,
                        'text' => '987',
                        'User' => [
                            'id' => 4,
                            'name' => 'jim',
                        ],
                    ],
                ],
            ],
        ];

        $r = Set::extract('/Comment/User[name=/\w+/]/..', $habtm);
        $this->assertEqual($r[0]['Comment']['User']['name'], 'bob');
        $this->assertEqual($r[1]['Comment']['User']['name'], 'tod');
        $this->assertEqual($r[2]['Comment']['User']['name'], 'dan');
        $this->assertEqual($r[3]['Comment']['User']['name'], 'dan');
        $this->assertEqual(count($r), 4);

        $r = Set::extract('/Comment/User[name=/[a-z]+/]/..', $habtm);
        $this->assertEqual($r[0]['Comment']['User']['name'], 'bob');
        $this->assertEqual($r[1]['Comment']['User']['name'], 'tod');
        $this->assertEqual($r[2]['Comment']['User']['name'], 'dan');
        $this->assertEqual($r[3]['Comment']['User']['name'], 'dan');
        $this->assertEqual(count($r), 4);

        $r = Set::extract('/Comment/User[name=/bob|dan/]/..', $habtm);
        $this->assertEqual($r[0]['Comment']['User']['name'], 'bob');
        $this->assertEqual($r[1]['Comment']['User']['name'], 'dan');
        $this->assertEqual(count($r), 2);

        $r = Set::extract('/Comment/User[name=/bob|tod/]/..', $habtm);
        $this->assertEqual($r[0]['Comment']['User']['name'], 'bob');
        $this->assertEqual($r[1]['Comment']['User']['name'], 'tod');
        $this->assertEqual(count($r), 2);

        $mixedKeys = [
            'User' => [
                0 => [
                    'id' => 4,
                    'name' => 'Neo',
                ],
                1 => [
                    'id' => 5,
                    'name' => 'Morpheus',
                ],
                'stringKey' => [],
            ],
        ];
        $expected = ['Neo', 'Morpheus'];
        $r = Set::extract('/User/name', $mixedKeys);
        $this->assertEqual($r, $expected);

        $f = [
            [
                'file' => [
                    'name' => 'zipfile.zip',
                    'type' => 'application/zip',
                    'tmp_name' => '/tmp/php178.tmp',
                    'error' => 0,
                    'size' => '564647',
                ],
            ],
            [
                'file' => [
                    'name' => 'zipfile2.zip',
                    'type' => 'application/x-zip-compressed',
                    'tmp_name' => '/tmp/php179.tmp',
                    'error' => 0,
                    'size' => '354784',
                ],
            ],
            [
                'file' => [
                    'name' => 'picture.jpg',
                    'type' => 'image/jpeg',
                    'tmp_name' => '/tmp/php180.tmp',
                    'error' => 0,
                    'size' => '21324',
                ],
            ],
        ];
        $expected = [['name' => 'zipfile2.zip', 'type' => 'application/x-zip-compressed', 'tmp_name' => '/tmp/php179.tmp', 'error' => 0, 'size' => '354784']];
        $r = Set::extract('/file/.[type=application/x-zip-compressed]', $f);
        $this->assertEqual($r, $expected);

        $expected = [['name' => 'zipfile.zip', 'type' => 'application/zip', 'tmp_name' => '/tmp/php178.tmp', 'error' => 0, 'size' => '564647']];
        $r = Set::extract('/file/.[type=application/zip]', $f);
        $this->assertEqual($r, $expected);

        $f = [
            [
                'file' => [
                    'name' => 'zipfile.zip',
                    'type' => 'application/zip',
                    'tmp_name' => '/tmp/php178.tmp',
                    'error' => 0,
                    'size' => '564647',
                ],
            ],
            [
                'file' => [
                    'name' => 'zipfile2.zip',
                    'type' => 'application/x zip compressed',
                    'tmp_name' => '/tmp/php179.tmp',
                    'error' => 0,
                    'size' => '354784',
                ],
            ],
            [
                'file' => [
                    'name' => 'picture.jpg',
                    'type' => 'image/jpeg',
                    'tmp_name' => '/tmp/php180.tmp',
                    'error' => 0,
                    'size' => '21324',
                ],
            ],
        ];
        $expected = [['name' => 'zipfile2.zip', 'type' => 'application/x zip compressed', 'tmp_name' => '/tmp/php179.tmp', 'error' => 0, 'size' => '354784']];
        $r = Set::extract('/file/.[type=application/x zip compressed]', $f);
        $this->assertEqual($r, $expected);

        $expected = [
            ['name' => 'zipfile.zip', 'type' => 'application/zip', 'tmp_name' => '/tmp/php178.tmp', 'error' => 0, 'size' => '564647'],
            ['name' => 'zipfile2.zip', 'type' => 'application/x zip compressed', 'tmp_name' => '/tmp/php179.tmp', 'error' => 0, 'size' => '354784'],
        ];
        $r = Set::extract('/file/.[tmp_name=/tmp\/php17/]', $f);
        $this->assertEqual($r, $expected);

        $hasMany = [
            'Node' => [
                'id' => 1,
                'name' => 'First',
                'state' => 50,
            ],
            'ParentNode' => [
                0 => [
                    'id' => 2,
                    'name' => 'Second',
                    'state' => 60,
                ],
            ],
        ];
        $result = Set::extract('/ParentNode/name', $hasMany);
        $expected = ['Second'];
        $this->assertEqual($result, $expected);

        $data = [
            [
                'Category' => [
                    'id' => 1,
                    'name' => 'First',
                ],
                0 => [
                    'value' => 50,
                ],
            ],
            [
                'Category' => [
                    'id' => 2,
                    'name' => 'Second',
                ],
                0 => [
                    'value' => 60,
                ],
            ],
        ];
        $expected = [
            [
                'Category' => [
                    'id' => 1,
                    'name' => 'First',
                ],
                0 => [
                    'value' => 50,
                ],
            ],
        ];
        $result = Set::extract('/Category[id=1]/..', $data);
        $this->assertEqual($result, $expected);

        $data = [
            [
                'ChildNode' => ['id' => 1],
                ['name' => 'Item 1'],
            ],
            [
                'ChildNode' => ['id' => 2],
                ['name' => 'Item 2'],
            ],
        ];

        $expected = [
            'Item 1',
            'Item 2',
        ];
        $result = Set::extract('/0/name', $data);
        $this->assertEqual($result, $expected);

        $data = [
            ['A1', 'B1'],
            ['A2', 'B2'],
        ];
        $expected = ['A1', 'A2'];
        $result = Set::extract('/0', $data);
        $this->assertEqual($result, $expected);
    }

    /**
     * test parent selectors with extract.
     */
    public function testExtractParentSelector()
    {
        $tree = [
            [
                'Category' => [
                    'name' => 'Category 1',
                ],
                'children' => [
                    [
                        'Category' => [
                            'name' => 'Category 1.1',
                        ],
                    ],
                ],
            ],
            [
                'Category' => [
                    'name' => 'Category 2',
                ],
                'children' => [
                    [
                        'Category' => [
                            'name' => 'Category 2.1',
                        ],
                    ],
                    [
                        'Category' => [
                            'name' => 'Category 2.2',
                        ],
                    ],
                ],
            ],
            [
                'Category' => [
                    'name' => 'Category 3',
                ],
                'children' => [
                    [
                        'Category' => [
                            'name' => 'Category 3.1',
                        ],
                    ],
                ],
            ],
        ];
        $expected = [['Category' => $tree[1]['Category']]];
        $r = Set::extract('/Category[name=Category 2]', $tree);
        $this->assertEqual($r, $expected);

        $expected = [['Category' => $tree[1]['Category'], 'children' => $tree[1]['children']]];
        $r = Set::extract('/Category[name=Category 2]/..', $tree);
        $this->assertEqual($r, $expected);

        $expected = [['children' => $tree[1]['children'][0]], ['children' => $tree[1]['children'][1]]];
        $r = Set::extract('/Category[name=Category 2]/../children', $tree);
        $this->assertEqual($r, $expected);

        $single = [
            [
                'CallType' => [
                    'name' => 'Internal Voice',
                ],
                'x' => [
                    'hour' => 7,
                ],
            ],
        ];

        $expected = [7];
        $r = Set::extract('/CallType[name=Internal Voice]/../x/hour', $single);
        $this->assertEqual($r, $expected);

        $multiple = [
            [
                'CallType' => [
                    'name' => 'Internal Voice',
                ],
                'x' => [
                    'hour' => 7,
                ],
            ],
            [
                'CallType' => [
                    'name' => 'Internal Voice',
                ],
                'x' => [
                    'hour' => 2,
                ],
            ],
            [
                'CallType' => [
                    'name' => 'Internal Voice',
                ],
                'x' => [
                    'hour' => 1,
                ],
            ],
        ];

        $expected = [7, 2, 1];
        $r = Set::extract('/CallType[name=Internal Voice]/../x/hour', $multiple);
        $this->assertEqual($r, $expected);

        $a = [
            'Model' => [
                '0' => [
                    'id' => 18,
                    'SubModelsModel' => [
                        'id' => 1,
                        'submodel_id' => 66,
                        'model_id' => 18,
                        'type' => 1,
                    ],
                ],
                '1' => [
                    'id' => 0,
                    'SubModelsModel' => [
                        'id' => 2,
                        'submodel_id' => 66,
                        'model_id' => 0,
                        'type' => 1,
                    ],
                ],
                '2' => [
                    'id' => 17,
                    'SubModelsModel' => [
                        'id' => 3,
                        'submodel_id' => 66,
                        'model_id' => 17,
                        'type' => 2,
                    ],
                ],
                '3' => [
                    'id' => 0,
                    'SubModelsModel' => [
                        'id' => 4,
                        'submodel_id' => 66,
                        'model_id' => 0,
                        'type' => 2,
                    ],
                ],
            ],
        ];

        $expected = [
            [
                'Model' => [
                    'id' => 17,
                    'SubModelsModel' => [
                        'id' => 3,
                        'submodel_id' => 66,
                        'model_id' => 17,
                        'type' => 2,
                    ],
                ],
            ],
            [
                'Model' => [
                    'id' => 0,
                    'SubModelsModel' => [
                        'id' => 4,
                        'submodel_id' => 66,
                        'model_id' => 0,
                        'type' => 2,
                    ],
                ],
            ],
        ];
        $r = Set::extract('/Model/SubModelsModel[type=2]/..', $a);
        $this->assertEqual($r, $expected);
    }

    /**
     * test that extract() still works when arrays don't contain a 0 index.
     */
    public function testExtractWithNonZeroArrays()
    {
        $nonZero = [
            1 => [
                'User' => [
                    'id' => 1,
                    'name' => 'John',
                ],
            ],
            2 => [
                'User' => [
                    'id' => 2,
                    'name' => 'Bob',
                ],
            ],
            3 => [
                'User' => [
                    'id' => 3,
                    'name' => 'Tony',
                ],
            ],
        ];
        $expected = [1, 2, 3];
        $r = Set::extract('/User/id', $nonZero);
        $this->assertEqual($r, $expected);

        $expected = [
            ['User' => ['id' => 1, 'name' => 'John']],
            ['User' => ['id' => 2, 'name' => 'Bob']],
            ['User' => ['id' => 3, 'name' => 'Tony']],
        ];
        $result = Set::extract('/User', $nonZero);
        $this->assertEqual($result, $expected);

        $nonSequential = [
            'User' => [
                0 => ['id' => 1],
                2 => ['id' => 2],
                6 => ['id' => 3],
                9 => ['id' => 4],
                3 => ['id' => 5],
            ],
        ];

        $nonZero = [
            'User' => [
                2 => ['id' => 1],
                4 => ['id' => 2],
                6 => ['id' => 3],
                9 => ['id' => 4],
                3 => ['id' => 5],
            ],
        ];

        $expected = [1, 2, 3, 4, 5];
        $this->assertEqual(Set::extract('/User/id', $nonSequential), $expected);

        $result = Set::extract('/User/id', $nonZero);
        $this->assertEqual($result, $expected, 'Failed non zero array key extract');

        $expected = [1, 2, 3, 4, 5];
        $this->assertEqual(Set::extract('/User/id', $nonSequential), $expected);

        $result = Set::extract('/User/id', $nonZero);
        $this->assertEqual($result, $expected, 'Failed non zero array key extract');

        $startingAtOne = [
            'Article' => [
                1 => [
                    'id' => 1,
                    'approved' => 1,
                ],
            ],
        ];

        $expected = [0 => ['Article' => ['id' => 1, 'approved' => 1]]];
        $result = Set::extract('/Article[approved=1]', $startingAtOne);
        $this->assertEqual($result, $expected);

        $items = [
            240 => [
                'A' => [
                    'field1' => 'a240',
                    'field2' => 'a240',
                ],
                'B' => [
                    'field1' => 'b240',
                    'field2' => 'b240',
                ],
            ],
        ];

        $expected = [
            0 => 'b240',
        ];

        $result = Set::extract('/B/field1', $items);
        $this->assertIdentical($result, $expected);
        $this->assertIdentical($result, Set::extract('{n}.B.field1', $items));
    }

    /**
     * testExtractWithArrays method.
     */
    public function testExtractWithArrays()
    {
        $data = [
            'Level1' => [
                'Level2' => ['test1', 'test2'],
                'Level2bis' => ['test3', 'test4'],
            ],
        ];
        $this->assertEqual(Set::extract('/Level1/Level2', $data), [['Level2' => ['test1', 'test2']]]);
        $this->assertEqual(Set::extract('/Level1/Level2bis', $data), [['Level2bis' => ['test3', 'test4']]]);
    }

    /**
     * test extract() with elements that have non-array children.
     */
    public function testExtractWithNonArrayElements()
    {
        $data = [
            'node' => [
                ['foo'],
                'bar',
            ],
        ];
        $result = Set::extract('/node', $data);
        $expected = [
            ['node' => ['foo']],
            'bar',
        ];
        $this->assertEqual($result, $expected);

        $data = [
            'node' => [
                'foo' => ['bar'],
                'bar' => ['foo'],
            ],
        ];
        $result = Set::extract('/node', $data);
        $expected = [
            ['foo' => ['bar']],
            ['bar' => ['foo']],
        ];
        $this->assertEqual($result, $expected);

        $data = [
            'node' => [
                'foo' => [
                    'bar',
                ],
                'bar' => 'foo',
            ],
        ];
        $result = Set::extract('/node', $data);
        $expected = [
            ['foo' => ['bar']],
            'foo',
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * testMatches method.
     */
    public function testMatches()
    {
        $a = [
            ['Article' => ['id' => 1, 'title' => 'Article 1']],
            ['Article' => ['id' => 2, 'title' => 'Article 2']],
            ['Article' => ['id' => 3, 'title' => 'Article 3']],
        ];

        $this->assertTrue(Set::matches(['id=2'], $a[1]['Article']));
        $this->assertFalse(Set::matches(['id>2'], $a[1]['Article']));
        $this->assertTrue(Set::matches(['id>=2'], $a[1]['Article']));
        $this->assertFalse(Set::matches(['id>=3'], $a[1]['Article']));
        $this->assertTrue(Set::matches(['id<=2'], $a[1]['Article']));
        $this->assertFalse(Set::matches(['id<2'], $a[1]['Article']));
        $this->assertTrue(Set::matches(['id>1'], $a[1]['Article']));
        $this->assertTrue(Set::matches(['id>1', 'id<3', 'id!=0'], $a[1]['Article']));

        $this->assertTrue(Set::matches(['3'], null, 3));
        $this->assertTrue(Set::matches(['5'], null, 5));

        $this->assertTrue(Set::matches(['id'], $a[1]['Article']));
        $this->assertTrue(Set::matches(['id', 'title'], $a[1]['Article']));
        $this->assertFalse(Set::matches(['non-existant'], $a[1]['Article']));

        $this->assertTrue(Set::matches('/Article[id=2]', $a));
        $this->assertFalse(Set::matches('/Article[id=4]', $a));
        $this->assertTrue(Set::matches([], $a));

        $r = [
            'Attachment' => [
                'keep' => [],
            ],
            'Comment' => [
                'keep' => [
                    'Attachment' => [
                        'fields' => [
                            0 => 'attachment',
                        ],
                    ],
                ],
            ],
            'User' => [
                'keep' => [],
            ],
            'Article' => [
                'keep' => [
                    'Comment' => [
                        'fields' => [
                            0 => 'comment',
                            1 => 'published',
                        ],
                    ],
                    'User' => [
                        'fields' => [
                            0 => 'user',
                        ],
                    ],
                ],
            ],
        ];

        $this->assertTrue(Set::matches('/Article/keep/Comment', $r));
        $this->assertEqual(Set::extract('/Article/keep/Comment/fields', $r), ['comment', 'published']);
        $this->assertEqual(Set::extract('/Article/keep/User/fields', $r), ['user']);
    }

    /**
     * testSetExtractReturnsEmptyArray method.
     */
    public function testSetExtractReturnsEmptyArray()
    {
        $this->assertIdentical(Set::extract([], '/Post/id'), []);

        $this->assertIdentical(Set::extract('/Post/id', []), []);

        $this->assertIdentical(Set::extract('/Post/id', [
            ['Post' => ['name' => 'bob']],
            ['Post' => ['name' => 'jim']],
        ]), []);

        $this->assertIdentical(Set::extract([], 'Message.flash'), null);
    }

    /**
     * testClassicExtract method.
     */
    public function testClassicExtract()
    {
        $a = [
            ['Article' => ['id' => 1, 'title' => 'Article 1']],
            ['Article' => ['id' => 2, 'title' => 'Article 2']],
            ['Article' => ['id' => 3, 'title' => 'Article 3']],
        ];

        $result = Set::extract($a, '{n}.Article.id');
        $expected = [1, 2, 3];
        $this->assertIdentical($result, $expected);

        $result = Set::extract($a, '{n}.Article.title');
        $expected = ['Article 1', 'Article 2', 'Article 3'];
        $this->assertIdentical($result, $expected);

        $result = Set::extract($a, '1.Article.title');
        $expected = 'Article 2';
        $this->assertIdentical($result, $expected);

        $result = Set::extract($a, '3.Article.title');
        $expected = null;
        $this->assertIdentical($result, $expected);

        $a = [
            [
                'Article' => ['id' => 1, 'title' => 'Article 1',
                'User' => ['id' => 1, 'username' => 'mariano.iglesias'], ],
            ],
            [
                'Article' => ['id' => 2, 'title' => 'Article 2',
                'User' => ['id' => 1, 'username' => 'mariano.iglesias'], ],
            ],
            [
                'Article' => ['id' => 3, 'title' => 'Article 3',
                'User' => ['id' => 2, 'username' => 'phpnut'], ],
            ],
        ];

        $result = Set::extract($a, '{n}.Article.User.username');
        $expected = ['mariano.iglesias', 'mariano.iglesias', 'phpnut'];
        $this->assertIdentical($result, $expected);

        $a = [
            ['Article' => ['id' => 1, 'title' => 'Article 1',
                'Comment' => [
                    ['id' => 10, 'title' => 'Comment 10'],
                    ['id' => 11, 'title' => 'Comment 11'],
                    ['id' => 12, 'title' => 'Comment 12'], ], ]],
            ['Article' => ['id' => 2, 'title' => 'Article 2',
                'Comment' => [
                    ['id' => 13, 'title' => 'Comment 13'],
                    ['id' => 14, 'title' => 'Comment 14'], ], ]],
            ['Article' => ['id' => 3, 'title' => 'Article 3']], ];

        $result = Set::extract($a, '{n}.Article.Comment.{n}.id');
        $expected = [[10, 11, 12], [13, 14], null];
        $this->assertIdentical($result, $expected);

        $result = Set::extract($a, '{n}.Article.Comment.{n}.title');
        $expected = [
            ['Comment 10', 'Comment 11', 'Comment 12'],
            ['Comment 13', 'Comment 14'],
            null,
        ];
        $this->assertIdentical($result, $expected);

        $a = [['1day' => '20 sales'], ['1day' => '2 sales']];
        $result = Set::extract($a, '{n}.1day');
        $expected = ['20 sales', '2 sales'];
        $this->assertIdentical($result, $expected);

        $a = [
            'pages' => ['name' => 'page'],
            'fruites' => ['name' => 'fruit'],
            0 => ['name' => 'zero'],
        ];
        $result = Set::extract($a, '{s}.name');
        $expected = ['page', 'fruit'];
        $this->assertIdentical($result, $expected);

        $a = [
            0 => ['pages' => ['name' => 'page']],
            1 => ['fruites' => ['name' => 'fruit']],
            'test' => [['name' => 'jippi']],
            'dot.test' => [['name' => 'jippi']],
        ];

        $result = Set::extract($a, '{n}.{s}.name');
        $expected = [0 => ['page'], 1 => ['fruit']];
        $this->assertIdentical($result, $expected);

        $result = Set::extract($a, '{s}.{n}.name');
        $expected = [['jippi'], ['jippi']];
        $this->assertIdentical($result, $expected);

        $result = Set::extract($a, '{\w+}.{\w+}.name');
        $expected = [
            ['pages' => 'page'],
            ['fruites' => 'fruit'],
            'test' => ['jippi'],
            'dot.test' => ['jippi'],
        ];
        $this->assertIdentical($result, $expected);

        $result = Set::extract($a, '{\d+}.{\w+}.name');
        $expected = [['pages' => 'page'], ['fruites' => 'fruit']];
        $this->assertIdentical($result, $expected);

        $result = Set::extract($a, '{n}.{\w+}.name');
        $expected = [['pages' => 'page'], ['fruites' => 'fruit']];
        $this->assertIdentical($result, $expected);

        $result = Set::extract($a, '{s}.{\d+}.name');
        $expected = [['jippi'], ['jippi']];
        $this->assertIdentical($result, $expected);

        $result = Set::extract($a, '{s}');
        $expected = [[['name' => 'jippi']], [['name' => 'jippi']]];
        $this->assertIdentical($result, $expected);

        $result = Set::extract($a, '{[a-z]}');
        $expected = [
            'test' => [['name' => 'jippi']],
            'dot.test' => [['name' => 'jippi']],
        ];
        $this->assertIdentical($result, $expected);

        $result = Set::extract($a, '{dot\.test}.{n}');
        $expected = ['dot.test' => [['name' => 'jippi']]];
        $this->assertIdentical($result, $expected);

        $a = new stdClass();
        $a->articles = [
            ['Article' => ['id' => 1, 'title' => 'Article 1']],
            ['Article' => ['id' => 2, 'title' => 'Article 2']],
            ['Article' => ['id' => 3, 'title' => 'Article 3']], ];

        $result = Set::extract($a, 'articles.{n}.Article.id');
        $expected = [1, 2, 3];
        $this->assertIdentical($result, $expected);

        $result = Set::extract($a, 'articles.{n}.Article.title');
        $expected = ['Article 1', 'Article 2', 'Article 3'];
        $this->assertIdentical($result, $expected);
    }

    /**
     * testInsert method.
     */
    public function testInsert()
    {
        $a = [
            'pages' => ['name' => 'page'],
        ];

        $result = Set::insert($a, 'files', ['name' => 'files']);
        $expected = [
            'pages' => ['name' => 'page'],
            'files' => ['name' => 'files'],
        ];
        $this->assertIdentical($result, $expected);

        $a = [
            'pages' => ['name' => 'page'],
        ];
        $result = Set::insert($a, 'pages.name', []);
        $expected = [
            'pages' => ['name' => []],
        ];
        $this->assertIdentical($result, $expected);

        $a = [
            'pages' => [
                0 => ['name' => 'main'],
                1 => ['name' => 'about'],
            ],
        ];

        $result = Set::insert($a, 'pages.1.vars', ['title' => 'page title']);
        $expected = [
            'pages' => [
                0 => ['name' => 'main'],
                1 => ['name' => 'about', 'vars' => ['title' => 'page title']],
            ],
        ];
        $this->assertIdentical($result, $expected);
    }

    /**
     * testRemove method.
     */
    public function testRemove()
    {
        $a = [
            'pages' => ['name' => 'page'],
            'files' => ['name' => 'files'],
        ];

        $result = Set::remove($a, 'files');
        $expected = [
            'pages' => ['name' => 'page'],
        ];
        $this->assertIdentical($result, $expected);

        $a = [
            'pages' => [
                0 => ['name' => 'main'],
                1 => ['name' => 'about', 'vars' => ['title' => 'page title']],
            ],
        ];

        $result = Set::remove($a, 'pages.1.vars');
        $expected = [
            'pages' => [
                0 => ['name' => 'main'],
                1 => ['name' => 'about'],
            ],
        ];
        $this->assertIdentical($result, $expected);

        $result = Set::remove($a, 'pages.2.vars');
        $expected = $a;
        $this->assertIdentical($result, $expected);
    }

    /**
     * testCheck method.
     */
    public function testCheck()
    {
        $set = [
            'My Index 1' => ['First' => 'The first item'],
        ];
        $this->assertTrue(Set::check($set, 'My Index 1.First'));
        $this->assertTrue(Set::check($set, 'My Index 1'));
        $this->assertTrue(Set::check($set, []));

        $set = [
            'My Index 1' => ['First' => ['Second' => ['Third' => ['Fourth' => 'Heavy. Nesting.']]]],
        ];
        $this->assertTrue(Set::check($set, 'My Index 1.First.Second'));
        $this->assertTrue(Set::check($set, 'My Index 1.First.Second.Third'));
        $this->assertTrue(Set::check($set, 'My Index 1.First.Second.Third.Fourth'));
        $this->assertFalse(Set::check($set, 'My Index 1.First.Seconds.Third.Fourth'));
    }

    /**
     * testWritingWithFunkyKeys method.
     */
    public function testWritingWithFunkyKeys()
    {
        $set = Set::insert([], 'Session Test', 'test');
        $this->assertEqual(Set::extract($set, 'Session Test'), 'test');

        $set = Set::remove($set, 'Session Test');
        $this->assertFalse(Set::check($set, 'Session Test'));

        $this->assertTrue($set = Set::insert([], 'Session Test.Test Case', 'test'));
        $this->assertTrue(Set::check($set, 'Session Test.Test Case'));
    }

    /**
     * testDiff method.
     */
    public function testDiff()
    {
        $a = [
            0 => ['name' => 'main'],
            1 => ['name' => 'about'],
        ];
        $b = [
            0 => ['name' => 'main'],
            1 => ['name' => 'about'],
            2 => ['name' => 'contact'],
        ];

        $result = Set::diff($a, $b);
        $expected = [
            2 => ['name' => 'contact'],
        ];
        $this->assertIdentical($result, $expected);

        $result = Set::diff($a, []);
        $expected = $a;
        $this->assertIdentical($result, $expected);

        $result = Set::diff([], $b);
        $expected = $b;
        $this->assertIdentical($result, $expected);

        $b = [
            0 => ['name' => 'me'],
            1 => ['name' => 'about'],
        ];

        $result = Set::diff($a, $b);
        $expected = [
            0 => ['name' => 'main'],
        ];
        $this->assertIdentical($result, $expected);

        $a = [];
        $b = ['name' => 'bob', 'address' => 'home'];
        $result = Set::diff($a, $b);
        $this->assertIdentical($result, $b);

        $a = ['name' => 'bob', 'address' => 'home'];
        $b = [];
        $result = Set::diff($a, $b);
        $this->assertIdentical($result, $a);

        $a = ['key' => true, 'another' => false, 'name' => 'me'];
        $b = ['key' => 1, 'another' => 0];
        $expected = ['name' => 'me'];
        $result = Set::diff($a, $b);
        $this->assertIdentical($result, $expected);

        $a = ['key' => 'value', 'another' => null, 'name' => 'me'];
        $b = ['key' => 'differentValue', 'another' => null];
        $expected = ['key' => 'value', 'name' => 'me'];
        $result = Set::diff($a, $b);
        $this->assertIdentical($result, $expected);

        $a = ['key' => 'value', 'another' => null, 'name' => 'me'];
        $b = ['key' => 'differentValue', 'another' => 'value'];
        $expected = ['key' => 'value', 'another' => null, 'name' => 'me'];
        $result = Set::diff($a, $b);
        $this->assertIdentical($result, $expected);

        $a = ['key' => 'value', 'another' => null, 'name' => 'me'];
        $b = ['key' => 'differentValue', 'another' => 'value'];
        $expected = ['key' => 'differentValue', 'another' => 'value', 'name' => 'me'];
        $result = Set::diff($b, $a);
        $this->assertIdentical($result, $expected);

        $a = ['key' => 'value', 'another' => null, 'name' => 'me'];
        $b = [0 => 'differentValue', 1 => 'value'];
        $expected = $a + $b;
        $result = Set::diff($a, $b);
        $this->assertIdentical($result, $expected);
    }

    /**
     * testContains method.
     */
    public function testContains()
    {
        $a = [
            0 => ['name' => 'main'],
            1 => ['name' => 'about'],
        ];
        $b = [
            0 => ['name' => 'main'],
            1 => ['name' => 'about'],
            2 => ['name' => 'contact'],
            'a' => 'b',
        ];

        $this->assertTrue(Set::contains($a, $a));
        $this->assertFalse(Set::contains($a, $b));
        $this->assertTrue(Set::contains($b, $a));
    }

    /**
     * testCombine method.
     */
    public function testCombine()
    {
        $result = Set::combine([], '{n}.User.id', '{n}.User.Data');
        $this->assertFalse($result);
        $result = Set::combine('', '{n}.User.id', '{n}.User.Data');
        $this->assertFalse($result);

        $a = [
            ['User' => ['id' => 2, 'group_id' => 1,
                'Data' => ['user' => 'mariano.iglesias', 'name' => 'Mariano Iglesias'], ]],
            ['User' => ['id' => 14, 'group_id' => 2,
                'Data' => ['user' => 'phpnut', 'name' => 'Larry E. Masters'], ]],
            ['User' => ['id' => 25, 'group_id' => 1,
                'Data' => ['user' => 'gwoo', 'name' => 'The Gwoo'], ]], ];
        $result = Set::combine($a, '{n}.User.id');
        $expected = [2 => null, 14 => null, 25 => null];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, '{n}.User.id', '{n}.User.non-existant');
        $expected = [2 => null, 14 => null, 25 => null];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, '{n}.User.id', '{n}.User.Data');
        $expected = [
            2 => ['user' => 'mariano.iglesias',	'name' => 'Mariano Iglesias'],
            14 => ['user' => 'phpnut',	'name' => 'Larry E. Masters'],
            25 => ['user' => 'gwoo',	'name' => 'The Gwoo'], ];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, '{n}.User.id', '{n}.User.Data.name');
        $expected = [
            2 => 'Mariano Iglesias',
            14 => 'Larry E. Masters',
            25 => 'The Gwoo', ];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, '{n}.User.id', '{n}.User.Data', '{n}.User.group_id');
        $expected = [
            1 => [
                2 => ['user' => 'mariano.iglesias', 'name' => 'Mariano Iglesias'],
                25 => ['user' => 'gwoo', 'name' => 'The Gwoo'], ],
            2 => [
                14 => ['user' => 'phpnut', 'name' => 'Larry E. Masters'], ], ];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, '{n}.User.id', '{n}.User.Data.name', '{n}.User.group_id');
        $expected = [
            1 => [
                2 => 'Mariano Iglesias',
                25 => 'The Gwoo', ],
            2 => [
                14 => 'Larry E. Masters', ], ];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, '{n}.User.id');
        $expected = [2 => null, 14 => null, 25 => null];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, '{n}.User.id', '{n}.User.Data');
        $expected = [
            2 => ['user' => 'mariano.iglesias', 'name' => 'Mariano Iglesias'],
            14 => ['user' => 'phpnut', 'name' => 'Larry E. Masters'],
            25 => ['user' => 'gwoo', 'name' => 'The Gwoo'], ];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, '{n}.User.id', '{n}.User.Data.name');
        $expected = [2 => 'Mariano Iglesias', 14 => 'Larry E. Masters', 25 => 'The Gwoo'];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, '{n}.User.id', '{n}.User.Data', '{n}.User.group_id');
        $expected = [
            1 => [
                2 => ['user' => 'mariano.iglesias', 'name' => 'Mariano Iglesias'],
                25 => ['user' => 'gwoo', 'name' => 'The Gwoo'], ],
            2 => [
                14 => ['user' => 'phpnut', 'name' => 'Larry E. Masters'], ], ];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, '{n}.User.id', '{n}.User.Data.name', '{n}.User.group_id');
        $expected = [
            1 => [
                2 => 'Mariano Iglesias',
                25 => 'The Gwoo', ],
            2 => [
                14 => 'Larry E. Masters', ], ];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, '{n}.User.id', ['{0}: {1}', '{n}.User.Data.user', '{n}.User.Data.name'], '{n}.User.group_id');
        $expected = [
            1 => [
                2 => 'mariano.iglesias: Mariano Iglesias',
                25 => 'gwoo: The Gwoo', ],
            2 => [14 => 'phpnut: Larry E. Masters'], ];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, ['{0}: {1}', '{n}.User.Data.user', '{n}.User.Data.name'], '{n}.User.id');
        $expected = ['mariano.iglesias: Mariano Iglesias' => 2, 'phpnut: Larry E. Masters' => 14, 'gwoo: The Gwoo' => 25];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, ['{1}: {0}', '{n}.User.Data.user', '{n}.User.Data.name'], '{n}.User.id');
        $expected = ['Mariano Iglesias: mariano.iglesias' => 2, 'Larry E. Masters: phpnut' => 14, 'The Gwoo: gwoo' => 25];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, ['%1$s: %2$d', '{n}.User.Data.user', '{n}.User.id'], '{n}.User.Data.name');
        $expected = ['mariano.iglesias: 2' => 'Mariano Iglesias', 'phpnut: 14' => 'Larry E. Masters', 'gwoo: 25' => 'The Gwoo'];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, ['%2$d: %1$s', '{n}.User.Data.user', '{n}.User.id'], '{n}.User.Data.name');
        $expected = ['2: mariano.iglesias' => 'Mariano Iglesias', '14: phpnut' => 'Larry E. Masters', '25: gwoo' => 'The Gwoo'];
        $this->assertIdentical($result, $expected);

        $b = new stdClass();
        $b->users = [
            ['User' => ['id' => 2, 'group_id' => 1,
                'Data' => ['user' => 'mariano.iglesias', 'name' => 'Mariano Iglesias'], ]],
            ['User' => ['id' => 14, 'group_id' => 2,
                'Data' => ['user' => 'phpnut', 'name' => 'Larry E. Masters'], ]],
            ['User' => ['id' => 25, 'group_id' => 1,
                'Data' => ['user' => 'gwoo', 'name' => 'The Gwoo'], ]], ];
        $result = Set::combine($b, 'users.{n}.User.id');
        $expected = [2 => null, 14 => null, 25 => null];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($b, 'users.{n}.User.id', 'users.{n}.User.non-existant');
        $expected = [2 => null, 14 => null, 25 => null];
        $this->assertIdentical($result, $expected);

        $result = Set::combine($a, 'fail', 'fail');
        $this->assertEqual($result, []);
    }

    /**
     * testMapReverse method.
     */
    public function testMapReverse()
    {
        $result = Set::reverse(null);
        $this->assertEqual($result, null);

        $result = Set::reverse(false);
        $this->assertEqual($result, false);

        $expected = [
        'Array1' => [
                'Array1Data1' => 'Array1Data1 value 1', 'Array1Data2' => 'Array1Data2 value 2', ],
        'Array2' => [
                0 => ['Array2Data1' => 1, 'Array2Data2' => 'Array2Data2 value 2', 'Array2Data3' => 'Array2Data3 value 2', 'Array2Data4' => 'Array2Data4 value 4'],
                1 => ['Array2Data1' => 2, 'Array2Data2' => 'Array2Data2 value 2', 'Array2Data3' => 'Array2Data3 value 2', 'Array2Data4' => 'Array2Data4 value 4'],
                2 => ['Array2Data1' => 3, 'Array2Data2' => 'Array2Data2 value 2', 'Array2Data3' => 'Array2Data3 value 2', 'Array2Data4' => 'Array2Data4 value 4'],
                3 => ['Array2Data1' => 4, 'Array2Data2' => 'Array2Data2 value 2', 'Array2Data3' => 'Array2Data3 value 2', 'Array2Data4' => 'Array2Data4 value 4'],
                4 => ['Array2Data1' => 5, 'Array2Data2' => 'Array2Data2 value 2', 'Array2Data3' => 'Array2Data3 value 2', 'Array2Data4' => 'Array2Data4 value 4'], ],
        'Array3' => [
                0 => ['Array3Data1' => 1, 'Array3Data2' => 'Array3Data2 value 2', 'Array3Data3' => 'Array3Data3 value 2', 'Array3Data4' => 'Array3Data4 value 4'],
                1 => ['Array3Data1' => 2, 'Array3Data2' => 'Array3Data2 value 2', 'Array3Data3' => 'Array3Data3 value 2', 'Array3Data4' => 'Array3Data4 value 4'],
                2 => ['Array3Data1' => 3, 'Array3Data2' => 'Array3Data2 value 2', 'Array3Data3' => 'Array3Data3 value 2', 'Array3Data4' => 'Array3Data4 value 4'],
                3 => ['Array3Data1' => 4, 'Array3Data2' => 'Array3Data2 value 2', 'Array3Data3' => 'Array3Data3 value 2', 'Array3Data4' => 'Array3Data4 value 4'],
                4 => ['Array3Data1' => 5, 'Array3Data2' => 'Array3Data2 value 2', 'Array3Data3' => 'Array3Data3 value 2', 'Array3Data4' => 'Array3Data4 value 4'], ], ];
        $map = Set::map($expected, true);
        $this->assertEqual($map->Array1->Array1Data1, $expected['Array1']['Array1Data1']);
        $this->assertEqual($map->Array2[0]->Array2Data1, $expected['Array2'][0]['Array2Data1']);

        $result = Set::reverse($map);
        $this->assertIdentical($result, $expected);

        $expected = [
            'Post' => ['id' => 1, 'title' => 'First Post'],
            'Comment' => [
                ['id' => 1, 'title' => 'First Comment'],
                ['id' => 2, 'title' => 'Second Comment'],
            ],
            'Tag' => [
                ['id' => 1, 'title' => 'First Tag'],
                ['id' => 2, 'title' => 'Second Tag'],
            ],
        ];
        $map = Set::map($expected);
        $this->assertIdentical($map->title, $expected['Post']['title']);
        foreach ($map->Comment as $comment) {
            $ids[] = $comment->id;
        }
        $this->assertIdentical($ids, [1, 2]);

        $expected = [
        'Array1' => [
                'Array1Data1' => 'Array1Data1 value 1', 'Array1Data2' => 'Array1Data2 value 2', 'Array1Data3' => 'Array1Data3 value 3', 'Array1Data4' => 'Array1Data4 value 4',
                'Array1Data5' => 'Array1Data5 value 5', 'Array1Data6' => 'Array1Data6 value 6', 'Array1Data7' => 'Array1Data7 value 7', 'Array1Data8' => 'Array1Data8 value 8', ],
        'string' => 1,
        'another' => 'string',
        'some' => 'thing else',
        'Array2' => [
                0 => ['Array2Data1' => 1, 'Array2Data2' => 'Array2Data2 value 2', 'Array2Data3' => 'Array2Data3 value 2', 'Array2Data4' => 'Array2Data4 value 4'],
                1 => ['Array2Data1' => 2, 'Array2Data2' => 'Array2Data2 value 2', 'Array2Data3' => 'Array2Data3 value 2', 'Array2Data4' => 'Array2Data4 value 4'],
                2 => ['Array2Data1' => 3, 'Array2Data2' => 'Array2Data2 value 2', 'Array2Data3' => 'Array2Data3 value 2', 'Array2Data4' => 'Array2Data4 value 4'],
                3 => ['Array2Data1' => 4, 'Array2Data2' => 'Array2Data2 value 2', 'Array2Data3' => 'Array2Data3 value 2', 'Array2Data4' => 'Array2Data4 value 4'],
                4 => ['Array2Data1' => 5, 'Array2Data2' => 'Array2Data2 value 2', 'Array2Data3' => 'Array2Data3 value 2', 'Array2Data4' => 'Array2Data4 value 4'], ],
        'Array3' => [
                0 => ['Array3Data1' => 1, 'Array3Data2' => 'Array3Data2 value 2', 'Array3Data3' => 'Array3Data3 value 2', 'Array3Data4' => 'Array3Data4 value 4'],
                1 => ['Array3Data1' => 2, 'Array3Data2' => 'Array3Data2 value 2', 'Array3Data3' => 'Array3Data3 value 2', 'Array3Data4' => 'Array3Data4 value 4'],
                2 => ['Array3Data1' => 3, 'Array3Data2' => 'Array3Data2 value 2', 'Array3Data3' => 'Array3Data3 value 2', 'Array3Data4' => 'Array3Data4 value 4'],
                3 => ['Array3Data1' => 4, 'Array3Data2' => 'Array3Data2 value 2', 'Array3Data3' => 'Array3Data3 value 2', 'Array3Data4' => 'Array3Data4 value 4'],
                4 => ['Array3Data1' => 5, 'Array3Data2' => 'Array3Data2 value 2', 'Array3Data3' => 'Array3Data3 value 2', 'Array3Data4' => 'Array3Data4 value 4'], ], ];
        $map = Set::map($expected, true);
        $result = Set::reverse($map);
        $this->assertIdentical($result, $expected);

        $expected = [
        'Array1' => [
                'Array1Data1' => 'Array1Data1 value 1', 'Array1Data2' => 'Array1Data2 value 2', 'Array1Data3' => 'Array1Data3 value 3', 'Array1Data4' => 'Array1Data4 value 4',
                'Array1Data5' => 'Array1Data5 value 5', 'Array1Data6' => 'Array1Data6 value 6', 'Array1Data7' => 'Array1Data7 value 7', 'Array1Data8' => 'Array1Data8 value 8', ],
        'string' => 1,
        'another' => 'string',
        'some' => 'thing else',
        'Array2' => [
                0 => ['Array2Data1' => 1, 'Array2Data2' => 'Array2Data2 value 2', 'Array2Data3' => 'Array2Data3 value 2', 'Array2Data4' => 'Array2Data4 value 4'],
                1 => ['Array2Data1' => 2, 'Array2Data2' => 'Array2Data2 value 2', 'Array2Data3' => 'Array2Data3 value 2', 'Array2Data4' => 'Array2Data4 value 4'],
                2 => ['Array2Data1' => 3, 'Array2Data2' => 'Array2Data2 value 2', 'Array2Data3' => 'Array2Data3 value 2', 'Array2Data4' => 'Array2Data4 value 4'],
                3 => ['Array2Data1' => 4, 'Array2Data2' => 'Array2Data2 value 2', 'Array2Data3' => 'Array2Data3 value 2', 'Array2Data4' => 'Array2Data4 value 4'],
                4 => ['Array2Data1' => 5, 'Array2Data2' => 'Array2Data2 value 2', 'Array2Data3' => 'Array2Data3 value 2', 'Array2Data4' => 'Array2Data4 value 4'], ],
        'string2' => 1,
        'another2' => 'string',
        'some2' => 'thing else',
        'Array3' => [
                0 => ['Array3Data1' => 1, 'Array3Data2' => 'Array3Data2 value 2', 'Array3Data3' => 'Array3Data3 value 2', 'Array3Data4' => 'Array3Data4 value 4'],
                1 => ['Array3Data1' => 2, 'Array3Data2' => 'Array3Data2 value 2', 'Array3Data3' => 'Array3Data3 value 2', 'Array3Data4' => 'Array3Data4 value 4'],
                2 => ['Array3Data1' => 3, 'Array3Data2' => 'Array3Data2 value 2', 'Array3Data3' => 'Array3Data3 value 2', 'Array3Data4' => 'Array3Data4 value 4'],
                3 => ['Array3Data1' => 4, 'Array3Data2' => 'Array3Data2 value 2', 'Array3Data3' => 'Array3Data3 value 2', 'Array3Data4' => 'Array3Data4 value 4'],
                4 => ['Array3Data1' => 5, 'Array3Data2' => 'Array3Data2 value 2', 'Array3Data3' => 'Array3Data3 value 2', 'Array3Data4' => 'Array3Data4 value 4'], ],
        'string3' => 1,
        'another3' => 'string',
        'some3' => 'thing else', ];
        $map = Set::map($expected, true);
        $result = Set::reverse($map);
        $this->assertIdentical($result, $expected);

        $expected = ['User' => ['psword' => 'whatever', 'Icon' => ['id' => 851]]];
        $map = Set::map($expected);
        $result = Set::reverse($map);
        $this->assertIdentical($result, $expected);

        $expected = ['User' => ['psword' => 'whatever', 'Icon' => ['id' => 851]]];
        $class = new stdClass();
        $class->User = new stdClass();
        $class->User->psword = 'whatever';
        $class->User->Icon = new stdClass();
        $class->User->Icon->id = 851;
        $result = Set::reverse($class);
        $this->assertIdentical($result, $expected);

        $expected = ['User' => ['psword' => 'whatever', 'Icon' => ['id' => 851], 'Profile' => ['name' => 'Some Name', 'address' => 'Some Address']]];
        $class = new stdClass();
        $class->User = new stdClass();
        $class->User->psword = 'whatever';
        $class->User->Icon = new stdClass();
        $class->User->Icon->id = 851;
        $class->User->Profile = new stdClass();
        $class->User->Profile->name = 'Some Name';
        $class->User->Profile->address = 'Some Address';

        $result = Set::reverse($class);
        $this->assertIdentical($result, $expected);

        $expected = ['User' => ['psword' => 'whatever',
                        'Icon' => ['id' => 851],
                        'Profile' => ['name' => 'Some Name', 'address' => 'Some Address'],
                        'Comment' => [
                                ['id' => 1, 'article_id' => 1, 'user_id' => 1, 'comment' => 'First Comment for First Article', 'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31'],
                                ['id' => 2, 'article_id' => 1, 'user_id' => 2, 'comment' => 'Second Comment for First Article', 'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31'], ], ]];

        $class = new stdClass();
        $class->User = new stdClass();
        $class->User->psword = 'whatever';
        $class->User->Icon = new stdClass();
        $class->User->Icon->id = 851;
        $class->User->Profile = new stdClass();
        $class->User->Profile->name = 'Some Name';
        $class->User->Profile->address = 'Some Address';
        $class->User->Comment = new stdClass();
        $class->User->Comment->{'0'} = new stdClass();
        $class->User->Comment->{'0'}->id = 1;
        $class->User->Comment->{'0'}->article_id = 1;
        $class->User->Comment->{'0'}->user_id = 1;
        $class->User->Comment->{'0'}->comment = 'First Comment for First Article';
        $class->User->Comment->{'0'}->published = 'Y';
        $class->User->Comment->{'0'}->created = '2007-03-18 10:47:23';
        $class->User->Comment->{'0'}->updated = '2007-03-18 10:49:31';
        $class->User->Comment->{'1'} = new stdClass();
        $class->User->Comment->{'1'}->id = 2;
        $class->User->Comment->{'1'}->article_id = 1;
        $class->User->Comment->{'1'}->user_id = 2;
        $class->User->Comment->{'1'}->comment = 'Second Comment for First Article';
        $class->User->Comment->{'1'}->published = 'Y';
        $class->User->Comment->{'1'}->created = '2007-03-18 10:47:23';
        $class->User->Comment->{'1'}->updated = '2007-03-18 10:49:31';

        $result = Set::reverse($class);
        $this->assertIdentical($result, $expected);

        $expected = ['User' => ['psword' => 'whatever',
                        'Icon' => ['id' => 851],
                        'Profile' => ['name' => 'Some Name', 'address' => 'Some Address'],
                        'Comment' => [
                                ['id' => 1, 'article_id' => 1, 'user_id' => 1, 'comment' => 'First Comment for First Article', 'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31'],
                                ['id' => 2, 'article_id' => 1, 'user_id' => 2, 'comment' => 'Second Comment for First Article', 'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31'], ], ]];

        $class = new stdClass();
        $class->User = new stdClass();
        $class->User->psword = 'whatever';
        $class->User->Icon = new stdClass();
        $class->User->Icon->id = 851;
        $class->User->Profile = new stdClass();
        $class->User->Profile->name = 'Some Name';
        $class->User->Profile->address = 'Some Address';
        $class->User->Comment = [];
        $comment = new stdClass();
        $comment->id = 1;
        $comment->article_id = 1;
        $comment->user_id = 1;
        $comment->comment = 'First Comment for First Article';
        $comment->published = 'Y';
        $comment->created = '2007-03-18 10:47:23';
        $comment->updated = '2007-03-18 10:49:31';
        $comment2 = new stdClass();
        $comment2->id = 2;
        $comment2->article_id = 1;
        $comment2->user_id = 2;
        $comment2->comment = 'Second Comment for First Article';
        $comment2->published = 'Y';
        $comment2->created = '2007-03-18 10:47:23';
        $comment2->updated = '2007-03-18 10:49:31';
        $class->User->Comment = [$comment, $comment2];
        $result = Set::reverse($class);
        $this->assertIdentical($result, $expected);

        $model = new Model(['id' => false, 'name' => 'Model', 'table' => false]);
        $expected = [
            'Behaviors' => ['modelName' => 'Model', '_attached' => [], '_disabled' => [], '__methods' => [], '__mappedMethods' => []],
            'useDbConfig' => 'default', 'useTable' => false, 'displayField' => null, 'id' => false, 'data' => [], 'table' => 'models', 'primaryKey' => 'id', '_schema' => null, 'validate' => [],
            'validationErrors' => [], 'tablePrefix' => null, 'name' => 'Model', 'alias' => 'Model', 'tableToModel' => [], 'logTransactions' => false, 'cacheQueries' => false,
            'belongsTo' => [], 'hasOne' => [], 'hasMany' => [], 'hasAndBelongsToMany' => [], 'actsAs' => null, 'whitelist' => [], 'cacheSources' => true,
            'findQueryType' => null, 'recursive' => 1, 'order' => null, 'virtualFields' => [],
            '__associationKeys' => [
                'belongsTo' => ['className', 'foreignKey', 'conditions', 'fields', 'order', 'counterCache'],
                'hasOne' => ['className', 'foreignKey', 'conditions', 'fields', 'order', 'dependent'],
                'hasMany' => ['className', 'foreignKey', 'conditions', 'fields', 'order', 'limit', 'offset', 'dependent', 'exclusive', 'finderQuery', 'counterQuery'],
                'hasAndBelongsToMany' => ['className', 'joinTable', 'with', 'foreignKey', 'associationForeignKey', 'conditions', 'fields', 'order', 'limit', 'offset', 'unique', 'finderQuery', 'deleteQuery', 'insertQuery'], ],
            '__associations' => ['belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany'], '__backAssociation' => [], '__insertID' => null, '__numRows' => null, '__affectedRows' => null,
                '_findMethods' => ['all' => true, 'first' => true, 'count' => true, 'neighbors' => true, 'list' => true, 'threaded' => true], ];
        $result = Set::reverse($model);

        ksort($result);
        ksort($expected);

        $this->assertIdentical($result, $expected);

        $class = new stdClass();
        $class->User = new stdClass();
        $class->User->id = 100;
        $class->someString = 'this is some string';
        $class->Profile = new stdClass();
        $class->Profile->name = 'Joe Mamma';

        $result = Set::reverse($class);
        $expected = ['User' => ['id' => '100'], 'someString' => 'this is some string', 'Profile' => ['name' => 'Joe Mamma']];
        $this->assertEqual($result, $expected);

        $class = new stdClass();
        $class->User = new stdClass();
        $class->User->id = 100;
        $class->User->_name_ = 'User';
        $class->Profile = new stdClass();
        $class->Profile->name = 'Joe Mamma';
        $class->Profile->_name_ = 'Profile';

        $result = Set::reverse($class);
        $expected = ['User' => ['id' => '100'], 'Profile' => ['name' => 'Joe Mamma']];
        $this->assertEqual($result, $expected);
    }

    /**
     * testFormatting method.
     */
    public function testFormatting()
    {
        $data = [
            ['Person' => ['first_name' => 'Nate', 'last_name' => 'Abele', 'city' => 'Boston', 'state' => 'MA', 'something' => '42']],
            ['Person' => ['first_name' => 'Larry', 'last_name' => 'Masters', 'city' => 'Boondock', 'state' => 'TN', 'something' => '{0}']],
            ['Person' => ['first_name' => 'Garrett', 'last_name' => 'Woodworth', 'city' => 'Venice Beach', 'state' => 'CA', 'something' => '{1}']], ];

        $result = Set::format($data, '{1}, {0}', ['{n}.Person.first_name', '{n}.Person.last_name']);
        $expected = ['Abele, Nate', 'Masters, Larry', 'Woodworth, Garrett'];
        $this->assertEqual($result, $expected);

        $result = Set::format($data, '{0}, {1}', ['{n}.Person.last_name', '{n}.Person.first_name']);
        $this->assertEqual($result, $expected);

        $result = Set::format($data, '{0}, {1}', ['{n}.Person.city', '{n}.Person.state']);
        $expected = ['Boston, MA', 'Boondock, TN', 'Venice Beach, CA'];
        $this->assertEqual($result, $expected);

        $result = Set::format($data, '{{0}, {1}}', ['{n}.Person.city', '{n}.Person.state']);
        $expected = ['{Boston, MA}', '{Boondock, TN}', '{Venice Beach, CA}'];
        $this->assertEqual($result, $expected);

        $result = Set::format($data, '{{0}, {1}}', ['{n}.Person.something', '{n}.Person.something']);
        $expected = ['{42, 42}', '{{0}, {0}}', '{{1}, {1}}'];
        $this->assertEqual($result, $expected);

        $result = Set::format($data, '{%2$d, %1$s}', ['{n}.Person.something', '{n}.Person.something']);
        $expected = ['{42, 42}', '{0, {0}}', '{0, {1}}'];
        $this->assertEqual($result, $expected);

        $result = Set::format($data, '{%1$s, %1$s}', ['{n}.Person.something', '{n}.Person.something']);
        $expected = ['{42, 42}', '{{0}, {0}}', '{{1}, {1}}'];
        $this->assertEqual($result, $expected);

        $result = Set::format($data, '%2$d, %1$s', ['{n}.Person.first_name', '{n}.Person.something']);
        $expected = ['42, Nate', '0, Larry', '0, Garrett'];
        $this->assertEqual($result, $expected);

        $result = Set::format($data, '%1$s, %2$d', ['{n}.Person.first_name', '{n}.Person.something']);
        $expected = ['Nate, 42', 'Larry, 0', 'Garrett, 0'];
        $this->assertEqual($result, $expected);
    }

    /**
     * testFormattingNullValues method.
     */
    public function testFormattingNullValues()
    {
        $data = [
            ['Person' => ['first_name' => 'Nate', 'last_name' => 'Abele', 'city' => 'Boston', 'state' => 'MA', 'something' => '42']],
            ['Person' => ['first_name' => 'Larry', 'last_name' => 'Masters', 'city' => 'Boondock', 'state' => 'TN', 'something' => null]],
            ['Person' => ['first_name' => 'Garrett', 'last_name' => 'Woodworth', 'city' => 'Venice Beach', 'state' => 'CA', 'something' => null]], ];

        $result = Set::format($data, '%s', ['{n}.Person.something']);
        $expected = ['42', '', ''];
        $this->assertEqual($expected, $result);

        $result = Set::format($data, '{0}, {1}', ['{n}.Person.city', '{n}.Person.something']);
        $expected = ['Boston, 42', 'Boondock, ', 'Venice Beach, '];
        $this->assertEqual($expected, $result);
    }

    /**
     * testCountDim method.
     */
    public function testCountDim()
    {
        $data = ['one', '2', 'three'];
        $result = Set::countDim($data);
        $this->assertEqual($result, 1);

        $data = ['1' => '1.1', '2', '3'];
        $result = Set::countDim($data);
        $this->assertEqual($result, 1);

        $data = ['1' => ['1.1' => '1.1.1'], '2', '3' => ['3.1' => '3.1.1']];
        $result = Set::countDim($data);
        $this->assertEqual($result, 2);

        $data = ['1' => '1.1', '2', '3' => ['3.1' => '3.1.1']];
        $result = Set::countDim($data);
        $this->assertEqual($result, 1);

        $data = ['1' => '1.1', '2', '3' => ['3.1' => '3.1.1']];
        $result = Set::countDim($data, true);
        $this->assertEqual($result, 2);

        $data = ['1' => ['1.1' => '1.1.1'], '2', '3' => ['3.1' => ['3.1.1' => '3.1.1.1']]];
        $result = Set::countDim($data);
        $this->assertEqual($result, 2);

        $data = ['1' => ['1.1' => '1.1.1'], '2', '3' => ['3.1' => ['3.1.1' => '3.1.1.1']]];
        $result = Set::countDim($data, true);
        $this->assertEqual($result, 3);

        $data = ['1' => ['1.1' => '1.1.1'], ['2' => ['2.1' => ['2.1.1' => '2.1.1.1']]], '3' => ['3.1' => ['3.1.1' => '3.1.1.1']]];
        $result = Set::countDim($data, true);
        $this->assertEqual($result, 4);

        $data = ['1' => ['1.1' => '1.1.1'], ['2' => ['2.1' => ['2.1.1' => ['2.1.1.1']]]], '3' => ['3.1' => ['3.1.1' => '3.1.1.1']]];
        $result = Set::countDim($data, true);
        $this->assertEqual($result, 5);

        $data = ['1' => ['1.1' => '1.1.1'], ['2' => ['2.1' => ['2.1.1' => ['2.1.1.1' => '2.1.1.1.1']]]], '3' => ['3.1' => ['3.1.1' => '3.1.1.1']]];
        $result = Set::countDim($data, true);
        $this->assertEqual($result, 5);

        $set = ['1' => ['1.1' => '1.1.1'], ['2' => ['2.1' => ['2.1.1' => ['2.1.1.1' => '2.1.1.1.1']]]], '3' => ['3.1' => ['3.1.1' => '3.1.1.1']]];
        $result = Set::countDim($set, false, 0);
        $this->assertEqual($result, 2);

        $result = Set::countDim($set, true);
        $this->assertEqual($result, 5);
    }

    /**
     * testMapNesting method.
     */
    public function testMapNesting()
    {
        $expected = [
            [
                'IndexedPage' => [
                    'id' => 1,
                    'url' => 'http://blah.com/',
                    'hash' => '68a9f053b19526d08e36c6a9ad150737933816a5',
                    'headers' => [
                            'Date' => 'Wed, 14 Nov 2007 15:51:42 GMT',
                            'Server' => 'Apache',
                            'Expires' => 'Thu, 19 Nov 1981 08:52:00 GMT',
                            'Cache-Control' => 'private',
                            'Pragma' => 'no-cache',
                            'Content-Type' => 'text/html; charset=UTF-8',
                            'X-Original-Transfer-Encoding' => 'chunked',
                            'Content-Length' => '50210',
                    ],
                    'meta' => [
                            'keywords' => ['testing', 'tests'],
                            'description' => 'describe me',
                    ],
                    'get_vars' => '',
                    'post_vars' => [],
                    'cookies' => ['PHPSESSID' => 'dde9896ad24595998161ffaf9e0dbe2d'],
                    'redirect' => '',
                    'created' => '1195055503',
                    'updated' => '1195055503',
                ],
            ],
            [
                'IndexedPage' => [
                    'id' => 2,
                    'url' => 'http://blah.com/',
                    'hash' => '68a9f053b19526d08e36c6a9ad150737933816a5',
                    'headers' => [
                        'Date' => 'Wed, 14 Nov 2007 15:51:42 GMT',
                        'Server' => 'Apache',
                        'Expires' => 'Thu, 19 Nov 1981 08:52:00 GMT',
                        'Cache-Control' => 'private',
                        'Pragma' => 'no-cache',
                        'Content-Type' => 'text/html; charset=UTF-8',
                        'X-Original-Transfer-Encoding' => 'chunked',
                        'Content-Length' => '50210',
                    ],
                    'meta' => [
                            'keywords' => ['testing', 'tests'],
                            'description' => 'describe me',
                    ],
                    'get_vars' => '',
                    'post_vars' => [],
                    'cookies' => ['PHPSESSID' => 'dde9896ad24595998161ffaf9e0dbe2d'],
                    'redirect' => '',
                    'created' => '1195055503',
                    'updated' => '1195055503',
                ],
            ],
        ];

        $mapped = Set::map($expected);
        $ids = [];

        foreach ($mapped as $object) {
            $ids[] = $object->id;
        }
        $this->assertEqual($ids, [1, 2]);
        $this->assertEqual(get_object_vars($mapped[0]->headers), $expected[0]['IndexedPage']['headers']);

        $result = Set::reverse($mapped);
        $this->assertIdentical($result, $expected);

        $data = [
            [
                'IndexedPage' => [
                    'id' => 1,
                    'url' => 'http://blah.com/',
                    'hash' => '68a9f053b19526d08e36c6a9ad150737933816a5',
                    'get_vars' => '',
                    'redirect' => '',
                    'created' => '1195055503',
                    'updated' => '1195055503',
                ],
            ],
            [
                'IndexedPage' => [
                    'id' => 2,
                    'url' => 'http://blah.com/',
                    'hash' => '68a9f053b19526d08e36c6a9ad150737933816a5',
                    'get_vars' => '',
                    'redirect' => '',
                    'created' => '1195055503',
                    'updated' => '1195055503',
                ],
            ],
        ];
        $mapped = Set::map($data);

        $expected = new stdClass();
        $expected->_name_ = 'IndexedPage';
        $expected->id = 2;
        $expected->url = 'http://blah.com/';
        $expected->hash = '68a9f053b19526d08e36c6a9ad150737933816a5';
        $expected->get_vars = '';
        $expected->redirect = '';
        $expected->created = '1195055503';
        $expected->updated = '1195055503';
        $this->assertIdentical($mapped[1], $expected);

        $ids = [];

        foreach ($mapped as $object) {
            $ids[] = $object->id;
        }
        $this->assertEqual($ids, [1, 2]);

        $result = Set::map(null);
        $expected = null;
        $this->assertEqual($result, $expected);
    }

    /**
     * testNestedMappedData method.
     */
    public function testNestedMappedData()
    {
        $result = Set::map([
                [
                    'Post' => ['id' => '1', 'author_id' => '1', 'title' => 'First Post', 'body' => 'First Post Body', 'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'],
                    'Author' => ['id' => '1', 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31', 'test' => 'working'],
                ], [
                    'Post' => ['id' => '2', 'author_id' => '3', 'title' => 'Second Post', 'body' => 'Second Post Body', 'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'],
                    'Author' => ['id' => '3', 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31', 'test' => 'working'],
                ],
            ]);

        $expected = new stdClass();
        $expected->_name_ = 'Post';
        $expected->id = '1';
        $expected->author_id = '1';
        $expected->title = 'First Post';
        $expected->body = 'First Post Body';
        $expected->published = 'Y';
        $expected->created = '2007-03-18 10:39:23';
        $expected->updated = '2007-03-18 10:41:31';

        $expected->Author = new stdClass();
        $expected->Author->id = '1';
        $expected->Author->user = 'mariano';
        $expected->Author->password = '5f4dcc3b5aa765d61d8327deb882cf99';
        $expected->Author->created = '2007-03-17 01:16:23';
        $expected->Author->updated = '2007-03-17 01:18:31';
        $expected->Author->test = 'working';
        $expected->Author->_name_ = 'Author';

        $expected2 = new stdClass();
        $expected2->_name_ = 'Post';
        $expected2->id = '2';
        $expected2->author_id = '3';
        $expected2->title = 'Second Post';
        $expected2->body = 'Second Post Body';
        $expected2->published = 'Y';
        $expected2->created = '2007-03-18 10:41:23';
        $expected2->updated = '2007-03-18 10:43:31';

        $expected2->Author = new stdClass();
        $expected2->Author->id = '3';
        $expected2->Author->user = 'larry';
        $expected2->Author->password = '5f4dcc3b5aa765d61d8327deb882cf99';
        $expected2->Author->created = '2007-03-17 01:20:23';
        $expected2->Author->updated = '2007-03-17 01:22:31';
        $expected2->Author->test = 'working';
        $expected2->Author->_name_ = 'Author';

        $test = [];
        $test[0] = $expected;
        $test[1] = $expected2;

        $this->assertIdentical($test, $result);

        $result = Set::map(
                [
                    'Post' => ['id' => '1', 'author_id' => '1', 'title' => 'First Post', 'body' => 'First Post Body', 'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'],
                    'Author' => ['id' => '1', 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31', 'test' => 'working'],
                ]
            );
        $expected = new stdClass();
        $expected->_name_ = 'Post';
        $expected->id = '1';
        $expected->author_id = '1';
        $expected->title = 'First Post';
        $expected->body = 'First Post Body';
        $expected->published = 'Y';
        $expected->created = '2007-03-18 10:39:23';
        $expected->updated = '2007-03-18 10:41:31';

        $expected->Author = new stdClass();
        $expected->Author->id = '1';
        $expected->Author->user = 'mariano';
        $expected->Author->password = '5f4dcc3b5aa765d61d8327deb882cf99';
        $expected->Author->created = '2007-03-17 01:16:23';
        $expected->Author->updated = '2007-03-17 01:18:31';
        $expected->Author->test = 'working';
        $expected->Author->_name_ = 'Author';
        $this->assertIdentical($expected, $result);

        //Case where extra HABTM fields come back in a result
        $data = [
            'User' => [
                'id' => 1,
                'email' => 'user@example.com',
                'first_name' => 'John',
                'last_name' => 'Smith',
            ],
            'Piece' => [
                [
                    'id' => 1,
                    'title' => 'Moonlight Sonata',
                    'composer' => 'Ludwig van Beethoven',
                    'PiecesUser' => [
                        'id' => 1,
                        'created' => '2008-01-01 00:00:00',
                        'modified' => '2008-01-01 00:00:00',
                        'piece_id' => 1,
                        'user_id' => 2,
                    ],
                ],
                [
                    'id' => 2,
                    'title' => 'Moonlight Sonata 2',
                    'composer' => 'Ludwig van Beethoven',
                    'PiecesUser' => [
                        'id' => 2,
                        'created' => '2008-01-01 00:00:00',
                        'modified' => '2008-01-01 00:00:00',
                        'piece_id' => 2,
                        'user_id' => 2,
                    ],
                ],
            ],
        ];

        $result = Set::map($data);

        $expected = new stdClass();
        $expected->_name_ = 'User';
        $expected->id = 1;
        $expected->email = 'user@example.com';
        $expected->first_name = 'John';
        $expected->last_name = 'Smith';

        $piece = new stdClass();
        $piece->id = 1;
        $piece->title = 'Moonlight Sonata';
        $piece->composer = 'Ludwig van Beethoven';

        $piece->PiecesUser = new stdClass();
        $piece->PiecesUser->id = 1;
        $piece->PiecesUser->created = '2008-01-01 00:00:00';
        $piece->PiecesUser->modified = '2008-01-01 00:00:00';
        $piece->PiecesUser->piece_id = 1;
        $piece->PiecesUser->user_id = 2;
        $piece->PiecesUser->_name_ = 'PiecesUser';

        $piece->_name_ = 'Piece';

        $piece2 = new stdClass();
        $piece2->id = 2;
        $piece2->title = 'Moonlight Sonata 2';
        $piece2->composer = 'Ludwig van Beethoven';

        $piece2->PiecesUser = new stdClass();
        $piece2->PiecesUser->id = 2;
        $piece2->PiecesUser->created = '2008-01-01 00:00:00';
        $piece2->PiecesUser->modified = '2008-01-01 00:00:00';
        $piece2->PiecesUser->piece_id = 2;
        $piece2->PiecesUser->user_id = 2;
        $piece2->PiecesUser->_name_ = 'PiecesUser';

        $piece2->_name_ = 'Piece';

        $expected->Piece = [$piece, $piece2];

        $this->assertIdentical($expected, $result);

        //Same data, but should work if _name_ has been manually defined:
        $data = [
            'User' => [
                'id' => 1,
                'email' => 'user@example.com',
                'first_name' => 'John',
                'last_name' => 'Smith',
                '_name_' => 'FooUser',
            ],
            'Piece' => [
                [
                    'id' => 1,
                    'title' => 'Moonlight Sonata',
                    'composer' => 'Ludwig van Beethoven',
                    '_name_' => 'FooPiece',
                    'PiecesUser' => [
                        'id' => 1,
                        'created' => '2008-01-01 00:00:00',
                        'modified' => '2008-01-01 00:00:00',
                        'piece_id' => 1,
                        'user_id' => 2,
                        '_name_' => 'FooPiecesUser',
                    ],
                ],
                [
                    'id' => 2,
                    'title' => 'Moonlight Sonata 2',
                    'composer' => 'Ludwig van Beethoven',
                    '_name_' => 'FooPiece',
                    'PiecesUser' => [
                        'id' => 2,
                        'created' => '2008-01-01 00:00:00',
                        'modified' => '2008-01-01 00:00:00',
                        'piece_id' => 2,
                        'user_id' => 2,
                        '_name_' => 'FooPiecesUser',
                    ],
                ],
            ],
        ];

        $result = Set::map($data);

        $expected = new stdClass();
        $expected->_name_ = 'FooUser';
        $expected->id = 1;
        $expected->email = 'user@example.com';
        $expected->first_name = 'John';
        $expected->last_name = 'Smith';

        $piece = new stdClass();
        $piece->id = 1;
        $piece->title = 'Moonlight Sonata';
        $piece->composer = 'Ludwig van Beethoven';
        $piece->_name_ = 'FooPiece';
        $piece->PiecesUser = new stdClass();
        $piece->PiecesUser->id = 1;
        $piece->PiecesUser->created = '2008-01-01 00:00:00';
        $piece->PiecesUser->modified = '2008-01-01 00:00:00';
        $piece->PiecesUser->piece_id = 1;
        $piece->PiecesUser->user_id = 2;
        $piece->PiecesUser->_name_ = 'FooPiecesUser';

        $piece2 = new stdClass();
        $piece2->id = 2;
        $piece2->title = 'Moonlight Sonata 2';
        $piece2->composer = 'Ludwig van Beethoven';
        $piece2->_name_ = 'FooPiece';
        $piece2->PiecesUser = new stdClass();
        $piece2->PiecesUser->id = 2;
        $piece2->PiecesUser->created = '2008-01-01 00:00:00';
        $piece2->PiecesUser->modified = '2008-01-01 00:00:00';
        $piece2->PiecesUser->piece_id = 2;
        $piece2->PiecesUser->user_id = 2;
        $piece2->PiecesUser->_name_ = 'FooPiecesUser';

        $expected->Piece = [$piece, $piece2];

        $this->assertIdentical($expected, $result);
    }

    /**
     * testPushDiff method.
     */
    public function testPushDiff()
    {
        $array1 = ['ModelOne' => ['id' => 1001, 'field_one' => 'a1.m1.f1', 'field_two' => 'a1.m1.f2']];
        $array2 = ['ModelTwo' => ['id' => 1002, 'field_one' => 'a2.m2.f1', 'field_two' => 'a2.m2.f2']];

        $result = Set::pushDiff($array1, $array2);

        $this->assertIdentical($result, $array1 + $array2);

        $array3 = ['ModelOne' => ['id' => 1003, 'field_one' => 'a3.m1.f1', 'field_two' => 'a3.m1.f2', 'field_three' => 'a3.m1.f3']];
        $result = Set::pushDiff($array1, $array3);

        $expected = ['ModelOne' => ['id' => 1001, 'field_one' => 'a1.m1.f1', 'field_two' => 'a1.m1.f2', 'field_three' => 'a3.m1.f3']];
        $this->assertIdentical($result, $expected);

        $array1 = [
                0 => ['ModelOne' => ['id' => 1001, 'field_one' => 's1.0.m1.f1', 'field_two' => 's1.0.m1.f2']],
                1 => ['ModelTwo' => ['id' => 1002, 'field_one' => 's1.1.m2.f2', 'field_two' => 's1.1.m2.f2']], ];
        $array2 = [
                0 => ['ModelOne' => ['id' => 1001, 'field_one' => 's2.0.m1.f1', 'field_two' => 's2.0.m1.f2']],
                1 => ['ModelTwo' => ['id' => 1002, 'field_one' => 's2.1.m2.f2', 'field_two' => 's2.1.m2.f2']], ];

        $result = Set::pushDiff($array1, $array2);
        $this->assertIdentical($result, $array1);

        $array3 = [0 => ['ModelThree' => ['id' => 1003, 'field_one' => 's3.0.m3.f1', 'field_two' => 's3.0.m3.f2']]];

        $result = Set::pushDiff($array1, $array3);
        $expected = [
                    0 => ['ModelOne' => ['id' => 1001, 'field_one' => 's1.0.m1.f1', 'field_two' => 's1.0.m1.f2'],
                        'ModelThree' => ['id' => 1003, 'field_one' => 's3.0.m3.f1', 'field_two' => 's3.0.m3.f2'], ],
                    1 => ['ModelTwo' => ['id' => 1002, 'field_one' => 's1.1.m2.f2', 'field_two' => 's1.1.m2.f2']], ];
        $this->assertIdentical($result, $expected);

        $result = Set::pushDiff($array1, null);
        $this->assertIdentical($result, $array1);

        $result = Set::pushDiff($array1, $array2);
        $this->assertIdentical($result, $array1 + $array2);
    }

    /**
     * testSetApply method.
     */
    public function testApply()
    {
        $data = [
            ['Movie' => ['id' => 1, 'title' => 'movie 3', 'rating' => 5]],
            ['Movie' => ['id' => 1, 'title' => 'movie 1', 'rating' => 1]],
            ['Movie' => ['id' => 1, 'title' => 'movie 2', 'rating' => 3]],
        ];

        $result = Set::apply('/Movie/rating', $data, 'array_sum');
        $expected = 9;
        $this->assertEqual($result, $expected);

        if (PHP5) {
            $result = Set::apply('/Movie/rating', $data, 'array_product');
            $expected = 15;
            $this->assertEqual($result, $expected);
        }

        $result = Set::apply('/Movie/title', $data, 'ucfirst', ['type' => 'map']);
        $expected = ['Movie 3', 'Movie 1', 'Movie 2'];
        $this->assertEqual($result, $expected);

        $result = Set::apply('/Movie/title', $data, 'strtoupper', ['type' => 'map']);
        $expected = ['MOVIE 3', 'MOVIE 1', 'MOVIE 2'];
        $this->assertEqual($result, $expected);

        $result = Set::apply('/Movie/rating', $data, ['SetTest', '_method'], ['type' => 'reduce']);
        $expected = 9;
        $this->assertEqual($result, $expected);

        $result = Set::apply('/Movie/rating', $data, 'strtoupper', ['type' => 'non existing type']);
        $expected = null;
        $this->assertEqual($result, $expected);
    }

    /**
     * Helper method to test Set::apply().
     */
    public function _method($val1, $val2)
    {
        $val1 += $val2;

        return $val1;
    }

    /**
     * testXmlSetReverse method.
     */
    public function testXmlSetReverse()
    {
        App::import('Core', 'Xml');

        $string = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
		<rss version="2.0">
		  <channel>
		  <title>Cake PHP Google Group</title>
		  <link>http://groups.google.com/group/cake-php</link>
		  <description>Search this group before posting anything. There are over 20,000 posts and it&amp;#39;s very likely your question was answered before. Visit the IRC channel #cakephp at irc.freenode.net for live chat with users and developers of Cake. If you post, tell us the version of Cake, PHP, and database.</description>
		  <language>en</language>
		  	<item>
			  <title>constructng result array when using findall</title>
			  <link>http://groups.google.com/group/cake-php/msg/49bc00f3bc651b4f</link>
			  <description>i&#39;m using cakephp to construct a logical data model array that will be &lt;br&gt; passed to a flex app. I have the following model association: &lt;br&gt; ServiceDay-&amp;gt;(hasMany)ServiceTi me-&amp;gt;(hasMany)ServiceTimePrice. So what &lt;br&gt; the current output from my findall is something like this example: &lt;br&gt; &lt;p&gt;Array( &lt;br&gt; [0] =&amp;gt; Array(</description>
			  <guid isPermaLink="true">http://groups.google.com/group/cake-php/msg/49bc00f3bc651b4f</guid>
			  <author>bmil...@gmail.com(bpscrugs)</author>
			  <pubDate>Fri, 28 Dec 2007 00:44:14 UT</pubDate>
			  </item>
			  <item>
			  <title>Re: share views between actions?</title>
			  <link>http://groups.google.com/group/cake-php/msg/8b350d898707dad8</link>
			  <description>Then perhaps you might do us all a favour and refrain from replying to &lt;br&gt; things you do not understand. That goes especially for asinine comments. &lt;br&gt; Indeed. &lt;br&gt; To sum up: &lt;br&gt; No comment. &lt;br&gt; In my day, a simple &amp;quot;RTFM&amp;quot; would suffice. I&#39;ll keep in mind to ignore any &lt;br&gt; further responses from you. &lt;br&gt; You (and I) were referring to the *online documentation*, not other</description>
			  <guid isPermaLink="true">http://groups.google.com/group/cake-php/msg/8b350d898707dad8</guid>
			  <author>subtropolis.z...@gmail.com(subtropolis zijn)</author>
			  <pubDate>Fri, 28 Dec 2007 00:45:01 UT</pubDate>
			 </item>
		</channel>
		</rss>';
        $xml = new Xml($string);
        $result = Set::reverse($xml);
        $expected = ['Rss' => [
            'version' => '2.0',
            'Channel' => [
                'title' => 'Cake PHP Google Group',
                'link' => 'http://groups.google.com/group/cake-php',
                'description' => 'Search this group before posting anything. There are over 20,000 posts and it&#39;s very likely your question was answered before. Visit the IRC channel #cakephp at irc.freenode.net for live chat with users and developers of Cake. If you post, tell us the version of Cake, PHP, and database.',
                'language' => 'en',
                'Item' => [
                    [
                        'title' => 'constructng result array when using findall',
                        'link' => 'http://groups.google.com/group/cake-php/msg/49bc00f3bc651b4f',
                        'description' => "i'm using cakephp to construct a logical data model array that will be <br> passed to a flex app. I have the following model association: <br> ServiceDay-&gt;(hasMany)ServiceTi me-&gt;(hasMany)ServiceTimePrice. So what <br> the current output from my findall is something like this example: <br><p>Array( <br> [0] =&gt; Array(",
                        'guid' => ['isPermaLink' => 'true', 'value' => 'http://groups.google.com/group/cake-php/msg/49bc00f3bc651b4f'],
                        'author' => 'bmil...@gmail.com(bpscrugs)',
                        'pubDate' => 'Fri, 28 Dec 2007 00:44:14 UT',
                    ],
                    [
                        'title' => 'Re: share views between actions?',
                        'link' => 'http://groups.google.com/group/cake-php/msg/8b350d898707dad8',
                        'description' => 'Then perhaps you might do us all a favour and refrain from replying to <br> things you do not understand. That goes especially for asinine comments. <br> Indeed. <br> To sum up: <br> No comment. <br> In my day, a simple &quot;RTFM&quot; would suffice. I\'ll keep in mind to ignore any <br> further responses from you. <br> You (and I) were referring to the *online documentation*, not other',
                        'guid' => ['isPermaLink' => 'true', 'value' => 'http://groups.google.com/group/cake-php/msg/8b350d898707dad8'],
                        'author' => 'subtropolis.z...@gmail.com(subtropolis zijn)',
                        'pubDate' => 'Fri, 28 Dec 2007 00:45:01 UT',
                    ],
                ],
            ],
        ]];
        $this->assertEqual($result, $expected);
        $string = '<data><post title="Title of this post" description="cool"/></data>';

        $xml = new Xml($string);
        $result = Set::reverse($xml);
        $expected = ['Data' => ['Post' => ['title' => 'Title of this post', 'description' => 'cool']]];
        $this->assertEqual($result, $expected);

        $xml = new Xml('<example><item><title>An example of a correctly reversed XMLNode</title><desc/></item></example>');
        $result = Set::reverse($xml);
        $expected = ['Example' => [
                'Item' => [
                    'title' => 'An example of a correctly reversed XMLNode',
                    'desc' => [],
                ],
            ],
        ];
        $this->assertIdentical($result, $expected);

        $xml = new Xml('<example><item attr="123"><titles><title>title1</title><title>title2</title></titles></item></example>');
        $result = Set::reverse($xml);
        $expected =
            ['Example' => [
                'Item' => [
                    'attr' => '123',
                    'Titles' => [
                        'Title' => ['title1', 'title2'],
                    ],
                ],
            ],
        ];
        $this->assertIdentical($result, $expected);

        $xml = new Xml('<example attr="ex_attr"><item attr="123"><titles>list</titles>textforitems</item></example>');
        $result = Set::reverse($xml);
        $expected =
            ['Example' => [
                'attr' => 'ex_attr',
                'Item' => [
                    'attr' => '123',
                    'titles' => 'list',
                    'value' => 'textforitems',
                ],
            ],
        ];
        $this->assertIdentical($result, $expected);

        $string = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
		<rss version="2.0">
		  <channel>
		  <title>Cake PHP Google Group</title>
		  <link>http://groups.google.com/group/cake-php</link>
		  <description>Search this group before posting anything. There are over 20,000 posts and it&amp;#39;s very likely your question was answered before. Visit the IRC channel #cakephp at irc.freenode.net for live chat with users and developers of Cake. If you post, tell us the version of Cake, PHP, and database.</description>
		  <language>en</language>
		  	<item>
			  <title>constructng result array when using findall</title>
			  <link>http://groups.google.com/group/cake-php/msg/49bc00f3bc651b4f</link>
			  <description>i&#39;m using cakephp to construct a logical data model array that will be &lt;br&gt; passed to a flex app. I have the following model association: &lt;br&gt; ServiceDay-&amp;gt;(hasMany)ServiceTi me-&amp;gt;(hasMany)ServiceTimePrice. So what &lt;br&gt; the current output from my findall is something like this example: &lt;br&gt; &lt;p&gt;Array( &lt;br&gt; [0] =&amp;gt; Array(</description>
			  	<dc:creator>cakephp</dc:creator>
				<category><![CDATA[cakephp]]></category>
				<category><![CDATA[model]]></category>
			  <guid isPermaLink="true">http://groups.google.com/group/cake-php/msg/49bc00f3bc651b4f</guid>
			  <author>bmil...@gmail.com(bpscrugs)</author>
			  <pubDate>Fri, 28 Dec 2007 00:44:14 UT</pubDate>
			  </item>
			  <item>
			  <title>Re: share views between actions?</title>
			  <link>http://groups.google.com/group/cake-php/msg/8b350d898707dad8</link>
			  <description>Then perhaps you might do us all a favour and refrain from replying to &lt;br&gt; things you do not understand. That goes especially for asinine comments. &lt;br&gt; Indeed. &lt;br&gt; To sum up: &lt;br&gt; No comment. &lt;br&gt; In my day, a simple &amp;quot;RTFM&amp;quot; would suffice. I&#39;ll keep in mind to ignore any &lt;br&gt; further responses from you. &lt;br&gt; You (and I) were referring to the *online documentation*, not other</description>
			  	<dc:creator>cakephp</dc:creator>
				<category><![CDATA[cakephp]]></category>
				<category><![CDATA[model]]></category>
			  <guid isPermaLink="true">http://groups.google.com/group/cake-php/msg/8b350d898707dad8</guid>
			  <author>subtropolis.z...@gmail.com(subtropolis zijn)</author>
			  <pubDate>Fri, 28 Dec 2007 00:45:01 UT</pubDate>
			 </item>
		</channel>
		</rss>';

        $xml = new Xml($string);
        $result = Set::reverse($xml);

        $expected = ['Rss' => [
            'version' => '2.0',
            'Channel' => [
                'title' => 'Cake PHP Google Group',
                'link' => 'http://groups.google.com/group/cake-php',
                'description' => 'Search this group before posting anything. There are over 20,000 posts and it&#39;s very likely your question was answered before. Visit the IRC channel #cakephp at irc.freenode.net for live chat with users and developers of Cake. If you post, tell us the version of Cake, PHP, and database.',
                'language' => 'en',
                'Item' => [
                    [
                        'title' => 'constructng result array when using findall',
                        'link' => 'http://groups.google.com/group/cake-php/msg/49bc00f3bc651b4f',
                        'description' => "i'm using cakephp to construct a logical data model array that will be <br> passed to a flex app. I have the following model association: <br> ServiceDay-&gt;(hasMany)ServiceTi me-&gt;(hasMany)ServiceTimePrice. So what <br> the current output from my findall is something like this example: <br><p>Array( <br> [0] =&gt; Array(",
                        'creator' => 'cakephp',
                        'Category' => ['cakephp', 'model'],
                        'guid' => ['isPermaLink' => 'true', 'value' => 'http://groups.google.com/group/cake-php/msg/49bc00f3bc651b4f'],
                        'author' => 'bmil...@gmail.com(bpscrugs)',
                        'pubDate' => 'Fri, 28 Dec 2007 00:44:14 UT',
                    ],
                    [
                        'title' => 'Re: share views between actions?',
                        'link' => 'http://groups.google.com/group/cake-php/msg/8b350d898707dad8',
                        'description' => 'Then perhaps you might do us all a favour and refrain from replying to <br> things you do not understand. That goes especially for asinine comments. <br> Indeed. <br> To sum up: <br> No comment. <br> In my day, a simple &quot;RTFM&quot; would suffice. I\'ll keep in mind to ignore any <br> further responses from you. <br> You (and I) were referring to the *online documentation*, not other',
                        'creator' => 'cakephp',
                        'Category' => ['cakephp', 'model'],
                        'guid' => ['isPermaLink' => 'true', 'value' => 'http://groups.google.com/group/cake-php/msg/8b350d898707dad8'],
                        'author' => 'subtropolis.z...@gmail.com(subtropolis zijn)',
                        'pubDate' => 'Fri, 28 Dec 2007 00:45:01 UT',
                    ],
                ],
            ],
        ]];
        $this->assertEqual($result, $expected);

        $text = '<?xml version="1.0" encoding="UTF-8"?>
		<XRDS xmlns="xri://$xrds">
		<XRD xml:id="oauth" xmlns="xri://$XRD*($v*2.0)" version="2.0">
			<Type>xri://$xrds*simple</Type>
			<Expires>2008-04-13T07:34:58Z</Expires>
			<Service>
				<Type>http://oauth.net/core/1.0/endpoint/authorize</Type>
				<Type>http://oauth.net/core/1.0/parameters/auth-header</Type>
				<Type>http://oauth.net/core/1.0/parameters/uri-query</Type>
				<URI priority="10">https://ma.gnolia.com/oauth/authorize</URI>
				<URI priority="20">http://ma.gnolia.com/oauth/authorize</URI>
			</Service>
		</XRD>
		<XRD xmlns="xri://$XRD*($v*2.0)" version="2.0">
			<Type>xri://$xrds*simple</Type>
				<Service priority="10">
					<Type>http://oauth.net/discovery/1.0</Type>
					<URI>#oauth</URI>
				</Service>
		</XRD>
		</XRDS>';

        $xml = new Xml($text);
        $result = Set::reverse($xml);

        $expected = ['XRDS' => [
            'xmlns' => 'xri://$xrds',
            'XRD' => [
                [
                    'xml:id' => 'oauth',
                    'xmlns' => 'xri://$XRD*($v*2.0)',
                    'version' => '2.0',
                    'Type' => 'xri://$xrds*simple',
                    'Expires' => '2008-04-13T07:34:58Z',
                    'Service' => [
                        'Type' => [
                            'http://oauth.net/core/1.0/endpoint/authorize',
                            'http://oauth.net/core/1.0/parameters/auth-header',
                            'http://oauth.net/core/1.0/parameters/uri-query',
                        ],
                        'URI' => [
                            [
                                'value' => 'https://ma.gnolia.com/oauth/authorize',
                                'priority' => '10',
                            ],
                            [
                                'value' => 'http://ma.gnolia.com/oauth/authorize',
                                'priority' => '20',
                            ],
                        ],
                    ],
                ],
                [
                    'xmlns' => 'xri://$XRD*($v*2.0)',
                    'version' => '2.0',
                    'Type' => 'xri://$xrds*simple',
                    'Service' => [
                        'priority' => '10',
                        'Type' => 'http://oauth.net/discovery/1.0',
                        'URI' => '#oauth',
                    ],
                ],
            ],
        ]];
        $this->assertEqual($result, $expected);
    }

    /**
     * testStrictKeyCheck method.
     */
    public function testStrictKeyCheck()
    {
        $set = ['a' => 'hi'];
        $this->assertFalse(Set::check($set, 'a.b'));
    }

    /**
     * Tests Set::flatten.
     */
    public function testFlatten()
    {
        $data = ['Larry', 'Curly', 'Moe'];
        $result = Set::flatten($data);
        $this->assertEqual($result, $data);

        $data[9] = 'Shemp';
        $result = Set::flatten($data);
        $this->assertEqual($result, $data);

        $data = [
            [
                'Post' => ['id' => '1', 'author_id' => '1', 'title' => 'First Post'],
                'Author' => ['id' => '1', 'user' => 'nate', 'password' => 'foo'],
            ],
            [
                'Post' => ['id' => '2', 'author_id' => '3', 'title' => 'Second Post', 'body' => 'Second Post Body'],
                'Author' => ['id' => '3', 'user' => 'larry', 'password' => null],
            ],
        ];

        $result = Set::flatten($data);
        $expected = [
            '0.Post.id' => '1', '0.Post.author_id' => '1', '0.Post.title' => 'First Post', '0.Author.id' => '1',
            '0.Author.user' => 'nate', '0.Author.password' => 'foo', '1.Post.id' => '2', '1.Post.author_id' => '3',
            '1.Post.title' => 'Second Post', '1.Post.body' => 'Second Post Body', '1.Author.id' => '3',
            '1.Author.user' => 'larry', '1.Author.password' => null,
        ];
        $this->assertEqual($result, $expected);
    }

    /**
     * test normalization.
     */
    public function testNormalizeStrings()
    {
        $result = Set::normalize('one,two,three');
        $expected = ['one' => null, 'two' => null, 'three' => null];
        $this->assertEqual($expected, $result);

        $result = Set::normalize('one two three', true, ' ');
        $expected = ['one' => null, 'two' => null, 'three' => null];
        $this->assertEqual($expected, $result);

        $result = Set::normalize('one  ,  two   ,  three   ', true, ',', true);
        $expected = ['one' => null, 'two' => null, 'three' => null];
        $this->assertEqual($expected, $result);
    }

    /**
     * test normalizing arrays.
     */
    public function testNormalizeArrays()
    {
        $result = Set::normalize(['one', 'two', 'three']);
        $expected = ['one' => null, 'two' => null, 'three' => null];
        $this->assertEqual($expected, $result);

        $result = Set::normalize(['one', 'two', 'three'], false);
        $expected = ['one', 'two', 'three'];
        $this->assertEqual($expected, $result);

        $result = Set::normalize(['one' => 1, 'two' => 2, 'three' => 3, 'four'], false);
        $expected = ['one' => 1, 'two' => 2, 'three' => 3, 'four' => null];
        $this->assertEqual($expected, $result);

        $result = Set::normalize(['one' => 1, 'two' => 2, 'three' => 3, 'four']);
        $expected = ['one' => 1, 'two' => 2, 'three' => 3, 'four' => null];
        $this->assertEqual($expected, $result);

        $result = Set::normalize(['one' => ['a', 'b', 'c' => 'cee'], 'two' => 2, 'three']);
        $expected = ['one' => ['a', 'b', 'c' => 'cee'], 'two' => 2, 'three' => null];
        $this->assertEqual($expected, $result);
    }
}
