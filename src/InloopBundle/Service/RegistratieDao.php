<?php

namespace InloopBundle\Service;

use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;
use AppBundle\Entity\Klant;
use InloopBundle\Entity\Registratie;

class RegistratieDao extends AbstractDao implements SchorsingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'registratie.binnen',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'registratie.binnen',
            'registratie.buiten',
            'klant.id',
            'klant.achternaam',
            'geslacht.volledig',
            'locatie.naam',
        ],
    ];

    protected $class = Registratie::class;

    protected $alias = 'registratie';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, klant, locatie, geslacht")
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->innerJoin("{$this->alias}.locatie", 'locatie')
            ->leftJoin('klant.geslacht', 'geslacht')
            ->where("registratie.binnen >= '2017-01-01 00:00:00'")
        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    /**
     * @param Registratie $entity
     *
     * @return Registratie
     */
    public function update(Registratie $entity)
    {
        return parent::doUpdate($entity);
    }
}
