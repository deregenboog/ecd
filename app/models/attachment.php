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
 * @see	   http://github.com/davidpersson/media
 */
/**
 * Attachment Model Class.
 *
 * A ready-to-use model combining multiple behaviors.
 */
class Attachment extends AppModel
{
    // <editor-fold defaultstate="collapsed" desc="Group constants">
    /**
     * Attachments for the Maatschappelijk Werk.
     */
    const GROUP_MW = 'mw';
    const GROUP_PFO = 'pfo';
    const GROUP_BTO = 'bto';

    /**
     * Attachments for the Hi5.
     */
    const GROUP_HI5 = 'hi5';
    // </editor-fold>

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

    //for transactions
    public $transactional = true;

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
                // Use a UUID for the file name of a transferred file. Without
                // it, files like
                // http://t1.gstatic.com/images?q=tbn:JmRds8IibpPWZM:http://i299.photobucket.com/albums/mm311/arhitecturamoderna/madrid_airport_hg.jpg&t=1
                // and
                // http://t2.gstatic.com/images?q=tbn:35NWVkTNcxu_EM:http://www.bmw.gotik-romanik.de/BMW%20Welt%20Thumbnails/BMW%20Welt%20von%20Thomas%20Rieger,%204.jpg&t=1
                // are both saved at transfer/gen/images, overwriting each
                // other.
                // We can not use just :uuid:_:Source.extension:, because in
                // the given examples there's no extension and the media plugin
                // fails in putting there an empty one (it Set::filter all the
                // markers, for some reason I cannot understand, so that the
                // Source.extension is undefined!). We use Source.basename
                // instead, despite it produces long file names for some
                // images.
                'destinationFile' => ':Medium.short::DS::year:-:month::DS::uuid:_:Source.basename:',
                'baseDirectory' => MEDIA_TRANSFER,
                'createDirectory' => true,
                'alternativeFile' => 100,
                ],
            'Media.Media' => [
                'metadataLevel' => 2,
                'makeVersions' => true,
                'filterDirectory' => MEDIA_FILTER,
                ],
            ];
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
            'resource' => ['rule' => 'checkResource', 'message' => 'No resources!'],
            'access' => ['rule' => 'checkAccess', 'message' => 'No access!'],
            'location' => ['rule' => ['checkLocation', [
                MEDIA_TRANSFER, '/tmp/',
            ]], 'message' => 'There is a problem with the location of the file!'],
            'permission' => ['rule' => ['checkPermission', '*'], 'message' => 'You are not authorized to upload this file'],
            'size' => ['rule' => ['checkSize', '9M'], 'message' => 'Your size exceeds our limit of :maxSize'],
            'extension' => ['rule' => ['checkExtension', false, [
                'doc', 'odt', 'txt', 'pdf', 'jpg', 'docx', 'xlsx', 'jpeg', 'png',
            ]], 'message' => 'This file extension is not allowed!'],
            'mimeType' => ['rule' => ['checkMimeType', false, [
                'application/doc',
                'application/vnd.msword',
                'application/vnd.ms-word',
                'application/winword',
                'application/word',
                'application/x-msw6',
                'application/x-msword',
                'application/msword',
                'application/vnd.ms-office',
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'application/vnd.oasis.opendocument.text',
                'application/x-vnd.oasis.opendocument.text',
                'application/zip',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/pdf',
                'application/x-pdf',
                'application/acrobat',
                'applications/vnd.pdf',
                'text/pdf',
                'text/x-pdf',

                'text/plain',
        ]], 'message' => 'This MIME Type is not allowed!'], ],
        'alternative' => [
            'rule' => 'checkRepresent',
            'on' => 'create',
            'required' => false,
            'allowEmpty' => true,
        ], ];

    public function __construct($id = false, $table = null, $ds = null)
    {
        $this->validate['file']['size']['message'] = __tr(
            $this->validate['file']['size']['message'],
            ['maxSize' => Configure::read('attachment.max_size')]
        );
        $this->validate['file']['size']['rule'][1] = Configure::read('attachment.max_size');

        parent::__construct($id, $table, $ds);
    }

    /**
     * beforeMake Callback.
     *
     * Called from within `MediaBehavior::make()`
     *
     * $process an array with the following contents:
     *	overwrite - If the destination file should be overwritten if it exists
     *	directory - The destination directory (guranteed to exist)
     *	name - Medium name of $file (e.g. `'Image'`)
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

    /**
     * Gets the file path for the attachment id.
     *
     * @param string $id      Id of the attachment
     * @param string $version Version of the attachment
     *
     * @return string
     */
    public function getPathForId($id = null, $version = null)
    {
        if (null == $id) {
            return 'wrong path!';
        }
        //retrieve the file record from the DB
        $file_record = $this->find('first', [
            'conditions' => [
                'Attachment.id' => $id,
            ],
        ]);

        //check if the file exists in the database
        if (!$file_record) {
            return null;
        }

        //retrieve the file path
        $dir_name = $file_record['Attachment']['dirname'];
        $filename = $file_record['Attachment']['basename'];
        if (empty($version)) {
            $file_path = MEDIA.$dir_name.'/'.$filename;
        } else {
            //the file type is changed by the filter to png
            //we need to change the extension in the basename
            //we asume the versioning is only done for images
            //and that media plugin changes the period characters to underscores
            //we can also switch off the conversion in plugins/media/config/core.php
            $config = Configure::read('Media.filter.image');
            if (isset($config[$version]['convert'])) {
                $ext = explode('/', $config[$version]['convert']);
                $name = explode('.', $filename);
                $filename = $name[0].'.'.$ext[1];
            }
            $file_path = MEDIA.'filter/'.$version.'/'.$dir_name.'/'.
                $filename;
        }

        return $file_path;
    }

    //view()

    /**
     * Returns the controller that is associated to the group.
     *
     * @param string $group Group id
     */
    public function groupToController($group)
    {
        switch ($group) {
            case self::GROUP_MW:
                return 'maatschappelijk_werk';
            case self::GROUP_HI5:
                return 'Hi5';
            default:
                //if you get here add a new group in the switch
                assert(false);
        }
    }
}
