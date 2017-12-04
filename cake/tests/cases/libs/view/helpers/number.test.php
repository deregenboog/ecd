<?php
/**
 * NumberHelperTest file.
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
 * @since         CakePHP(tm) v 1.2.0.4206
 *
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
App::import('Helper', 'Number');

/**
 * NumberHelperTest class.
 */
class NumberHelperTest extends CakeTestCase
{
    /**
     * helper property.
     *
     * @var mixed null
     */
    public $helper = null;

    /**
     * setUp method.
     */
    public function startTest()
    {
        $this->Number = new NumberHelper();
    }

    /**
     * tearDown method.
     */
    public function endTest()
    {
        unset($this->Number);
    }

    /**
     * testFormatAndCurrency method.
     */
    public function testFormat()
    {
        $value = '100100100';

        $result = $this->Number->format($value, '#');
        $expected = '#100,100,100';
        $this->assertEqual($expected, $result);

        $result = $this->Number->format($value, 3);
        $expected = '100,100,100.000';
        $this->assertEqual($expected, $result);

        $result = $this->Number->format($value);
        $expected = '100,100,100';
        $this->assertEqual($expected, $result);

        $result = $this->Number->format($value, '-');
        $expected = '100-100-100';
        $this->assertEqual($expected, $result);
    }

    /**
     * Test currency method.
     */
    public function testCurrency()
    {
        $value = '100100100';

        $result = $this->Number->currency($value);
        $expected = '$100,100,100.00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, '#');
        $expected = '#100,100,100.00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, false);
        $expected = '100,100,100.00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'USD');
        $expected = '$100,100,100.00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'EUR');
        $expected = '&#8364;100.100.100,00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'GBP');
        $expected = '&#163;100,100,100.00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, '', ['thousands' => ' ', 'after' => '€', 'decimals' => ',', 'zero' => 'Gratuit']);
        $expected = '100 100 100,00€';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency(1000.45, null, ['after' => 'øre', 'before' => 'Kr. ', 'decimals' => ',', 'thousands' => '.']);
        $expected = 'Kr. 1.000,45';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency(0.5, 'USD');
        $expected = '50c';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency(0.5, null, ['after' => 'øre']);
        $expected = '50øre';
        $this->assertEqual($expected, $result);
    }

    /**
     * Test adding currency format options to the number helper.
     */
    public function testCurrencyAddFormat()
    {
        $this->Number->addFormat('NOK', ['before' => 'Kr. ']);
        $result = $this->Number->currency(1000, 'NOK');
        $expected = 'Kr. 1,000.00';
        $this->assertEqual($expected, $result);

        $this->Number->addFormat('Other', ['before' => '$$ ', 'after' => 'c!']);
        $result = $this->Number->currency(0.22, 'Other');
        $expected = '22c!';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency(-10, 'Other');
        $expected = '($$ 10.00)';
        $this->assertEqual($expected, $result);

        $this->Number->addFormat('Other2', ['before' => '$ ']);
        $result = $this->Number->currency(0.22, 'Other2');
        $expected = '$ 0.22';
        $this->assertEqual($expected, $result);
    }

    /**
     * testCurrencyPositive method.
     */
    public function testCurrencyPositive()
    {
        $value = '100100100';

        $result = $this->Number->currency($value);
        $expected = '$100,100,100.00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'USD', ['before' => '#']);
        $expected = '#100,100,100.00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, false);
        $expected = '100,100,100.00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'USD');
        $expected = '$100,100,100.00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'EUR');
        $expected = '&#8364;100.100.100,00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'GBP');
        $expected = '&#163;100,100,100.00';
        $this->assertEqual($expected, $result);
    }

    /**
     * testCurrencyNegative method.
     */
    public function testCurrencyNegative()
    {
        $value = '-100100100';

        $result = $this->Number->currency($value);
        $expected = '($100,100,100.00)';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'EUR');
        $expected = '(&#8364;100.100.100,00)';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'GBP');
        $expected = '(&#163;100,100,100.00)';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'USD', ['negative' => '-']);
        $expected = '-$100,100,100.00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'EUR', ['negative' => '-']);
        $expected = '-&#8364;100.100.100,00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'GBP', ['negative' => '-']);
        $expected = '-&#163;100,100,100.00';
        $this->assertEqual($expected, $result);
    }

    /**
     * testCurrencyCentsPositive method.
     */
    public function testCurrencyCentsPositive()
    {
        $value = '0.99';

        $result = $this->Number->currency($value, 'USD');
        $expected = '99c';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'EUR');
        $expected = '&#8364;0,99';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'GBP');
        $expected = '99p';
        $this->assertEqual($expected, $result);
    }

    /**
     * testCurrencyCentsNegative method.
     */
    public function testCurrencyCentsNegative()
    {
        $value = '-0.99';

        $result = $this->Number->currency($value, 'USD');
        $expected = '(99c)';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'EUR');
        $expected = '(&#8364;0,99)';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'GBP');
        $expected = '(99p)';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'USD', ['negative' => '-']);
        $expected = '-99c';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'EUR', ['negative' => '-']);
        $expected = '-&#8364;0,99';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'GBP', ['negative' => '-']);
        $expected = '-99p';
        $this->assertEqual($expected, $result);
    }

    /**
     * testCurrencyZero method.
     */
    public function testCurrencyZero()
    {
        $value = '0';

        $result = $this->Number->currency($value, 'USD');
        $expected = '$0.00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'EUR');
        $expected = '&#8364;0,00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'GBP');
        $expected = '&#163;0.00';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'GBP', ['zero' => 'FREE!']);
        $expected = 'FREE!';
        $this->assertEqual($expected, $result);
    }

    /**
     * testCurrencyOptions method.
     */
    public function testCurrencyOptions()
    {
        $value = '1234567.89';

        $result = $this->Number->currency($value, null, ['before' => 'GBP']);
        $expected = 'GBP1,234,567.89';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'GBP', ['places' => 0]);
        $expected = '&#163;1,234,568';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency('1234567.8912345', null, ['before' => 'GBP', 'places' => 3]);
        $expected = 'GBP1,234,567.891';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency('650.120001', null, ['before' => 'GBP', 'places' => 4]);
        $expected = 'GBP650.1200';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency($value, 'GBP', ['escape' => true]);
        $expected = '&amp;#163;1,234,567.89';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency('0.35', 'USD', ['after' => false]);
        $expected = '$0.35';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency('0.35', 'GBP', ['after' => false]);
        $expected = '&#163;0.35';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency('0.35', 'GBP');
        $expected = '35p';
        $this->assertEqual($expected, $result);

        $result = $this->Number->currency('0.35', 'EUR');
        $expected = '&#8364;0,35';
        $this->assertEqual($expected, $result);
    }

    /**
     * testToReadableSize method.
     */
    public function testToReadableSize()
    {
        $result = $this->Number->toReadableSize(0);
        $expected = '0 Bytes';
        $this->assertEqual($expected, $result);

        $result = $this->Number->toReadableSize(1);
        $expected = '1 Byte';
        $this->assertEqual($expected, $result);

        $result = $this->Number->toReadableSize(45);
        $expected = '45 Bytes';
        $this->assertEqual($expected, $result);

        $result = $this->Number->toReadableSize(1023);
        $expected = '1023 Bytes';
        $this->assertEqual($expected, $result);

        $result = $this->Number->toReadableSize(1024);
        $expected = '1 KB';
        $this->assertEqual($expected, $result);

        $result = $this->Number->toReadableSize(1024 * 512);
        $expected = '512 KB';
        $this->assertEqual($expected, $result);

        $result = $this->Number->toReadableSize(1024 * 1024 - 1);
        $expected = '1.00 MB';
        $this->assertEqual($expected, $result);

        $result = $this->Number->toReadableSize(1024 * 1024 * 512);
        $expected = '512.00 MB';
        $this->assertEqual($expected, $result);

        $result = $this->Number->toReadableSize(1024 * 1024 * 1024 - 1);
        $expected = '1.00 GB';
        $this->assertEqual($expected, $result);

        $result = $this->Number->toReadableSize(1024 * 1024 * 1024 * 512);
        $expected = '512.00 GB';
        $this->assertEqual($expected, $result);

        $result = $this->Number->toReadableSize(1024 * 1024 * 1024 * 1024 - 1);
        $expected = '1.00 TB';
        $this->assertEqual($expected, $result);

        $result = $this->Number->toReadableSize(1024 * 1024 * 1024 * 1024 * 512);
        $expected = '512.00 TB';
        $this->assertEqual($expected, $result);

        $result = $this->Number->toReadableSize(1024 * 1024 * 1024 * 1024 * 1024 - 1);
        $expected = '1024.00 TB';
        $this->assertEqual($expected, $result);

        $result = $this->Number->toReadableSize(1024 * 1024 * 1024 * 1024 * 1024 * 1024);
        $expected = (1024 * 1024).'.00 TB';
        $this->assertEqual($expected, $result);
    }

    /**
     * testToPercentage method.
     */
    public function testToPercentage()
    {
        $result = $this->Number->toPercentage(45, 0);
        $expected = '45%';
        $this->assertEqual($result, $expected);

        $result = $this->Number->toPercentage(45, 2);
        $expected = '45.00%';
        $this->assertEqual($result, $expected);

        $result = $this->Number->toPercentage(0, 0);
        $expected = '0%';
        $this->assertEqual($result, $expected);

        $result = $this->Number->toPercentage(0, 4);
        $expected = '0.0000%';
        $this->assertEqual($result, $expected);
    }
}
