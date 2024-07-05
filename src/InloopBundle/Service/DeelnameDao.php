<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Deelname;
use OekBundle\Entity\DeelnameStatus;

class DeelnameDao extends AbstractDao implements DeelnameDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'training.startdatum',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'training.id',
            'training.naam',
            'groep.naam',
            'training.startdatum',
            'training.einddatum',
        ],
    ];

    protected $class = Deelname::class;

    protected $alias = 'deelname';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('training')
            ->leftJoin('training.deelnames', 'deelname')
            ->leftJoin('deelname.deelnemer', 'deelnemer')
            ->innerJoin('training.groep', 'groep')
            ->where('deelname.deelnameStatus != :status_verwijderd')
            ->setParameter(':status_verwijderd', DeelnameStatus::STATUS_VERWIJDERD);

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * {inheritdoc}.
     */
    public function create(Deelname $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Deelname $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Deelname $entity)
    {
        $this->doDelete($entity);
    }
}
