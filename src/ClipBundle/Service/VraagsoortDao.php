<?php

namespace ClipBundle\Service;

use AppBundle\Service\AbstractDao;
use ClipBundle\Entity\Vraagsoort;

class VraagsoortDao extends AbstractDao implements VraagsoortDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'vraagsoort.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'vraagsoort.id',
            'vraagsoort.naam',
            'vraagsoort.actief',
        ],
    ];

    protected $class = Vraagsoort::class;

    protected $alias = 'vraagsoort';

    public function create(Vraagsoort $vraagsoort)
    {
        $this->doCreate($vraagsoort);
    }

    public function update(Vraagsoort $vraagsoort)
    {
        $this->doUpdate($vraagsoort);
    }

    public function delete(Vraagsoort $vraagsoort)
    {
        $this->doDelete($vraagsoort);
    }
}
