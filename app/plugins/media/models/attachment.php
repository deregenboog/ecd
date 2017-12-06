<?php
/**
 * Attachment Model File.
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
 * Attachment Model Class.
 *
 * A ready-to-use model combining multiple behaviors.
 */
class Attachment extends MediaAppModel
{
    /**
     * Name of model.
     *
     * @var string
     */
    public $name = 'Attachment';
    /**
     * Name of table to use.
     *
     * @var mixed
     */
    public $useTable = 'attachments';
    /**
     * actsAs property.
     *
     * @var array
     */
    public $actsAs = [
        'Media.Polymorphic' => [
            'classField' => 'model',
            'foreignKey' => 'foreign_key',
        ],
        'Media.Transfer' => [
            'trustClient' => false,
            'destinationFile' => ':Medium.short::DS::Source.basename:',
            'baseDirectory' => MEDIA_TRANSFER,
            'createDirectory' => true,
            'alternativeFile' => 100,
        ],
        'Media.Media' => [
            'metadataLevel' => 2,
            'makeVersions' => true,
            'filterDirectory' => MEDIA_FILTER,
    ], ];
    /**
     * Validation rules for file and alternative fields.
     *
     * For more information on the rules used here
     * see the source of TransferBehavior and MediaBehavior or
     * the test case for MediaValidation.
     *
     * If you experience problems with your model not validating,
     * try commenting the mimeType rule or providing less strict
     * settings for single rules.
     *
     * `checkExtension()` and `checkMimeType()` take both a blacklist and
     * a whitelist. If you are on windows make sure that you addtionally
     * specify the `'tmp'` extension in case you are using a whitelist.
     *
     * @var array
     */
    public $validate = [
        'file' => [
            'resource' => ['rule' => 'checkResource'],
            'access' => ['rule' => 'checkAccess'],
            'location' => ['rule' => ['checkLocation', [
                MEDIA_TRANSFER, '/tmp/',
            ]]],
            'permission' => ['rule' => ['checkPermission', '*']],
            'size' => ['rule' => ['checkSize', '5M']],
            'pixels' => ['rule' => ['checkPixels', '1600x1600']],
            'extension' => ['rule' => ['checkExtension', false, [
                'jpg', 'jpeg', 'png', 'tif', 'tiff', 'gif', 'pdf', 'tmp',
            ]]],
            'mimeType' => ['rule' => ['checkMimeType', false, [
                'image/jpeg', 'image/png', 'image/tiff', 'image/gif', 'application/pdf',
        ]]], ],
        'alternative' => [
            'rule' => 'checkRepresent',
            'on' => 'create',
            'required' => false,
            'allowEmpty' => true,
        ], ];

    /**
     * beforeMake Callback.
     *
     * Called from within `MediaBehavior::make()`
     *
     * $process an array with the following contents:
     *	overwrite - If the destination file should be overwritten if it exists
     *	directory - The destination directory (guranteed to exist)
     *  name - Medium name of $file (e.g. `'Image'`)
     *	version - The version requested to be processed (e.g. `'xl'`)
     *	instructions - An array containing which names of methods to be called
     *
     * @param string $file    Absolute path to the source file
     * @param array  $process directory, version, name, instructions, overwrite
     *
     * @return bool True signals that the file has been processed,
     *              false or null signals that the behavior should process the file
     */
    public function beforeMake($file, $process)
    {
    }
}
