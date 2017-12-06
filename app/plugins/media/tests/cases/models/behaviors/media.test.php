<?php
/**
 * Media Behavior Test Case File.
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
App::Import('Model', 'App');
require_once CORE_TEST_CASES.DS.'libs'.DS.'model'.DS.'models.php';
require_once dirname(__FILE__).DS.'..'.DS.'models.php';
require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).DS.'config'.DS.'core.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))).DS.'fixtures'.DS.'test_data.php';
/**
 * Media Behavior Test Case Class.
 */
class MediaBehaviorTestCase extends CakeTestCase
{
    public $fixtures = ['plugin.media.song', 'core.image'];

    public function start()
    {
        parent::start();
        $this->loadFixtures('Song');
    }

    public function setUp()
    {
        $this->TmpFolder = new Folder(TMP.'tests'.DS, true);
        $this->TmpFolder->create($this->TmpFolder->pwd().'static/img');
        $this->TmpFolder->create($this->TmpFolder->pwd().'static/doc');
        $this->TmpFolder->create($this->TmpFolder->pwd().'static/txt');
        $this->TmpFolder->create($this->TmpFolder->pwd().'filter');

        $this->TestData = new TestData();
        $this->file0 = $this->TestData->getFile([
            'image-png.png' => $this->TmpFolder->pwd().'static/img/image-png.png',
        ]);
        $this->file1 = $this->TestData->getFile([
            'image-jpg.jpg' => $this->TmpFolder->pwd().'static/img/image-jpg.jpg',
        ]);
        $this->file2 = $this->TestData->getFile([
            'text-plain.txt' => $this->TmpFolder->pwd().'static/txt/text-plain.txt',
        ]);

        $this->_behaviorSettings = [
            'baseDirectory' => $this->TmpFolder->pwd(),
            'makeVersions' => false,
            'createDirectory' => false,
            'filterDirectory' => $this->TmpFolder->pwd().'filter'.DS,
            'metadataLevel' => 1,
        ];

        $this->_mediaConfig = Configure::read('Media');
    }

    public function tearDown()
    {
        $this->TestData->flushFiles();
        $this->TmpFolder->delete();
        ClassRegistry::flush();
        Configure::write('Media', $this->_mediaConfig);
    }

    public function testSetup()
    {
        $Model = &ClassRegistry::init('TheVoid');
        $Model->Behaviors->attach('Media.Media');

        $Model = &ClassRegistry::init('Song');
        $Model->Behaviors->attach('Media.Media');
    }

    public function testFind()
    {
        $Model = &ClassRegistry::init('Song');
        $Model->Behaviors->attach('Media.Media', $this->_behaviorSettings);
        $result = $Model->find('all');
        $this->assertEqual(count($result), 3);

        /* Virtual */
        $result = $Model->findById(1);
        $this->assertTrue(Set::matches('/Song/size', $result));
        $this->assertTrue(Set::matches('/Song/mime_type', $result));
    }

    public function testSave()
    {
        $Model = &ClassRegistry::init('Song');
        $Model->Behaviors->attach('Media.Media', $this->_behaviorSettings);

        $file = $this->TestData->getFile([
            'application-pdf.pdf' => $this->TmpFolder->pwd().'static/doc/application-pdf.pdf',
        ]);
        $item = ['file' => $file];
        $Model->create();
        $result = $Model->save($item);
        $this->assertTrue($result);

        $result = $Model->findById(5);
        $expected = [
            'Song' => [
                'id' => '5',
                    'dirname' => 'static/doc',
                    'basename' => 'application-pdf.pdf',
                    'checksum' => 'f7ee91cffd90881f3d719e1bab1c4697',
                    'size' => 13903,
                    'mime_type' => 'application/pdf',
        ], ];
        $this->assertEqual($expected, $result);
    }

    public function testBeforeMake()
    {
        Configure::write('Media.filter.image', [
            's' => ['convert' => 'image/png', 'fit' => [5, 5]],
            'm' => ['convert' => 'image/png', 'fit' => [10, 10]],
        ]);

        $Model = &ClassRegistry::init('Unicorn', 'Model');

        $Model->Behaviors->attach('Media.Media', [
            'baseDirectory' => $this->TmpFolder->pwd(),
            'filterDirectory' => $this->TmpFolder->pwd().'filter'.DS,
            'makeVersions' => true,
            'createDirectory' => true,
            'metadataLevel' => 0, ]);

        $file = $this->TestData->getFile([
            'image-jpg.jpg' => $this->TmpFolder->pwd().'image-jpg.jpg', ]);

        $expected[] = [
                    $file,
                    [
                        'overwrite' => false,
                        'directory' => $this->TmpFolder->pwd().'filter'.DS.'s'.DS,
                        'name' => 'Image',
                        'version' => 's',
                        'instructions' => ['convert' => 'image/png', 'fit' => [5, 5]],
                         ],
                    ];
        $expected[] = [
            $file,
            [
                'overwrite' => false,
                'directory' => $this->TmpFolder->pwd().'filter'.DS.'m'.DS,
                'name' => 'Image',
                'version' => 'm',
                'instructions' => ['convert' => 'image/png', 'fit' => [10, 10]],
         ], ];

        $Model->make($file);
        $this->assertEqual($Model->beforeMakeArgs, $expected);
    }
}
