<?php

use Doctrine\DBAL\Driver\PDOMySql\Driver;

/**
 * This is core configuration file.
 *
 * Use it to configure core behaviour ofCake.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 0.2.9
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * In this file you set up your database connection details.
 */
/**
 * Database configuration class.
 * You can specify multiple configurations for production, development and testing.
 *
 * driver => The name of a supported driver; valid options are as follows:
 *		mysql 		- MySQL 4 & 5,
 *		mysqli 		- MySQL 4 & 5 Improved Interface (PHP5 only),
 *		sqlite		- SQLite (PHP5 only),
 *		postgres	- PostgreSQL 7 and higher,
 *		mssql		- Microsoft SQL Server 2000 and higher,
 *		db2			- IBM DB2, Cloudscape, and Apache Derby (http://php.net/ibm-db2)
 *		oracle		- Oracle 8 and higher
 *		firebird	- Firebird/Interbase
 *		sybase		- Sybase ASE
 *		adodb-[drivername]	- ADOdb interface wrapper (see below),
 *		odbc		- ODBC DBO driver
 *
 * You can add custom database drivers (or override existing drivers) by adding the
 * appropriate file to app/models/datasources/dbo.  Drivers should be named 'dbo_x.php',
 * where 'x' is the name of the database.
 *
 * persistent => true / false
 * Determines whether or not the database should use a persistent connection
 *
 * connect =>
 * ADOdb set the connect to one of these
 *	(http://phplens.com/adodb/supported.databases.html) and
 *	append it '|p' for persistent connection. (mssql|p for example, or just mssql for not persistent)
 * For all other databases, this setting is deprecated.
 *
 * host =>
 * the host you connect to the database.  To add a socket or port number, use 'port' => #
 *
 * prefix =>
 * Uses the given prefix for all the tables in this database.  This setting can be overridden
 * on a per-table basis with the Model::$tablePrefix property.
 *
 * schema =>
 * For Postgres and DB2, specifies which schema you would like to use the tables in. Postgres defaults to
 * 'public', DB2 defaults to empty.
 *
 * encoding =>
 * For MySQL, MySQLi, Postgres and DB2, specifies the character encoding to use when connecting to the
 * database.  Uses database default.
 */
class DATABASE_CONFIG
{
    public $default = [
        'driver' => '',
        'persistent' => false,
        'host' => '',
        'login' => '',
        'password' => '',
        'database' => '',
        'encoding' => '',
        'prefix' => '',
    ];

    public $test = [
        'driver' => '',
        'persistent' => false,
        'host' => '',
        'login' => '',
        'password' => '',
        'database' => '',
        'encoding' => '',
        'prefix' => '',
    ];

    public function __construct()
    {
        global $kernel;

        // In case this is called by a Cake console command no kernel has been
        // booted. For now use this ugly hack.
        // @todo Improve this!
        if (!$kernel) {
            require __DIR__.'/../autoload.php';
            $kernel = new AppKernel('dev', true);
            $kernel->boot();
        }

        $container = $kernel->getContainer();

        /* @var $conn Doctrine\DBAL\Connection */
        $conn = $container->get('database_connection');

        switch ($conn->getDriver()->getName()) {
            case 'pdo_mysql':
                $this->default['driver'] = 'mysql';
                break;
            default:
                throw new \Exception('Unsupported database driver: '.$conn->getDriver()->getName());
        }

        $this->default['host'] = $conn->getHost();
        $this->default['port'] = $conn->getPort();
        $this->default['login'] = $conn->getUsername();
        $this->default['password'] = $conn->getPassword();
        $this->default['database'] = $conn->getDatabase();
        $this->default['encoding'] = $conn->getParams()['charset'];
    }
}
