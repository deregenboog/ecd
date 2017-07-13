<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Trajectsoort;

class TrajectsoortDao extends AbstractDao implements TrajectsoortDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'trajectsoort.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'trajectsoort.id',
            'trajectsoort.naam',
            'trajectsoort.actief',
        ],
    ];

    protected $class = Trajectsoort::class;

    protected $alias = 'trajectsoort';

    public function create(Trajectsoort $trajectsoort)
    {
        $this->doCreate($trajectsoort);
    }

    public function update(Trajectsoort $trajectsoort)
    {
        $this->doUpdate($trajectsoort);
    }

    public function delete(Trajectsoort $trajectsoort)
    {
        $this->doDelete($trajectsoort);
    }
}
