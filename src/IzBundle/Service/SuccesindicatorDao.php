<?php

namespace IzBundle\Service;

use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityManagerInterface;
use IzBundle\Entity\Succesindicator;
use Knp\Component\Pager\PaginatorInterface;

class SuccesindicatorDao extends AbstractDao implements SuccesindicatorDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'indicator.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'indicator.id',
            'indicator.naam',
            'indicator.actief',
        ],
    ];

    protected $alias = 'indicator';

    public function __construct(EntityManagerInterface $entityManager, PaginatorInterface $paginator, $itemsPerPage, $class)
    {
        $this->class = $class;
        parent::__construct($entityManager, $paginator, $itemsPerPage);
    }

    public function create(Succesindicator $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Succesindicator $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Succesindicator $entity)
    {
        $this->doDelete($entity);
    }
}
