<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Resultaatgebiedsoort;

class ResultaatgebiedsoortDao extends AbstractDao implements ResultaatgebiedsoortDaoInterface
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

    protected $class = Resultaatgebiedsoort::class;

    protected $alias = 'resultaatgebiedsoort';

    public function create(Resultaatgebiedsoort $resultaatgebiedsoort)
    {
        $this->doCreate($resultaatgebiedsoort);
    }

    public function update(Resultaatgebiedsoort $resultaatgebiedsoort)
    {
        $this->doUpdate($resultaatgebiedsoort);
    }

    public function delete(Resultaatgebiedsoort $resultaatgebiedsoort)
    {
        $this->doDelete($resultaatgebiedsoort);
    }
}
