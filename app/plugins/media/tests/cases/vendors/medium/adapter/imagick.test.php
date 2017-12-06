<?php
/**
 * Imagick Medium Adapter Test Case File.
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
App::import('Vendor', 'Media.ImageMedium', ['file' => 'medium'.DS.'image.php']);
App::import('Vendor', 'Media.DocumentMedium', ['file' => 'medium'.DS.'document.php']);
App::import('Vendor', 'ImagickMediumAdapter', ['file' => 'medium'.DS.'adapter'.DS.'imagick.php']);
require_once dirname(__FILE__).DS.'..'.DS.'..'.DS.'..'.DS.'..'.DS.'fixtures'.DS.'test_data.php';
/**
 * Test Imagick Image Medium Class.
 */
class TestImagickImageMedium extends ImageMedium
{
    public $adapters = ['Imagick'];
}
/**
 * Test Imagick Document Medium Class.
 */
class TestImagickDocumentMedium extends DocumentMedium
{
    public $adapters = ['Imagick'];
}
/**
 * Imagick Medium Adapter Test Case Class.
 */
class ImagickMediumAdapterTest extends CakeTestCase
{
    public function setUp()
    {
        $this->TestData = new TestData();
    }

    public function tearDown()
    {
        $this->TestData->flushFiles();
    }

    public function skip()
    {
        $this->skipUnless(extension_loaded('imagick'), 'Imagick extension not loaded');
    }

    public function showImage($string, $mimeType = null)
    {
        echo '<img src="data:'.$mimeType.';base64,'.base64_encode($string).'" />';
    }

    public function testBasic()
    {
        $result = new TestImagickImageMedium($this->TestData->getFile('image-jpg.jpg'));
        $this->assertIsA($result, 'object');

        $Medium = new TestImagickImageMedium($this->TestData->getFile('image-jpg.jpg'));
        $result = $Medium->toString();
        $this->assertTrue(!empty($result));
    }

    public function testInformation()
    {
        $file = $this->TestData->getFile('image-jpg.jpg');
        $Medium = new TestImagickImageMedium($file);

        $result = $Medium->width();
        $this->assertEqual($result, 70);

        $result = $Medium->height();
        $this->assertEqual($result, 47);
    }

    public function testManipulation()
    {
        $Medium = new TestImagickImageMedium($this->TestData->getFile('image-jpg.jpg'));
        $Medium->fit(10, 10);
        $this->assertTrue($Medium->width() <= 10);
        $this->assertTrue($Medium->height() <= 10);

        $Medium = new TestImagickImageMedium($this->TestData->getFile('image-jpg.jpg'));
        $Medium = $Medium->convert('image/png');
        if ($this->assertIsA($Medium, 'ImageMedium')) {
            $result = $Medium->mimeType;
            $this->assertEqual($result, 'image/png');
        }

        $Medium = new TestImagickImageMedium($this->TestData->getFile('image-jpg.jpg'));
        $Medium = $Medium->convert('image/png');
        if ($this->assertIsA($Medium, 'ImageMedium')) {
            $tmpFile = TMP.uniqid('test_suite_');
            $tmpFile = $Medium->store($tmpFile);
            $this->assertEqual(MimeType::guessType($tmpFile), 'image/png');
            unlink($tmpFile);
        }
    }

    public function testCompress()
    {
        $Medium = new TestImagickImageMedium($this->TestData->getFile('image-jpg.jpg'));
        $resultCompress = $Medium->compress(1.5);
        $resultStore = $Medium->store($this->TestData->getFile('test-compress-1.5.jpg'), true);
        $this->assertTrue($resultCompress);
        $this->assertTrue($resultStore);

        $Medium = new TestImagickImageMedium($this->TestData->getFile('image-png.png'));
        $resultCompress = $Medium->compress(1.5);
        $resultStore = $Medium->store($this->TestData->getFile('test-compress-1.5.png'), true);
        $this->assertTrue($resultCompress);
        $this->assertTrue($resultStore);
    }
}
