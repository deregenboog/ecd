<?php

namespace GaBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use GaBundle\Entity\KlantIntake;

class KlantIntakeDao extends AbstractDao implements KlantIntakeDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'klant.geboortedatum',
            'medewerker.voornaam',
            'dossier.intakedatum',
            'dossier.afsluitdatum',
        ],
    ];

    protected $class = KlantIntake::class;

    protected $alias = 'intake';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, klant, werkgebied, medewerker")
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->leftJoin("{$this->alias}.medewerker", 'medewerker')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    /**
     * @param Klant $klant
     *
     * @return Deelnemer
     */
    public function findOneByKlant(Klant $klant)
    {
        return $this->repository->findOneBy(['klant' => $klant]);
    }

    public function create(KlantIntake $entity)
    {
        $this->doCreate($entity);
    }

    public function update(KlantIntake $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(KlantIntake $entity)
    {
        $this->doDelete($entity);
    }
}
