<?php
/**
 * Short description for file.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html>
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html CakePHP(tm) Tests
 * @since         CakePHP(tm) v 1.2.0.4667
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 */
class UuiditemsUuidportfolioNumericidFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'UuiditemsUuidportfolioNumericid'
     */
    public $name = 'UuiditemsUuidportfolioNumericid';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'key' => 'primary'],
        'uuiditem_id' => ['type' => 'string', 'length' => 36, 'null' => false],
        'uuidportfolio_id' => ['type' => 'string', 'length' => 36, 'null' => false],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['uuiditem_id' => '481fc6d0-b920-43e0-a40d-6d1740cf8569', 'uuidportfolio_id' => '4806e091-6940-4d2b-b227-303740cf8569'],
        ['uuiditem_id' => '48298a29-81c0-4c26-a7fb-413140cf8569', 'uuidportfolio_id' => '480af662-eb8c-47d3-886b-230540cf8569'],
        ['uuiditem_id' => '482b7756-8da0-419a-b21f-27da40cf8569', 'uuidportfolio_id' => '4806e091-6940-4d2b-b227-303740cf8569'],
        ['uuiditem_id' => '482cfd4b-0e7c-4ea3-9582-4cec40cf8569', 'uuidportfolio_id' => '4806e091-6940-4d2b-b227-303740cf8569'],
    ];
}
