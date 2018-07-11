<?php

namespace GaBundle\Service;

use AppBundle\Service\AbstractDao;
use GaBundle\Entity\VerwijzingDoor;

class VerwijzingDoorDao extends AbstractDao implements VerwijzingDoorDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'verwijzing.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'verwijzing.id',
            'verwijzing.naam',
            'verwijzing.actief',
        ],
    ];

    protected $class = VerwijzingDoor::class;

    protected $alias = 'verwijzing';

    /**
     * {inheritdoc}.
     */
    public function create(VerwijzingDoor $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(VerwijzingDoor $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(VerwijzingDoor $entity)
    {
        $this->doDelete($entity);
    }
}
