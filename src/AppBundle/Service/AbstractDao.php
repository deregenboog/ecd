<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\FilterInterface;

class AbstractDao
{
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

    public function findAll($page = 1, FilterInterface $filter = null)
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
        return $this->repository->find($id);
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
