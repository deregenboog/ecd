<?php
/**
 * Pear Mp3 Medium Adapter File.
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
 * Pear Mp3 Medium Adapter Class.
 *
 * @see       http://pear.php.net/package/MP3_Id
 */
class PearMp3MediumAdapter extends MediumAdapter
{
    public $require = [
        'mimeTypes' => ['audio/mpeg'],
        'imports' => [
            ['type' => 'Vendor', 'name' => 'MP3_Id', 'file' => 'MP3/Id.php'],
    ], ];

    public function initialize($Medium)
    {
        if (isset($Medium->objects['MP3_Id'])) {
            return true;
        }

        if (!isset($Medium->file)) {
            return false;
        }

        $Object = new MP3_Id();
        $Object->read($Medium->file);
        $Object->study();

        $Medium->objects['MP3_Id'] = &$Object;

        return true;
    }

    public function artist($Medium)
    {
        return $Medium->objects['MP3_Id']->getTag('artists');
    }

    public function title($Medium)
    {
        return $Medium->objects['MP3_Id']->getTag('name');
    }

    public function album($Medium)
    {
        return $Medium->objects['MP3_Id']->getTag('album');
    }

    public function year($Medium)
    {
        return $Medium->objects['MP3_Id']->getTag('year');
    }

    public function duration($Medium)
    {
        $duration = $Medium->objects['MP3_Id']->getTag('lengths');

        if ($duration != -1) {
            return $duration;
        }
    }

    public function track($Medium)
    {
        return $Medium->objects['MP3_Id']->getTag('track');
    }

    public function samplingRate($Medium)
    {
        return $Medium->objects['MP3_Id']->getTag('frequency');
    }

    public function bitRate($Medium)
    {
        if ($bitrate = $Medium->objects['MP3_Id']->getTag('bitrate')) {
            return $bitrate * 1000;
        }
    }
}
