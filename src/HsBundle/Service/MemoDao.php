<?php

namespace HsBundle\Service;

use HsBundle\Entity\Memo;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;

class MemoDao extends AbstractDao implements MemoDaoInterface
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
    public function create(Memo $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function update(Memo $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function delete(Memo $entity)
    {
        $this->doDelete($entity);
    }
}
