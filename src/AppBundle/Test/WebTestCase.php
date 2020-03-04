<?php

namespace AppBundle\Test;

use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Liip\FunctionalTestBundle\Test\WebTestCase as CoreWebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class WebTestCase extends CoreWebTestCase
{
    use FixturesTrait;
//    use RecreateDatabaseTrait;

    /**
     * @var Client
     */
    protected $client;

    protected function setUp()
    {
        parent::setUp();

        $this->loadFixtureFiles([
            '@AppBundle/DataFixtures/ORM/fixtures.yaml',
            '@ClipBundle/DataFixtures/ORM/fixtures.yaml',
//             '@DagbestedingBundle/DataFixtures/ORM/fixtures.yaml',
            '@ErOpUitBundle/DataFixtures/ORM/fixtures.yaml',
//             '@GaBundle/DataFixtures/ORM/fixtures.yaml',
//             '@HsBundle/DataFixtures/ORM/fixtures.yaml',
//             '@InloopBundle/DataFixtures/ORM/fixtures.yaml',
//             '@IzBundle/DataFixtures/ORM/fixtures.yaml',
//             '@MwBundle/DataFixtures/ORM/fixtures.yaml',
//             '@OdpBundle/DataFixtures/ORM/fixtures.yaml',
//             '@OekBundle/DataFixtures/ORM/fixtures.yaml',
//             '@PfoBundle/DataFixtures/ORM/fixtures.yaml',
        ]);

        unset($_GET);
        $this->client = static::createClient();
    }

    protected function tearDown(): void
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
     * @see https://symfony.com/doc/4.4/testing/http_authentication.html
     */
    public function logIn(UserInterface $user, $additionalRoles = []): self
    {
        if (!is_array($additionalRoles)) {
            $additionalRoles = [$additionalRoles];
        }

        $session = self::$container->get('session');

        $token = new UsernamePasswordToken($user, null, 'main', array_merge($user->getRoles(), $additionalRoles));
        $session->set('_security_main', serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);

        return $this;
    }
}
