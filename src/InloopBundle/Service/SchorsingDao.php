<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Schorsing;

class SchorsingDao extends AbstractDao implements SchorsingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'schorsing.datumVan',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'geslacht.volledig',
            'locatie.naam',
            'schorsing.datumVan',
            'schorsing.datumTot',
        ],
    ];

    protected $class = Schorsing::class;

    protected $alias = 'schorsing';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select($this->alias.', klant, geslacht, locatie')
            ->innerJoin($this->alias.'.klant', 'klant')
            ->leftJoin('klant.geslacht', 'geslacht')
            ->leftJoin($this->alias.'.locaties', 'locatie')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }
}
