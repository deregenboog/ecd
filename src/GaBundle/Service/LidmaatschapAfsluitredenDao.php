<?php

namespace GaBundle\Service;

use AppBundle\Service\AbstractDao;
use GaBundle\Entity\LidmaatschapAfsluitreden;

class LidmaatschapAfsluitredenDao extends AbstractDao implements LidmaatschapAfsluitredenDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'afsluitreden.naam',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'afsluitreden.id',
            'afsluitreden.naam',
        ],
    ];

    protected $class = LidmaatschapAfsluitreden::class;

    protected $alias = 'afsluitreden';

    public function create(LidmaatschapAfsluitreden $entity)
    {
        $this->doCreate($entity);
    }

    public function update(LidmaatschapAfsluitreden $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(LidmaatschapAfsluitreden $entity)
    {
        $this->doDelete($entity);
    }
}
