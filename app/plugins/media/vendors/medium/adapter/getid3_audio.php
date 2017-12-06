<?php
/**
 * Getid3 Audio Medium Adapter File.
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
 * Getid3 Audio Medium Adapter Class.
 *
 * @see       http://getid3.sourceforge.net/
 */
class Getid3AudioMediumAdapter extends MediumAdapter
{
    public $require = [
        'mimeTypes' => [
            'application/ogg',
            'audio/ogg',
            'audio/mpeg',
            'audio/ms-wma',
            'audio/ms-asf',
            'audio/realaudio',
            'audio/pn-realaudio',
            'audio/pn-multirate-realaudio',
            'audio/wav',
            'audio/riff',
            'audio/wavpack',
            'audio/musepack', /* MPC */
            'audio/aac',
            'audio/mp4', /* AAC */
            'audio/m4a', /* AAC */
            'audio/m4b', /* AAC */
            'audio/ac3',
            'audio/aiff',
            'audio/ape',
            'audio/shorten',
            'audio/basic',
            'audio/midi',
            'audio/flac',
            'audio/voc',
            'audio/s3m',
            'audio/xm',
            'audio/it',
            'audio/mod',
            'audio/matroska',
            /* Not in freedesktop.org database */
            'audio/pac',
            'audio/bonk',
            'audio/dts',
            'audio/cda',
            /*
             * This is a reminder since audio Medium shouldn't have 'application/octet-stream'
             * MIME type.
             *
             * LA (Lossless Audio), OptimFROG, TTA, LiteWave,
             * RKAU, AVR (Audio Visual Research) and some Ogg files.
             */
            'application/octet-stream',
          ],
        'imports' => [
            ['type' => 'Vendor', 'name' => 'getID3', 'file' => 'getid3/getid3.php'],
        ],
        'extensions' => ['gd'],
    ];

    public function initialize($Medium)
    {
        if (isset($Medium->objects['getID3'])) {
            return true;
        }

        if (!isset($Medium->file)) {
            return false;
        }

        $Object = new getID3();
        $Object->encoding = 'UTF-8';
        $Object->analyze($Medium->file);

        if (isset($Object->info['error'])) {
            return false;
        }

        getid3_lib::CopyTagsToComments($Object->info);

        $Medium->objects['getID3'] = &$Object;

        return true;
    }

    public function artist($Medium)
    {
        if (isset($Medium->objects['getID3']->info['comments']['artist'][0])) {
            return $Medium->objects['getID3']->info['comments']['artist'][0];
        }
        if (isset($Medium->objects['getID3']->info['comments']['author'][0])) {
            return $Medium->objects['getID3']->info['comments']['author'][0];
        }
    }

    public function title($Medium)
    {
        if (isset($Medium->objects['getID3']->info['comments']['title'][0])) {
            return $Medium->objects['getID3']->info['comments']['title'][0];
        }
    }

    public function album($Medium)
    {
        if (isset($Medium->objects['getID3']->info['comments']['album'][0])) {
            return $Medium->objects['getID3']->info['comments']['album'][0];
        }
    }

    public function year($Medium)
    {
        foreach (['year', 'date', 'creation_date'] as $field) {
            if (!isset($Medium->objects['getID3']->info['comments'][$field][0])) {
                continue;
            }
            $date = $Medium->objects['getID3']->info['comments'][$field][0];

            if ('year' !== $field) {
                $date = strftime('%Y', strtotime($date));
            }
            if ($date) {
                return $date;
            }
        }
    }

    public function duration($Medium)
    {
        if (isset($Medium->objects['getID3']->info['playtime_seconds'])) {
            return $Medium->objects['getID3']->info['playtime_seconds'];
        }
    }

    public function track($Medium)
    {
        if (isset($Medium->objects['getID3']->info['comments']['track_number'][0])) {
            return $Medium->objects['getID3']->info['comments']['track_number'][0];
        }
        if (isset($Medium->objects['getID3']->info['comments']['tracknumber'][0])) {
            return $Medium->objects['getID3']->info['comments']['tracknumber'][0];
        }
    }

    public function samplingRate($Medium)
    {
        if (isset($Medium->objects['getID3']->info['audio']['sample_rate'])) {
            return $Medium->objects['getID3']->info['audio']['sample_rate'];
        }
    }

    public function bitRate($Medium)
    {
        if (isset($Medium->objects['getID3']->info['ogg']['bitrate_nominal'])) {
            return $Medium->objects['getID3']->info['ogg']['bitrate_nominal'];
        }
        if (isset($Medium->objects['getID3']->info['bitrate'])) {
            return $Medium->objects['getID3']->info['bitrate'];
        }
    }

    public function convert($Medium, $mimeType)
    {
        if ('Image' === Medium::name(null, $mimeType)) {
            $coverArt = $this->__coverArt($Medium);

            if (!$coverArt) {
                return false;
            }

            $resource = @imagecreatefromstring($coverArt);

            if (!is_resource($resource)) {
                return false;
            }

            $Image = Medium::factory(['gd' => $resource], 'image/gd');

            return $Image->convert($mimeType);
        }

        return false;
    }

    public function __coverArt($Medium)
    {
        if (!empty($Medium->objects['getID3']->info['id3v2']['APIC'][0]['data'])) {
            return $Medium->objects['getID3']->info['id3v2']['APIC'][0]['data'];
        }
        if (!empty($Medium->objects['getID3']->info['id3v2']['PIC'][0]['data'])) {
            return $Medium->objects['getID3']->info['id3v2']['PIC'][0]['data'];
        }
        if (!empty($Medium->objects['getID3']->info['flac']['PICTURE'][0]['image_data'])) {
            return $Medium->objects['getID3']->info['flac']['PICTURE'][0]['image_data'];
        }
        if (!empty($Medium->objects['getID3']->info['vorbiscomment']['coverart'][0])) {
            return base64_decode($Medium->objects['getID3']->info['vorbiscomment']['coverart'][0]);
        }

        return false;
    }
}
