<?php

namespace HsBundle\Service;

use HsBundle\Entity\Declaratie;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;

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

    /**
     * {inheritdoc}
     */
    public function findAll($page = 1, FilterInterface $filter = null)
    {
        return parent::findAll($page);
    }

    /**
     * {inheritdoc}
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * {inheritdoc}
     */
    public function create(Declaratie $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function update(Declaratie $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function delete(Declaratie $entity)
    {
        $this->doDelete($entity);
    }
}
