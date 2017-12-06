<?php
/**
 * Image Medium Test Case File.
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
require_once dirname(__FILE__).DS.'..'.DS.'..'.DS.'..'.DS.'fixtures'.DS.'test_data.php';
/**
 * Image Medium Test Case Class.
 */
class ImageMediumTest extends CakeTestCase
{
    public function setUp()
    {
        $this->TestData = new TestData();
    }

    public function tearDown()
    {
        $this->TestData->flushFiles();
    }

    public function testInformation()
    {
        $file = $this->TestData->getFile('image-jpg.jpg');
        $Medium = new ImageMedium($file);
        $result = $Medium->width();
        $expecting = 70;
        $this->assertEqual($result, $expecting);

        $result = $Medium->height();
        $expecting = 47;
        $this->assertEqual($result, $expecting);

        $result = $Medium->quality();
        $expecting = 1;
        $this->assertEqual($result, $expecting);

        $result = $Medium->ratio();
        $expecting = '3:2';
        $this->assertEqual($result, $expecting);

        $result = $Medium->megapixel();
        $expecting = 0;
        $this->assertEqual($result, $expecting);
    }

    public function testTransitions()
    {
        exec('which gs 2>&1', $output, $return);
        $this->skipUnless(0 === $return, 'gs command not available');

        $Medium = new DocumentMedium($this->TestData->getFile('application-pdf.pdf'));
        $Medium = $Medium->convert('image/png');
        if ($this->assertIsA($Medium, 'ImageMedium')) {
            $tmpFile = $Medium->store(TMP.uniqid('test_suite_'));
            $this->assertEqual(MimeType::guessType($tmpFile), 'image/png');
            unlink($tmpFile);
        }

        $Medium = new DocumentMedium($this->TestData->getFile('application-pdf.pdf'));
        $Medium = $Medium->convert('image/png');
        if ($this->assertIsA($Medium, 'ImageMedium')) {
            $result = $Medium->fit(10, 10);
            $this->assertTrue($result);
            $this->assertTrue($Medium->width() <= 10);
            $this->assertTrue($Medium->height() <= 10);
        }
    }
}
