<?php

namespace GaBundle\Service;

use AppBundle\Service\AbstractDao;
use GaBundle\Entity\IntakeAfsluitreden;

class IntakeAfsluitredenDao extends AbstractDao implements IntakeAfsluitredenDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'afsluitreden.naam',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'afsluitreden.id',
            'afsluitreden.naam',
        ],
    ];

    protected $class = IntakeAfsluitreden::class;

    protected $alias = 'afsluitreden';

    public function create(IntakeAfsluitreden $entity)
    {
        $this->doCreate($entity);
    }

    public function update(IntakeAfsluitreden $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(IntakeAfsluitreden $entity)
    {
        $this->doDelete($entity);
    }
}
