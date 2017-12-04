<?php
/**
 * Cake Socket connection class.
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
 * @since         CakePHP(tm) v 1.2.0
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Core', 'Validation');

/**
 * Cake network socket connection class.
 *
 * Core base class for network communication.
 */
class CakeSocket extends Object
{
    /**
     * Object description.
     *
     * @var string
     */
    public $description = 'Remote DataSource Network Socket Interface';

    /**
     * Base configuration settings for the socket connection.
     *
     * @var array
     */
    public $_baseConfig = [
        'persistent' => false,
        'host' => 'localhost',
        'protocol' => 'tcp',
        'port' => 80,
        'timeout' => 30,
    ];

    /**
     * Configuration settings for the socket connection.
     *
     * @var array
     */
    public $config = [];

    /**
     * Reference to socket connection resource.
     *
     * @var resource
     */
    public $connection = null;

    /**
     * This boolean contains the current state of the CakeSocket class.
     *
     * @var bool
     */
    public $connected = false;

    /**
     * This variable contains an array with the last error number (num) and string (str).
     *
     * @var array
     */
    public $lastError = [];

    /**
     * Constructor.
     *
     * @param array $config Socket configuration, which will be merged with the base configuration
     *
     * @see CakeSocket::$_baseConfig
     */
    public function __construct($config = [])
    {
        parent::__construct();

        $this->config = array_merge($this->_baseConfig, $config);
        if (!is_numeric($this->config['protocol'])) {
            $this->config['protocol'] = getprotobyname($this->config['protocol']);
        }
    }

    /**
     * Connect the socket to the given host and port.
     *
     * @return bool Success
     */
    public function connect()
    {
        if (null != $this->connection) {
            $this->disconnect();
        }

        $scheme = null;
        if (isset($this->config['request']) && $this->config['request']['uri']['scheme'] == 'https') {
            $scheme = 'ssl://';
        }

        if (true == $this->config['persistent']) {
            $tmp = null;
            $this->connection = @pfsockopen($scheme.$this->config['host'], $this->config['port'], $errNum, $errStr, $this->config['timeout']);
        } else {
            $this->connection = @fsockopen($scheme.$this->config['host'], $this->config['port'], $errNum, $errStr, $this->config['timeout']);
        }

        if (!empty($errNum) || !empty($errStr)) {
            $this->setLastError($errNum, $errStr);
        }

        $this->connected = is_resource($this->connection);
        if ($this->connected) {
            stream_set_timeout($this->connection, $this->config['timeout']);
        }

        return $this->connected;
    }

    /**
     * Get the host name of the current connection.
     *
     * @return string Host name
     */
    public function host()
    {
        if (Validation::ip($this->config['host'])) {
            return gethostbyaddr($this->config['host']);
        } else {
            return gethostbyaddr($this->address());
        }
    }

    /**
     * Get the IP address of the current connection.
     *
     * @return string IP address
     */
    public function address()
    {
        if (Validation::ip($this->config['host'])) {
            return $this->config['host'];
        } else {
            return gethostbyname($this->config['host']);
        }
    }

    /**
     * Get all IP addresses associated with the current connection.
     *
     * @return array IP addresses
     */
    public function addresses()
    {
        if (Validation::ip($this->config['host'])) {
            return [$this->config['host']];
        } else {
            return gethostbynamel($this->config['host']);
        }
    }

    /**
     * Get the last error as a string.
     *
     * @return string Last error
     */
    public function lastError()
    {
        if (!empty($this->lastError)) {
            return $this->lastError['num'].': '.$this->lastError['str'];
        } else {
            return null;
        }
    }

    /**
     * Set the last error.
     *
     * @param int    $errNum Error code
     * @param string $errStr Error string
     */
    public function setLastError($errNum, $errStr)
    {
        $this->lastError = ['num' => $errNum, 'str' => $errStr];
    }

    /**
     * Write data to the socket.
     *
     * @param string $data The data to write to the socket
     *
     * @return bool Success
     */
    public function write($data)
    {
        if (!$this->connected) {
            if (!$this->connect()) {
                return false;
            }
        }
        $totalBytes = strlen($data);
        for ($written = 0, $rv = 0; $written < $totalBytes; $written += $rv) {
            $rv = fwrite($this->connection, substr($data, $written));
            if (false === $rv || 0 === $rv) {
                return $written;
            }
        }

        return $written;
    }

    /**
     * Read data from the socket. Returns false if no data is available or no connection could be
     * established.
     *
     * @param int $length Optional buffer length to read; defaults to 1024
     *
     * @return mixed Socket data
     */
    public function read($length = 1024)
    {
        if (!$this->connected) {
            if (!$this->connect()) {
                return false;
            }
        }

        if (!feof($this->connection)) {
            $buffer = fread($this->connection, $length);
            $info = stream_get_meta_data($this->connection);
            if ($info['timed_out']) {
                $this->setLastError(E_WARNING, __('Connection timed out', true));

                return false;
            }

            return $buffer;
        } else {
            return false;
        }
    }

    /**
     * Abort socket operation.
     *
     * @return bool Success
     */
    public function abort()
    {
    }

    /**
     * Disconnect the socket from the current connection.
     *
     * @return bool Success
     */
    public function disconnect()
    {
        if (!is_resource($this->connection)) {
            $this->connected = false;

            return true;
        }
        $this->connected = !fclose($this->connection);

        if (!$this->connected) {
            $this->connection = null;
        }

        return !$this->connected;
    }

    /**
     * Destructor, used to disconnect from current connection.
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Resets the state of this Socket instance to it's initial state (before Object::__construct got executed).
     *
     * @return bool True on success
     */
    public function reset($state = null)
    {
        if (empty($state)) {
            static $initalState = [];
            if (empty($initalState)) {
                $initalState = get_class_vars(__CLASS__);
            }
            $state = $initalState;
        }

        foreach ($state as $property => $value) {
            $this->{$property} = $value;
        }

        return true;
    }
}
