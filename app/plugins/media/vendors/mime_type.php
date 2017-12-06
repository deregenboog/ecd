<?php
/**
 * Mime Type File.
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
uses('file');
/**
 * Mime Type Class.
 *
 * Detection of a file's MIME type by it's contents and/or extension.
 * This is the main interface for MIME type detection wrapping
 * (native) magic and glob mechanisms.
 */
class MimeType extends Object
{
    /**
     * Magic.
     *
     * @var mixed An instance of the MimeMagic or finfo class or a string containing 'mime_magic'
     */
    public $__magic;
    /**
     * Glob.
     *
     * @var object An instance of the MimeGlob class
     */
    public $__glob;

    /**
     * Return a singleton instance of MimeType.
     *
     * @return object MimeType instance
     */
    public function &getInstance()
    {
        static $instance = [];

        if (!$instance) {
            $instance[0] = new self();
            $instance[0]->__loadMagic(Configure::read('Mime.magic'));
            $instance[0]->__loadGlob(Configure::read('Mime.glob'));
        }

        return $instance[0];
    }

    /**
     * Change configuration during runtime.
     *
     * @param string $property Either "magic" or "glob"
     * @param array  $config   Config specifying engine and db
     *                         e.g. array('engine' => 'fileinfo', 'db' => '/etc/magic')
     */
    public function config($property = 'magic', $config = [])
    {
        $_this = &self::getInstance();

        if ('magic' === $property) {
            $_this->__loadMagic($config);
        } elseif ('glob' === $property) {
            $_this->__loadGlob($config);
        }
    }

    /**
     * Guesses the extension (suffix) for an existing file or a MIME type.
     *
     * @param string $file    A MIME type or an absolute path to file
     * @param array  $options Currently not used
     *
     * @return mixed A string with the first matching extension (w/o leading dot),
     *               false if nothing matched
     */
    public function guessExtension($file, $options = [])
    {
        $_this = &self::getInstance();
        $globMatch = [];
        $preferred = [
            'bz2', 'css', 'doc', 'html', 'jpg',
            'mpeg', 'mp3', 'ogg', 'php', 'ps',
            'rm', 'ra', 'rv', 'swf', 'tar',
            'tiff', 'txt', 'xhtml', 'xml',
        ];

        if (is_file($file)) {
            $mimeType = $_this->guessType($file);
        } else {
            $mimeType = $file;
        }

        if (is_a($_this->__glob, 'MimeGlob')) {
            $globMatch = $_this->__glob->analyze($mimeType, true);
        }

        if (1 === count($globMatch)) {
            return array_shift($globMatch);
        }

        $preferMatch = array_intersect($globMatch, $preferred);

        if (1 === count($preferMatch)) {
            return array_shift($preferMatch);
        }

        return null;
    }

    /**
     * Guesses the MIME type of the file.
     *
     * Empty results are currently not handled:
     * 	application/x-empty
     * 	application/x-not-regular-file
     *
     * @param string  $file
     * @param options $options Valid options are:
     *                         - `'paranoid'` If set to true only then content for the file is used for detection
     *                         - `'properties'` Used for simplification, defaults to false
     *                         - `'experimental'` Used for simplification, defaults to false
     *
     * @return mixed string with MIME type on success
     */
    public function guessType($file, $options = [])
    {
        $_this = &self::getInstance();

        $defaults = [
            'paranoid' => false,
            'properties' => false,
            'experimental' => true,
        ];
        extract($options + $defaults);

        $magicMatch = $globMatch = [];

        if (!$paranoid) {
            if (is_a($_this->__glob, 'MimeGlob')) {
                $globMatch = $_this->__glob->analyze($file);
            }
            if (1 === count($globMatch)) {
                return self::simplify(array_shift($globMatch), $properties, $experimental);
            }
        }

        if (!is_readable($file)) {
            return null;
        }

        if (is_a($_this->__magic, 'finfo')) {
            $magicMatch = $_this->__magic->file($file);
        } elseif ('mime_magic' === $_this->__magic) {
            $magicMatch = mime_content_type($file);
        } elseif (is_a($_this->__magic, 'MimeMagic')) {
            $magicMatch = $_this->__magic->analyze($file);
        }
        $magicMatch = empty($magicMatch) ? [] : [$magicMatch];

        if (empty($magicMatch)) {
            $File = new File($file);

            if (preg_match('/[\t\n\r]+/', $File->read(32))) {
                return 'text/plain';
            }

            return 'application/octet-stream';
        }

        if (1 === count($magicMatch)) {
            return self::simplify(array_shift($magicMatch), $properties, $experimental);
        }

        if ($globMatch && $magicMatch) {
            $combinedMatch = array_intersect($globMatch, $magicMatch);

            if (1 === count($combinedMatch)) {
                return self::simplify(array_shift($combinedMatch), $properties, $experimental);
            }
        }

        return null;
    }

    /**
     * Simplifies a MIME type string.
     *
     * @param string $mimeType
     * @param bool If true removes properties
     * @param bool If true removes experimental indicators
     *
     * @return string
     */
    public function simplify($mimeType, $properties = false, $experimental = false)
    {
        if (!$experimental) {
            $mimeType = str_replace('x-', null, $mimeType);
        }

        if (!$properties) {
            if (false !== strpos($mimeType, ';')) {
                $mimeType = strtok($mimeType, ';');
            } else {
                $mimeType = strtok($mimeType, ' ');
            }
        }

        return $mimeType;
    }

    /**
     * Sets magic property.
     *
     * @param array $config Configuration settings to take into account
     */
    public function __loadMagic($config = [])
    {
        $engine = $db = null;

        if (is_array($config)) {
            extract($config, EXTR_OVERWRITE);
        }

        if (('fileinfo' === $engine || null === $engine) && extension_loaded('fileinfo')) {
            if (isset($db)) {
                $this->__magic = new finfo(FILEINFO_MIME, $db);
            } else {
                $this->__magic = new finfo(FILEINFO_MIME);
            }
        } elseif (('mime_magic' === $engine || null === $engine) && extension_loaded('mime_magic')) {
            $this->__magic = 'mime_magic';
        } elseif ('core' === $engine || null === $engine) {
            App::import('Vendor', 'Media.MimeMagic');

            if ($cached = Cache::read('mime_magic_db', '_cake_core_')) {
                $db = $cached;
            }

            if (!isset($db)) {
                $db = $this->__db('magic');
            }
            if (isset($db)) {
                $this->__magic = new MimeMagic($db);

                if (!$cached) {
                    Cache::write('mime_magic_db', $this->__magic->toArray(), '_cake_core_');
                }
            }
        } else {
            $this->__magic = null;
        }
    }

    /**
     * Sets glob property.
     *
     * @param array $config Configuration settings to take into account
     */
    public function __loadGlob($config = [])
    {
        $engine = $db = null;

        if (is_array($config)) {
            extract($config, EXTR_OVERWRITE);
        }

        if ('core' === $engine || null === $engine) {
            App::import('Vendor', 'Media.MimeGlob');

            if ($cached = Cache::read('mime_glob_db', '_cake_core_')) {
                $db = $cached;
            }

            if (!isset($db)) {
                $db = $this->__db('glob');
            }
            if (isset($db)) {
                $this->__glob = new MimeGlob($db);

                if (!$cached) {
                    Cache::write('mime_glob_db', $this->__glob->toArray(), '_cake_core_');
                }
            }
        } else {
            $this->__glob = null;
        }
    }

    /**
     * Finds the db file for given type.
     *
     * @param string $type Either 'magic' or 'glob'
     *
     * @return mixed If no file was found null otherwise the absolute path to the file
     */
    public function __db($type)
    {
        $searchPaths = [
            'mime_'.$type.'.php' => [CONFIGS],
            'mime_'.$type.'.db' => array_merge(
                App::path('vendors'),
                [dirname(__FILE__).DS]
            ), ];

        foreach ($searchPaths as $basename => $paths) {
            foreach ($paths as $path) {
                if (is_readable($path.$basename)) {
                    return $path.$basename;
                }
            }
        }
    }
}
