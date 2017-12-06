<?php
/**
 * Document Medium File.
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
 * Document Medium Class.
 */
class DocumentMedium extends Medium
{
    /**
     * Compatible adapters.
     *
     * @var array
     */
    public $adapters = ['Imagick', 'ImagickShell'];

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
     * Determines a (known) ratio of medium.
     *
     * @return mixed if String if $known is true or float if false
     */
    public function ratio($known = true)
    {
        if (!$known) {
            return $this->width() / $this->height();
        }

        return $this->_knownRatio($this->width(), $this->height());
    }
}
