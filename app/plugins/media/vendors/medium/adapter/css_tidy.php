<?php
/**
 * Css Tidy Medium Adapter File.
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
 * Css Tidy Medium Adapter Class.
 *
 * @see       http://csstidy.sourceforge.net/
 */
class CssTidyMediumAdapter extends MediumAdapter
{
    public $require = [
        'mimeTypes' => ['text/css'],
        'extensions' => ['ctype'],
        'imports' => [
            ['type' => 'Vendor', 'name' => 'csstidy', 'file' => 'csstidy/class.csstidy.php'], ],
    ];

    public $_template = 'high_compression'; // or: highest_compression

    public function initialize($Medium)
    {
        if (!isset($Medium->contents['raw']) && isset($Medium->file)) {
            return $Medium->contents['raw'] = file_get_contents($Medium->file);
        }

        return true;
    }

    public function store($Medium, $file)
    {
        return file_put_contents($Medium->contents['raw'], $file);
    }

    public function compress($Medium)
    {
        $Tidy = new csstidy();
        $Tidy->load_template($this->_template);
        $Tidy->parse($Medium->contents['raw']);

        if ($compressed = $Tidy->print->plain()) {
            $Medium->content['raw'] = $compressed;

            return true;
        }

        return false;
    }
}
