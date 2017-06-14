<?php

namespace DagbestedingBundle\Service;

use DagbestedingBundle\Entity\Trajectbegeleider;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;

class TrajectbegeleiderDao extends AbstractDao implements TrajectbegeleiderDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'medewerker.voornaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'trajectbegeleider.id',
            'medewerker.voornaam',
        ],
    ];

    protected $class = Trajectbegeleider::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('trajectbegeleider')
            ->innerJoin('trajectbegeleider.medewerker', 'medewerker');

        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
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
