<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use GaBundle\Entity\Verslag;

class VerslagDao extends AbstractDao implements VerslagDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'medewerker.voornaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'coordinator.id',
            'medewerker.voornaam',
        ],
    ];

    protected $class = Verslag::class;

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        return parent::findAll($page);
    }

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
    public function create(Verslag $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Verslag $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Verslag $entity)
    {
        $this->doDelete($entity);
    }
}
