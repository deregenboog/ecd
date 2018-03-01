<?php

namespace IzBundle\Service;

use IzBundle\Entity\BinnengekomenVia;
use AppBundle\Service\AbstractDao;
use IzBundle\Entity\IzEindeVraagAanbod;
use IzBundle\Entity\EindeKoppeling;

class EindeKoppelingDao extends AbstractDao implements EindeKoppelingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'afsluitreden.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'afsluitreden.id',
            'afsluitreden.naam',
            'afsluitreden.actief',
        ],
    ];

    protected $class = EindeKoppeling::class;

    protected $alias = 'afsluitreden';

    public function create(EindeKoppeling $entity)
    {
        $this->doCreate($entity);
    }

    public function update(EindeKoppeling $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(EindeKoppeling $entity)
    {
        $this->doDelete($entity);
    }
}
