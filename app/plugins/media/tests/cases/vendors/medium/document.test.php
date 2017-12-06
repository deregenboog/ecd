<?php
/**
 * Document Medium Test Case File.
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
App::import('Vendor', 'Media.DocumentMedium', ['file' => 'medium'.DS.'document.php']);
require_once dirname(__FILE__).DS.'..'.DS.'..'.DS.'..'.DS.'fixtures'.DS.'test_data.php';
/**
 * Document Medium Test Case Class.
 */
class DocumentMediumTest extends CakeTestCase
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
        exec('which gs 2>&1', $output, $return);
        $this->skipUnless(0 === $return, 'gs command not available');
    }

    public function testInformation()
    {
        $file = $this->TestData->getFile('application-pdf.pdf');
        $Medium = new DocumentMedium($file);
        $result = $Medium->width();
        $expecting = 595;
        $this->assertEqual($result, $expecting);

        $result = $Medium->height();
        $expecting = 842;
        $this->assertEqual($result, $expecting);

        $result = $Medium->quality();
        $expecting = 0;
        $this->assertEqual($result, $expecting);

        $result = $Medium->ratio();
        $expecting = '1:√2';
        $this->assertEqual($result, $expecting);
    }
}
