<?php
/**
 * Short description for campaign_fixture.php.
 *
 * Long description for campaign_fixture.php
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 *
 * @see          http://www.cakephp.org
 * @since         1.2
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * CampaignFixture class.
 */
class CampaignFixture extends CakeTestFixture
{
    /**
     * name property.
     *
     * @var string 'Campaign'
     */
    public $name = 'Campaign';

    /**
     * fields property.
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer', 'key' => 'primary'],
        'name' => ['type' => 'string', 'length' => 255, 'null' => false],
    ];

    /**
     * records property.
     *
     * @var array
     */
    public $records = [
        ['name' => 'Hurtigruten'],
        ['name' => 'Colorline'],
        ['name' => 'Queen of Scandinavia'],
    ];
}
