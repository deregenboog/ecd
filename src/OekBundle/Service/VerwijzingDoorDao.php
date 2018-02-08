<?php

namespace OekBundle\Service;

use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;
use AppBundle\Entity\Klant;
use JMS\DiExtraBundle\Annotation as DI;
use OekBundle\Entity\Deelnemer;
use OekBundle\Entity\Training;
use OekBundle\Entity\VerwijzingDoor;

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
