<?php
/**
 * RssHelperTest file.
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
App::import('Helper', ['Rss', 'Time']);

/**
 * RssHelperTest class.
 */
class RssHelperTest extends CakeTestCase
{
    /**
     * setUp method.
     */
    public function setUp()
    {
        $this->Rss = new RssHelper();
        $this->Rss->Time = new TimeHelper();
        $this->Rss->beforeRender();

        $manager = &XmlManager::getInstance();
        $manager->namespaces = [];
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        unset($this->Rss);
    }

    /**
     * testAddNamespace method.
     */
    public function testAddNamespace()
    {
        $this->Rss->addNs('custom', 'http://example.com/dtd.xml');
        $manager = &XmlManager::getInstance();

        $expected = ['custom' => 'http://example.com/dtd.xml'];
        $this->assertEqual($manager->namespaces, $expected);

        $this->Rss->removeNs('custom');

        $this->Rss->addNs('dummy', 'http://dummy.com/1.0/');
        $result = $this->Rss->document();
        $expected = [
            'rss' => [
                'xmlns:dummy' => 'http://dummy.com/1.0/',
                'version' => '2.0',
            ],
        ];
        $this->assertTags($result, $expected);

        $this->Rss->removeNs('dummy');
    }

    /**
     * testRemoveNamespace method.
     */
    public function testRemoveNamespace()
    {
        $this->Rss->addNs('custom', 'http://example.com/dtd.xml');
        $this->Rss->addNs('custom2', 'http://example.com/dtd2.xml');
        $manager = &XmlManager::getInstance();

        $expected = ['custom' => 'http://example.com/dtd.xml', 'custom2' => 'http://example.com/dtd2.xml'];
        $this->assertEqual($manager->namespaces, $expected);

        $this->Rss->removeNs('custom');
        $expected = ['custom2' => 'http://example.com/dtd2.xml'];
        $this->assertEqual($manager->namespaces, $expected);
    }

    /**
     * testDocument method.
     */
    public function testDocument()
    {
        $result = $this->Rss->document();
        $expected = [
            'rss' => [
                'version' => '2.0',
            ],
        ];
        $this->assertTags($result, $expected);

        $result = $this->Rss->document(['contrived' => 'parameter']);
        $expected = [
            'rss' => [
                'version' => '2.0',
            ],
            '<parameter',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Rss->document(null, 'content');
        $expected = [
            'rss' => [
                'version' => '2.0',
            ],
            'content',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Rss->document(['contrived' => 'parameter'], 'content');
        $expected = [
            'rss' => [
                'contrived' => 'parameter',
                'version' => '2.0',
            ],
            'content',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testChannel method.
     */
    public function testChannel()
    {
        $attrib = ['a' => '1', 'b' => '2'];
        $elements = ['title' => 'title'];
        $content = 'content';

        $result = $this->Rss->channel($attrib, $elements, $content);
        $expected = [
            'channel' => [
                'a' => '1',
                'b' => '2',
            ],
            '<title',
            'title',
            '/title',
            '<link',
            RssHelper::url('/', true),
            '/link',
            '<description',
            'content',
            '/channel',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * test correct creation of channel sub elements.
     */
    public function testChannelElements()
    {
        $attrib = [];
        $elements = [
            'title' => 'Title of RSS Feed',
            'link' => 'http://example.com',
            'description' => 'Description of RSS Feed',
            'image' => [
                'title' => 'Title of image',
                'url' => 'http://example.com/example.png',
                'link' => 'http://example.com',
            ],
            'cloud' => [
                'domain' => 'rpc.sys.com',
                'port' => '80',
                'path' => '/RPC2',
                'registerProcedure' => 'myCloud.rssPleaseNotify',
                'protocol' => 'xml-rpc',
            ],
        ];
        $content = 'content-here';
        $result = $this->Rss->channel($attrib, $elements, $content);
        $expected = [
            '<channel',
                '<title', 'Title of RSS Feed', '/title',
                '<link', 'http://example.com', '/link',
                '<description', 'Description of RSS Feed', '/description',
                '<image',
                    '<title', 'Title of image', '/title',
                    '<url', 'http://example.com/example.png', '/url',
                    '<link', 'http://example.com', '/link',
                '/image',
                'cloud' => [
                    'domain' => 'rpc.sys.com',
                    'port' => '80',
                    'path' => '/RPC2',
                    'registerProcedure' => 'myCloud.rssPleaseNotify',
                    'protocol' => 'xml-rpc',
                ],
            'content-here',
            '/channel',
        ];
        $this->assertTags($result, $expected);
    }

    public function testChannelElementAttributes()
    {
        $attrib = [];
        $elements = [
            'title' => 'Title of RSS Feed',
            'link' => 'http://example.com',
            'description' => 'Description of RSS Feed',
            'image' => [
                'title' => 'Title of image',
                'url' => 'http://example.com/example.png',
                'link' => 'http://example.com',
            ],
            'atom:link' => [
                'attrib' => [
                    'href' => 'http://www.example.com/rss.xml',
                    'rel' => 'self',
                    'type' => 'application/rss+xml', ],
            ],
        ];
        $content = 'content-here';
        $result = $this->Rss->channel($attrib, $elements, $content);
        $expected = [
            '<channel',
                '<title', 'Title of RSS Feed', '/title',
                '<link', 'http://example.com', '/link',
                '<description', 'Description of RSS Feed', '/description',
                '<image',
                    '<title', 'Title of image', '/title',
                    '<url', 'http://example.com/example.png', '/url',
                    '<link', 'http://example.com', '/link',
                '/image',
                'atom:link' => [
                    'href' => 'http://www.example.com/rss.xml',
                    'rel' => 'self',
                    'type' => 'application/rss+xml',
                ],
            'content-here',
            '/channel',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testItems method.
     */
    public function testItems()
    {
        $items = [
            ['title' => 'title1', 'guid' => 'http://www.example.com/guid1', 'link' => 'http://www.example.com/link1', 'description' => 'description1'],
            ['title' => 'title2', 'guid' => 'http://www.example.com/guid2', 'link' => 'http://www.example.com/link2', 'description' => 'description2'],
            ['title' => 'title3', 'guid' => 'http://www.example.com/guid3', 'link' => 'http://www.example.com/link3', 'description' => 'description3'],
        ];

        $result = $this->Rss->items($items);
        $expected = [
            '<item',
                '<title', 'title1', '/title',
                '<guid', 'http://www.example.com/guid1', '/guid',
                '<link', 'http://www.example.com/link1', '/link',
                '<description', 'description1', '/description',
            '/item',
            '<item',
                '<title', 'title2', '/title',
                '<guid', 'http://www.example.com/guid2', '/guid',
                '<link', 'http://www.example.com/link2', '/link',
                '<description', 'description2', '/description',
            '/item',
            '<item',
                '<title', 'title3', '/title',
                '<guid', 'http://www.example.com/guid3', '/guid',
                '<link', 'http://www.example.com/link3', '/link',
                '<description', 'description3', '/description',
            '/item',
        ];
        $this->assertTags($result, $expected);

        $result = $this->Rss->items([]);
        $expected = '';
        $this->assertEqual($result, $expected);
    }

    /**
     * testItem method.
     */
    public function testItem()
    {
        $item = [
            'title' => 'My title',
            'description' => 'My description',
            'link' => 'http://www.google.com/',
        ];
        $result = $this->Rss->item(null, $item);
        $expected = [
            '<item',
            '<title',
            'My title',
            '/title',
            '<description',
            'My description',
            '/description',
            '<link',
            'http://www.google.com/',
            '/link',
            '<guid',
            'http://www.google.com/',
            '/guid',
            '/item',
        ];
        $this->assertTags($result, $expected);

        $item = [
            'title' => [
                'value' => 'My Title',
                'cdata' => true,
            ],
            'link' => 'http://www.example.com/1',
            'description' => [
                'value' => 'descriptive words',
                'cdata' => true,
            ],
            'pubDate' => '2008-05-31 12:00:00',
            'guid' => 'http://www.example.com/1',
        ];
        $result = $this->Rss->item(null, $item);

        $expected = [
            '<item',
            '<title',
            '<![CDATA[My Title]]',
            '/title',
            '<link',
            'http://www.example.com/1',
            '/link',
            '<description',
            '<![CDATA[descriptive words]]',
            '/description',
            '<pubDate',
            date('r', strtotime('2008-05-31 12:00:00')),
            '/pubDate',
            '<guid',
            'http://www.example.com/1',
            '/guid',
            '/item',
        ];
        $this->assertTags($result, $expected);

        $item = [
            'title' => [
                'value' => 'My Title & more',
                'cdata' => true,
            ],
        ];
        $result = $this->Rss->item(null, $item);
        $expected = [
            '<item',
            '<title',
            '<![CDATA[My Title &amp; more]]',
            '/title',
            '/item',
        ];
        $this->assertTags($result, $expected);

        $item = [
            'title' => [
                'value' => 'My Title & more',
                'convertEntities' => false,
            ],
        ];
        $result = $this->Rss->item(null, $item);
        $expected = [
            '<item',
            '<title',
            'My Title & more',
            '/title',
            '/item',
        ];
        $this->assertTags($result, $expected);

        $item = [
            'title' => [
                'value' => 'My Title & more',
                'cdata' => true,
                'convertEntities' => false,
            ],
        ];
        $result = $this->Rss->item(null, $item);
        $expected = [
            '<item',
            '<title',
            '<![CDATA[My Title & more]]',
            '/title',
            '/item',
        ];
        $this->assertTags($result, $expected);

        $item = [
            'category' => [
                'value' => 'CakePHP',
                'cdata' => true,
                'domain' => 'http://www.cakephp.org',
            ],
        ];
        $result = $this->Rss->item(null, $item);
        $expected = [
            '<item',
            'category' => ['domain' => 'http://www.cakephp.org'],
            '<![CDATA[CakePHP]]',
            '/category',
            '/item',
        ];
        $this->assertTags($result, $expected);

        $item = [
            'category' => [
                [
                    'value' => 'CakePHP',
                    'cdata' => true,
                    'domain' => 'http://www.cakephp.org',
                ],
                [
                    'value' => 'Bakery',
                    'cdata' => true,
                ],
            ],
        ];
        $result = $this->Rss->item(null, $item);
        $expected = [
            '<item',
            'category' => ['domain' => 'http://www.cakephp.org'],
            '<![CDATA[CakePHP]]',
            '/category',
            '<category',
            '<![CDATA[Bakery]]',
            '/category',
            '/item',
        ];
        $this->assertTags($result, $expected);

        $item = [
            'title' => [
                'value' => 'My Title',
                'cdata' => true,
            ],
            'link' => 'http://www.example.com/1',
            'description' => [
                'value' => 'descriptive words',
                'cdata' => true,
            ],
            'enclosure' => [
                'url' => '/test.flv',
            ],
            'pubDate' => '2008-05-31 12:00:00',
            'guid' => 'http://www.example.com/1',
            'category' => [
                [
                    'value' => 'CakePHP',
                    'cdata' => true,
                    'domain' => 'http://www.cakephp.org',
                ],
                [
                    'value' => 'Bakery',
                    'cdata' => true,
                ],
            ],
        ];
        $result = $this->Rss->item(null, $item);
        $expected = [
            '<item',
            '<title',
            '<![CDATA[My Title]]',
            '/title',
            '<link',
            'http://www.example.com/1',
            '/link',
            '<description',
            '<![CDATA[descriptive words]]',
            '/description',
            'enclosure' => ['url' => RssHelper::url('/test.flv', true)],
            '<pubDate',
            date('r', strtotime('2008-05-31 12:00:00')),
            '/pubDate',
            '<guid',
            'http://www.example.com/1',
            '/guid',
            'category' => ['domain' => 'http://www.cakephp.org'],
            '<![CDATA[CakePHP]]',
            '/category',
            '<category',
            '<![CDATA[Bakery]]',
            '/category',
            '/item',
        ];
        $this->assertTags($result, $expected);

        $item = [
            'title' => 'Foo bar',
            'link' => [
                'url' => 'http://example.com/foo?a=1&b=2',
                'convertEntities' => false,
            ],
            'description' => [
                'value' => 'descriptive words',
                'cdata' => true,
            ],
            'pubDate' => '2008-05-31 12:00:00',
        ];
        $result = $this->Rss->item(null, $item);
        $expected = [
            '<item',
            '<title',
            'Foo bar',
            '/title',
            '<link',
            'http://example.com/foo?a=1&amp;b=2',
            '/link',
            '<description',
            '<![CDATA[descriptive words]]',
            '/description',
            '<pubDate',
            date('r', strtotime('2008-05-31 12:00:00')),
            '/pubDate',
            '<guid',
            'http://example.com/foo?a=1&amp;b=2',
            '/guid',
            '/item',
        ];
        $this->assertTags($result, $expected);
    }

    /**
     * testTime method.
     */
    public function testTime()
    {
    }

    /**
     * testElementAttrNotInParent method.
     */
    public function testElementAttrNotInParent()
    {
        $attributes = [
            'title' => 'Some Title',
            'link' => 'http://link.com',
            'description' => 'description',
        ];
        $elements = ['enclosure' => ['url' => 'http://test.com']];

        $result = $this->Rss->item($attributes, $elements);
        $expected = [
            'item' => [
                'title' => 'Some Title',
                'link' => 'http://link.com',
                'description' => 'description',
            ],
            'enclosure' => [
                'url' => 'http://test.com',
            ],
            '/item',
        ];
        $this->assertTags($result, $expected);
    }
}
