<?php

namespace IzBundle\Service;

use IzBundle\Entity\BinnengekomenVia;
use AppBundle\Service\AbstractDao;
use IzBundle\Entity\IzEindeVraagAanbod;
use IzBundle\Entity\EindeKoppeling;
use IzBundle\Entity\Afsluiting;

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

    protected $class = Afsluiting::class;

    protected $alias = 'afsluitreden';

    public function create(Afsluiting $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Afsluiting $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Afsluiting $entity)
    {
        $this->doDelete($entity);
    }
}
