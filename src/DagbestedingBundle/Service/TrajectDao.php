<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Traject;
use AppBundle\Filter\FilterInterface;

class TrajectDao extends AbstractDao implements TrajectDaoInterface
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

    protected $class = Traject::class;

    protected $alias = 'traject';

//     public function findAll($page = null, FilterInterface $filter = null)
//     {
//         $builder = $this->repository->createQueryBuilder($this->alias);

//         if ($filter) {
//             $filter->applyTo($builder);
//         }

//         return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
//     }

    public function create(Traject $traject)
    {
        $this->doCreate($traject);
    }

    public function update(Traject $traject)
    {
        $this->doUpdate($traject);
    }

    public function delete(Traject $traject)
    {
        $this->doDelete($traject);
    }
}
