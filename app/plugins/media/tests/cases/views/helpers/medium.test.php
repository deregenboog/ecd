<?php
/**
 * Medium Helper Test Case File.
 *
 * Copyright (c) 2007-2010 David Persson
 *
 * Distributed under the terms of the MIT License.
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP version 5
 * CakePHP version 1.2
 *
 * @copyright  2007-2010 David Persson <davidpersson@gmx.de>
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 * @see       http://github.com/davidpersson/media
 */
App::import('Core', ['Helper', 'AppHelper', 'ClassRegistry']);
App::import('Helper', 'Media.Medium');
require_once dirname(__FILE__).DS.'..'.DS.'..'.DS.'..'.DS.'fixtures'.DS.'test_data.php';
/**
 * Mock Medium Helper.
 */
class MockMediumHelper extends MediumHelper
{
    public function versions()
    {
        return $this->_versions;
    }

    public function directories()
    {
        return $this->_directories;
    }
}
/**
 * Medium Helper Test Case Class.
 */
class MediumHelperTestCase extends CakeTestCase
{
    public function setUp()
    {
        $this->_config = Configure::read('Media');

        $this->TmpFolder = new Folder(TMP.'tests'.DS, true);
        $this->TmpFolder->create($this->TmpFolder->pwd().'static');
        $this->TmpFolder->create($this->TmpFolder->pwd().'static'.DS.'img');
        $this->TmpFolder->create($this->TmpFolder->pwd().'filter');
        $this->TmpFolder->create($this->TmpFolder->pwd().'filter'.DS.'s'.DS.'static'.DS.'img');
        $this->TmpFolder->create($this->TmpFolder->pwd().'transfer');
        $this->TmpFolder->create($this->TmpFolder->pwd().'transfer'.DS.'img');
        $this->TmpFolder->create($this->TmpFolder->pwd().'filter'.DS.'s'.DS.'transfer'.DS.'img');
        $this->TmpFolder->create($this->TmpFolder->pwd().'theme');
        $this->TmpFolder->create($this->TmpFolder->pwd().'theme'.DS.'blanko');
        $this->TmpFolder->create($this->TmpFolder->pwd().'theme'.DS.'blanko'.DS.'img'.DS);

        $this->TestData = new TestData();

        $this->file0 = $this->TestData->getFile([
            'image-png.png' => $this->TmpFolder->pwd().'static/img/image-png.png', ]);
        $this->file1 = $this->TestData->getFile([
            'image-png.png' => $this->TmpFolder->pwd().'filter/s/static/img/image-png.png', ]);
        $this->file2 = $this->TestData->getFile([
            'image-png.png' => $this->TmpFolder->pwd().'filter/s/static/img/dot.ted.name.png', ]);
        $this->file3 = $this->TestData->getFile([
            'image-png.png' => $this->TmpFolder->pwd().'transfer/img/image-png-x.png', ]);
        $this->file4 = $this->TestData->getFile([
            'image-png.png' => $this->TmpFolder->pwd().'filter/s/transfer/img/image-png-x.png', ]);
        $this->file5 = $this->TestData->getFile([
            'image-png.png' => $this->TmpFolder->pwd().'theme/blanko/img/image-blanko.png', ]);

        $settings = [
            'static' => [$this->TmpFolder->pwd().'static'.DS => 'media/static/'],
            'filter' => [$this->TmpFolder->pwd().'filter'.DS => 'media/filter/'],
            'transfer' => [$this->TmpFolder->pwd().'transfer'.DS => false],
            'theme' => [$this->TmpFolder->pwd().'theme'.DS => 'media/theme/'],
        ];
        $this->Helper = new MediumHelper($settings);
    }

    public function tearDown()
    {
        Configure::write('Media', $this->_config);
        $this->TestData->flushFiles();
        $this->TmpFolder->delete();
        ClassRegistry::flush();
    }

    public function testConstruct()
    {
        $settings = [
            'static' => [$this->TmpFolder->pwd().'static'.DS => 'media/static/'],
            'theme' => [$this->TmpFolder->pwd().'theme'.DS => 'media/theme/'],
        ];
        Configure::write('Media.filter', [
            'image' => ['s' => [], 'm' => []],
            'video' => ['s' => [], 'xl' => []],
        ]);
        $Helper = new MockMediumHelper($settings);

        $this->assertEqual($Helper->versions(), ['s', 'm', 'xl']);

        $expected = [
            'static' => $this->TmpFolder->pwd().'static'.DS,
            'transfer' => MEDIA_TRANSFER,
            'filter' => MEDIA_FILTER,
            'theme' => $this->TmpFolder->pwd().'theme'.DS,
        ];
        $this->assertEqual($Helper->directories(), $expected);
    }

    public function testUrl()
    {
        $result = $this->Helper->url('static/img/image-png');
        $this->assertEqual($result, 'media/static/img/image-png.png');

        $result = $this->Helper->url('filter/s/static/img/image-png');
        $this->assertEqual($result, 'media/filter/s/static/img/image-png.png');

        $result = $this->Helper->url('transfer/img/image-png-x');
        $this->assertNull($result);

        $result = $this->Helper->url('filter/s/transfer/img/image-png-x');
        $this->assertEqual($result, 'media/filter/s/transfer/img/image-png-x.png');

        $result = $this->Helper->url($this->TmpFolder->pwd().'filter/s/transfer/img/image-png-x.png');
        $this->assertEqual($result, 'media/filter/s/transfer/img/image-png-x.png');
    }

    public function testWebroot()
    {
        $result = $this->Helper->webroot('static/img/image-png');
        $this->assertEqual($result, 'media/static/img/image-png.png');

        $result = $this->Helper->webroot('filter/s/static/img/image-png');
        $this->assertEqual($result, 'media/filter/s/static/img/image-png.png');

        $result = $this->Helper->webroot('transfer/img/image-png-x');
        $this->assertNull($result);

        $result = $this->Helper->webroot('filter/s/transfer/img/image-png-x');
        $this->assertEqual($result, 'media/filter/s/transfer/img/image-png-x.png');

        $result = $this->Helper->webroot($this->TmpFolder->pwd().'filter/s/transfer/img/image-png-x.png');
        $this->assertEqual($result, 'media/filter/s/transfer/img/image-png-x.png');
    }

    public function testFile()
    {
        $this->expectError();
        $this->Helper->file('image-png');

        $result = $this->Helper->file('static/img/not-existant.jpg');
        $this->assertFalse($result);

        $result = $this->Helper->file('img/image-png');
        $this->assertEqual($result, $this->file0);

        $result = $this->Helper->file('static/img/image-png');
        $this->assertEqual($result, $this->file0);

        $result = $this->Helper->file('static/img/image-png.png');
        $this->assertEqual($result, $this->file0);

        $result = $this->Helper->file('s/img/image-png');
        $this->assertEqual($result, $this->file1);

        $result = $this->Helper->file('filter/s/img/image-png');
        $this->assertEqual($result, $this->file1);

        $result = $this->Helper->file('filter/s/static/img/image-png');
        $this->assertEqual($result, $this->file1);

        $result = $this->Helper->file('filter/s/img/dot.ted.name');
        $this->assertEqual($result, $this->file2);

        $result = $this->Helper->file('transfer/img/image-png-x');
        $this->assertEqual($result, $this->file3);

        $result = $this->Helper->file('filter/s/transfer/img/image-png-x');
        $this->assertEqual($result, $this->file4);

        $result = $this->Helper->file($this->TmpFolder->pwd().'filter/s/transfer/img/image-png-x.png');
        $this->assertEqual($result, $this->file4);

        $result = $this->Helper->file('theme/blanko/img/image-blanko');
        $this->assertEqual($result, $this->file5);
    }

    public function testFileArraySyntax()
    {
        $result = $this->Helper->file([
            'dirname' => 'static/img',
            'basename' => 'not-existant.jpg',
        ]);
        $this->assertFalse($result);

        $result = $this->Helper->file([
            'dirname' => 'static/img',
            'basename' => 'image-png',
        ]);
        $this->assertEqual($result, $this->file0);

        $result = $this->Helper->file([
            'dirname' => 'static/img/',
            'basename' => 'image-png',
        ]);
        $this->assertEqual($result, $this->file0);
    }

    public function testFileMixedSyntax()
    {
        $result = $this->Helper->file('static', [
            'dirname' => 'img',
            'basename' => 'not-existant.jpg',
        ]);
        $this->assertFalse($result);

        $result = $this->Helper->file('static', [
            'dirname' => 'img',
            'basename' => 'image-png',
        ]);
        $this->assertEqual($result, $this->file0);

        $result = $this->Helper->file('static/', [
            'dirname' => 'img',
            'basename' => 'image-png',
        ]);
        $this->assertEqual($result, $this->file0);
    }
}
