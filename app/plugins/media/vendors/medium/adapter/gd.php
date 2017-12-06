<?php
/**
 * Gd Medium Adapter File.
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
 * Gd Medium Adapter Class.
 */
class GdMediumAdapter extends MediumAdapter
{
    public $require = [
        'mimeTypes' => ['image/gd'], /* Gets dynamically set in constructor */
        'extensions' => ['gd'],
    ];

    public $_Image;

    public $_formatMap = [
        'image/jpeg' => 'jpeg',
        'image/gif' => 'gif',
        'image/png' => 'png',
        'image/gd' => 'gd',
        'image/vnd.wap.wbmp' => 'wbmp',
        'image/xbm' => 'xbm',
    ];

    public $_format;

    public $_compression;

    public $_pngFilter;

    public function compatible($Medium)
    {
        $types = imageTypes();
        if ($types & IMG_GIF) {
            $this->require['mimeTypes'][] = 'image/gif';
        }
        if ($types & IMG_JPG) {
            $this->require['mimeTypes'][] = 'image/jpeg';
        }
        if ($types & IMG_PNG) {
            $this->require['mimeTypes'][] = 'image/png';
        }
        if ($types & IMG_WBMP) {
            $this->require['mimeTypes'][] = 'image/wbmp';
        }
        if ($types & IMG_XPM) {
            $this->require['mimeTypes'][] = 'image/xpm';
        }

        return parent::compatible($Medium);
    }

    public function initialize($Medium)
    {
        $this->_format = $this->_formatMap[$Medium->mimeType];

        if (isset($Medium->resources['gd'])) {
            return true;
        }
        if (!isset($Medium->file)) {
            return false;
        }

        $Medium->resources['gd'] = call_user_func_array(
            'imageCreateFrom'.$this->_format,
            [$Medium->file]
        );

        if (!$this->_isResource($Medium->resources['gd'])) {
            return false;
        }

        if (imageIsTrueColor($Medium->resources['gd'])) {
            imageAlphaBlending($Medium->resources['gd'], false);
            imageSaveAlpha($Medium->resources['gd'], true);
        }

        return true;
    }

    public function toString($Medium)
    {
        ob_start();
        $this->store($Medium, null);

        return ob_get_clean();
    }

    public function store($Medium, $file)
    {
        $args = [$Medium->resources['gd'], $file];

        switch ($Medium->mimeType) {
            case 'image/jpeg':
                if (isset($this->_compression)) {
                    $args[] = $this->_compression;
                }
                break;

            case 'image/png':
                if (isset($this->_compression)) {
                    $args[] = $this->_compression;

                    if (isset($this->_pngFilter)) {
                        $args[] = $this->_pngFilter;
                    }
                }
                break;
        }

        return call_user_func_array('image'.$this->_format, $args);
    }

    public function convert($Medium, $mimeType)
    {
        if (in_array($mimeType, $this->require['mimeTypes'])) {
            return $this->_format = $this->_formatMap[$mimeType];
        }

        return false;
    }

    public function compress($Medium, $value)
    {
        switch ($Medium->mimeType) {
            case 'image/jpeg':
                $this->_compression = (int) (100 - ($value * 10));
                break;

            case 'image/png':
                if (version_compare(PHP_VERSION, '5.1.2', '>=')) {
                    $this->_compression = (int) $value;
                }
                if (version_compare(PHP_VERSION, '5.1.3', '>=')) {
                    $filter = ($value * 10) % 10;
                    $map = [
                        0 => PNG_FILTER_NONE,
                        1 => PNG_FILTER_SUB,
                        2 => PNG_FILTER_UP,
                        3 => PNG_FILTER_AVG,
                        4 => PNG_FILTER_PAETH,
                    ];

                    if (array_key_exists($filter, $map)) {
                        $this->_pngFilter = $map[$filter];
                    } elseif (5 == $filter) {
                        if (intval($value) <= 5 && imageIsTrueColor($Medium->resources['gd'])) {
                            $this->_pngFilter = PNG_ALL_FILTERS;
                        } else {
                            $this->_pngFilter = PNG_NO_FILTER;
                        }
                    } else {
                        $this->_pngFilter = PNG_ALL_FILTERS;
                    }
                }
                break;
        }

        return true;
    }

    public function crop($Medium, $left, $top, $width, $height)
    {
        $left = (int) $left;
        $top = (int) $top;
        $width = (int) $width;
        $height = (int) $height;

        $Image = imageCreateTrueColor($width, $height);
        $this->_adjustTransparency($Medium->resources['gd'], $Image);

        if ($this->_isTransparent($Medium->resources['gd'])) {
            imageCopyResized(
                $Image,
                $Medium->resources['gd'],
                0, 0,
                $left, $top,
                $width, $height,
                $width, $height
            );
        } else {
            imageCopyResampled(
                $Image,
                $Medium->resources['gd'],
                0, 0,
                $left, $top,
                $width, $height,
                $width, $height
            );
        }
        if ($this->_isResource($Image)) {
            $Medium->resources['gd'] = $Image;

            return true;
        }

        return false;
    }

    public function resize($Medium, $width, $height)
    {
        $width = (int) $width;
        $height = (int) $height;

        $Image = imageCreateTrueColor($width, $height);
        $this->_adjustTransparency($Medium->resources['gd'], $Image);

        if ($this->_isTransparent($Medium->resources['gd'])) {
            imageCopyResized(
                $Image,
                $Medium->resources['gd'],
                0, 0,
                0, 0,
                $width, $height,
                $this->width($Medium), $this->height($Medium)
            );
        } else {
            imageCopyResampled(
                $Image,
                $Medium->resources['gd'],
                0, 0,
                0, 0,
                $width, $height,
                $this->width($Medium), $this->height($Medium)
            );
        }
        if ($this->_isResource($Image)) {
            $Medium->resources['gd'] = $Image;

            return true;
        }

        return false;
    }

    public function cropAndResize($Medium, $cropLeft, $cropTop, $cropWidth, $cropHeight, $resizeWidth, $resizeHeight)
    {
        $cropLeft = (int) $cropLeft;
        $cropTop = (int) $cropTop;
        $cropWidth = (int) $cropWidth;
        $cropHeight = (int) $cropHeight;
        $resizeWidth = (int) $resizeWidth;
        $resizeHeight = (int) $resizeHeight;

        $Image = imageCreateTrueColor($resizeWidth, $resizeHeight);
        $this->_adjustTransparency($Medium->resources['gd'], $Image);

        if ($this->_isTransparent($Medium->resources['gd'])) {
            imageCopyResized(
                $Image,
                $Medium->resources['gd'],
                0, 0,
                $cropLeft, $cropTop,
                $resizeWidth, $resizeHeight,
                $cropWidth, $cropHeight
            );
        } else {
            imageCopyResampled(
                $Image,
                $Medium->resources['gd'],
                0, 0,
                $cropLeft, $cropTop,
                $resizeWidth, $resizeHeight,
                $cropWidth, $cropHeight
            );
        }
        if ($this->_isResource($Image)) {
            $Medium->resources['gd'] = $Image;

            return true;
        }

        return false;
    }

    public function width($Medium)
    {
        return imageSX($Medium->resources['gd']);
    }

    public function height($Medium)
    {
        return imageSY($Medium->resources['gd']);
    }

    public function _isResource($Image)
    {
        return is_resource($Image) && 'gd' == get_resource_type($Image);
    }

    public function _isTransparent($Image)
    {
        return imageColorTransparent($Image) >= 0;
    }

    public function _adjustTransparency(&$Source, &$Destination)
    {
        if ($this->_isTransparent($Source)) {
            $rgba = imageColorsForIndex($Source, imageColorTransparent($Source));
            $color = imageColorAllocate($Destination, $rgba['red'], $rgba['green'], $rgba['blue']);
            imageColorTransparent($Destination, $color);
            imageFill($Destination, 0, 0, $color);
        } else {
            if ('png' == $this->_format) {
                imageAlphaBlending($Destination, false);
                imageSaveAlpha($Destination, true);
            } elseif ('gif' != $this->_format) {
                $white = imageColorAllocate($Destination, 255, 255, 255);
                imageFill($Destination, 0, 0, $white);
            }
        }
    }
}
