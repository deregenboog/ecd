<?php
/**
 * SocketTest file.
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
App::import('Core', 'CakeSocket');

/**
 * SocketTest class.
 */
class CakeSocketTest extends CakeTestCase
{
    /**
     * setUp method.
     */
    public function setUp()
    {
        $this->Socket = new CakeSocket();
    }

    /**
     * tearDown method.
     */
    public function tearDown()
    {
        unset($this->Socket);
    }

    /**
     * testConstruct method.
     */
    public function testConstruct()
    {
        $this->Socket->__construct();
        $baseConfig = $this->Socket->_baseConfig;
        $this->assertIdentical($baseConfig, [
            'persistent' => false,
            'host' => 'localhost',
            'protocol' => 'tcp',
            'port' => 80,
            'timeout' => 30,
        ]);

        $this->Socket->reset();
        $this->Socket->__construct(['host' => 'foo-bar']);
        $baseConfig['host'] = 'foo-bar';
        $baseConfig['protocol'] = getprotobyname($baseConfig['protocol']);
        $this->assertIdentical($this->Socket->config, $baseConfig);

        $this->Socket = new CakeSocket(['host' => 'www.cakephp.org', 'port' => 23, 'protocol' => 'udp']);
        $baseConfig = $this->Socket->_baseConfig;

        $baseConfig['host'] = 'www.cakephp.org';
        $baseConfig['port'] = 23;
        $baseConfig['protocol'] = 17;

        $this->assertIdentical($this->Socket->config, $baseConfig);
    }

    /**
     * testSocketConnection method.
     */
    public function testSocketConnection()
    {
        $this->assertFalse($this->Socket->connected);
        $this->Socket->disconnect();
        $this->assertFalse($this->Socket->connected);
        $this->Socket->connect();
        $this->assertTrue($this->Socket->connected);
        $this->Socket->connect();
        $this->assertTrue($this->Socket->connected);

        $this->Socket->disconnect();
        $config = ['persistent' => true];
        $this->Socket = new CakeSocket($config);
        $this->Socket->connect();
        $this->assertTrue($this->Socket->connected);
    }

    /**
     * testSocketHost method.
     */
    public function testSocketHost()
    {
        $this->Socket = new CakeSocket();
        $this->Socket->connect();
        $this->assertEqual($this->Socket->address(), '127.0.0.1');
        $this->assertEqual(gethostbyaddr('127.0.0.1'), $this->Socket->host());
        $this->assertEqual($this->Socket->lastError(), null);
        $this->assertTrue(in_array('127.0.0.1', $this->Socket->addresses()));

        $this->Socket = new CakeSocket(['host' => '127.0.0.1']);
        $this->Socket->connect();
        $this->assertEqual($this->Socket->address(), '127.0.0.1');
        $this->assertEqual(gethostbyaddr('127.0.0.1'), $this->Socket->host());
        $this->assertEqual($this->Socket->lastError(), null);
        $this->assertTrue(in_array('127.0.0.1', $this->Socket->addresses()));
    }

    /**
     * testSocketWriting method.
     */
    public function testSocketWriting()
    {
        $request = "GET / HTTP/1.1\r\nConnection: close\r\n\r\n";
        $this->assertTrue($this->Socket->write($request));
    }

    /**
     * testSocketReading method.
     */
    public function testSocketReading()
    {
        $this->Socket = new CakeSocket(['timeout' => 5]);
        $this->Socket->connect();
        $this->assertEqual($this->Socket->read(26), null);

        $config = ['host' => 'www.cakephp.org', 'timeout' => 1];
        $this->Socket = new CakeSocket($config);
        $this->assertTrue($this->Socket->connect());
        $this->assertFalse($this->Socket->read(1024 * 1024));
        $this->assertEqual($this->Socket->lastError(), '2: '.__('Connection timed out', true));

        $config = ['host' => 'www.cakephp.org', 'timeout' => 30];
        $this->Socket = new CakeSocket($config);
        $this->assertTrue($this->Socket->connect());
        $this->assertEqual($this->Socket->read(26), null);
        $this->assertEqual($this->Socket->lastError(), null);
    }

    /**
     * testLastError method.
     */
    public function testLastError()
    {
        $this->Socket = new CakeSocket();
        $this->Socket->setLastError(4, 'some error here');
        $this->assertEqual($this->Socket->lastError(), '4: some error here');
    }

    /**
     * testReset method.
     */
    public function testReset()
    {
        $config = [
            'persistent' => true,
            'host' => '127.0.0.1',
            'protocol' => 'udp',
            'port' => 80,
            'timeout' => 20,
        ];
        $anotherSocket = new CakeSocket($config);
        $anotherSocket->reset();
        $this->assertEqual([], $anotherSocket->config);
    }
}
