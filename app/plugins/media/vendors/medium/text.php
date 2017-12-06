<?php
/**
 * Text Medium File.
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
 * Text Medium Class.
 */
class TextMedium extends Medium
{
    /**
     * Compatible adapters.
     *
     * @var array
     */
    public $adapters = ['PearText', 'BasicText'];

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
     * Flesch Score.
     *
     * @return float
     */
    public function fleschScore()
    {
        return round($this->Adapters->dispatchMethod($this, 'fleschScore', null, [
            'normalize' => false,
        ]), 2);
    }

    /**
     * Lexical Density in percent.
     *
     * 40- 50 low (easy to read)
     * 60- 70 high (hard to read)
     *
     * @see http://www.usingenglish.com/glossary/lexical-density-test.html
     *
     * @return int
     */
    public function lexicalDensity()
    {
        return $this->Adapters->dispatchMethod($this, 'lexicalDensity', null, [
            'normalize' => true,
        ]);
    }

    /**
     * Number of sentences.
     *
     * @return int
     */
    public function sentences()
    {
        return $this->Adapters->dispatchMethod($this, 'sentences', null, [
            'normalize' => true,
        ]);
    }

    /**
     * Number of syllables.
     *
     * @return int
     */
    public function syllables()
    {
        return $this->Adapters->dispatchMethod($this, 'syllables', null, [
            'normalize' => true,
        ]);
    }

    /**
     * Truncate to given amount of characters.
     *
     * @param int $characters
     *
     * @return string
     */
    public function truncate($characters)
    {
        return $this->Adapters->dispatchMethod($this, 'truncate', [$characters]);
    }

    /**
     * Number of words.
     *
     * @param bool $unique
     *
     * @return int
     */
    public function words($unique = false)
    {
        return $this->Adapters->dispatchMethod($this, 'words', null, [
            'normalize' => true,
        ]);
    }
}
