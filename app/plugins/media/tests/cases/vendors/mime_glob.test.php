<?php
/**
 * Mime Glob Test Case File.
 *
 * Copyright (c) 2007-2010 David Persson
 *
 * Distributed under the terms of the MIT License.
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP version 5
 * CakePHP version 1.2
 *
 * @author     David Persson <davidpersson@gmx.de>
 * @copyright  2007-2010 David Persson <davidpersson@gmx.de>
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 * @see       http://github.com/davidpersson/media
 */
App::import('Vendor', 'Media.MimeGlob');
require_once dirname(dirname(dirname(__FILE__))).DS.'fixtures'.DS.'test_data.php';
/**
 * Mime Glob Test Case Class.
 */
class MimeGlobTest extends CakeTestCase
{
    public function setUp()
    {
        Configure::write('Cache.disable', true);
        $this->TestData = new TestData();
    }

    public function tearDown()
    {
        $this->TestData->flushFiles();
    }

    public function testFormat()
    {
        $this->assertNull(MimeGlob::format(true));
        $this->assertNull(MimeGlob::format(5));
        //		$this->assertNull(MimeGlob::format(array('foo' => 'bar')));
        $this->assertNull(MimeGlob::format('does-not-exist.db'));

        $file = $this->TestData->getFile('glob.apache.snippet.db');
        $this->assertEqual(MimeGlob::format($file), 'Apache Module mod_mime');

        $file = $this->TestData->getFile('glob.freedesktop.snippet.db');
        $this->assertEqual(MimeGlob::format($file), 'Freedesktop Shared MIME-info Database');
    }

    public function testRead()
    {
        $fileA = $this->TestData->getFile('glob.apache.snippet.db');
        $fileB = $this->TestData->getFile('glob.freedesktop.snippet.db');

        $Mime = new MimeGlob($fileA);

        $Mime = new MimeGlob($fileB);

        $this->expectError();
        $Mime = new MimeGlob(5);
    }

    public function testToArrayAndRead()
    {
        $file = $this->TestData->getFile('glob.apache.snippet.db');

        $Mime = new MimeGlob($file);
        $expected = $Mime->toArray();
        $Mime = new MimeGlob($expected);
        $result = $Mime->toArray();

        $this->assertEqual($result, $expected);
    }

    public function testAnalyzeFail()
    {
        $file = $this->TestData->getFile('glob.apache.snippet.db');
        $Mime = new MimeGlob($file);

        $this->assertEqual($Mime->analyze('i-dont-exist.sla'), []);

        $file = $this->TestData->getFile('glob.freedesktop.snippet.db');
        $Mime = new MimeGlob($file);
    }

    public function testApacheAnalyze()
    {
        $file = $this->TestData->getFile('glob.apache.snippet.db');
        $Mime = new MimeGlob($file);

        $this->assertEqual($Mime->analyze('file.3gp'), []);
        $this->assertEqual($Mime->analyze('file.avi'), []);
        $this->assertEqual($Mime->analyze('file.bz2'), []);
        $this->assertEqual($Mime->analyze('file.mp4'), []);
        $this->assertEqual($Mime->analyze('file.css'), ['text/css']);
        $this->assertEqual($Mime->analyze('file.flac'), ['application/x-flac']);
        $this->assertEqual($Mime->analyze('file.swf'), ['application/x-shockwave-flash']);
        $this->assertEqual($Mime->analyze('file.gif'), ['image/gif']);
        $this->assertEqual($Mime->analyze('file.gz'), []);
        $this->assertEqual($Mime->analyze('file.html'), ['text/html']);
        $this->assertEqual($Mime->analyze('file.mp3'), ['audio/mpeg']);
        $this->assertEqual($Mime->analyze('file.class'), ['application/java-vm']);
        $this->assertEqual($Mime->analyze('file.js'), ['application/x-javascript']);
        $this->assertEqual($Mime->analyze('file.jpg'), ['image/jpeg']);
        $this->assertEqual($Mime->analyze('file.mpeg'), []);
        $this->assertEqual($Mime->analyze('file.ogg'), ['application/ogg']);
        $this->assertEqual($Mime->analyze('file.php'), []);
        $this->assertEqual($Mime->analyze('file.pdf'), ['application/pdf']);
        $this->assertEqual($Mime->analyze('file.png'), ['image/png']);
        $this->assertEqual($Mime->analyze('file.ps'), ['application/postscript']);
        $this->assertEqual($Mime->analyze('file.po'), []);
        $this->assertEqual($Mime->analyze('file.pot'), ['text/plain']);
        $this->assertEqual($Mime->analyze('file.mo'), []);
        $this->assertEqual($Mime->analyze('file.rm'), ['audio/x-pn-realaudio']);
        $this->assertEqual($Mime->analyze('file.rtf'), ['text/rtf']);
        $this->assertEqual($Mime->analyze('file.txt'), ['text/plain']);
        $this->assertEqual($Mime->analyze('file.doc'), ['application/msword']);
        $this->assertEqual($Mime->analyze('file.docx'), []);
        $this->assertEqual($Mime->analyze('file.odt'), ['application/vnd.oasis.opendocument.text']);
        $this->assertEqual($Mime->analyze('file.tar'), ['application/x-tar']);
        $this->assertEqual($Mime->analyze('file.wav'), ['audio/x-wav']);
        $this->assertEqual($Mime->analyze('file.xhtml'), ['application/xhtml+xml']);
        $this->assertEqual($Mime->analyze('file.xml'), ['application/xml']);
    }

    public function testApacheAnalyzeReverse()
    {
        $file = $this->TestData->getFile('glob.apache.snippet.db');
        $Mime = new MimeGlob($file);

        $this->assertEqual($Mime->analyze('text/plain', true), ['asc', 'txt', 'text', 'diff', 'pot']);
        $this->assertEqual($Mime->analyze('application/pdf', true), ['pdf']);
    }

    public function testFreedesktopAnalyze()
    {
        $file = $this->TestData->getFile('glob.freedesktop.snippet.db');
        $Mime = new MimeGlob($file);

        $this->assertEqual($Mime->analyze('file.3gp'), []);
        $this->assertEqual($Mime->analyze('file.avi'), []);
        $this->assertEqual($Mime->analyze('file.bz2'), ['application/x-bzip']);
        $this->assertEqual($Mime->analyze('file.mp4'), []);
        $this->assertEqual($Mime->analyze('file.css'), ['text/css']);
        $this->assertEqual($Mime->analyze('file.flac'), []);
        $this->assertEqual($Mime->analyze('file.swf'), []);
        $this->assertEqual($Mime->analyze('file.gif'), ['image/gif']);
        $this->assertEqual($Mime->analyze('file.gz'), ['application/x-gzip']);
        $this->assertEqual($Mime->analyze('file.html'), []);
        $this->assertEqual($Mime->analyze('file.mp3'), []);
        $this->assertEqual($Mime->analyze('file.class'), ['application/x-java']);
        $this->assertEqual($Mime->analyze('file.js'), ['application/javascript']);
        $this->assertEqual($Mime->analyze('file.jpg'), []);
        $this->assertEqual($Mime->analyze('file.mpeg'), []);
        $this->assertEqual($Mime->analyze('file.ogg'), []);
        $this->assertEqual($Mime->analyze('file.php'), []);
        $this->assertEqual($Mime->analyze('file.pdf'), ['application/pdf']);
        $this->assertEqual($Mime->analyze('file.png'), []);
        $this->assertEqual($Mime->analyze('file.ps'), []);
        $this->assertEqual($Mime->analyze('file.po'), ['text/x-gettext-translation']);
        $this->assertEqual($Mime->analyze('file.pot'), ['application/vnd.ms-powerpoint', 'text/x-gettext-translation-template']);
        $this->assertEqual($Mime->analyze('file.mo'), ['application/x-gettext-translation']);
        $this->assertEqual($Mime->analyze('file.rm'), []);
        $this->assertEqual($Mime->analyze('file.rtf'), ['application/rtf']);
        $this->assertEqual($Mime->analyze('file.txt'), ['text/plain']);
        $this->assertEqual($Mime->analyze('file.doc'), ['application/msword']);
        $this->assertEqual($Mime->analyze('file.docx'), []);
        $this->assertEqual($Mime->analyze('file.odt'), ['application/vnd.oasis.opendocument.text']);
        $this->assertEqual($Mime->analyze('file.tar'), ['application/x-tar']);
        $this->assertEqual($Mime->analyze('file.wav'), []);
        $this->assertEqual($Mime->analyze('file.xhtml'), ['application/xhtml+xml']);
        $this->assertEqual($Mime->analyze('file.xml'), ['application/xml']);
    }

    public function testShippedAnalyze()
    {
        $file = dirname(dirname(dirname(dirname(__FILE__)))).DS.'vendors'.DS.'mime_glob.db';
        $skip = $this->skipIf(!file_exists($file), '%s. No shipped glob db.');

        if ($skip) { /* Skipping does not silence the error */
            $this->expectError();
        }
        $Mime = new MimeGlob($file);

        $this->assertEqual($Mime->analyze('file.3gp'), ['video/3gpp']);
        $this->assertEqual($Mime->analyze('file.avi'), ['video/x-msvideo']);
        $this->assertEqual($Mime->analyze('file.bz2'), ['application/x-bzip']);
        $this->assertEqual($Mime->analyze('file.mp4'), ['video/mp4']);
        $this->assertEqual($Mime->analyze('file.css'), ['text/css']);
        $this->assertEqual($Mime->analyze('file.flac'), ['audio/x-flac']);
        $this->assertEqual($Mime->analyze('file.swf'), ['application/x-shockwave-flash']);
        $this->assertEqual($Mime->analyze('file.gif'), ['image/gif']);
        $this->assertEqual($Mime->analyze('file.gz'), ['application/x-gzip']);
        $this->assertEqual($Mime->analyze('file.html'), ['text/html']);
        $this->assertEqual($Mime->analyze('file.mp3'), ['audio/mpeg']);
        $this->assertEqual($Mime->analyze('file.class'), ['application/x-java']);
        $this->assertEqual($Mime->analyze('file.js'), ['application/javascript']);
        $this->assertEqual($Mime->analyze('file.jpg'), ['image/jpeg']);
        $this->assertEqual($Mime->analyze('file.mpeg'), ['video/mpeg']);
        $this->assertEqual($Mime->analyze('file.ogg'), ['application/ogg', 'audio/x-vorbis+ogg', 'audio/x-flac+ogg', 'audio/x-speex+ogg', 'video/x-theora+ogg']);
        $this->assertEqual($Mime->analyze('file.php'), ['application/x-php']);
        $this->assertEqual($Mime->analyze('file.pdf'), ['application/pdf']);
        $this->assertEqual($Mime->analyze('file.png'), ['image/png']);
        $this->assertEqual($Mime->analyze('file.ps'), ['application/postscript']);
        $this->assertEqual($Mime->analyze('file.po'), ['text/x-gettext-translation']);
        $this->assertEqual($Mime->analyze('file.pot'), ['application/vnd.ms-powerpoint', 'text/x-gettext-translation-template']);
        $this->assertEqual($Mime->analyze('file.mo'), ['application/x-gettext-translation']);
        $this->assertEqual($Mime->analyze('file.rm'), ['application/vnd.rn-realmedia']);
        $this->assertEqual($Mime->analyze('file.rtf'), ['application/rtf']);
        $this->assertEqual($Mime->analyze('file.txt'), ['text/plain']);
        /* Fails with text/plain */
        // $this->assertEqual($Mime->analyze('file.doc'), array('application/msword', 'application/msword'));
        /* This really shouldn't fail */
        // $this->assertEqual($Mime->analyze('file.docx'), array('application/vnd.openxmlformats-officedocument.wordprocessingml.document'));
        $this->assertEqual($Mime->analyze('file.odt'), ['application/vnd.oasis.opendocument.text']);
        $this->assertEqual($Mime->analyze('file.tar'), ['application/x-tar']);
        $this->assertEqual($Mime->analyze('file.wav'), ['audio/x-wav']);
        $this->assertEqual($Mime->analyze('file.xhtml'), ['application/xhtml+xml']);
        $this->assertEqual($Mime->analyze('file.xml'), ['application/xml']);
    }
}
