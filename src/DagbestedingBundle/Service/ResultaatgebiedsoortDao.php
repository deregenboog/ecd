<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Resultaatgebiedsoort;

class ResultaatgebiedsoortDao extends AbstractDao implements ResultaatgebiedsoortDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'resultaatgebiedsoort.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'resultaatgebiedsoort.id',
            'resultaatgebiedsoort.naam',
            'resultaatgebiedsoort.actief',
        ],
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
