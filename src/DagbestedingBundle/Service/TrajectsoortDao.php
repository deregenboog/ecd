<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Trajectsoort;

class TrajectsoortDao extends AbstractDao implements TrajectsoortDaoInterface
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
