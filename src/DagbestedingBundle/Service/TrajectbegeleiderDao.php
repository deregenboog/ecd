<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Trajectbegeleider;

class TrajectbegeleiderDao extends AbstractDao implements TrajectbegeleiderDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'trajectbegeleider.displayName',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'trajectbegeleider.id',
            'trajectbegeleider.displayName',
            'trajectbegeleider.actief',
        ],
    ];

    protected $class = Trajectbegeleider::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('trajectbegeleider')
            ->leftJoin('trajectbegeleider.medewerker', 'medewerker');

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Trajectbegeleider $trajectbegeleider)
    {
        $this->doCreate($trajectbegeleider);
    }

    public function update(Trajectbegeleider $trajectbegeleider)
    {
        $this->doUpdate($trajectbegeleider);
    }

    public function delete(Trajectbegeleider $trajectbegeleider)
    {
        $this->doDelete($trajectbegeleider);
    }
}
