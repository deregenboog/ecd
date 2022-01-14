<?php

namespace OekBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use OekBundle\Entity\Training;
use OekBundle\Entity\DeelnameStatus;

class TrainingDao extends AbstractDao implements TrainingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'training.startdatum',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'training.id',
            'training.naam',
            'groep.naam',
            'training.startdatum',
            'training.einddatum',
        ],
    ];

    protected $class = Training::class;

    protected $alias = 'training';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('training')
            ->leftJoin('training.deelnames', 'deelname')
            ->leftJoin('deelname.deelnemer', 'deelnemer')
            ->innerJoin('training.groep', 'groep')
            ->where('deelname.deelnameStatus != :status_verwijderd OR deelname IS NULL')
            ->setParameter(":status_verwijderd",DeelnameStatus::STATUS_VERWIJDERD);
        ;

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
    public function create(Training $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Training $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Training $entity)
    {
        $this->doDelete($entity);
    }
}
