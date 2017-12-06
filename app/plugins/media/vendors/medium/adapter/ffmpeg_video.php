<?php
/**
 * Ffmpeg Video Medium Adapter File.
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
 * Ffmpeg Video Medium Adapter Class.
 *
 * @see       http://ffmpeg.mplayerhq.hu/
 */
class FfmpegVideoMediumAdapter extends MediumAdapter
{
    public $require = [
        'mimeTypes' => [
            'video/mpeg',
            'video/mswmv',
            'video/msasf',
            'video/msvideo',
            'video/quicktime',
            'video/flv',
            'video/ogg',
        ],
        'extensions' => ['ffmpeg', 'gd'],
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

        return true;
    }

    public function convert($Medium, $mimeType)
    {
        if ('Image' === Medium::name(null, $mimeType)) {
            $randomFrame = rand(1, $Medium->objects['ffmpeg_movie']->getFrameCount() - 1);
            $resource = $Medium->objects['ffmpeg_movie']->getFrame($randomFrame)->toGDImage();

            if (!is_resource($resource)) {
                return false;
            }

            $Image = Medium::factory(['gd' => $resource], 'image/gd');

            return $Image->convert($mimeType);
        }

        return false;
    }

    public function title($Medium)
    {
        return $Medium->objects['ffmpeg_movie']->getTitle();
    }

    public function year($Medium)
    {
        return $Medium->objects['ffmpeg_movie']->getYear();
    }

    public function duration($Medium)
    {
        return $Medium->objects['ffmpeg_movie']->getDuration();
    }

    public function width($Medium)
    {
        return $Medium->objects['ffmpeg_movie']->getFrameWidth();
    }

    public function height($Medium)
    {
        return $Medium->objects['ffmpeg_movie']->getFrameHeight();
    }

    public function bitRate($Medium)
    {
        return $Medium->objects['ffmpeg_movie']->getBitRate();
    }
}
