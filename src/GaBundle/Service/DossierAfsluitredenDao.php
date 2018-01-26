<?php

namespace GaBundle\Service;

use AppBundle\Service\AbstractDao;
use GaBundle\Entity\DossierAfsluitreden;

class DossierAfsluitredenDao extends AbstractDao implements DossierAfsluitredenDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'afsluitreden.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'afsluitreden.id',
            'afsluitreden.naam',
        ],
    ];

    protected $class = DossierAfsluitreden::class;

    protected $alias = 'afsluitreden';

    public function create(DossierAfsluitreden $entity)
    {
        $this->doCreate($entity);
    }

    public function update(DossierAfsluitreden $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(DossierAfsluitreden $entity)
    {
        $this->doDelete($entity);
    }
}
