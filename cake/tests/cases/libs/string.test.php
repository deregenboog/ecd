<?php
/**
 * StringTest file.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html CakePHP(tm) Tests
 * @since         CakePHP(tm) v 1.2.0.5432
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Core', 'Str');

/**
 * StringTest class.
 */
class StringTest extends CakeTestCase
{
    /**
     * testUuidGeneration method.
     */
    public function testUuidGeneration()
    {
        $result = Str::uuid();
        $pattern = '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/';
        $match = preg_match($pattern, $result);
        $this->assertTrue($match);
    }

    /**
     * testMultipleUuidGeneration method.
     */
    public function testMultipleUuidGeneration()
    {
        $check = [];
        $count = mt_rand(10, 1000);
        $pattern = '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/';

        for ($i = 0; $i < $count; ++$i) {
            $result = Str::uuid();
            $match = preg_match($pattern, $result);
            $this->assertTrue($match);
            $this->assertFalse(in_array($result, $check));
            $check[] = $result;
        }
    }

    /**
     * testInsert method.
     */
    public function testInsert()
    {
        $string = 'some string';
        $expected = 'some string';
        $result = Str::insert($string, []);
        $this->assertEqual($result, $expected);

        $string = '2 + 2 = :sum. Cake is :adjective.';
        $expected = '2 + 2 = 4. Cake is yummy.';
        $result = Str::insert($string, ['sum' => '4', 'adjective' => 'yummy']);
        $this->assertEqual($result, $expected);

        $string = '2 + 2 = %sum. Cake is %adjective.';
        $result = Str::insert($string, ['sum' => '4', 'adjective' => 'yummy'], ['before' => '%']);
        $this->assertEqual($result, $expected);

        $string = '2 + 2 = 2sum2. Cake is 9adjective9.';
        $result = Str::insert($string, ['sum' => '4', 'adjective' => 'yummy'], ['format' => '/([\d])%s\\1/']);
        $this->assertEqual($result, $expected);

        $string = '2 + 2 = 12sum21. Cake is 23adjective45.';
        $expected = '2 + 2 = 4. Cake is 23adjective45.';
        $result = Str::insert($string, ['sum' => '4', 'adjective' => 'yummy'], ['format' => '/([\d])([\d])%s\\2\\1/']);
        $this->assertEqual($result, $expected);

        $string = ':web :web_site';
        $expected = 'www http';
        $result = Str::insert($string, ['web' => 'www', 'web_site' => 'http']);
        $this->assertEqual($result, $expected);

        $string = '2 + 2 = <sum. Cake is <adjective>.';
        $expected = '2 + 2 = <sum. Cake is yummy.';
        $result = Str::insert($string, ['sum' => '4', 'adjective' => 'yummy'], ['before' => '<', 'after' => '>']);
        $this->assertEqual($result, $expected);

        $string = '2 + 2 = \:sum. Cake is :adjective.';
        $expected = '2 + 2 = :sum. Cake is yummy.';
        $result = Str::insert($string, ['sum' => '4', 'adjective' => 'yummy']);
        $this->assertEqual($result, $expected);

        $string = '2 + 2 = !:sum. Cake is :adjective.';
        $result = Str::insert($string, ['sum' => '4', 'adjective' => 'yummy'], ['escape' => '!']);
        $this->assertEqual($result, $expected);

        $string = '2 + 2 = \%sum. Cake is %adjective.';
        $expected = '2 + 2 = %sum. Cake is yummy.';
        $result = Str::insert($string, ['sum' => '4', 'adjective' => 'yummy'], ['before' => '%']);
        $this->assertEqual($result, $expected);

        $string = ':a :b \:a :a';
        $expected = '1 2 :a 1';
        $result = Str::insert($string, ['a' => 1, 'b' => 2]);
        $this->assertEqual($result, $expected);

        $string = ':a :b :c';
        $expected = '2 3';
        $result = Str::insert($string, ['b' => 2, 'c' => 3], ['clean' => true]);
        $this->assertEqual($result, $expected);

        $string = ':a :b :c';
        $expected = '1 3';
        $result = Str::insert($string, ['a' => 1, 'c' => 3], ['clean' => true]);
        $this->assertEqual($result, $expected);

        $string = ':a :b :c';
        $expected = '2 3';
        $result = Str::insert($string, ['b' => 2, 'c' => 3], ['clean' => true]);
        $this->assertEqual($result, $expected);

        $string = ':a, :b and :c';
        $expected = '2 and 3';
        $result = Str::insert($string, ['b' => 2, 'c' => 3], ['clean' => true]);
        $this->assertEqual($result, $expected);

        $string = '":a, :b and :c"';
        $expected = '"1, 2"';
        $result = Str::insert($string, ['a' => 1, 'b' => 2], ['clean' => true]);
        $this->assertEqual($result, $expected);

        $string = '"${a}, ${b} and ${c}"';
        $expected = '"1, 2"';
        $result = Str::insert($string, ['a' => 1, 'b' => 2], ['before' => '${', 'after' => '}', 'clean' => true]);
        $this->assertEqual($result, $expected);

        $string = '<img src=":src" alt=":alt" class="foo :extra bar"/>';
        $expected = '<img src="foo" class="foo bar"/>';
        $result = Str::insert($string, ['src' => 'foo'], ['clean' => 'html']);

        $this->assertEqual($result, $expected);

        $string = '<img src=":src" class=":no :extra"/>';
        $expected = '<img src="foo"/>';
        $result = Str::insert($string, ['src' => 'foo'], ['clean' => 'html']);
        $this->assertEqual($result, $expected);

        $string = '<img src=":src" class=":no :extra"/>';
        $expected = '<img src="foo" class="bar"/>';
        $result = Str::insert($string, ['src' => 'foo', 'extra' => 'bar'], ['clean' => 'html']);
        $this->assertEqual($result, $expected);

        $result = Str::insert('this is a ? string', 'test');
        $expected = 'this is a test string';
        $this->assertEqual($result, $expected);

        $result = Str::insert('this is a ? string with a ? ? ?', ['long', 'few?', 'params', 'you know']);
        $expected = 'this is a long string with a few? params you know';
        $this->assertEqual($result, $expected);

        $result = Str::insert('update saved_urls set url = :url where id = :id', ['url' => 'http://www.testurl.com/param1:url/param2:id', 'id' => 1]);
        $expected = 'update saved_urls set url = http://www.testurl.com/param1:url/param2:id where id = 1';
        $this->assertEqual($result, $expected);

        $result = Str::insert('update saved_urls set url = :url where id = :id', ['id' => 1, 'url' => 'http://www.testurl.com/param1:url/param2:id']);
        $expected = 'update saved_urls set url = http://www.testurl.com/param1:url/param2:id where id = 1';
        $this->assertEqual($result, $expected);

        $result = Str::insert(':me cake. :subject :verb fantastic.', ['me' => 'I :verb', 'subject' => 'cake', 'verb' => 'is']);
        $expected = 'I :verb cake. cake is fantastic.';
        $this->assertEqual($result, $expected);

        $result = Str::insert(':I.am: :not.yet: passing.', ['I.am' => 'We are'], ['before' => ':', 'after' => ':', 'clean' => ['replacement' => ' of course', 'method' => 'text']]);
        $expected = 'We are of course passing.';
        $this->assertEqual($result, $expected);

        $result = Str::insert(
            ':I.am: :not.yet: passing.',
            ['I.am' => 'We are'],
            ['before' => ':', 'after' => ':', 'clean' => true]
        );
        $expected = 'We are passing.';
        $this->assertEqual($result, $expected);

        $result = Str::insert('?-pended result', ['Pre']);
        $expected = 'Pre-pended result';
        $this->assertEqual($result, $expected);

        $string = 'switching :timeout / :timeout_count';
        $expected = 'switching 5 / 10';
        $result = Str::insert($string, ['timeout' => 5, 'timeout_count' => 10]);
        $this->assertEqual($result, $expected);

        $string = 'switching :timeout / :timeout_count';
        $expected = 'switching 5 / 10';
        $result = Str::insert($string, ['timeout_count' => 10, 'timeout' => 5]);
        $this->assertEqual($result, $expected);

        $string = 'switching :timeout_count by :timeout';
        $expected = 'switching 10 by 5';
        $result = Str::insert($string, ['timeout' => 5, 'timeout_count' => 10]);
        $this->assertEqual($result, $expected);

        $string = 'switching :timeout_count by :timeout';
        $expected = 'switching 10 by 5';
        $result = Str::insert($string, ['timeout_count' => 10, 'timeout' => 5]);
        $this->assertEqual($result, $expected);
    }

    /**
     * test Clean Insert.
     */
    public function testCleanInsert()
    {
        $result = Str::cleanInsert(':incomplete', [
            'clean' => true, 'before' => ':', 'after' => '',
        ]);
        $this->assertEqual($result, '');

        $result = Str::cleanInsert(':incomplete', [
            'clean' => ['method' => 'text', 'replacement' => 'complete'],
            'before' => ':', 'after' => '', ]
        );
        $this->assertEqual($result, 'complete');

        $result = Str::cleanInsert(':in.complete', [
            'clean' => true, 'before' => ':', 'after' => '',
        ]);
        $this->assertEqual($result, '');

        $result = Str::cleanInsert(':in.complete and', [
            'clean' => true, 'before' => ':', 'after' => '', ]
        );
        $this->assertEqual($result, '');

        $result = Str::cleanInsert(':in.complete or stuff', [
            'clean' => true, 'before' => ':', 'after' => '',
        ]);
        $this->assertEqual($result, 'stuff');

        $result = Str::cleanInsert(
            '<p class=":missing" id=":missing">Text here</p>',
            ['clean' => 'html', 'before' => ':', 'after' => '']
        );
        $this->assertEqual($result, '<p>Text here</p>');
    }

    /**
     * Tests that non-insertable variables (i.e. arrays) are skipped when used as values in
     * Str::insert().
     */
    public function testAutoIgnoreBadInsertData()
    {
        $data = ['foo' => 'alpha', 'bar' => 'beta', 'fale' => []];
        $result = Str::insert('(:foo > :bar || :fale!)', $data, ['clean' => 'text']);
        $this->assertEqual($result, '(alpha > beta || !)');
    }

    /**
     * testTokenize method.
     */
    public function testTokenize()
    {
        $result = Str::tokenize('A,(short,boring test)');
        $expected = ['A', '(short,boring test)'];
        $this->assertEqual($result, $expected);

        $result = Str::tokenize('A,(short,more interesting( test)');
        $expected = ['A', '(short,more interesting( test)'];
        $this->assertEqual($result, $expected);

        $result = Str::tokenize('A,(short,very interesting( test))');
        $expected = ['A', '(short,very interesting( test))'];
        $this->assertEqual($result, $expected);

        $result = Str::tokenize('"single tag"', ' ', '"', '"');
        $expected = ['"single tag"'];
        $this->assertEqual($expected, $result);

        $result = Str::tokenize('tagA "single tag" tagB', ' ', '"', '"');
        $expected = ['tagA', '"single tag"', 'tagB'];
        $this->assertEqual($expected, $result);
    }

    public function testReplaceWithQuestionMarkInString()
    {
        $string = ':a, :b and :c?';
        $expected = '2 and 3?';
        $result = Str::insert($string, ['b' => 2, 'c' => 3], ['clean' => true]);
        $this->assertEqual($expected, $result);
    }
}
