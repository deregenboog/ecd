<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use GaBundle\Entity\Activiteit;

class ActiviteitDao extends AbstractDao implements ActiviteitDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'activiteit.datum',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'activiteit.naam',
            'activiteit.datum',
            'activiteit.afgesloten',
            'groep.naam',
        ],
    ];

    protected $class = Activiteit::class;

    protected $alias = 'activiteit';

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->leftJoin("{$this->alias}.groep", 'groep')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    public function create(Activiteit $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * @param Activiteit[] $entities
     */
    public function createBatch(array $entities)
    {
        foreach ($entities as $entity) {
            assert($entity instanceof Activiteit);
            $this->entityManager->persist($entity);
        }
        $this->entityManager->flush();

        return $entities;
    }

    public function update(Activiteit $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Activiteit $entity)
    {
        $this->doDelete($entity);
    }
}
