<?php

namespace InloopBundle\Service;

use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Locatie;

class LocatieDao extends AbstractDao implements LocatieDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'locatie.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'locatie.id',
            'locatie.naam',
            'locatie.actief',
        ],
    ];

    protected $class = Locatie::class;

    protected $alias = 'locatie';

    public function create(Locatie $locatie)
    {
        $this->doCreate($locatie);
    }

    public function update(Locatie $locatie)
    {
        $this->doUpdate($locatie);
    }

    public function delete(Locatie $locatie)
    {
        $this->doDelete($locatie);
    }

    public function getWachtlijstLocaties()
    {
        $builder = $this->entityManager->createQueryBuilder("locatie");
        $builder->select("locatie.naam")
            ->from(Locatie::class,"locatie")
            ->where("locatie.wachtlijst = 1");
        $wachtlijstlocaties = $builder->getQuery()->getResult();
        return $wachtlijstlocaties;
    }
}
