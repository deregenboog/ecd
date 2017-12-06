<?php
/**
 * Attachment Test Case File.
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
App::import('Model', 'Media.Attachment');
require_once 'models.php';
require_once dirname(dirname(dirname(__FILE__))).DS.'fixtures'.DS.'test_data.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))).DS.'config'.DS.'core.php';
/**
 * Attachment Test Case Class.
 */
class AttachmentTestCase extends CakeTestCase
{
    public $fixtures = [
        'plugin.media.movie', 'plugin.media.actor',
        'plugin.media.attachment', 'plugin.media.pirate',
    ];

    public function setUp()
    {
        $this->TestData = new TestData();
        $this->TestFolder = new Folder(TMP.'test_suite'.DS, true);
        new Folder($this->TestFolder->pwd().'transfer'.DS, true);
        new Folder($this->TestFolder->pwd().'static'.DS, true);
        new Folder($this->TestFolder->pwd().'filter'.DS, true);
    }

    public function tearDown()
    {
        $this->TestData->flushFiles();
        $this->TestFolder->delete();
        ClassRegistry::flush();
    }

    public function testHasOne()
    {
        $Model = ClassRegistry::init('Movie');
        $assoc = [
            'Attachment' => [
                'className' => 'Media.Attachment',
                'foreignKey' => 'foreign_key',
                'conditions' => ['Attachment.model' => 'Movie'],
                'dependent' => true,
        ], ];
        $Model->bindModel(['hasOne' => $assoc], false);

        $Model->Attachment->Behaviors->attach('Media.Polymorphic', [
            'classField' => 'model',
            'foreignKey' => 'foreign_key',
        ]);
        $Model->Attachment->Behaviors->attach('Media.Transfer', [
            'baseDirectory' => TMP,
            'destinationFile' => 'test_suite:DS:transfer:DS::Source.basename:',
        ]);
        $Model->Attachment->Behaviors->attach('Media.Media', [
            'filterDirectory' => $this->TestFolder->pwd().'filter'.DS,
        ]);

        $file = $this->TestData->getFile(['image-jpg.jpg' => 'ta.jpg']);
        $data = [
            'Movie' => ['title' => 'Weekend', 'director' => 'Jean-Luc Godard'],
            'Attachment' => ['file' => $file, 'model' => 'Movie'],
        ];

        $Model->create();
        $this->assertTrue($Model->saveAll($data, ['validate' => 'first']));
        $file = $Model->Attachment->getLastTransferredFile();
        $this->assertTrue(file_exists($file));

        $result = $Model->find('first', ['conditions' => ['title' => 'Weekend']]);
        $expected = [
            'id' => 1,
            'model' => 'Movie',
            'foreign_key' => 4,
            'dirname' => 'tmp/test_suite/transfer',
            'basename' => 'ta.jpg',
            'checksum' => '1920c29e7fbe4d1ad2f9173ef4591133',
            'group' => null,
            'alternative' => null,
        ];
        $this->assertEqual($result['Attachment'], $expected);

        $result = $Model->delete($Model->getLastInsertID());
        $this->assertTrue($result);
        $this->assertFalse(file_exists($this->TestFolder->pwd().'transfer'.DS.'ta.jpg'));
    }

    public function testHasMany()
    {
        $Model = ClassRegistry::init('Movie');
        $assoc = [
            'Attachment' => [
                'className' => 'Media.Attachment',
                'foreignKey' => 'foreign_key',
                'conditions' => ['Attachment.model' => 'Movie'],
                'dependent' => true,
        ], ];
        $Model->bindModel(['hasMany' => $assoc], false);

        $Model->Attachment->Behaviors->attach('Media.Polymorphic', [
            'classField' => 'model',
            'foreignKey' => 'foreign_key',
        ]);
        $Model->Attachment->Behaviors->attach('Media.Transfer', [
            'baseDirectory' => TMP,
            'destinationFile' => 'test_suite:DS:transfer:DS::Source.basename:',
        ]);
        $Model->Attachment->Behaviors->attach('Media.Media', [
            'filterDirectory' => $this->TestFolder->pwd().'filter'.DS,
        ]);

        $fileA = $this->TestData->getFile(['image-jpg.jpg' => 'ta.jpg']);
        $fileB = $this->TestData->getFile(['image-png.png' => 'tb.png']);
        $data = [
            'Movie' => ['title' => 'Weekend', 'director' => 'Jean-Luc Godard'],
            'Attachment' => [
                ['file' => $fileA, 'model' => 'Movie'],
                ['file' => $fileB, 'model' => 'Movie'],
        ], ];

        $Model->create();
        $result = $Model->saveAll($data, ['validate' => 'first']);
        $this->assertTrue($result);
        $this->assertTrue(file_exists($this->TestFolder->pwd().'ta.jpg'));
        $this->assertTrue(file_exists($this->TestFolder->pwd().'tb.png'));

        $result = $Model->find('first', ['conditions' => ['title' => 'Weekend']]);
        $expected = [
            0 => [
                'id' => 1,
                'model' => 'Movie',
                'foreign_key' => 4,
                'dirname' => 'tmp/test_suite/transfer',
                'basename' => 'ta.jpg',
                'checksum' => '1920c29e7fbe4d1ad2f9173ef4591133',
                'group' => null,
                'alternative' => null,
            ],
            1 => [
                'id' => 2,
                'model' => 'Movie',
                'foreign_key' => 4,
                'dirname' => 'tmp/test_suite/transfer',
                'basename' => 'tb.png',
                'checksum' => '7f9af648b511f2c83b1744f42254983f',
                'group' => null,
                'alternative' => null,
        ], ];
        $this->assertEqual($result['Attachment'], $expected);

        $result = $Model->delete($Model->getLastInsertID());
        $this->assertTrue($result);
        $this->assertFalse(file_exists($this->TestFolder->pwd().'transfer'.DS.'ta.jpg'));
        $this->assertFalse(file_exists($this->TestFolder->pwd().'transfer'.DS.'tb.jpg'));
    }

    public function testGroupedHasMany()
    {
        $Model = ClassRegistry::init('Movie');
        $assoc = [
            'Photo' => [
                'className' => 'Media.Attachment',
                'foreignKey' => 'foreign_key',
                'conditions' => ['Photo.model' => 'Movie', 'Photo.group' => 'photo'],
                'dependent' => true,
        ], ];
        $Model->bindModel(['hasMany' => $assoc], false);

        $Model->Photo->Behaviors->attach('Media.Polymorphic', [
            'classField' => 'model',
            'foreignKey' => 'foreign_key',
        ]);
        $Model->Photo->Behaviors->attach('Media.Transfer', [
            'baseDirectory' => TMP,
            'destinationFile' => 'test_suite:DS:transfer:DS:photo:DS::Source.basename:',
        ]);
        $Model->Photo->Behaviors->attach('Media.Media', [
            'filterDirectory' => $this->TestFolder->pwd().'filter'.DS,
        ]);

        $fileA = $this->TestData->getFile(['image-png.png' => 'ta.png']);
        $fileB = $this->TestData->getFile(['image-png.png' => 'tb.png']);
        $data = [
            'Movie' => ['title' => 'Weekend', 'director' => 'Jean-Luc Godard'],
            'Photo' => [
                ['file' => $fileA, 'model' => 'Movie', 'group' => 'photo'],
                ['file' => $fileB, 'model' => 'Movie', 'group' => 'photo'],
        ], ];

        $Model->create();
        $result = $Model->saveAll($data, ['validate' => 'first']);
        $this->assertTrue($result);
        $this->assertTrue(file_exists($this->TestFolder->pwd().'transfer'.DS.'photo'.DS.'ta.png'));
        $this->assertTrue(file_exists($this->TestFolder->pwd().'transfer'.DS.'photo'.DS.'tb.png'));

        $result = $Model->find('first', ['conditions' => ['title' => 'Weekend']]);
        $expected = [
            'Movie' => [
                'id' => 4,
                'title' => 'Weekend',
                'director' => 'Jean-Luc Godard',
            ],
            'Actor' => [],
            'Photo' => [
                0 => [
                    'id' => 1,
                    'model' => 'Movie',
                    'foreign_key' => 4,
                    'dirname' => 'tmp/test_suite/transfer/photo',
                    'basename' => 'ta.png',
                    'checksum' => '7f9af648b511f2c83b1744f42254983f',
                    'group' => 'photo',
                    'alternative' => null,
                ],
                1 => [
                    'id' => 2,
                    'model' => 'Movie',
                    'foreign_key' => 4,
                    'dirname' => 'tmp/test_suite/transfer/photo',
                    'basename' => 'tb.png',
                    'checksum' => '7f9af648b511f2c83b1744f42254983f',
                    'group' => 'photo',
                    'alternative' => null,
        ], ], ];
        $this->assertEqual($result, $expected);

        $result = $Model->delete($Model->getLastInsertID());
        $this->assertTrue($result);
        $this->assertFalse(file_exists($this->TestFolder->pwd().'transfer'.DS.'photo'.DS.'ta.png'));
        $this->assertFalse(file_exists($this->TestFolder->pwd().'transfer'.DS.'photo'.DS.'tb.png'));
    }
}
