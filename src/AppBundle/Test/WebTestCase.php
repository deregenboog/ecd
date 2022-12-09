<?php

namespace AppBundle\Test;

use Doctrine\DBAL\Connection;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

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
        $this->connection->rollback();

        parent::tearDown();
    }

    /**
     * @param UserInterface $user
     *
     * @see https://symfony.com/doc/4.4/testing/http_authentication.html
     */
    protected function logIn(UserInterface $user, $additionalRoles = []): void
    {
        if (!is_array($additionalRoles)) {
            $additionalRoles = [$additionalRoles];
        }

        $session = self::$container->get('session');

        $roles = array_merge($user->getRoles(), $additionalRoles);
        $token = new UsernamePasswordToken($user, null, 'main', $roles);
        $session->set('_security_main', serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
