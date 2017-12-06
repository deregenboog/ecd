<?php
/**
 * Imagick Shell Medium Adapter Test Case File.
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
App::import('Vendor', 'ImagickShellMediumAdapter', ['file' => 'medium'.DS.'adapter'.DS.'imagick_shell.php']);
require_once dirname(__FILE__).DS.'..'.DS.'..'.DS.'..'.DS.'..'.DS.'fixtures'.DS.'test_data.php';
/**
 * Test Imagick Shell Image Medium Class.
 */
class TestImagickShellImageMedium extends ImageMedium
{
    public $adapters = ['ImagickShell'];
}
/**
 * Test Imagick Shell Document Medium Class.
 */
class TestImagickShellDocumentMedium extends DocumentMedium
{
    public $adapters = ['ImagickShell'];
}
/**
 * Imagick Shell Medium Adapter Test Case Class.
 */
class ImagickShellMediumAdapterTest extends CakeTestCase
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
        exec('which convert 2>&1', $output, $return);
        $this->skipUnless(0 === $return, 'convert command not available');
        exec('which identify 2>&1', $output, $return);
        $this->skipUnless(0 === $return, 'identify command not available');
        exec('which gs 2>&1', $output, $return);
        $this->skipUnless(0 === $return, 'gs command not available');
    }

    public function showImage($string, $mimeType = null)
    {
        echo '<img src="data:'.$mimeType.';base64,'.base64_encode($string).'" />';
    }

    public function testBasic()
    {
        $result = new TestImagickShellImageMedium($this->TestData->getFile('image-jpg.jpg'));
        $this->assertIsA($result, 'object');

        $Medium = new TestImagickShellImageMedium($this->TestData->getFile('image-jpg.jpg'));
        $result = $Medium->toString();
        $this->assertTrue(!empty($result));
    }

    public function testInformation()
    {
        $Medium = new TestImagickShellImageMedium($this->TestData->getFile('image-jpg.jpg'));

        $result = $Medium->width();
        $this->assertEqual($result, 70);

        $result = $Medium->height();
        $this->assertEqual($result, 47);
    }

    public function testManipulation()
    {
        $Medium = new TestImagickShellImageMedium($this->TestData->getFile('image-jpg.jpg'));
        $Medium->fit(10, 10);
        $this->assertTrue($Medium->width() <= 10);
        $this->assertTrue($Medium->height() <= 10);

        $Medium = new TestImagickShellImageMedium($this->TestData->getFile('image-jpg.jpg'));
        $Medium->convert('image/png');
        $result = $Medium->mimeType;
        $this->assertTrue($result, 'image/png');
    }

    public function testCompress()
    {
        $Medium = new TestImagickShellImageMedium($this->TestData->getFile('image-jpg.jpg'));
        $resultCompress = $Medium->compress(1.5);
        $resultStore = $Medium->store($this->TestData->getFile('test-compress-1.5.jpg'), true);
        $this->assertTrue($resultCompress);
        $this->assertTrue($resultStore);

        $Medium = new TestImagickShellImageMedium($this->TestData->getFile('image-png.png'));
        $resultCompress = $Medium->compress(1.5);
        $resultStore = $Medium->store($this->TestData->getFile('test-compress-1.5.png'), true);
        $this->assertTrue($resultCompress);
        $this->assertTrue($resultStore);
    }

    public function testTransitions()
    {
        $Medium = new DocumentMedium($this->TestData->getFile('application-pdf.pdf'));
        $Medium->Adapters->detach(array_diff($Medium->adapters, ['ImagickShell']));

        $Medium = $Medium->convert('image/png');
        $Medium->Adapters->detach(array_diff($Medium->adapters, ['ImagickShell']));
        $this->assertIsA($Medium, 'ImageMedium');

        $tmpFile = $Medium->store(TMP.uniqid('test_suite_'));
        $this->assertEqual(MimeType::guessType($tmpFile), 'image/png');
        unlink($tmpFile);

        $Medium = new DocumentMedium($this->TestData->getFile('application-pdf.pdf'));
        $Medium->Adapters->detach(array_diff($Medium->adapters, ['ImagickShell']));
        $Medium = $Medium->convert('image/png');
        $Medium->Adapters->detach(array_diff($Medium->adapters, ['ImagickShell']));
        $result = $Medium->fit(10, 10);
        $this->assertTrue($result);
        $this->assertTrue($Medium->width() <= 10);
        $this->assertTrue($Medium->height() <= 10);
    }
}
