<?php

namespace AppBundle\Test;

use Doctrine\DBAL\Connection;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class WebTestCase extends CoreWebTestCase
{
    use FixturesTrait;
    use RecreateDatabaseTrait;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Connection
     */
    protected $connection;

    protected function setUp()
    {
        parent::setUp();

        $this->client = $this->makeClient();

        $this->connection = static::$kernel->getContainer()->get('doctrine')->getConnection();
        $this->connection->beginTransaction();
        $this->connection->setAutoCommit(false);
    }

    protected function tearDown(): void
    {
        $this->connection->rollback();

        parent::tearDown();
    }

    /**
     * @param UserInterface $user
     *
     * @see https://symfony.com/doc/4.4/testing/http_authentication.html
     */
    public function logIn(UserInterface $user, $additionalRoles = []): self
    {
        if (!is_array($additionalRoles)) {
            $additionalRoles = [$additionalRoles];
        }

        $session = $this->client->getContainer()->get('session');

        $token = new UsernamePasswordToken($user, null, 'main', array_merge($user->getRoles(), $additionalRoles));
        $session->set('_security_main', serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
