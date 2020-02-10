<?php

namespace GaBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use GaBundle\Entity\Intake;

class IntakeDao extends AbstractDao implements IntakeDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'klant.geboortedatum',
            'medewerker.voornaam',
            'intake.intakedatum',
            'intake.afsluitdatum',
        ],
    ];

    protected $class = Intake::class;

    protected $alias = 'intake';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, klant, medewerker")
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->leftJoin("{$this->alias}.medewerker", 'medewerker')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    /**
     * @param Klant $klant
     *
     * @return Intake
     */
    public function findOneByKlant(Klant $klant)
    {
        return $this->repository->findOneBy(['klant' => $klant]);
    }

    public function create(Intake $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Intake $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Intake $entity)
    {
        $this->doDelete($entity);
    }
}
