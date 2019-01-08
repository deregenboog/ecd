<?php

namespace ErOpUitBundle\Service;

use AppBundle\Service\AbstractDao;
use ErOpUitBundle\Entity\Uitschrijfreden;

class UitschrijfredenDao extends AbstractDao implements UitschrijfredenDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'uitschrijfreden.naam',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'uitschrijfreden.id',
            'uitschrijfreden.naam',
        ],
    ];

    protected $class = Uitschrijfreden::class;

    protected $alias = 'uitschrijfreden';

    public function create(Uitschrijfreden $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Uitschrijfreden $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Uitschrijfreden $entity)
    {
        $this->doDelete($entity);
    }
}
