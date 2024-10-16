<?php

namespace AppBundle\Service;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Model\ActivatableInterface;
use AppBundle\Model\UsesKlantTrait;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var string Name of the entity which is the 'parent' of this entity. ie klant for verhuurder.
     */
    protected $searchEntityName = '';

    public function __construct(EntityManagerInterface $entityManager, ?PaginatorInterface $paginator = null, $itemsPerPage = 10)
    {
        if (!$this->class) {
            throw new \RuntimeException('Class must be set');
        }

        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($this->class);
        $this->paginator = $paginator;
        $this->itemsPerPage = $itemsPerPage;
    }

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        if (!$this->alias) {
            throw new \RuntimeException('Alias must be set');
        }

        $builder = $this->repository->createQueryBuilder($this->alias);

        return $this->doFindAll($builder, $page, $filter);
    }

    protected function doFindAll(QueryBuilder $builder, $page = 1, ?FilterInterface $filter = null)
    {
        if ($filter) {
            $filter->applyTo($builder);
        }
        //       $sql = SqlExtractor::getFullSQL($builder->getQuery());

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

    public function findOneBySearchEntity($searchEntity)
    {
        if (!$this->searchEntityName) {
            throw new \Exception('SearchEntityName in DAO niet geset');
        }
        $entity = $this->repository->findOneBy([$this->searchEntityName => $searchEntity]);
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
        if ($entity instanceof ActivatableInterface) {// && $entity->isDeletable() === false
            $entity->setActief(false);
        } elseif ($entity instanceof ActivatableInterface && false !== $entity->isDeletable()) {
            try {
                $this->entityManager->remove($entity);
            } catch (ForeignKeyConstraintViolationException $e) {
                // ondanks dat ie isDeletable wel true zet, moet ie toch echt inactief worden gezet.
                // Dubbele interpretatie van ' is deletable'....: mag de verwijder knop worden laten zien of niet....
            }
        } else {
            $this->entityManager->remove($entity);
        }
        //        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    /**
     * @return int
     */
    public function countAll(?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id)")
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        return $builder->getQuery()->getSingleScalarResult();
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
