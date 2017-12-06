<?php
/**
 * Ffmpeg Audio Medium Adapter File.
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
/**
 * Ffmpeg Audio Medium Adapter Class.
 *
 * @see       http://ffmpeg.mplayerhq.hu/
 */
class FfmpegAudioMediumAdapter extends MediumAdapter
{
    public $require = [
        'mimeTypes' => [
            'audio/mpeg',
            /* Ffmpeg Extension can't read meta info other than ID3! */
            'audio/ms-wma',
            'audio/realaudio',
            'audio/wav',
            'audio/ogg',
            /* Some Ogg files may have 'application/octet-stream' MIME type. */
            'application/octet-stream',
        ],
        'extensions' => ['ffmpeg'],
    ];

    public function initialize($Medium)
    {
        if (isset($Medium->objects['ffmpeg_movie'])) {
            return true;
        }
        if (!isset($Medium->file)) {
            return false;
        }

        $Medium->objects['ffmpeg_movie'] = new ffmpeg_movie($Medium->file);

        if (!$Medium->objects['ffmpeg_movie']->hasAudio()) {
            return false;
        }

        return true;
    }

    public function artist($Medium)
    {
        return $Medium->objects['ffmpeg_movie']->getArtist();
    }

    public function title($Medium)
    {
        return $Medium->objects['ffmpeg_movie']->getTitle();
    }

    public function album($Medium)
    {
        return $Medium->objects['ffmpeg_movie']->getAlbum();
    }

    public function year($Medium)
    {
        return $Medium->objects['ffmpeg_movie']->getYear();
    }

    public function duration($Medium)
    {
        return $Medium->objects['ffmpeg_movie']->getDuration();
    }

    public function track($Medium)
    {
        return $Medium->objects['ffmpeg_movie']->getTrackNumber();
    }

    public function samplingRate($Medium)
    {
        return $Medium->objects['ffmpeg_movie']->getAudioSampleRate();
    }

    public function bitRate($Medium)
    {
        return $Medium->objects['ffmpeg_movie']->getBitRate();
    }
}
