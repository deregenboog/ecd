<?php
/**
 * Css Medium File.
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
 * Css Medium Class.
 */
class CssMedium extends Medium
{
    /**
     * Compatible adapters.
     *
     * @var array
     */
    public $adapters = ['CssTidy', 'BasicText'];

    /**
     * Number of characters.
     *
     * @return int
     */
    public function characters()
    {
        return $this->Adapters->dispatchMethod($this, 'characters', null, [
            'normalize' => true,
        ]);
    }

    /**
     * Compresses contents. of the medium.
     *
     * @return string
     */
    public function compress()
    {
        return $this->Adapters->dispatchMethod($this, 'compress');
    }
}
