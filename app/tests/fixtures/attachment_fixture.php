<?php

/* Attachment Fixture generated on: 2011-11-18 11:11:24 : 1321612944 */
class AttachmentFixture extends CakeTestFixture
{
    public $name = 'Attachment';

    public $fields = array(
        'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
        'model' => array('type' => 'string', 'null' => false, 'default' => null),
        'foreign_key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36),
        'dirname' => array('type' => 'string', 'null' => true, 'default' => null),
        'basename' => array('type' => 'string', 'null' => false, 'default' => null),
        'checksum' => array('type' => 'string', 'null' => false, 'default' => null),
        'group' => array('type' => 'string', 'null' => true, 'default' => null),
        'alternative' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'),
    );

    public $records = array(
        array(
            'id' => '4ec63690-1c48-435a-887a-6c4c8e3aec7a',
            'model' => 'Lorem ipsum dolor sit amet',
            'foreign_key' => 'Lorem ipsum dolor sit amet',
            'dirname' => 'Lorem ipsum dolor sit amet',
            'basename' => 'Lorem ipsum dolor sit amet',
            'checksum' => 'Lorem ipsum dolor sit amet',
            'group' => 'Lorem ipsum dolor sit amet',
            'alternative' => 'Lorem ipsum dolor sit amet',
            'created' => '2011-11-18 11:42:24',
            'modified' => '2011-11-18 11:42:24',
        ),
    );
}
