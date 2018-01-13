<?php

namespace PfoBundle\Service;

use AppBundle\Service\AbstractDao;
use PfoBundle\Entity\Groep;

class GroepDao extends AbstractDao implements GroepDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'groep.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'groep.id',
            'groep.naam',
            'groep.startdatum',
            'groep.einddatum',
        ],
    ];

    protected $class = Groep::class;

    protected $alias = 'groep';

    /**
     * {inheritdoc}.
     */
    public function create(Groep $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Groep $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Groep $entity)
    {
        $this->doDelete($entity);
    }
}
