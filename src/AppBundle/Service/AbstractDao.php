<?php

namespace AppBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDaoInterface;
use AppBundle\Model\UsesKlantTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;

abstract class AbstractDao implements AbstractDaoInterface
{
    use UsesKlantTrait;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    /**
     * @var int
     */
    protected $itemsPerPage;

    protected $paginationOptions = [];

    protected $class = '';

    protected $alias = '';

    /**
     * @var string Sets the propertyname of a relation to Klant. Klant is special entity which can be lazy loaded
     * and cause errors when accessing it because a special filter clause due to poor implementation.
     * This causes errors later on. Fix it beforehand by filling this field so it gets probed during load time instead of view.
     *
     * @TODO Should be solved properly thus at the root cause of it.
     */
    protected $klantPropertyName = '';

    public function __construct(EntityManager $entityManager, PaginatorInterface $paginator, $itemsPerPage)
    {
        if (!$this->class) {
            throw new \RuntimeException('Class must be set');
        }

        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($this->class);
        $this->paginator = $paginator;
        $this->itemsPerPage = $itemsPerPage;
    }

    public function findAll($page = null, FilterInterface $filter = null)
    {
        if (!$this->alias) {
            throw new \RuntimeException('Alias must be set');
        }

        $builder = $this->repository->createQueryBuilder($this->alias);

        return $this->doFindAll($builder, $page, $filter);
    }

    protected function doFindAll(QueryBuilder $builder, $page = 1, FilterInterface $filter = null)
    {
        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function find($id)
    {
        $entity = $this->repository->find($id);
        $this->tryLoadKlant($entity);
        return $entity;
    }

    public function setItemsPerPage(int $itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    protected function doCreate($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    protected function doUpdate($entity)
    {
        $this->entityManager->flush();
    }

    protected function doDelete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}
