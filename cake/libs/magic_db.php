<?php
/**
 * MagicDb parser and file analyzer.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 1.2.0
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (!class_exists('Object')) {
    require LIBS.'object.php';
}
if (!class_exists('File')) {
    require LIBS.'file.php';
}

/**
 * A class to parse and use the MagicDb for file type analysis.
 */
class MagicDb extends Object
{
    /**
     * Holds the parsed MagicDb for this class instance.
     *
     * @var array
     */
    public $db = [];

    /**
     * Reads a MagicDb from various formats.
     *
     * @var mixed Can be an array containing the db, a magic db as a string, or a filename pointing to a magic db in .db or magic.db.php format
     *
     * @return bool returns false if reading / validation failed or true on success
     *
     * @author        Felix
     */
    public function read($magicDb = null)
    {
        if (!is_string($magicDb) && !is_array($magicDb)) {
            return false;
        }
        if (is_array($magicDb) || 0 === strpos($magicDb, '# FILE_ID DB')) {
            $data = $magicDb;
        } else {
            $File = new File($magicDb);
            if (!$File->exists()) {
                return false;
            }
            if ('php' == $File->ext()) {
                include $File->pwd();
                $data = $magicDb;
            } else {
                // @TODO: Needs test coverage
                $data = $File->read();
            }
        }

        $magicDb = $this->toArray($data);
        if (!$this->validates($magicDb)) {
            return false;
        }

        return (bool) ($this->db = $magicDb);
    }

    /**
     * Parses a MagicDb $data string into an array or returns the current MagicDb instance as an array.
     *
     * @param string $data A MagicDb string to turn into an array
     *
     * @return array A parsed MagicDb array or an empty array if the $data param was invalid. Returns the db property if $data is not set.
     */
    public function toArray($data = null)
    {
        if (is_array($data)) {
            return $data;
        }
        if (null === $data) {
            return $this->db;
        }

        if (0 !== strpos($data, '# FILE_ID DB')) {
            return [];
        }

        $lines = explode("\r\n", $data);
        $db = [];

        $validHeader = count($lines) > 3
                    && preg_match('/^# Date:([0-9]{4}-[0-9]{2}-[0-9]{2})$/', $lines[1], $date)
                    && preg_match('/^# Source:(.+)$/', $lines[2], $source)
                    && 0 == strlen($lines[3]);
        if (!$validHeader) {
            return $db;
        }

        $db = ['header' => ['Date' => $date[1], 'Source' => $source[1]], 'database' => []];
        $lines = array_splice($lines, 3);

        $format = [];
        while (!empty($lines)) {
            $line = array_shift($lines);
            if (isset($line[0]) && '#' == $line[0] || empty($line)) {
                continue;
            }

            $columns = explode("\t", $line);
            if (in_array($columns[0][0], ['>', '&'])) {
                $format[] = $columns;
            } elseif (!empty($format)) {
                $db['database'][] = $format;
                $format = [$columns];
            } else {
                $format = [$columns];
            }
        }

        return $db;
    }

    /**
     * Returns true if the MagicDb instance or the passed $magicDb is valid.
     *
     * @param mixed $magicDb A $magicDb string / array to validate (optional)
     *
     * @return bool True if the $magicDb / instance db validates, false if not
     */
    public function validates($magicDb = null)
    {
        if (is_null($magicDb)) {
            $magicDb = $this->db;
        } elseif (!is_array($magicDb)) {
            $magicDb = $this->toArray($magicDb);
        }

        return isset($magicDb['header'], $magicDb['database']) && is_array($magicDb['header']) && is_array($magicDb['database']);
    }

    /**
     * Analyzes a given $file using the currently loaded MagicDb information based on the desired $options.
     *
     * @param string $file    Absolute path to the file to analyze
     * @param array  $options TBT
     *
     * @return mixed
     */
    public function analyze($file, $options = [])
    {
        if (!is_string($file)) {
            return false;
        }

        $matches = [];
        $MagicFileResource = new MagicFileResource($file);
        foreach ($this->db['database'] as $format) {
            $magic = $format[0];
            $match = $MagicFileResource->test($magic);
            if (false === $match) {
                continue;
            }
            $matches[] = $magic;
        }

        return $matches;
    }
}

/**
 * MagicFileResource.
 */
class MagicFileResource extends Object
{
    /**
     * undocumented variable.
     *
     * @var unknown
     */
    public $resource = null;

    /**
     * undocumented variable.
     *
     * @var unknown
     */
    public $offset = 0;

    /**
     * undocumented function.
     *
     * @param unknown $file
     */
    public function __construct($file)
    {
        if (file_exists($file)) {
            $this->resource = new File($file);
        } else {
            $this->resource = $file;
        }
    }

    /**
     * undocumented function.
     *
     * @param unknown $magic
     */
    public function test($magic)
    {
        $offset = null;
        $type = null;
        $expected = null;
        $comment = null;
        if (isset($magic[0])) {
            $offset = $magic[0];
        }
        if (isset($magic[1])) {
            $type = $magic[1];
        }
        if (isset($magic[2])) {
            $expected = $magic[2];
        }
        if (isset($magic[3])) {
            $comment = $magic[3];
        }
        $val = $this->extract($offset, $type, $expected);

        return $val == $expected;
    }

    /**
     * undocumented function.
     *
     * @param unknown $type
     * @param unknown $length
     */
    public function read($length = null)
    {
        if (!is_object($this->resource)) {
            return substr($this->resource, $this->offset, $length);
        }

        return $this->resource->read($length);
    }

    /**
     * undocumented function.
     *
     * @param unknown $type
     * @param unknown $expected
     */
    public function extract($offset, $type, $expected)
    {
        switch ($type) {
            case 'string':
                $this->offset($offset);
                $val = $this->read(strlen($expected));
                if ($val === $expected) {
                    return true;
                }
                break;
        }
    }

    /**
     * undocumented function.
     *
     * @param unknown $offset
     * @param unknown $whence
     */
    public function offset($offset = null)
    {
        if (is_null($offset)) {
            if (!is_object($this->resource)) {
                return $this->offset;
            }

            return $this->offset;
        }

        if (!ctype_digit($offset)) {
            return false;
        }
        if (is_object($this->resource)) {
            $this->resource->offset($offset);
        } else {
            $this->offset = $offset;
        }
    }
}
