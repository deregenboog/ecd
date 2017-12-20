<?php
/**
 * XmlHelperTest file.
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
if (!defined('CAKEPHP_UNIT_TEST_EXECUTION')) {
    define('CAKEPHP_UNIT_TEST_EXECUTION', 1);
}
App::import('Helper', 'Xml');

/**
 * TestXml class.
 */
class TestXml extends Object
{
    /**
     * content property.
     *
     * @var string ''
     */
    public $content = '';

    /**
     * construct method.
     *
     * @param mixed $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * toString method.
     */
    public function toString()
    {
        return $this->content;
    }
}

/**
 * XmlHelperTest class.
 */
class XmlHelperTest extends CakeTestCase
{
    /**
     * setUp method.
     */
    public function setUp()
    {
        $this->Xml = new XmlHelper();
        $this->Xml->beforeRender();
        $manager = &XmlManager::getInstance();
        $manager->namespaces = [];
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        unset($this->Xml);
    }

    /**
     * testAddNamespace method.
     */
    public function testAddNamespace()
    {
        $this->Xml->addNs('custom', 'http://example.com/dtd.xml');
        $manager = &XmlManager::getInstance();

        $expected = ['custom' => 'http://example.com/dtd.xml'];
        $this->assertEqual($manager->namespaces, $expected);
    }

    /**
     * testRemoveNamespace method.
     */
    public function testRemoveNamespace()
    {
        $this->Xml->addNs('custom', 'http://example.com/dtd.xml');
        $this->Xml->addNs('custom2', 'http://example.com/dtd2.xml');
        $manager = &XmlManager::getInstance();

        $expected = ['custom' => 'http://example.com/dtd.xml', 'custom2' => 'http://example.com/dtd2.xml'];
        $this->assertEqual($manager->namespaces, $expected);

        $this->Xml->removeNs('custom');
        $expected = ['custom2' => 'http://example.com/dtd2.xml'];
        $this->assertEqual($manager->namespaces, $expected);
    }

    /**
     * testRenderZeroElement method.
     */
    public function testRenderZeroElement()
    {
        $result = $this->Xml->elem('count', null, 0);
        $expected = '<count>0</count>';
        $this->assertEqual($result, $expected);

        $result = $this->Xml->elem('count', null, ['cdata' => true, 'value' => null]);
        $expected = '<count />';
        $this->assertEqual($result, $expected);
    }

    /**
     * testRenderElementWithNamespace method.
     */
    public function testRenderElementWithNamespace()
    {
        $result = $this->Xml->elem('count', ['namespace' => 'myNameSpace'], 'content');
        $expected = '<myNameSpace:count>content</myNameSpace:count>';
        $this->assertEqual($result, $expected);

        $result = $this->Xml->elem('count', ['namespace' => 'myNameSpace'], 'content', false);
        $expected = '<myNameSpace:count>content';
        $this->assertEqual($result, $expected);

        $expected .= '</myNameSpace:count>';
        $result .= $this->Xml->closeElem();
        $this->assertEqual($result, $expected);
    }

    /**
     * testRenderElementWithComplexContent method.
     */
    public function testRenderElementWithComplexContent()
    {
        $result = $this->Xml->elem('count', ['namespace' => 'myNameSpace'], ['contrived' => 'content']);
        $expected = '<myNameSpace:count><content /></myNameSpace:count>';
        $this->assertEqual($result, $expected);

        $result = $this->Xml->elem('count', ['namespace' => 'myNameSpace'], ['cdata' => true, 'value' => 'content']);
        $expected = '<myNameSpace:count><![CDATA[content]]></myNameSpace:count>';
        $this->assertEqual($result, $expected);
    }

    /**
     * testSerialize method.
     */
    public function testSerialize()
    {
        $data = [
            'test1' => 'test with no quotes',
            'test2' => 'test with "double quotes"',
        ];
        $result = $this->Xml->serialize($data);
        $expected = '<std_class test1="test with no quotes" test2="test with &quot;double quotes&quot;" />';
        $this->assertIdentical($result, $expected);

        $data = [
            'test1' => 'test with no quotes',
            'test2' => 'test without double quotes',
        ];
        $result = $this->Xml->serialize($data);
        $expected = '<std_class test1="test with no quotes" test2="test without double quotes" />';
        $this->assertIdentical($result, $expected);

        $data = [
            'ServiceDay' => ['ServiceTime' => ['ServiceTimePrice' => ['dollar' => 1, 'cents' => '2']]],
        ];
        $result = $this->Xml->serialize($data);
        $expected = '<service_day><service_time><service_time_price dollar="1" cents="2" /></service_time></service_day>';
        $this->assertIdentical($result, $expected);

        $data = [
            'ServiceDay' => ['ServiceTime' => ['ServiceTimePrice' => ['dollar' => 1, 'cents' => '2']]],
        ];
        $result = $this->Xml->serialize($data, ['format' => 'tags']);
        $expected = '<service_day><service_time><service_time_price><dollar>1</dollar><cents>2</cents></service_time_price></service_time></service_day>';
        $this->assertIdentical($result, $expected);

        $data = [
            'Pages' => ['id' => 2, 'url' => 'http://www.url.com/rb/153/?id=bbbb&t=access'],
        ];
        $result = $this->Xml->serialize($data);
        $expected = '<pages id="2" url="http://www.url.com/rb/153/?id=bbbb&amp;t=access" />';
        $this->assertIdentical($result, $expected);

        $test = [
            'Test' => ['test' => true],
        ];
        $expected = '<test test="1" />';
        $result = $this->Xml->serialize($test);
        $this->assertidentical($expected, $result);
    }

    /**
     * testSerializeOnMultiDimensionalArray method.
     */
    public function testSerializeOnMultiDimensionalArray()
    {
        $data = [
            'Statuses' => [
                ['Status' => ['id' => 1]],
                ['Status' => ['id' => 2]],
            ],
        ];
        $result = $this->Xml->serialize($data, ['format' => 'tags']);
        $expected = '<statuses><status><id>1</id></status><status><id>2</id></status></statuses>';
        $this->assertIdentical($result, $expected);
    }

    /**
     * testHeader method.
     */
    public function testHeader()
    {
        $expectedDefaultEncoding = Configure::read('App.encoding');
        if (empty($expectedDefaultEncoding)) {
            $expectedDefaultEncoding = 'UTF-8';
        }
        $attrib = [];
        $result = $this->Xml->header($attrib);
        $expected = '<?xml version="1.0" encoding="'.$expectedDefaultEncoding.'" ?>';
        $this->assertIdentical($result, $expected);

        $attrib = [
            'encoding' => 'UTF-8',
            'version' => '1.1',
        ];
        $result = $this->Xml->header($attrib);
        $expected = '<?xml version="1.1" encoding="UTF-8" ?>';
        $this->assertIdentical($result, $expected);

        $attrib = [
            'encoding' => 'UTF-8',
            'version' => '1.2',
            'additional' => 'attribute',
        ];
        $result = $this->Xml->header($attrib);
        $expected = '<?xml version="1.2" encoding="UTF-8" additional="attribute" ?>';
        $this->assertIdentical($result, $expected);

        $attrib = 'encoding="UTF-8" someOther="value"';
        $result = $this->Xml->header($attrib);
        $expected = '<?xml encoding="UTF-8" someOther="value" ?>';
        $this->assertIdentical($result, $expected);
    }

    /**
     * test that calling elem() and then header() doesn't break.
     */
    public function testElemThenHeader()
    {
        $this->Xml->elem('test', [], 'foo', false);
        $this->assertPattern('/<\?xml/', $this->Xml->header());
    }
}
