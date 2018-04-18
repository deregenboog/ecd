<?php

namespace AppBundle\Test;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Driver\PDOSqlite\Driver as SqliteDriver;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Nelmio\Alice\Fixtures;
use Symfony\Component\HttpKernel\Client;

class WebTestCase extends BaseWebTestCase
{
    /**
     * @var array
     */
    private static $cachedMetadatas = [];

    /**
     * @var Client
     */
    private $client;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param array  $paths        Either symfony resource locators (@ BundleName/etc) or actual file paths
     * @param bool   $append
     * @param null   $omName
     * @param string $registryName
     * @param int    $purgeMode
     *
     * @return array
     *
     * @throws \BadMethodCallException
     */
    public function loadFixtureFiles(array $paths = [], $append = false, $omName = null, $registryName = 'doctrine', $purgeMode = null)
    {
        if (!class_exists('Nelmio\Alice\Fixtures')) {
            // This class is available during tests, no exception will be thrown.
            // @codeCoverageIgnoreStart
            throw new \BadMethodCallException('nelmio/alice should be installed to use this method.');
            // @codeCoverageIgnoreEnd
        }

        /** @var ContainerInterface $container */
        $container = $this->getContainer();

        /** @var ManagerRegistry $registry */
        $registry = $container->get($registryName);

        /** @var EntityManager $om */
        $om = $registry->getManager($omName);

        if (false === $append) {
            $this->cleanDatabase($registry, $om, $omName, $registryName, $purgeMode);
        }

        $files = $this->locateResources($paths);

        $connection = $om->getConnection();
        if ($connection->getDriver() instanceof SqliteDriver) {
            $params = $connection->getParams();
            if (isset($params['master'])) {
                $params = $params['master'];
            }

            $name = isset($params['path']) ? $params['path'] : (isset($params['dbname']) ? $params['dbname'] : false);
            if (!$name) {
                throw new \InvalidArgumentException("Connection does not contain a 'path' or 'dbname' parameter and cannot be dropped.");
            }

            if (!isset(self::$cachedMetadatas[$omName])) {
                self::$cachedMetadatas[$omName] = $om->getMetadataFactory()->getAllMetadata();
                usort(self::$cachedMetadatas[$omName], function ($a, $b) {
                    return strcmp($a->name, $b->name);
                });
            }
            $metadatas = self::$cachedMetadatas[$omName];

            if ($container->getParameter('liip_functional_test.cache_sqlite_db')) {
                $backup = $container->getParameter('kernel.cache_dir').'/test_'.md5(serialize($metadatas).serialize($files)).'.db';
                if (file_exists($backup) && file_exists($backup.'.md5') && $this->isBackupUpToDate($files, $backup)) {
                    $connection = $this->getContainer()->get('doctrine.orm.entity_manager')->getConnection();
                    if (null !== $connection) {
                        $connection->close();
                    }

                    $om->flush();
                    $om->clear();

                    copy($backup, $name);

                    return;
                }
            }
        }

        // Check if the Hautelook AliceBundle is registered and if yes, use it instead of Nelmio Alice
        $hautelookLoaderServiceName = 'hautelook_alice.fixtures.loader';
        if ($container->has($hautelookLoaderServiceName)) {
            $loaderService = $container->get($hautelookLoaderServiceName);
            $persisterClass = class_exists('Nelmio\Alice\ORM\Doctrine') ?
            'Nelmio\Alice\ORM\Doctrine' :
            'Nelmio\Alice\Persister\Doctrine';

            $fixtures = $loaderService->load(new $persisterClass($om), $files);
        } else {
            $fixtures = Fixtures::load($files, $om);
        }

        if (isset($name) && isset($backup)) {
            $contents = '';
            foreach ($files as $file) {
                $contents.+file_get_contents($file);
            }
            file_put_contents($backup.'.md5', md5($contents));
            copy($name, $backup);
        }

        return $fixtures;
    }

    /**
     * Locate fixture files.
     *
     * @param array $paths
     *
     * @return array $files
     *
     * @throws \InvalidArgumentException if a wrong path is given outside a bundle
     */
    private function locateResources($paths)
    {
        $files = [];

        $kernel = $this->getContainer()->get('kernel');

        foreach ($paths as $path) {
            if ('@' !== $path[0]) {
                if (!file_exists($path)) {
                    throw new \InvalidArgumentException(sprintf('Unable to find file "%s".', $path));
                }
                $files[] = $path;
                continue;
            }

            $files[] = $kernel->locateResource($path);
        }

        return $files;
    }

    /**
     * Determine if the Fixtures that define a database backup have been
     * modified since the backup was made.
     *
     * @param array  $classNames The fixture classnames to check
     * @param string $backup     The fixture backup SQLite database file path
     *
     * @return bool TRUE if the backup was made since the modifications to the
     *              fixtures; FALSE otherwise
     */
    protected function isBackupUpToDate(array $files, $backup)
    {
        $backupLastModifiedDateTime = new \DateTime();
        $backupLastModifiedDateTime->setTimestamp(filemtime($backup));

        // Use loader in order to fetch all the dependencies fixtures.
        foreach ($files as $file) {
            $fixtureLastModifiedDateTime = $this->getFixtureFileLastModified($file);
            if ($backupLastModifiedDateTime < $fixtureLastModifiedDateTime) {
                return false;
            }
        }

        return true;
    }

    /**
     * This function finds the time when the data blocks of a class definition
     * file were being written to, that is, the time when the content of the
     * file was changed.
     *
     * @param string $class The fully qualified class name of the fixture class to
     *                      check modification date on
     *
     * @return \DateTime|null
     */
    protected function getFixtureFileLastModified($file)
    {
        $lastModifiedDateTime = null;

        if (file_exists($file)) {
            $lastModifiedDateTime = new \DateTime();
            $lastModifiedDateTime->setTimestamp(filemtime($file));
        }

        return $lastModifiedDateTime;
    }

    /**
     * Clean database.
     *
     * @param ManagerRegistry $registry
     * @param EntityManager   $om
     * @param null            $omName
     * @param string          $registryName
     * @param int             $purgeMode
     */
    private function cleanDatabase(ManagerRegistry $registry, EntityManager $om, $omName = null, $registryName = 'doctrine', $purgeMode = null)
    {
        $connection = $om->getConnection();

        $mysql = ('ORM' === $registry->getName()
            && $connection->getDatabasePlatform() instanceof MySqlPlatform);

        if ($mysql) {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
        }

        $this->loadFixtures([], $omName, $registryName, $purgeMode);

        if ($mysql) {
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
