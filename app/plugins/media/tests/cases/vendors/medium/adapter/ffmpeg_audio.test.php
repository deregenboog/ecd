<?php
/**
 * FfmpegAudio Medium Adapter Test Case File.
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
App::import('Vendor', 'Media.AudioMedium', ['file' => 'medium'.DS.'audio.php']);
App::import('Vendor', 'FfMpegAudioMediumAdapter', ['file' => 'medium'.DS.'adapter'.DS.'ff_mpeg_audio.php']);
require_once dirname(__FILE__).DS.'..'.DS.'..'.DS.'..'.DS.'..'.DS.'fixtures'.DS.'test_data.php';
/**
 * Test Ffmpeg Audio Medium Adapter Class.
 */
class TestFfmpegAudioMedium extends AudioMedium
{
    public $adapters = ['FfmpegAudio'];
}
/**
 * FfMpeg Audio Medium Adapter Test Case Class.
 */
class FfmpegAudioMediumAdapterTest extends CakeTestCase
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
        $this->skipUnless(extension_loaded('ffmpeg'), '%s ffmpeg extension not loaded');
    }

    public function testBasic()
    {
        $result = new TestFfmpegAudioMedium($this->TestData->getFile('audio-mpeg.ID3v1.mp3'));
        $this->assertIsA($result, 'object');

        $Medium = new TestFfmpegAudioMedium($this->TestData->getFile('audio-mpeg.ID3v1.mp3'));
        $result = $Medium->toString();
        $this->assertTrue(!empty($result));
    }

    public function testInformationId3v1()
    {
        $Medium = new TestFfmpegAudioMedium($this->TestData->getFile('audio-mpeg.ID3v1.mp3'));

        $result = $Medium->artist();
        $this->assertEqual($result, 'Artist');

        $result = $Medium->title();
        $this->assertEqual($result, 'Title');

        $result = $Medium->album();
        $this->assertEqual($result, 'Album');

        $result = $Medium->year();
        $this->assertEqual($result, 2009);

        $result = $Medium->track();
        $this->assertEqual($result, 1);

        $result = $Medium->duration();
        $this->assertEqual($result, 1);

        $result = $Medium->bitRate();
        $this->assertEqual($result, 64000);

        $result = $Medium->samplingRate();
        $this->assertEqual($result, 24000);

        $result = $Medium->quality();
        $this->assertEqual($result, 1);
    }

    public function testInformationId3v2()
    {
        $Medium = new TestFfmpegAudioMedium($this->TestData->getFile('audio-mpeg.ID3v2.mp3'));

        $result = $Medium->artist();
        $this->assertEqual($result, 'Artist');

        $result = $Medium->title();
        $this->assertEqual($result, 'Title');

        $result = $Medium->album();
        $this->assertEqual($result, 'Album');

        $result = $Medium->year();
        $this->assertEqual($result, 2009);

        $result = $Medium->track();
        $this->assertEqual($result, 1);

        $result = $Medium->duration();
        $this->assertEqual($result, 1);

        $result = $Medium->bitRate();
        $this->assertEqual($result, 64000);

        $result = $Medium->samplingRate();
        $this->assertEqual($result, 24000);

        $result = $Medium->quality();
        $this->assertEqual($result, 1);
    }

    public function testInformationNotag()
    {
        $Medium = new TestFfmpegAudioMedium($this->TestData->getFile('audio-mpeg.notag.mp3'));

        $result = $Medium->artist();
        $this->assertEqual($result, null);

        $result = $Medium->title();
        $this->assertEqual($result, null);

        $result = $Medium->album();
        $this->assertEqual($result, null);

        $result = $Medium->year();
        $this->assertEqual($result, null);

        $result = $Medium->track();
        $this->assertEqual($result, null);

        $result = $Medium->duration();
        $this->assertEqual($result, 1);

        $result = $Medium->bitRate();
        $this->assertEqual($result, 64000);

        $result = $Medium->samplingRate();
        $this->assertEqual($result, 24000);

        $result = $Medium->quality();
        $this->assertEqual($result, 1);
    }

    public function testInformationVorbisComment()
    {
        $Medium = new TestFfmpegAudioMedium($this->TestData->getFile('audio-vorbis.comments.ogg'));

        $result = $Medium->artist();
        $this->assertEqual($result, 'Artist');

        $result = $Medium->title();
        $this->assertEqual($result, 'Title');

        $result = $Medium->album();
        $this->assertEqual($result, 'Album');

        $result = $Medium->year();
        $this->assertEqual($result, 2009);

        $result = $Medium->track();
        $this->assertEqual($result, 1);

        $result = $Medium->duration();
        $this->assertEqual($result, 1);

        $result = $Medium->bitRate();
        $this->assertEqual($result, 36666);

        $result = $Medium->samplingRate();
        $this->assertEqual($result, 24000);

        $result = $Medium->quality();
        $this->assertEqual($result, 1);
    }

    public function testInformationVorbisNotag()
    {
        $Medium = new TestFfmpegAudioMedium($this->TestData->getFile('audio-vorbis.notag.ogg'));

        $result = $Medium->artist();
        $this->assertEqual($result, null);

        $result = $Medium->title();
        $this->assertEqual($result, null);

        $result = $Medium->album();
        $this->assertEqual($result, null);

        $result = $Medium->year();
        $this->assertEqual($result, null);

        $result = $Medium->track();
        $this->assertEqual($result, null);

        $result = $Medium->duration();
        $this->assertEqual($result, 1);

        $result = $Medium->bitRate();
        $this->assertEqual($result, 36666);

        $result = $Medium->samplingRate();
        $this->assertEqual($result, 24000);

        $result = $Medium->quality();
        $this->assertEqual($result, 1);
    }
}
