<?php
/**
 * Video Medium File.
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
App::import('Vendor', 'Media.Medium');
/**
 * Video Medium Class.
 */
class VideoMedium extends Medium
{
    /**
     * Compatible adapters.
     *
     * @var array
     */
    public $adapters = ['Getid3Video', 'FfmpegVideo'];

    /**
     * Title stored in medium metadata.
     *
     * @return mixed String if metadata info exists, else null
     */
    public function title()
    {
        return $this->Adapters->dispatchMethod($this, 'title', null, [
            'normalize' => true,
        ]);
    }

    /**
     * Year stored in medium metadata.
     *
     * @return mixed Integer if metadata info exists, else null
     */
    public function year()
    {
        return $this->Adapters->dispatchMethod($this, 'year', null, [
            'normalize' => true,
        ]);
    }

    /**
     * Duration in seconds.
     *
     * @return int
     */
    public function duration()
    {
        return $this->Adapters->dispatchMethod($this, 'duration', null, [
            'normalize' => true,
        ]);
    }

    /**
     * Current height of medium.
     *
     * @return int
     */
    public function height()
    {
        return $this->Adapters->dispatchMethod($this, 'height', null, [
            'normalize' => true,
        ]);
    }

    /**
     * Current width of medium.
     *
     * @return int
     */
    public function width()
    {
        return $this->Adapters->dispatchMethod($this, 'width', null, [
            'normalize' => true,
        ]);
    }

    /**
     * Current bit rate of medium.
     *
     * @url http://en.wikipedia.org/wiki/Bit_rate
     *
     * @return int
     */
    public function bitRate()
    {
        return $this->Adapters->dispatchMethod($this, 'bitRate', null, [
            'normalize' => true,
        ]);
    }

    /**
     * Determines the quality of the medium by
     * taking definition and bit rate into account.
     *
     * @return int A number indicating quality between 1 (worst) and 5 (best)
     */
    public function quality()
    {
        $definition = $this->width() * $this->height();
        $bitRate = $this->bitRate();

        if (empty($definition) || empty($bitRate)) {
            return;
        }

        /* Normalized between 1 and 5 where min = 19200 and max = 414720 */
        $definitionMax = 720 * 576;
        $definitionMin = 160 * 120;
        $qualityMax = 5;
        $qualityMin = 1;

        if ($definition >= $definitionMax) {
            $quality = $qualityMax;
        } elseif ($definition <= $definitionMin) {
            $quality = $qualityMin;
        } else {
            $quality =
                (($definition - $definitionMin) / ($definitionMax - $definitionMin))
                * ($qualityMax - $qualityMin)
                + $qualityMin;
        }

        $bitRateCoef = 3;

        if ($bitRate <= 128000) {
            $quality = ($quality + $qualityMin * $bitRateCoef) / ($bitRateCoef + 1);
        } elseif ($bitRate <= 564000) {
            $quality = ($quality + $qualityMax * 2 / 5 * $bitRateCoef) / ($bitRateCoef + 1);
        } elseif ($bitRate <= 1152000) {
            $quality = ($quality + $qualityMax * 3 / 5 * $bitRateCoef) / ($bitRateCoef + 1);
        } elseif ($bitRate <= 2240000) {
            $quality = ($quality + $qualityMax * 4 / 5 * $bitRateCoef) / ($bitRateCoef + 1);
        } else {
            $quality = ($quality + $qualityMax * $bitRateCoef) / ($bitRateCoef + 1);
        }

        return (int) round($quality);
    }

    /**
     * Determines a (known) ratio of medium.
     *
     * @return mixed String if $known is true or float if false
     */
    public function ratio($known = true)
    {
        $width = $this->width();
        $height = $this->height();

        if (empty($width) || empty($height)) {
            return;
        }

        if (!$known) {
            return $width / $height;
        }

        return $this->_knownRatio($width, $height);
    }
}
