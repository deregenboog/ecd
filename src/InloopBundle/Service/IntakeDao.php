<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Intake;

class IntakeDao extends AbstractDao implements IntakeDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'intake.intakedatum',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'intake.intakedatum',
            'klant.id',
            'klant.achternaam',
            'geslacht.volledig',
            'intakelocatie.naam',
        ],
    ];

    protected $class = Intake::class;

    protected $alias = 'intake';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, klant, intakelocatie, geslacht")
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->leftJoin("{$this->alias}.intakelocatie", 'intakelocatie')
            ->leftJoin('klant.geslacht', 'geslacht')
        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    /**
     * @param Intake $entity
     *
     * @return Intake
     */
    public function create(Intake $entity)
    {
        return parent::doCreate($entity);
    }

    /**
     * @param Intake $entity
     *
     * @return Intake
     */
    public function update(Intake $entity)
    {
        return parent::doUpdate($entity);
    }
}
