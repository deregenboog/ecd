<?php

namespace HsBundle\Service;

use AppBundle\Service\AbstractDao;
use HsBundle\Entity\DeclaratieCategorie;
use AppBundle\Filter\FilterInterface;

class DeclaratieCategorieDao extends AbstractDao implements DeclaratieCategorieDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'declaratiecategorie.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'declaratiecategorie.id',
            'declaratiecategorie.naam',
        ],
    ];

    protected $class = DeclaratieCategorie::class;

    protected $alias = 'declaratiecategorie';

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
    public function create(DeclaratieCategorie $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function update(DeclaratieCategorie $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function delete(DeclaratieCategorie $entity)
    {
        $this->doDelete($entity);
    }
}
