<?php

namespace AppBundle\Test;

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Nelmio\Alice\Fixtures;
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

    protected function setUp()
    {
        parent::setUp();

        $this->loadFixtureFiles([
            '@AppBundle/DataFixtures/ORM/fixtures.yml',
            '@ClipBundle/DataFixtures/ORM/fixtures.yml',
//             '@DagbestedingBundle/DataFixtures/ORM/fixtures.yml',
            '@ErOpUitBundle/DataFixtures/ORM/fixtures.yml',
//             '@GaBundle/DataFixtures/ORM/fixtures.yml',
//             '@HsBundle/DataFixtures/ORM/fixtures.yml',
//             '@InloopBundle/DataFixtures/ORM/fixtures.yml',
//             '@IzBundle/DataFixtures/ORM/fixtures.yml',
//             '@MwBundle/DataFixtures/ORM/fixtures.yml',
//             '@OdpBundle/DataFixtures/ORM/fixtures.yml',
//             '@OekBundle/DataFixtures/ORM/fixtures.yml',
//             '@PfoBundle/DataFixtures/ORM/fixtures.yml',
        ]);

        unset($_GET);
        $this->client = static::createClient();
    }

    protected function tearDown()
    {
        //see https://stackoverflow.com/questions/36032168/symfony-and-phpunit-memory-leak
        // Remove properties defined during the test
        $refl = new \ReflectionObject($this);
        foreach ($refl->getProperties() as $prop) {
            if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }
        $this->client = null;
        parent::tearDown();
    }

    /**
     * @param UserInterface $user
     *
     * @see https://symfony.com/doc/3.4/testing/http_authentication.html
     */
    protected function logIn(UserInterface $user, $additionalRoles = [])
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
