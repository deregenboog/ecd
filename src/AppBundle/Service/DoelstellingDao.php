<?php

namespace AppBundle\Service;

use AppBundle\Entity\Land;
use AppBundle\Entity\Doelstelling;
use AppBundle\Filter\FilterInterface;
use AppBundle\Repository\DoelstellingRepositoryInterface;

class DoelstellingDao extends AbstractDao
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'doelstelling.repository',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'doelstelling.repository',
            'doelstelling.categorie',
            'doelstelling.kpi',
        ],
    ];

    protected $class = Doelstelling::class;

    protected $alias = 'doelstelling';

    public function findAll($page = null, FilterInterface $filter = null,$repos=[])
    {
        $builder = $this->repository->createQueryBuilder($this->alias);




        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            $paginatedResult = $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        $result =  $builder->getQuery()->getResult();

        //Results should be connected to the repositories used.
        $result = $this->connectDoelstellingenInterfaceReposToResult($result,$repos);
        return $this->paginator->paginate($result,$page,$this->itemsPerPage,$this->paginationOptions);
        return $result;
    }

    private function connectDoelstellingenInterfaceReposToResult($result,$repos)
    {
        foreach($repos as $repo)
        {
            /**
             * @var $repo DoelstellingRepositoryInterface;
             */
            $repo->kpis = $repo->getKpis();
        }

        foreach($result as $row)
        {
            $repo = $repos[$row->getRepository()];
                $kpis = $repo->kpis;
                $kpiLabel = array_search($row->getKpi(),$kpis);
                $row->kpiLabel = $kpiLabel;
                $row->repositoryLabel = $repo->getPrestatieLabel();
                $row->actueel = $repo->getCurrentNumber($row);
        }
        return $result;
    }

    /**
     * @param int $id
     *
     * @return Land
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * @param Doelstelling $prestatie
     */
    public function create(Doelstelling $prestatie)
    {
        return $this->doCreate($prestatie);
    }

    /**
     *@param Doelstelling $prestatie
     */
    public function update(Doelstelling $prestatie)
    {
        return $this->doUpdate($prestatie);
    }

    /**
     * @param Doelstelling $prestatie
     */
    public function delete(Doelstelling $prestatie)
    {
        return $this->doDelete($prestatie);
    }
}
