<?php

namespace MwBundle\Service;

use AppBundle\Service\AbstractDao;
use MwBundle\Entity\AfsluitredenKlant;
use MwBundle\Entity\Resultaat;

class ResultaatDao extends AbstractDao
{
    protected $class = Resultaat::class;

    protected $alias = 'resultaat';

    protected $paginationOptions = [
        'defaultSortFieldName' => 'resultaat.naam',
        'defaultSortDirection' => 'asc',
    ];

    /**
     * {inheritdoc}.
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Resultaat $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Resultaat $entity)
    {
        $this->doUpdate($entity);
    }
}
