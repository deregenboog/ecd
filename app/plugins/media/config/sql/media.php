<?php
/**
 * Media Schema File.
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
 * Media Schema Class.
 */
class MediaSchema extends CakeSchema
{
    /**
     * before.
     *
     * @param array $event
     */
    public function before($event = [])
    {
        return true;
    }

    /**
     * after.
     *
     * @param array $event
     */
    public function after($event = [])
    {
    }

    /**
     * attachments.
     *
     * @var array
     */
    public $attachments = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'extra' => 'auto_increment', 'length' => 10],
        'model' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255],
        'foreign_key' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 36],
        'dirname' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 255],
        'basename' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255],
        'checksum' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255],
        'group' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 255],
        'alternative' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 50],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
    ];
}
