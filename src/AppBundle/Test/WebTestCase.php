<?php

namespace AppBundle\Test;

use Doctrine\DBAL\Connection;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class WebTestCase extends BaseWebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Connection
     */
    protected $connection;

    protected function setUp(): void
    {
        parent::setUp();

        unset($_GET);
        $this->client = static::createClient();

        $this->connection = static::$kernel->getContainer()->get('doctrine')->getConnection();
        $this->connection->beginTransaction();
        $this->connection->setAutoCommit(false);
    }

    protected function tearDown(): void
    {
        if ($this->connection->isTransactionActive()) {
            $this->connection->rollback();
        }

        parent::tearDown();
    }
}
