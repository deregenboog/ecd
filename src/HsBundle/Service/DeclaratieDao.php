<?php

namespace HsBundle\Service;

use HsBundle\Entity\Declaratie;
use AppBundle\Service\AbstractDao;

class DeclaratieDao extends AbstractDao implements DeclaratieDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'medewerker.voornaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'coordinator.id',
            'medewerker.voornaam',
        ],
    ];

    protected $class = Declaratie::class;

    protected $alias = 'declaratie';

    /**
     * {inheritdoc}.
     */
    public function create(Declaratie $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Declaratie $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Declaratie $entity)
    {
        $this->doDelete($entity);
    }
}
