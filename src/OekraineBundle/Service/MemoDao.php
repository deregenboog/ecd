<?php

namespace OekraineBundle\Service;

use AppBundle\Entity\Memo;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use AppBundle\Service\MemoDaoInterface as ServiceMemoDaoInterface;
use OekraineBundle\Service\MemoDaoInterface;

class MemoDao extends AbstractDao implements ServiceMemoDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'medewerker.voornaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'coordinator.id',
            'medewerker.voornaam',
        ],
    ];

    protected $class = Memo::class;

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
    public function create(Memo $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Memo $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Memo $entity)
    {
        $this->doDelete($entity);
    }
}
