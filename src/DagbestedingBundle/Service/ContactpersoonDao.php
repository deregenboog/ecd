<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Contactpersoon;
use AppBundle\Filter\FilterInterface;

class ContactpersoonDao extends AbstractDao implements ContactpersoonDaoInterface
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

    protected $class = Contactpersoon::class;

    protected $alias = 'contactpersoon';

//     public function findAll($page = null, FilterInterface $filter = null)
//     {
//         $builder = $this->repository->createQueryBuilder($this->alias)
//             ->innerJoin('contactpersoon.klant', 'klant')
//         ;

//         if ($filter) {
//             $filter->applyTo($builder);
//         }

//         return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
//     }

    public function create(Contactpersoon $contactpersoon)
    {
        $this->doCreate($contactpersoon);
    }

    public function update(Contactpersoon $contactpersoon)
    {
        $this->doUpdate($contactpersoon);
    }

    public function delete(Contactpersoon $contactpersoon)
    {
        $this->doDelete($contactpersoon);
    }
}
