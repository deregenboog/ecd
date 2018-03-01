<?php

namespace IzBundle\Service;

use IzBundle\Entity\BinnengekomenVia;
use AppBundle\Service\AbstractDao;
use IzBundle\Entity\IzEindeVraagAanbod;
use IzBundle\Entity\EindeVraagAanbod;

class EindeVraagAanbodDao extends AbstractDao implements EindeVraagAanbodDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'afsluitreden.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'afsluitreden.id',
            'afsluitreden.naam',
        ],
    ];

    protected $class = EindeVraagAanbod::class;

    protected $alias = 'afsluitreden';

    public function create(EindeVraagAanbod $entity)
    {
        $this->doCreate($entity);
    }

    public function update(EindeVraagAanbod $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(EindeVraagAanbod $entity)
    {
        $this->doDelete($entity);
    }
}
