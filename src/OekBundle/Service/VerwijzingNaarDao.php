<?php

namespace OekBundle\Service;

use AppBundle\Service\AbstractDao;
use OekBundle\Entity\VerwijzingNaar;

class VerwijzingNaarDao extends AbstractDao implements VerwijzingNaarDaoInterface
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

    protected $class = VerwijzingNaar::class;

    protected $alias = 'verwijzing';

    /**
     * {inheritdoc}.
     */
    public function create(VerwijzingNaar $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(VerwijzingNaar $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(VerwijzingNaar $entity)
    {
        $this->doDelete($entity);
    }
}
