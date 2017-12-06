<?php
/**
 * ImagickShell Medium Adapter File.
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
 * ImagickShell Medium Adapter Class.
 *
 * @see       http://www.imagemagick.org/
 */
class ImagickShellMediumAdapter extends MediumAdapter
{
    public $require = [
        'mimeTypes' => [/* readable */
            'image/jpeg',
            'image/gif',
            'image/png',
            'image/tiff',
            'image/wpg',
            'image/xbm',
            'image/xcf',
            'image/wbmp',
            'image/ms-bmp',
            'image/pcx',
            'image/quicktime',
            'image/svg',
            'image/xpm',
            'image/ico',
            'image/psd',
            ],
         'commands' => ['convert', 'identify'],
    ];

    public $_formatMap = [/* writable */
        'image/jpeg' => 'jpeg',
        'image/gif' => 'gif',
        'image/png' => 'png',
        'image/tiff' => 'tiff',
        'image/wbmp' => 'wbmp',
        'image/ms-bmp' => 'bmp',
        'image/pcx' => 'pcx',
        'image/ico' => 'ico',
        'image/xbm' => 'xbm',
        'image/psd' => 'psd',
    ];

    public $_temporaryFormat = 'png';
    public $_compressionType;
    public $_compression;
    public $_pngFilter;

    public function compatible($Medium)
    {
        if ($this->_which(['gs', 'gswin32c'])) { /* Check for ghostscript */
            $this->require['mimeTypes'][] = 'application/pdf';
        }

        return parent::compatible($Medium);
    }

    public function initialize($Medium)
    {
        if (!isset($Medium->files['temporary'])) {
            if (!isset($Medium->file)) {
                return false;
            }
            $Medium->files['temporary'] = TMP.uniqid('medium_');
        }

        return $this->_execute(':command: :source: :format:::destination:', [
            'command' => 'convert',
            'source' => $Medium->file,
            'destination' => $Medium->files['temporary'],
            'format' => $this->_temporaryFormat,
         ]);
    }

    public function store($Medium, $file)
    {
        $args = [
            'command' => 'convert',
            'source' => $Medium->files['temporary'],
            'sourceFormat' => $this->_temporaryFormat,
            'destination' => $file,
            'format' => $this->_formatMap[$Medium->mimeType],
        ];

        if (isset($this->_compressionType)) {
            $args['compress'] = $this->_compressionType;
        }
        if (isset($this->_compression)) {
            if ('ZIP' === $this->_compressionType) {
                $args['quality'] = $this->_compression * 10 + $this->_pngFilter;
            } else {
                $args['quality'] = $this->_compression;
            }
        }

        return $this->_execute(':command:'
                                .(isset($args['compress']) ? ' -compress :compress:' : '')
                                .(isset($args['quality']) ? ' -quality :quality:' : '')
                                .' :sourceFormat:::source: :format:::destination:', $args);
    }

    public function convert($Medium, $mimeType)
    {
        if (!isset($this->_formatMap[$mimeType])) {
            return false;
        }

        $Medium->mimeType = $mimeType;

        if ('Document' === $Medium->name) { // application/pdf -> image
            $this->store($Medium, $Medium->files['temporary']);

            /* Unset files to prevent too early deletion by $Medium */
            $temporary = $Medium->files['temporary'];
            unset($Medium->files);

            return Medium::factory(['temporary' => $temporary], $mimeType);
        }

        return true;
    }

    public function compress($Medium, $value)
    {
        switch ($Medium->mimeType) {
            case 'image/tiff':
                $this->_compressionType = 'LZW';
                break;
            case 'image/png':
                $this->_compressionType = 'ZIP';
                $this->_compression = (int) $value;
                $this->_pngFilter = ($value * 10) % 10;
                break;
            case 'image/jpeg':
                $this->_compressionType = 'JPEG';
                $this->_compression = (int) (100 - ($value * 10));
                break;
        }

        return true;
    }

    public function crop($Medium, $left, $top, $width, $height)
    {
        return $this->_execute(':command: -crop :width:x:height:+:left:+:top: :source: :destination:', [
            'command' => 'convert',
            'width' => (int) $width,
            'height' => (int) $height,
            'left' => (int) $left,
            'top' => (int) $top,
            'source' => $Medium->files['temporary'],
            'destination' => $Medium->files['temporary'],
        ]);
    }

    public function resize($Medium, $width, $height)
    {
        return $this->_execute(':command: -geometry :width:x:height:! :source: :destination:', [
            'command' => 'convert',
            'width' => (int) $width,
            'height' => (int) $height,
            'source' => $Medium->files['temporary'],
            'destination' => $Medium->files['temporary'],
        ]);
    }

    public function cropAndResize($Medium, $cropLeft, $cropTop, $cropWidth, $cropHeight, $resizeWidth, $resizeHeight)
    {
        return 	$this->crop($Medium, $cropLeft, $cropTop, $cropWidth, $cropHeight)
                && $this->resize($Medium, $resizeWidth, $resizeHeight);

        /* This is faster but broken: convert: geometry does not contain image `xxxx.jpg'.
        return $this->_execute(':command: -crop :cropWidth:x:cropHeight:+:cropLeft:+:cropTop: -geometry :resizeWidth:x:resizeHeight:! :source: :destination:',
                                array(
                                      'command'      => 'convert',
                                      'cropLeft'     => intval($cropLeft),
                                      'cropTop'      => intval($cropTop),
                                      'cropWidth'    => intval($cropWidth),
                                      'cropHeight'   => intval($cropHeight),
                                      'resizeWidth'  => intval($resizeWidth),
                                      'resizeHeight' => intval($resizeHeight),
                                      'source'       => $Medium->files['temporary'],
                                      'destination'  => $Medium->files['temporary'],
                                     )
                                 );
        */
    }

    public function width($Medium)
    {
        return $this->_execute(':command: -format %w :file:', [
            'command' => 'identify',
            'file' => $Medium->files['temporary'],
        ]);
    }

    public function height($Medium)
    {
        return $this->_execute(':command: -format %h :file:', [
            'command' => 'identify',
             'file' => $Medium->files['temporary'],
        ]);
    }
}
