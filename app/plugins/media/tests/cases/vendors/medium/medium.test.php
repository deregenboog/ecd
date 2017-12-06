<?php
/**
 * Medium Test Case File.
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
App::import('Vendor', 'Media.Medium');
require_once dirname(__FILE__).DS.'..'.DS.'..'.DS.'..'.DS.'fixtures'.DS.'test_data.php';
/**
 * Banana Medium Adapter Class.
 */
class BananaMediumAdapter extends MediumAdapter
{
}
/**
 * Cherry Medium Adapter Class.
 */
class CherryMediumAdapter extends MediumAdapter
{
}
/**
 * Sweet Medium Adapter Class.
 */
class SweetMedium extends Medium
{
    public $adapters = ['Banana', 'Cherry'];
}
/**
 * Medium Test Case Class.
 */
class MediumTest extends CakeTestCase
{
    public function setUp()
    {
        $this->TestData = new TestData();
    }

    public function tearDown()
    {
        $this->TestData->flushFiles();
    }

    public function testMediumFactory()
    {
        $file = $this->TestData->getFile('image-jpg.jpg');
        $result = Medium::factory($file);
        $this->assertIsA($result, 'ImageMedium');

        $file = $this->TestData->getFile('image-png.png');
        $result = Medium::factory($file);
        $this->assertIsA($result, 'ImageMedium');

        $file = $this->TestData->getFile('image-gif.gif');
        $result = Medium::factory($file);
        $this->assertIsA($result, 'ImageMedium');

        $file = $this->TestData->getFile('text-plain.txt');
        $result = Medium::factory($file);
        $this->assertIsA($result, 'TextMedium');

        $file = $this->TestData->getFile('application-pdf.pdf');
        $result = Medium::factory($file);
        $this->assertIsA($result, 'DocumentMedium');
    }

    public function testMediumNameAndShort()
    {
        $file = $this->TestData->getFile('image-jpg.jpg');
        $result = Medium::factory($file);
        $this->assertEqual($result->name, 'Image');
        $this->assertEqual($result->short, 'img');

        $file = $this->TestData->getFile('image-png.png');
        $result = Medium::factory($file);
        $this->assertEqual($result->name, 'Image');
        $this->assertEqual($result->short, 'img');

        $file = $this->TestData->getFile('image-gif.gif');
        $result = Medium::factory($file);
        $this->assertEqual($result->name, 'Image');
        $this->assertEqual($result->short, 'img');

        $file = $this->TestData->getFile('text-plain.txt');
        $result = Medium::factory($file);
        $this->assertEqual($result->name, 'Text');
        $this->assertEqual($result->short, 'txt');

        $file = $this->TestData->getFile('application-pdf.pdf');
        $result = Medium::factory($file);
        $this->assertEqual($result->name, 'Document');
        $this->assertEqual($result->short, 'doc');
    }

    public function testMediumAdapterCollection()
    {
        //		$Collection = new MediumAdapterCollection();
//		$Collection
    }

    public function testMake()
    {
        $instructions = ['convert' => 'image/png', 'zoomCrop' => [10, 10]];
        $Medium = Medium::make($this->TestData->getFile('image-jpg.jpg'), $instructions);
        $this->assertIsA($Medium, 'Medium');
    }
}
