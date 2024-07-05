<?php

namespace BuurtboerderijBundle\Service;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use BuurtboerderijBundle\Entity\Vrijwilliger;

class VrijwilligerDao extends AbstractDao implements VrijwilligerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'appVrijwilliger.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'appVrijwilliger.id',
            'appVrijwilliger.voornaam',
            'appVrijwilliger.achternaam',
            'vrijwilliger.aanmelddatum',
            'vrijwilliger.afsluitdatum',
            'werkgebied.naam',
        ],
    ];

    protected $class = Vrijwilliger::class;

    protected $alias = 'vrijwilliger';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, appVrijwilliger")
            ->innerJoin('vrijwilliger.vrijwilliger', 'appVrijwilliger')
            ->leftJoin('appVrijwilliger.werkgebied', 'werkgebied')
        ;

        if ($filter) {
            if ($filter->vrijwilliger) {
                $filter->vrijwilliger->alias = 'appVrijwilliger';
            }
            $filter->applyTo($builder);
        }

        return $this->doFindAll($builder, $page, $filter);
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
    public function findOneByVrijwilliger(AppVrijwilliger $vrijwilliger)
    {
        return $this->repository->findOneBy(['vrijwilliger' => $vrijwilliger]);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Vrijwilliger $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Vrijwilliger $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Vrijwilliger $entity)
    {
        $this->doDelete($entity);
    }
}
