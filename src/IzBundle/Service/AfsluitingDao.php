<?php

namespace IzBundle\Service;

use IzBundle\Entity\BinnengekomenVia;
use AppBundle\Service\AbstractDao;
use IzBundle\Entity\IzEindeVraagAanbod;
use IzBundle\Entity\EindeKoppeling;
use IzBundle\Entity\IzAfsluiting;

class AfsluitingDao extends AbstractDao implements AfsluitingDaoInterface
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

    protected $class = IzAfsluiting::class;

    protected $alias = 'afsluitreden';

    public function create(IzAfsluiting $entity)
    {
        $this->doCreate($entity);
    }

    public function update(IzAfsluiting $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(IzAfsluiting $entity)
    {
        $this->doDelete($entity);
    }
}
