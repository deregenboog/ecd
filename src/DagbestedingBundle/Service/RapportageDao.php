<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Rapportage;
use AppBundle\Filter\FilterInterface;

class RapportageDao extends AbstractDao implements RapportageDaoInterface
{
    protected $paginationOptions = [
//         'defaultSortFieldName' => 'klant.achternaam',
//         'defaultSortDirection' => 'asc',
//         'sortFieldWhitelist' => [
//             'klant.id',
//             'klant.achternaam',
//             'klant.werkgebied',
//         ],
    ];

    protected $class = Rapportage::class;

    protected $alias = 'rapportage';

//     public function findAll($page = null, FilterInterface $filter = null)
//     {
//         $builder = $this->repository->createQueryBuilder($this->alias)
//             ->innerJoin('rapportage.klant', 'klant')
//         ;

//         if ($filter) {
//             $filter->applyTo($builder);
//         }

//         return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
//     }

    public function create(Rapportage $rapportage)
    {
        $this->doCreate($rapportage);
    }

    public function update(Rapportage $rapportage)
    {
        $this->doUpdate($rapportage);
    }

    public function delete(Rapportage $rapportage)
    {
        $this->doDelete($rapportage);
    }
}
