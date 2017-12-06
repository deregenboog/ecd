<?php
/**
 * Sync Task File.
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
 * Sync Task Class.
 */
class SyncTask extends MediaShell
{
    /**
     * model.
     *
     * @var string
     */
    public $model;
    /**
     * Directory.
     *
     * @var string
     */
    public $directory;
    /**
     * Default answer to use if prompted for input.
     *
     * @var string
     */
    public $_answer = 'n';
    /**
     * Model.
     *
     * @var Model
     */
    public $_Model;
    /**
     * baseDirectory from the model's media behavior settings.
     *
     * @var string
     */
    public $_baseDirectory;
    /**
     * Folder to search.
     *
     * @var Folder
     */
    public $_Folder;
    /**
     * Current item retrieved from the model.
     *
     * @var array
     */
    public $__dbItem;
    /**
     * Current item retrieved from the filesystem.
     *
     * @var array
     */
    public $__fsItem;
    /**
     * Current set of items retrieved from the model.
     *
     * @var array
     */
    public $__dbMap;
    /**
     * Current set of items retrieved from the filesystem.
     *
     * @var array
     */
    public $__fsMap;
    /**
     * Current file object.
     *
     * @var File
     */
    public $__File;
    /**
     * An alternative for the current file.
     *
     * @var mixed
     */
    public $__alternativeFile;

    /**
     * Main execution method.
     *
     * @return bool
     */
    public function execute()
    {
        $this->_answer = isset($this->params['auto']) ? 'y' : 'n';
        $this->model = array_shift($this->args);
        $this->directory = array_shift($this->args);

        if (!isset($this->model)) {
            $this->model = $this->in('Name of model:', null, 'Media.Attachment');
        }
        if (!isset($this->directory)) {
            $this->directory = $this->in('Directory to search:', null, MEDIA_TRANSFER);
        }

        $this->_Model = ClassRegistry::init($this->model);

        if (!isset($this->_Model->Behaviors->Media)) {
            $this->err('MediaBehavior is not attached to Model');

            return false;
        }
        $this->_baseDirectory = $this->_Model->Behaviors->Media->settings[$this->_Model->alias]['baseDirectory'];
        $this->_Folder = new Folder($this->directory);
        $this->interactive = isset($this->model, $this->directory);

        if ($this->interactive) {
            $input = $this->in('Interactive?', 'y/n', 'y');

            if ('n' == $input) {
                $this->interactive = false;
            }
        }

        $this->out();
        $this->out(sprintf('%-25s: %s', 'Model', $this->_Model->name));
        $this->out(sprintf('%-25s: %s', 'Search directory', $this->shortPath($this->_Folder->pwd())));
        $this->out(sprintf('%-25s: %s', 'Automatic repair', 'y' == $this->_answer ? 'yes' : 'no'));

        if ('n' == $this->in('Looks OK?', 'y,n', 'y')) {
            return false;
        }
        $this->_Model->Behaviors->disable('Media');
        $this->_checkFilesWithRecords();
        $this->_checkRecordsWithFiles();
        $this->_Model->Behaviors->enable('Media');
        $this->out();

        return true;
    }

    /**
     * Checks if files are in sync with records.
     */
    public function _checkFilesWithRecords()
    {
        $this->out();
        $this->out('Checking if files are in sync with records');
        $this->hr();

        list($this->__fsMap, $this->__dbMap) = $this->_generateMaps();

        foreach ($this->__dbMap as $dbItem) {
            $message = sprintf(
                '%-60s -> %s/%s',
                $this->shortPath($dbItem['file']),
                $this->_Model->name, $dbItem['id']
            );
            $this->out();
            $this->out($message);

            $this->__dbItem = $dbItem;
            $this->__File = new File($dbItem['file']);
            $this->__alternativeFile = $this->_findByChecksum($dbItem['checksum'], $this->__fsMap);

            if ($this->_findByFile($this->__alternativeFile, $this->__dbMap)) {
                $this->__alternativeFile = false;
            }

            if ($this->_handleNotReadable()) {
                continue;
            }
            if ($this->_handleOrphanedRecord()) {
                continue;
            }
            if ($this->_handleChecksumMismatch()) {
                continue;
            }
        }
    }

    /**
     * Checks if records are in sync with files.
     */
    public function _checkRecordsWithFiles()
    {
        $this->out();
        $this->out('Checking if records are in sync with files');
        $this->hr();

        list($this->__fsMap, $this->__dbMap) = $this->_generateMaps();

        foreach ($this->__fsMap as $fsItem) {
            $message = sprintf(
                '%-60s <- %s/%s',
                $this->shortPath($fsItem['file']),
                $this->_Model->name,
                '?'
            );
            $this->out();
            $this->out($message);

            $this->__File = new File($fsItem['file']);
            $this->__fsItem = $fsItem;

            if ($this->_handleOrphanedFile()) {
                continue;
            }
        }
    }

    /* handle methods */

    /**
     * Handles existent but not readable files.
     *
     * @return mixed
     */
    public function _handleNotReadable()
    {
        if (!$this->__File->readable() && $this->__File->exists()) {
            $this->out('File exists but is not readable');

            return true;
        }
    }

    /**
     * Handles orphaned records.
     *
     * @return mixed
     */
    public function _handleOrphanedRecord()
    {
        if ($this->__File->exists()) {
            return;
        }
        $this->out('Orphaned');

        if ($this->_fixWithAlternative()) {
            return true;
        }
        if ($this->_fixDeleteRecord()) {
            return true;
        }

        return false;
    }

    /**
     * Handles mismatching checksums.
     *
     * @return mixed
     */
    public function _handleChecksumMismatch()
    {
        if ($this->__dbItem['checksum'] == $this->__File->md5(true)) {
            return;
        }
        $this->out('Checksums mismatch');

        if ($this->_fixWithAlternative()) {
            return true;
        }
        $input = $this->in('Correct the checksum of the record?', 'y,n', $this->_answer);

        if ('y' == $input) {
            $data = [
                'id' => $this->__dbItem['id'],
                'checksum' => $this->__File->md5(true),
            ];
            $this->_Model->save($data);
            $this->out('Corrected checksum');

            return true;
        }

        if ($this->_fixDeleteRecord()) {
            return true;
        }
    }

    /**
     * Handles orphaned files.
     *
     * @return mixed
     */
    public function _handleOrphanedFile()
    {
        if ($this->_findByFile($this->__fsItem['file'], $this->__dbMap)) {
            return;
        }
        $this->out('Orphaned');

        $input = $this->in('Delete file?', 'y,n', $this->_answer);

        if ('y' == $input) {
            $File->delete();
            $this->out('File deleted');

            return true;
        }
    }

    /* fix methods */

    /**
     * Updates a record with an alternative file.
     *
     * @return bool
     */
    public function _fixWithAlternative()
    {
        if (!$this->__alternativeFile) {
            return false;
        }
        $message = sprintf(
            'This file has an identical checksum: %s',
            $this->shortPath($this->__alternativeFile)
        );
        $this->out($message);
        $input = $this->in('Select this file and update record?', 'y,n', $this->_answer);

        if ('n' == $input) {
            return false;
        }

        $data = [
            'id' => $this->__dbItem['id'],
            'dirname' => dirname(str_replace($this->_baseDirectory, '', $this->__alternativeFile)),
            'basename' => basename($this->__alternativeFile),
        ];
        $this->_Model->save($data);
        $this->out('Corrected dirname and basename');

        return true;
    }

    /**
     * Deletes current record.
     *
     * @return booelan
     */
    public function _fixDeleteRecord()
    {
        $input = $this->in('Delete record?', 'y,n', $this->_answer);

        if ('y' == $input) {
            $this->_Model->delete($this->__dbItem['id']);
            $this->out('Record deleted');

            return true;
        }

        return false;
    }

    /* map related methods */

    /**
     * Generates filesystem and model maps.
     */
    public function _generateMaps()
    {
        $fsFiles = $this->_Folder->findRecursive();
        $results = $this->_Model->find('all');
        $fsMap = [];
        $dbMap = [];

        foreach ($fsFiles as $value) {
            $File = new File($value);
            $fsMap[] = [
                'file' => $File->pwd(),
                'checksum' => $File->md5(true),
            ];
        }
        foreach ($results as $result) {
            $dbMap[] = [
                'id' => $result[$this->_Model->name]['id'],
                'file' => $this->_baseDirectory
                        .$result[$this->_Model->name]['dirname']
                        .DS.$result[$this->_Model->name]['basename'],
                'checksum' => $result[$this->_Model->name]['checksum'],
            ];
        }

        return [$fsMap, $dbMap];
    }

    /**
     * Finds an item's file by it's checksum.
     *
     * @param string $checksum
     * @param array  $map      Map to use as a haystack
     *
     * @return mixed
     */
    public function _findByChecksum($checksum, $map)
    {
        foreach ($map as $item) {
            if ($checksum == $item['checksum']) {
                return $item['file'];
            }
        }

        return false;
    }

    /**
     * Finds an item's file by it's name.
     *
     * @param string $file
     * @param array  $map  Map to use as a haystack
     *
     * @return mixed
     */
    public function _findByFile($file, $map)
    {
        foreach ($map as $item) {
            if ($file == $item['file']) {
                return $item['file'];
            }
        }

        return false;
    }
}
