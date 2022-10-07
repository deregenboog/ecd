<?php

namespace AppBundle\Test;

use Doctrine\ORM\Tools\SchemaTool;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Liip\FunctionalTestBundle\Test\WebTestCase as CoreWebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class WebTestCase extends CoreWebTestCase
{
//    use FixturesTrait;

    use RecreateDatabaseTrait;

    /**
     * @var Client
     */
    protected $client;

    /** @var AbstractDatabaseTool */
    protected $databaseTool;

    protected function setUp()
    {

//        parent::setUp();
//        self::bootKernel();

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();

        $this->client = $this->makeClient(); // dont know why but this must be called before loading fixtures.

         $this->databaseTool->loadAliceFixture([

            '@AppBundle/DataFixtures/ORM/fixtures.yml',
            '@ClipBundle/DataFixtures/ORM/fixtures.yml',
            '@DagbestedingBundle/DataFixtures/ORM/fixtures.yml',
             '@GaBundle/DataFixtures/ORM/fixtures.yml',
            '@ErOpUitBundle/DataFixtures/ORM/fixtures.yml',
             '@HsBundle/DataFixtures/ORM/fixtures.yml',
             '@InloopBundle/DataFixtures/ORM/fixtures.yml',
             '@IzBundle/DataFixtures/ORM/fixtures.yml',
             '@TwBundle/DataFixtures/ORM/fixtures.yml',
             '@OekBundle/DataFixtures/ORM/fixtures.yml',
//             '@PfoBundle/DataFixtures/ORM/fixtures.yml',
        ]);

        unset($_GET);//why?
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
     * Override from FixturesTrait, fixtures trait doesn't support Symfony4 loading.
     * https://github.com/liip/LiipTestFixturesBundle/issues/66
     */
//    protected function loadFixtures(array $classNames = [], bool $append = false, ?string $omName = null, string $registryName = 'doctrine', ?int $purgeMode = null): ?AbstractExecutor
//    {
//        $dbToolCollection = static::$container->get('liip_test_fixtures.services.database_tool_collection');
//        $dbTool = $dbToolCollection->get($omName, $registryName, $purgeMode, $this);
//        $dbTool->setExcludedDoctrineTables($this->excludedDoctrineTables);
//
//        return $dbTool->loadFixtures($classNames, $append);
//    }
//
//    public function loadFixtureFiles(array $paths = [], bool $append = false, ?string $omName = null, $registryName = 'doctrine', ?int $purgeMode = null): array
//    {
//        $dbToolCollection = self::$container->get('liip_test_fixtures.services.database_tool_collection');
//        $dbTool = $dbToolCollection->get($omName, $registryName, $purgeMode, $this);
//        $dbTool->setExcludedDoctrineTables($this->excludedDoctrineTables);
//
//        return $dbTool->loadAliceFixture($paths, $append);
//    }

    public function assertHasErrors(InvitationCode $code, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($code);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    /**
     * @param UserInterface $user
     *
     * @see https://symfony.com/doc/3.4/testing/http_authentication.html
     */
    public function logIn(UserInterface $user, $additionalRoles = []): self
    {
        if (!is_array($additionalRoles)) {
            $additionalRoles = [$additionalRoles];
        }

        $session = $this->client->getContainer()->get('session');

        $roles = array_merge($user->getRoles(), $additionalRoles);
        $token = new UsernamePasswordToken($user, null, 'main', $roles);
        $session->set('_security_main', serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
        return $this;
    }
}
