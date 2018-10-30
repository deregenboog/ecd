<?php

namespace AppBundle\Test;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Driver\PDOSqlite\Driver as SqliteDriver;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Nelmio\Alice\Fixtures;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\Medewerker;

class WebTestCase extends BaseWebTestCase
{
    protected $client = null;

    protected function setUp()
    {
        $this->loadFixtureFiles([
            '@AppBundle/DataFixtures/ORM/fixtures.yml',
            '@ClipBundle/DataFixtures/ORM/fixtures.yml',
//             '@DagbestedingBundle/DataFixtures/ORM/fixtures.yml',
//             '@GaBundle/DataFixtures/ORM/fixtures.yml',
//             '@HsBundle/DataFixtures/ORM/fixtures.yml',
//             '@InloopBundle/DataFixtures/ORM/fixtures.yml',
//             '@IzBundle/DataFixtures/ORM/fixtures.yml',
//             '@MwBundle/DataFixtures/ORM/fixtures.yml',
//             '@OdpBundle/DataFixtures/ORM/fixtures.yml',
//             '@OekBundle/DataFixtures/ORM/fixtures.yml',
//             '@PfoBundle/DataFixtures/ORM/fixtures.yml',
        ]);

        $this->client = static::createClient();
    }

    /**
     * @param UserInterface $user
     *
     * @see https://symfony.com/doc/3.4/testing/http_authentication.html
     */
    protected function logIn(UserInterface $user)
    {
        $session = $this->client->getContainer()->get('session');

        $firewallName = 'main';
        $firewallContext = 'main';

        $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
