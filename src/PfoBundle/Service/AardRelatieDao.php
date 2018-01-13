<?php

namespace PfoBundle\Service;

use AppBundle\Service\AbstractDao;
use PfoBundle\Entity\AardRelatie;

class AardRelatieDao extends AbstractDao implements AardRelatieDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'aardRelatie.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'aardRelatie.id',
            'aardRelatie.naam',
            'aardRelatie.startdatum',
            'aardRelatie.einddatum',
        ],
        'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = AardRelatie::class;

    protected $alias = 'aardRelatie';

    /**
     * {inheritdoc}.
     */
    public function create(AardRelatie $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(AardRelatie $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(AardRelatie $entity)
    {
        $this->doDelete($entity);
    }
}
