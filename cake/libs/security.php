<?php
/**
 * Core Security.
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
 * @since         CakePHP(tm) v .0.10.0.1233
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Security Library contains utility methods related to security.
 */
class Security extends Object
{
    /**
     * Default hash method.
     *
     * @var string
     */
    public $hashType = null;

    /**
     * Singleton implementation to get object instance.
     *
     * @return object
     * @static
     */
    public static function &getInstance()
    {
        static $instance = [];
        if (!$instance) {
            $instance[0] = new self();
        }

        return $instance[0];
    }

    /**
     * Get allowed minutes of inactivity based on security level.
     *
     * @return int Allowed inactivity in minutes
     * @static
     */
    public static function inactiveMins()
    {
        switch (Configure::read('Security.level')) {
            case 'high':
                return 10;
            break;
            case 'medium':
                return 100;
            break;
            case 'low':
            default:
                return 300;
                break;
        }
    }

    /**
     * Generate authorization hash.
     *
     * @return string Hash
     * @static
     */
    public static function generateAuthKey()
    {
        if (!class_exists('String')) {
            App::import('Core', 'String');
        }

        return self::hash(String::uuid());
    }

    /**
     * Validate authorization hash.
     *
     * @param string $authKey Authorization hash
     *
     * @return bool Success
     * @static
     *
     * @todo Complete implementation
     */
    public function validateAuthKey($authKey)
    {
        return true;
    }

    /**
     * Create a hash from string using given method.
     * Fallback on next available method.
     *
     * @param string $string String to hash
     * @param string $type   Method to use (sha1/sha256/md5)
     * @param bool   $salt   If true, automatically appends the application's salt
     *                       value to $string (Security.salt)
     *
     * @return string Hash
     * @static
     */
    public static function hash($string, $type = null, $salt = false)
    {
        $_this = &self::getInstance();

        if ($salt) {
            if (is_string($salt)) {
                $string = $salt.$string;
            } else {
                $string = Configure::read('Security.salt').$string;
            }
        }

        if (empty($type)) {
            $type = $_this->hashType;
        }
        $type = strtolower($type);

        if ('sha1' == $type || null == $type) {
            if (function_exists('sha1')) {
                $return = sha1($string);

                return $return;
            }
            $type = 'sha256';
        }

        if ('sha256' == $type && function_exists('mhash')) {
            return bin2hex(mhash(MHASH_SHA256, $string));
        }

        if (function_exists('hash')) {
            return hash($type, $string);
        }

        return md5($string);
    }

    /**
     * Sets the default hash method for the Security object.  This affects all objects using
     * Security::hash().
     *
     * @param string $hash Method to use (sha1/sha256/md5)
     * @static
     *
     * @see Security::hash()
     */
    public static function setHash($hash)
    {
        $_this = &self::getInstance();
        $_this->hashType = $hash;
    }

    /**
     * Encrypts/Decrypts a text using the given key.
     *
     * @param string $text Encrypted string to decrypt, normal string to encrypt
     * @param string $key  Key to use
     *
     * @return string Encrypted/Decrypted string
     * @static
     */
    public function cipher($text, $key)
    {
        if (empty($key)) {
            trigger_error(__('You cannot use an empty key for Security::cipher()', true), E_USER_WARNING);

            return '';
        }

        srand(Configure::read('Security.cipherSeed'));
        $out = '';
        $keyLength = strlen($key);
        for ($i = 0, $textLength = strlen($text); $i < $textLength; ++$i) {
            $j = ord(substr($key, $i % $keyLength, 1));
            while ($j--) {
                rand(0, 255);
            }
            $mask = rand(0, 255);
            $out .= chr(ord(substr($text, $i, 1)) ^ $mask);
        }
        srand();

        return $out;
    }
}
