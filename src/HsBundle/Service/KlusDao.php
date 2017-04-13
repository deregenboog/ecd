<?php

namespace HsBundle\Service;

use HsBundle\Entity\Klus;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;

class KlusDao extends AbstractDao implements KlusDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klus.datum',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'klus.id',
            'klus.datum',
            'klant.achternaam',
            'klant.werkgebied',
            'activiteit.naam',
        ],
    ];

    protected $class = Klus::class;

    protected $alias = 'klus';

    /**
     * {inheritdoc}
     */
    public function findAll($page = 1, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin('klus.klant', 'klant')
            ->innerJoin('klus.activiteit', 'activiteit')
        ;

        return $this->doFindAll($builder, $page, $filter);
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
    public function create(Klus $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function update(Klus $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function delete(Klus $entity)
    {
        $this->doDelete($entity);
    }
}
